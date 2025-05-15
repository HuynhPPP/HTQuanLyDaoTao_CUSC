<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;

class LDAPTest extends Controller
{
    public function testConnection()
    {
        $domain = 'cusc.ctu.vn';
        $ldapconfig = [
            'host' => '10.0.0.2',
            'port' => 389,
            'basedn' => 'dc=cusc,dc=ctu,dc=vn',
        ];

        try {
            // Kết nối đến LDAP server
            $ds = ldap_connect($ldapconfig['host'], $ldapconfig['port']);
            if (!$ds) {
                return "Không thể kết nối đến LDAP server";
            }

            // Thiết lập các options
            ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);

            // Thử bind với tài khoản admin (nếu có)
            $bind = @ldap_bind($ds, "admin@{$domain}", "password");
            if (!$bind) {
                return "Không thể bind với LDAP server: " . ldap_error($ds);
            }

            // Tìm kiếm tất cả users
            $search = ldap_search($ds, $ldapconfig['basedn'], "(objectClass=user)");
            $entries = ldap_get_entries($ds, $search);

            $users = [];
            for ($i = 0; $i < $entries['count']; $i++) {
                $users[] = [
                    'username' => $entries[$i]['samaccountname'][0] ?? 'N/A',
                    'displayname' => $entries[$i]['displayname'][0] ?? 'N/A'
                ];
            }

            ldap_close($ds);
            return view('ldap-test', ['users' => $users]);

        } catch (Exception $e) {
            if (isset($ds)) {
                ldap_close($ds);
            }
            return "Lỗi: " . $e->getMessage();
        }
    }
} 