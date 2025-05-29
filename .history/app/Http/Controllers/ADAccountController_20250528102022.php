<?php

namespace App\Http\Controllers;

use Adldap\Laravel\Facades\Adldap;
use App\Models\ADAccount;
use Illuminate\Http\Request;

class ADAccountController extends Controller
{
    public function index()
    {
        $accounts = ADAccount::all();
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

    public function syncFromAD()
{
    try {
        $adUsers = Adldap::search()->users()->get();

        foreach ($adUsers as $adUser) {
            $username = $adUser->getAccountName();

            if (!$username) continue;

            ADAccount::updateOrCreate(
                ['username' => $username],
                [
                    'display_name' => $adUser->getDisplayName() ?? '',
                    'email' => $adUser->getEmail() ?? '',
                    'user_type' => 'staff' // Có thể cần ánh xạ thông minh hơn
                ]
            );
        }

        return response()->json(['status' => 'success']);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}

}