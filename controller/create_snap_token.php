<?php
// controller/create_snap_token.php (robust - validation + safe params)
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

$logDir = __DIR__ . '/logs';
if (!is_dir($logDir)) @mkdir($logDir, 0775, true);
$logFile = $logDir . '/create_snap_token_error.log';
function log_msg($m)
{
    global $logFile;
    @file_put_contents($logFile, date('[Y-m-d H:i:s] ') . $m . PHP_EOL, FILE_APPEND | LOCK_EX);
}

header('Content-Type: application/json; charset=utf-8');

// only POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed. Use POST.']);
    exit;
}

// log incoming
log_msg("Incoming POST: " . json_encode($_POST));

// require composer
$autoload = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoload)) {
    http_response_code(500);
    $msg = "Composer autoload not found at {$autoload}. Run composer install.";
    log_msg($msg);
    echo json_encode(['error' => $msg]);
    exit;
}
require_once $autoload;

// check extensions early
if (!function_exists('curl_init')) {
    http_response_code(500);
    $msg = "PHP cURL extension is not enabled. Enable php_curl in php.ini and restart web server.";
    log_msg($msg);
    echo json_encode(['error' => $msg]);
    exit;
}
if (!class_exists('mysqli')) {
    http_response_code(500);
    $msg = "PHP MySQLi extension is not enabled. Enable mysqli (php_mysqli) in php.ini and restart web server.";
    log_msg($msg);
    echo json_encode(['error' => $msg]);
    exit;
}

// MIDTRANS CONFIG - sesuaikan serverKey/clientKey jika perlu
\Midtrans\Config::$serverKey = '#';
\Midtrans\Config::$clientKey = '#';
\Midtrans\Config::$isProduction = false;
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

// DB config
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'lapanganbola';
$mysqli = @new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
    $msg = 'DB connection failed: ' . $mysqli->connect_error;
    log_msg($msg);
    http_response_code(500);
    echo json_encode(['error' => $msg]);
    exit;
}

// === Validate & sanitize input ===
$id_pesanan = isset($_POST['id_pesanan']) ? intval($_POST['id_pesanan']) : null;
$id_user    = isset($_POST['id_user']) ? intval($_POST['id_user']) : null;
$total_bayar_raw = $_POST['total_bayar'] ?? null;

// Normalize total_bayar -> float (Midtrans requires >= 0.01)
if ($total_bayar_raw !== null) {
    // remove non-digit except dot and comma
    $clean = preg_replace('/[^\d.,]/', '', (string)$total_bayar_raw);
    $clean = str_replace(',', '.', $clean);
    $total_bayar = is_numeric($clean) ? floatval($clean) : null;
} else {
    $total_bayar = null;
}

$nama_customer = isset($_POST['nama']) ? trim($_POST['nama']) : 'Pelanggan';
$email_raw = isset($_POST['email']) ? trim($_POST['email']) : '';
$phone_raw = isset($_POST['phone']) ? trim($_POST['phone']) : '';

// Basic validations
if (empty($id_user) || (empty($id_pesanan) && empty($total_bayar))) {
    http_response_code(400);
    $msg = 'Parameter missing: id_user dan total_bayar (atau id_pesanan valid) diperlukan.';
    log_msg($msg . ' POST=' . json_encode($_POST));
    echo json_encode(['error' => $msg]);
    exit;
}
if ($total_bayar === null || $total_bayar <= 0) {
    http_response_code(400);
    $msg = 'total_bayar invalid. Must be a number > 0.';
    log_msg($msg . ' total_raw=' . $total_bayar_raw);
    echo json_encode(['error' => $msg]);
    exit;
}

// email validation: include only if valid
$email_customer = null;
if (!empty($email_raw) && filter_var($email_raw, FILTER_VALIDATE_EMAIL)) {
    $email_customer = $email_raw;
}

// phone include only if not empty
$phone_customer = null;
if (!empty($phone_raw)) $phone_customer = $phone_raw;

// create order_id (unique)
$order_id = 'INV-' . ($id_pesanan ?? '0') . '-' . time();

// Insert initial row to tb_pembayaran (ensure NOT NULL columns filled)
$insert_sql = "INSERT INTO tb_pembayaran (id_pesanan,id_user,status_pembayaran,total_bayar,metode_pembayaran,tanggal_pembayaran,snap_token,transaksi_id_midtrans)
               VALUES (?, ?, 'pending', ?, 'midtrans_snap', NOW(), '', ?)";
$stmt = $mysqli->prepare($insert_sql);
if (!$stmt) {
    $msg = 'DB prepare error (insert): ' . $mysqli->error;
    log_msg($msg);
    http_response_code(500);
    echo json_encode(['error' => $msg]);
    exit;
}
$trans_id_temp = $order_id; // store order_id temporarily in transaksi_id_midtrans
if (!$stmt->bind_param('iids', $id_pesanan, $id_user, $total_bayar, $trans_id_temp)) {
    $msg = 'DB bind_param error (insert): ' . $stmt->error;
    log_msg($msg);
    http_response_code(500);
    echo json_encode(['error' => $msg]);
    exit;
}
if (!$stmt->execute()) {
    $msg = 'DB execute error (insert): ' . $stmt->error;
    log_msg($msg);
    http_response_code(500);
    echo json_encode(['error' => $msg]);
    exit;
}
$id_pembayaran = $stmt->insert_id;
$stmt->close();

// Build customer_details only with valid fields
$customer_details = ['first_name' => $nama_customer];
if ($email_customer) $customer_details['email'] = $email_customer;
if ($phone_customer) $customer_details['phone'] = $phone_customer;

// Build item_details: ensure price numeric and matches gross_amount
$item_details = [
    [
        'id' => $id_pesanan ? (string)$id_pesanan : 'ITEM-' . $id_pembayaran,
        'price' => $total_bayar,
        'quantity' => 1,
        'name' => 'Pembayaran #' . ($id_pesanan ?? $id_pembayaran)
    ]
];

// Prepare params
$params = [
    'transaction_details' => [
        'order_id' => $order_id,
        'gross_amount' => $total_bayar
    ],
    'item_details' => $item_details,
    'customer_details' => $customer_details
];

// extra safeguard: double-check gross == sum(item.price * qty)
$sum = 0;
foreach ($item_details as $it) {
    $sum += floatval($it['price']) * intval($it['quantity']);
}
if (abs($sum - floatval($total_bayar)) > 0.0001) {
    // mismatch -> rollback pembayaran row? update status error
    $stmtErr = $mysqli->prepare("UPDATE tb_pembayaran SET status_pembayaran = 'error' WHERE id_pembayaran = ?");
    if ($stmtErr) {
        $stmtErr->bind_param('i', $id_pembayaran);
        $stmtErr->execute();
        $stmtErr->close();
    }
    $msg = "Total mismatch: gross_amount={$total_bayar}, sum_item={$sum}";
    log_msg($msg . ' PARAMS: ' . json_encode($params));
    http_response_code(400);
    echo json_encode(['error' => $msg]);
    exit;
}

try {
    // call Midtrans Snap
    $snap = \Midtrans\Snap::createTransaction($params);

    // normalize result token/redirect
    $token = null;
    $redirect_url = null;
    if (is_array($snap)) {
        $token = $snap['token'] ?? null;
        $redirect_url = $snap['redirect_url'] ?? null;
    } elseif (is_object($snap)) {
        $token = $snap->token ?? null;
        $redirect_url = $snap->redirect_url ?? null;
    }

    // Update snap_token (never NULL) and keep transaksi_id_midtrans as order_id for matching
    $safe_snap_token = $token ?? $redirect_url ?? '';
    $stmt2 = $mysqli->prepare("UPDATE tb_pembayaran SET snap_token = ? WHERE id_pembayaran = ?");
    if ($stmt2) {
        $stmt2->bind_param('si', $safe_snap_token, $id_pembayaran);
        $stmt2->execute();
        $stmt2->close();
    } else {
        log_msg("DB prepare failed (update snap_token): " . $mysqli->error);
    }

    // success response
    echo json_encode([
        'success' => true,
        'token' => $token ?? null,
        'redirect_url' => $redirect_url ?? null,
        'order_id' => $order_id,
        'id_pembayaran' => $id_pembayaran
    ]);
    exit;
} catch (Exception $e) {
    // mark pembayaran error
    $stmtErr = $mysqli->prepare("UPDATE tb_pembayaran SET status_pembayaran = 'error' WHERE id_pembayaran = ?");
    if ($stmtErr) {
        $stmtErr->bind_param('i', $id_pembayaran);
        $stmtErr->execute();
        $stmtErr->close();
    }

    $msg = 'MIDTRANS EXCEPTION: ' . $e->getMessage();
    log_msg($msg . ' PARAMS: ' . json_encode($params));
    http_response_code(500);
    // forward readable error message if available
    echo json_encode(['error' => $msg]);
    exit;
}
