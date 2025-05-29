<?php

namespace App\Http\Controllers;

use LdapRecord\Container;
use LdapRecord\Models\ActiveDirectory\User as LdapUser;

use App\Models\ADAccount;
use Illuminate\Http\Request;

class ADAccountController extends Controller
{
    public function index()
    {
        $accounts = ADAccount::with(['user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Lấy thông tin chi tiết từ AD cho mỗi tài khoản
        foreach ($accounts as $account) {
            try {
                $adUser = \Adldap\Laravel\Facades\Adldap::search()
                    ->where('samaccountname', '=', $account->username)
                    ->first();

                if ($adUser) {
                    $account->ad_info = [
                        'displayname' => $adUser->getDisplayName(),
                        'email' => $adUser->getEmail(),
                        'department' => $adUser->getDepartment(),
                        'title' => $adUser->getTitle(),
                        'last_logon' => $adUser->getLastLogon(),
                        'account_expires' => $adUser->getAccountExpires(),
                        'is_active' => !$adUser->isDisabled()
                    ];
                }
            } catch (\Exception $e) {
                $account->ad_info = null;
            }
        }

        return view('admin.ad_accounts.index', compact('accounts'));
    }

    public function create()
    {
        return view('admin.ad_accounts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:ad_accounts',
            'display_name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'user_type' => 'required|in:admin,staff,teacher,student'
        ]);

        try {
            // Tạo user trong AD
            $user = Adldap::make()->user();

            $user->setAccountName($request->username)
                ->setDisplayName($request->display_name)
                ->setUserPrincipalName($request->username . '@cusc.edu.vn')
                ->setEmail($request->email)
                ->setPassword($request->password);

            // Thêm user vào group tương ứng
            $group = Adldap::search()->groups()->findBy('cn', $request->user_type);
            if ($group) {
                $user->addGroup($group);
            }

            $user->save();

            // Lưu thông tin vào CSDL local
            ADAccount::create([
                'username' => $request->username,
                'display_name' => $request->display_name,
                'email' => $request->email,
                'user_type' => $request->user_type
            ]);

            return redirect()->route('ad-accounts.index')
                ->with('success', 'Tạo tài khoản thành công');

        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    public function destroy($username)
    {
        try {
            // Xóa user từ AD
            $user = Adldap::search()->users()->findBy('samaccountname', $username);
            if ($user) {
                $user->delete();
            }

            // Xóa record từ CSDL local
            ADAccount::where('username', $username)->delete();

            return redirect()->route('ad-accounts.index')
                ->with('success', 'Xóa tài khoản thành công');

        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }
}