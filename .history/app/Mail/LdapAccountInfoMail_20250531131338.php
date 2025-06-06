<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\LdapAccount;

class LdapAccountInfoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ldapAccount;

    public function __construct(LdapAccount $ldapAccount)
    {
        $this->ldapAccount = $ldapAccount;
    }

    public function build()
    {
        return $this->subject('Thông Tin Tài Khoản Hệ Thống CUSC')
            ->view('emails.ldap_account_info')
            ->with([
                'username' => $this->ldapAccount->username,
                'password' => $this->ldapAccount->initial_password,
                'full_name' => $this->ldapAccount->full_name
            ]);
    }
}