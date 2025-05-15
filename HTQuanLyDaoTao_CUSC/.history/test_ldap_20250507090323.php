<?php
// Bật hiển thị lỗi
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Thông tin kết nối
$ldap_host = "10.0.0.2";
$ldap_port = 389;
$ldap_user = "cusc\\your_username"; // Định dạng domain\username
$ldap_password = "your_password";

// Tạo connection string
$ldap_conn_string = "ldap://{$ldap_host}:{$ldap_port}";
echo "Đang kết nối đến: {$ldap_conn_string}\n";

// Kết nối đến LDAP server
$ds = ldap_connect($ldap_conn_string);

if (!$ds) {
    die("Không thể kết nối đến LDAP server. Vui lòng kiểm tra:\n" .
        "1. LDAP server có đang chạy không\n" .
        "2. IP và port có chính xác không\n" .
        "3. Firewall có cho phép kết nối không\n");
}

// Thiết lập các options
ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);

// Thử bind
echo "Đang thử bind với username: {$ldap_user}\n";
if (!ldap_bind($ds, $ldap_user, $ldap_password)) {
    $error = ldap_error($ds);
    $errno = ldap_errno($ds);
    die("LDAP bind thất bại:\n" .
        "Mã lỗi: {$errno}\n" .
        "Thông báo: {$error}\n" .
        "Vui lòng kiểm tra:\n" .
        "1. Username và password có chính xác không\n" .
        "2. Định dạng username có đúng không (domain\\username)\n" .
        "3. Tài khoản có quyền truy cập LDAP không\n");
}

echo "LDAP bind successful";
ldap_close($ds);
