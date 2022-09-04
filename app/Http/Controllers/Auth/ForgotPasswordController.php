<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Write code on Method
     *
     * @param Request $request
     * @return response()
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $user = DB::table('users')->select(DB::raw('users.name as name, groups.need_ldap as need_ldap'))
            ->where('email', $request->email)
            ->leftJoin('groups', 'users.group_id', '=', 'groups.id')
            ->whereNull('users.deleted_at')
            ->whereNull('groups.deleted_at')->first();
        if (is_null($user) || !empty($user->need_ldap)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['email' => 'Your group cannot change it`s password!']);
        }

        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::to($request->email)
            ->send(new ResetPasswordMail(
                ['name' => $user->name, 'resetLink' => route('password.reset', ['token' => $token])]
            ));

        return view('auth/passwords/email')->with('message', 'Na vašu emialovú adresu sme odoslali link na reset hesla!');
    }
}
