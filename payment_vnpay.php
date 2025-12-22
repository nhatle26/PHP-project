<?php
session_start();
require_once "model/connect.php";

/* =========================
   KIỂM TRA ĐẦU VÀO
========================= */
$user_id = $_SESSION['user_id'] ?? 0;
$cart    = $_SESSION['cart'] ?? [];

if ($user_id <= 0 || empty($cart)) {
    header("Location: view-cart.php");
    exit();
}

/* =========================
   LẤY DỮ LIỆU FORM
========================= */
$payment_method = $_POST['payment_method'] ?? 'cod';
$note = $_POST['note'] ?? '';

/* =========================
   TÍNH TỔNG TIỀN (LẤY GIÁ TỪ DB – CHUẨN SHOP THẬT)
========================= */
$total = 0;

foreach ($cart as $item) {
    $product_id = (int)($item['id'] ?? 0);
    $qty = isset($item['qty']) && $item['qty'] > 0 ? (int)$item['qty'] : 1;

    if ($product_id <= 0) continue;

    $stmtP = $conn->prepare("SELECT price FROM products WHERE id = ?");
    $stmtP->bind_param("i", $product_id);
    $stmtP->execute();
    $product = $stmtP->get_result()->fetch_assoc();

    $price = (float)($product['price'] ?? 0);
    $total += $price * $qty;
}

/* Nếu total vẫn = 0 thì chặn luôn */
if ($total <= 0) {
    die("Lỗi: Tổng tiền không hợp lệ. Kiểm tra lại giỏ hàng.");
}

/* =========================
   LƯU ĐƠN HÀNG VÀO DB
========================= */
$status = 0; // 0 = chờ thanh toán
$bank_name = ($payment_method === 'vnpay') ? 'VNPAY' : 'COD';

$stmt = $conn->prepare("
    INSERT INTO orders
    (total, date_order, status, user_id, payment_method, bank_name, note)
    VALUES (?, NOW(), ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "diisss",
    $total,
    $status,
    $user_id,
    $payment_method,
    $bank_name,
    $note
);

$stmt->execute();
$order_id = $stmt->insert_id;

/* =========================
   THANH TOÁN COD
========================= */
if ($payment_method === 'cod') {
    unset($_SESSION['cart']);
    header("Location: success.php?order_id=" . $order_id);
    exit();
}

/* =========================
   THANH TOÁN VNPAY
========================= */
$vnp_TmnCode    = "XXXXXXX"; // TMN CODE
$vnp_HashSecret = "YYYYYYYY"; // SECRET KEY
$vnp_Url        = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
$vnp_Returnurl  = "http://localhost/php_project/vnpay_return.php";

$inputData = array(
    "vnp_Version"    => "2.1.0",
    "vnp_TmnCode"    => $vnp_TmnCode,
    "vnp_Amount"     => $total * 100,
    "vnp_Command"    => "pay",
    "vnp_CreateDate" => date('YmdHis'),
    "vnp_CurrCode"   => "VND",
    "vnp_IpAddr"     => $_SERVER['REMOTE_ADDR'],
    "vnp_Locale"     => "vn",
    "vnp_OrderInfo"  => "Thanh toan don hang #" . $order_id,
    "vnp_OrderType"  => "billpayment",
    "vnp_ReturnUrl"  => $vnp_Returnurl,
    "vnp_TxnRef"     => $order_id
);

ksort($inputData);

$hashdata = "";
$query = "";

foreach ($inputData as $key => $value) {
    $hashdata .= ($hashdata ? '&' : '') . $key . "=" . $value;
    $query .= urlencode($key) . "=" . urlencode($value) . "&";
}

$vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
$vnp_Url .= "?" . $query . "vnp_SecureHash=" . $vnpSecureHash;

header("Location: " . $vnp_Url);
exit();
