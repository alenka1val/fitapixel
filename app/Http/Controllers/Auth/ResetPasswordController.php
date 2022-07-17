<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectTo = RouteServiceProvider::HOME;


    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param Request $request
     * @return Factory|View
     */
    public function showResetForm(Request $request)
    {
        $token = $request->route()->parameter('token');

        return view('auth.passwords.reset')->with(['token' => $token]);
    }


    /**
     * Write code on Method
     *
     * @param Request $request
     * @return response()
     */
    public function reset(Request $request)
    {
        $request->validate($this->rules(), $this->validationErrorMessages());

        if ($request->password != $request->password_confirmation) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['password' => 'Passwords do not match!']);
        }

        $updatePassword = DB::table('password_resets')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])
            ->first();

        if (!$updatePassword) {
            return back()->withInput()->withErrors(['email' => 'Invalid token!']);
        }

        DB::beginTransaction();
        try {
            $user = DB::table('users')->where('email', $request->email)
                ->update(['password' => Hash::make($request->password)]);

            DB::table('password_resets')->where(['email' => $request->email])->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
            return redirect()->back()
                ->withInput();
        }

        return view('auth/login')->with('message', 'Your password has been changed!');
    }
}
