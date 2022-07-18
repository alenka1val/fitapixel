<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isEmpty;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $user = DB::table('users')
            ->whereRaw("email = '$request->email' or ais_uid = '$request->email'")
            ->first();
        if ($user == null) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['email' => 'Invalid Name or Password']);
        }

        $user_group = DB::table('groups')
            ->where('id', $user->group_id)
            ->first();
        if (is_null($user_group)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['email' => 'Invalid Group']);
        }

        $password = $request->password;
        if (!empty($user_group->need_ldap)) {
            $ldap_values = $this->LDAPLogin($user->ais_uid, $password, $user_group->need_ldap);
            if (!$ldap_values['authenticated']) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['email' => "AIS login ERROR:" . $ldap_values['status']]);
            }
            $password = env('LDAP_USER_PASSWORD');
        } else {
            if ($request->email != $user->email) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['email' => 'Invalid Name or Password']);
            }
        }

        $credentials = array(
            'email' => $user->email,
            'password' => $password
        );

        if (Auth::attempt($credentials)) {
            $request->session()->push('role', $user_group->permission);
            return redirect()->intended($this->redirectPath());
        }

        return redirect()->back()
            ->withInput()
            ->withErrors(['email' => 'Authentication failed']);
    }

    public function LDAPLogin($login = null, $password = null, $need_ldap_groups = "")
    {
        $ldap_dn = "uid=$login," . env('LDAP_DN');

        $return_object = array();
        $entries = array();
        $return_object['authenticated'] = false;
        $ldap_con = @ldap_connect(env('LDAP_HOSTNAME'), env('LDAP_PORT'));
        if ($ldap_con) {
            ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, env('LDAP_OPT_PROTOCOL_VERSION'));
            ldap_set_option($ldap_con, LDAP_OPT_REFERRALS, env('LDAP_OPT_REFERRALS'));

            $ldap_bind = @ldap_bind($ldap_con, $ldap_dn, $password);
            if ($ldap_bind) {
                $return_object['status'] = "Authenticated";

                $results = ldap_search($ldap_con, env('LDAP_DN'), "(uid=" . $login . ")", explode(",", env('LDAP_FIELDS')));
                if ($results === false) {
                    $return_object['status'] = "Problem finding your data: " . ldap_error($ldap_con);
                } else {
                    $user = ldap_get_entries($ldap_con, $results);
                    if (!empty($need_ldap_groups)) {
                        $need_ldap_groups = explode(',', $need_ldap_groups);
                        $_right = false;
                        foreach ($need_ldap_groups as $v) {
                            if (array_search($v, $user[0]['employeetype']) !== false) {
                                $_right = true;
                                break;
                            }
                        }
                        if (!$_right) {
                            $return_object['status'] = "At AIS STU, you are not a member of the required group.";
                            $return_object['entries'] = $entries;
                            return $return_object;
                        }
                    }
                    $entries['surname'] = $user[0]['sn'][0];
                    $entries['name'] = $user[0]['givenname'][0];
                    $entries['web'] = 'http://is.stuba.sk/lide/clovek.pl?id=' . $user[0]['uisid'][0] . '&lang=sk';
                    $entries['group'] = $user[0]['employeetype'][0];
                    $entries['mail'] = $user[0]['mail'][0];
                    $return_object['authenticated'] = true;
                }
            } else {
                $return_object['status'] = "Invalid Name or Password";
            }
        } else {
            $return_object['status'] = "LDAP server connection lost";
        }

        ldap_close($ldap_con);
        $return_object['entries'] = $entries;
        return $return_object;
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->forget('role');
        $request->session()->forget('webAdmin');
        $request->session()->flush();
        $request->session()->regenerate();

        return redirect('home');
    }
}
