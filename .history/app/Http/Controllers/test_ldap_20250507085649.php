<?php
$ds = ldap_connect("10.0.0.2", 389);
ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);

if (!$ds) {
    die("LDAP connect failed");
}

if (!ldap_bind($ds, "cusc\\your_username", "your_password")) {
    die("LDAP bind failed: " . ldap_error($ds));
}

echo "LDAP bind successful";
ldap_close($ds);
