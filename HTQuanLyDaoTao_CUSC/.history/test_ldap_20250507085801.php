<?php

// Thông tin kết nối LDAP
$ldap_host = "ldap://your-ldap-server";
$ldap_port = 389;
$ldap_dn = "dc=example,dc=com";
$ldap_user = "cn=admin,dc=example,dc=com";
$ldap_password = "your-password";

// Kết nối đến LDAP server
$ldap_conn = ldap_connect($ldap_host, $ldap_port);

if (!$ldap_conn) {
    die("Không thể kết nối đến LDAP server");
}

// Thiết lập phiên bản LDAP
ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($ldap_conn, LDAP_OPT_REFERRALS, 0);

// Bind với LDAP server
$bind = ldap_bind($ldap_conn, $ldap_user, $ldap_password);

if (!$bind) {
    die("Không thể bind với LDAP server: " . ldap_error($ldap_conn));
}

echo "Kết nối LDAP thành công!\n";

// Đóng kết nối
ldap_close($ldap_conn); 