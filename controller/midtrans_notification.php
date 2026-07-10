<?php
// controller/midtrans_notification.php
// Robust webhook handler + debugging (logs headers, remote addr, raw body)
// Copy this file to controller/midtrans_notification.php

require_once __DIR__ . '/../vendor/autoload.php';

// MIDTRANS CONFIG - sesuaikan jika perlu
\Midtrans\Config::$serverKey = 'SB-Mid-server-FZ1_nmdIZR8w5wnMDoRfHQCX';
\Midtrans\Config::$isProduction = false;

header('Content-Type: application/json');

// --- Log files (ensure folder writable) ---
$logDir = __DIR__ . '/logs';
if (!is_dir($logDir)) @mkdir($logDir, 0775, true);
$debugLog = $logDir . '/midtrans_notification_debug.txt';
$mainLog = $logDir . '/midtrans_notification_log.txt';
$errLog = $logDir . '/midtrans_notification_error.txt';
$unmatchedFile = $logDir . '/midtrans_unmatched.txt';

// helper writers
function write_debug($path, $msg)
{
    @file_put_contents($path, date('[Y-m-d H:i:s] ') . $msg . PHP_EOL, FILE_APPEND | LOCK_EX);
}

// --- Debug: headers + remote IP + raw body (always log) ---
$remoteAddr = $_SERVER['REMOTE_ADDR'] ?? ($_SERVER['HTTP_X_FORWARDED_FOR'] ?? 'unknown');
$headers = function_exists('getallheaders') ? getallheaders() : [];
write_debug($debugLog, "REMOTE_ADDR={$remoteAddr}");
write_debug($debugLog, "REQUEST_URI=" . ($_SERVER['REQUEST_URI'] ?? '-'));
write_debug($debugLog, "HEADERS=" . json_encode($headers));

// read raw body
$rawBody = file_get_contents('php://input');
write_debug($debugLog, "RAW_BODY=" . ($rawBody === '' ? '[EMPTY]' : $rawBody));
write_debug($debugLog, str_repeat('-', 60));

// --- DB config ---
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'lapanganbola';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
    $msg = 'DB connection failed: ' . $mysqli->connect_error;
    write_debug($errLog, $msg);
    http_response_code(500);
    echo json_encode(['error' => $msg]);
    exit;
}

// Try to decode raw body first (preferred)
$body = null;
if (!empty($rawBody)) {
    $decoded = json_decode($rawBody, true);
    if (is_array($decoded)) {
        $body = $decoded;
    } else {
        // keep null, we'll try Midtrans SDK fallback below
        write_debug($debugLog, "RAW body not valid JSON or decode returned null/false.");
    }
}

// Fallback: try Midtrans SDK Notification object (will parse headers & body)
if ($body === null) {
    try {
        $notif = new \Midtrans\Notification();
        // Convert object to array for easier usage
        $body = json_decode(json_encode($notif), true);
        write_debug($debugLog, "Notification object built by SDK and converted to array.");
    } catch (Exception $e) {
        $err = "Failed to decode body and to build Notification object: " . $e->getMessage();
        write_debug($errLog, $err);
        // Save raw to unmatched and respond 200 (to avoid retries), but inform logs
        @file_put_contents($unmatchedFile, date('Y-m-d H:i:s') . ' - Failed decode: ' . $rawBody . PHP_EOL, FILE_APPEND);
        // respond 200/400? We'll send 200 to avoid infinite retries but log the issue.
        http_response_code(200);
        echo json_encode(['status' => 'ignored', 'reason' => 'invalid notification payload or SDK error']);
        exit;
    }
}

// normalize fields with fallbacks
$transaction_status = $body['transaction_status'] ?? $body['status_code'] ?? ($body['transaction']['status'] ?? null);
$fraud_status = $body['fraud_status'] ?? null;
$order_id = $body['order_id'] ?? ($body['transaction_details']['order_id'] ?? null);
$transaction_id = $body['transaction_id'] ?? ($body['transaction_details']['transaction_id'] ?? null);
$payment_type = $body['payment_type'] ?? null;
$va_numbers = $body['va_numbers'] ?? ($body['va_number'] ?? null);
$permata_va_number = $body['permata_va_number'] ?? null;
$store = $body['store'] ?? null;
$biller_code = $body['biller_code'] ?? null;
$bill_key = $body['bill_key'] ?? null;

// log extracted summary
write_debug($mainLog, "EXTRACTED order_id=" . ($order_id ?? '[null]') . " tx_id=" . ($transaction_id ?? '[null]') . " status=" . ($transaction_status ?? '[null]') . " payment_type=" . ($payment_type ?? '[null]'));

// order_id required to match pembayaran row (we stored order_id temporarily in transaksi_id_midtrans)
if (empty($order_id)) {
    write_debug($errLog, "order_id missing in notification; raw saved.");
    @file_put_contents($unmatchedFile, date('Y-m-d H:i:s') . ' - Missing order_id: ' . $rawBody . PHP_EOL, FILE_APPEND);
    http_response_code(200);
    echo json_encode(['status' => 'ignored', 'message' => 'order_id missing']);
    exit;
}

// find pembayaran row by transaksi_id_midtrans (we stored order_id there initially), fallback snap_token
$id_pembayaran = null;
$id_pesanan = null;

$stmt = $mysqli->prepare("SELECT id_pembayaran, id_pesanan FROM tb_pembayaran WHERE transaksi_id_midtrans = ? LIMIT 1");
if ($stmt) {
    $stmt->bind_param('s', $order_id);
    if ($stmt->execute()) {
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            $id_pembayaran = $row['id_pembayaran'];
            $id_pesanan = $row['id_pesanan'];
        }
    } else {
        write_debug($errLog, "DB exec error on select transaksi_id_midtrans: " . $stmt->error);
    }
    $stmt->close();
}

if (empty($id_pembayaran)) {
    $searchToken = $order_id;
    $stmt2 = $mysqli->prepare("SELECT id_pembayaran, id_pesanan FROM tb_pembayaran WHERE snap_token = ? OR transaksi_id_midtrans = ? LIMIT 1");
    if ($stmt2) {
        $stmt2->bind_param('ss', $searchToken, $searchToken);
        if ($stmt2->execute()) {
            $res2 = $stmt2->get_result();
            if ($row2 = $res2->fetch_assoc()) {
                $id_pembayaran = $row2['id_pembayaran'];
                $id_pesanan = $row2['id_pesanan'];
            }
        } else {
            write_debug($errLog, "DB exec error on select snap_token fallback: " . $stmt2->error);
        }
        $stmt2->close();
    }
}

if (empty($id_pembayaran)) {
    write_debug($errLog, "No matching tb_pembayaran found for order_id={$order_id}");
    @file_put_contents($unmatchedFile, date('Y-m-d H:i:s') . ' - Unmatched: ' . $rawBody . PHP_EOL, FILE_APPEND);
    http_response_code(200);
    echo json_encode(['status' => 'ignored', 'message' => 'no matching pembayaran record']);
    exit;
}

// map transaction_status -> our status_pembayaran
$new_status = 'pending';
$tran = strtolower((string)$transaction_status);
if (in_array($tran, ['capture', 'settlement', 'settled'])) {
    $new_status = 'paid';
} elseif ($tran === 'pending') {
    $new_status = 'pending';
} elseif (in_array($tran, ['deny', 'cancel', 'expire', 'expired', 'failure'])) {
    $new_status = 'failed';
} else {
    $new_status = $transaction_status;
}

// determine metode_pembayaran
$metode_pembayaran = 'unknown';
$pt = strtolower((string)$payment_type);
if (!empty($pt)) {
    if (strpos($pt, 'bank') !== false || strpos($pt, 'bank_transfer') !== false) {
        $bank_label = null;
        if (is_array($va_numbers) && isset($va_numbers[0]['bank'])) {
            $bank_label = $va_numbers[0]['bank'];
        } elseif (!empty($permata_va_number)) {
            $bank_label = 'permata';
        }
        $metode_pembayaran = 'bank_transfer' . ($bank_label ? " ({$bank_label})" : '');
    } elseif ($pt === 'credit_card' || strpos($pt, 'card') !== false) {
        $metode_pembayaran = 'credit_card';
    } elseif (in_array($pt, ['gopay', 'shopeepay', 'qris', 'qris_dynamic'])) {
        $metode_pembayaran = $pt;
    } elseif ($pt === 'cstore' || !empty($store)) {
        $metode_pembayaran = 'cstore' . (!empty($store) ? " ({$store})" : '');
    } else {
        $metode_pembayaran = $pt;
    }
} else {
    if (is_array($va_numbers) && isset($va_numbers[0]['bank'])) {
        $metode_pembayaran = 'bank_transfer (' . $va_numbers[0]['bank'] . ')';
    } elseif (!empty($store)) {
        $metode_pembayaran = 'cstore (' . $store . ')';
    } elseif (!empty($biller_code) || !empty($bill_key)) {
        $metode_pembayaran = 'bill_payment';
    } else {
        $metode_pembayaran = 'unknown';
    }
}

// choose transaction id to store
$txid_to_store = !empty($transaction_id) ? $transaction_id : $order_id;

// update tb_pembayaran
$update_sql = "UPDATE tb_pembayaran SET status_pembayaran = ?, metode_pembayaran = ?, transaksi_id_midtrans = ? WHERE id_pembayaran = ?";
$stmtUp = $mysqli->prepare($update_sql);
if ($stmtUp) {
    $stmtUp->bind_param('sssi', $new_status, $metode_pembayaran, $txid_to_store, $id_pembayaran);
    if (!$stmtUp->execute()) {
        $dberr = "Failed to update tb_pembayaran: " . $stmtUp->error . " | params: new_status={$new_status}, metode={$metode_pembayaran}, txid={$txid_to_store}, id_pembayaran={$id_pembayaran}";
        write_debug($errLog, $dberr);
        http_response_code(500);
        echo json_encode(['error' => $dberr]);
        $stmtUp->close();
        exit;
    }
    $stmtUp->close();
} else {
    $dberr = "Prepare failed for update tb_pembayaran: " . $mysqli->error;
    write_debug($errLog, $dberr);
    http_response_code(500);
    echo json_encode(['error' => $dberr]);
    exit;
}

// if paid => update tb_pesanan status_pesanan
if ($new_status === 'paid' && !empty($id_pesanan)) {
    $desired_order_status = 'lunas';
    $stmt3 = $mysqli->prepare("UPDATE tb_pesanan SET status_pesanan = ? WHERE id_pesanan = ?");
    if ($stmt3) {
        $stmt3->bind_param('si', $desired_order_status, $id_pesanan);
        if (!$stmt3->execute()) {
            write_debug($errLog, "Failed to update tb_pesanan: " . $stmt3->error);
        }
        $stmt3->close();
    } else {
        write_debug($errLog, "Prepare failed for update tb_pesanan: " . $mysqli->error);
    }
}

// final audit log
write_debug($mainLog, "UPDATED id_pembayaran={$id_pembayaran} id_pesanan={$id_pesanan} status={$new_status} metode={$metode_pembayaran} txid={$txid_to_store}");

// respond 200 OK
http_response_code(200);
echo json_encode(['status' => 'ok']);
exit;
