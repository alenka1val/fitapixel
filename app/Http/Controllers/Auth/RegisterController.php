<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Auth\LoginController;
use function PHPUnit\Framework\isEmpty;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
//        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {

        if (auth()->user()) {
            $user_group = DB::table('groups')
                ->where('id', auth()->user()->group_id)
                ->first();

            if (is_null($user_group)){
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['user' => "Logged user have no valid Group"]);
            }

            if ($user_group->permission == "admin") {
                $groups = DB::table('groups')
                    ->select(['id', 'name'])
                    ->get();
            }
        } else {
            $groups = DB::table('groups')
                ->select(['id', 'name'])
                ->where('permission', 'photographer')
                ->get();
        }

        return view('auth/register')->with('groups', $groups);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'group_id' => ['required', 'integer'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            'web' => $data['web'],
            'address_street' => $data['address_street'],
            'address_city' => $data['address_city'],
            'address_zip_code' => $data['address_zip_code'],
            'ais_uid' => $data['ais_uid'],
            'description' => $data['description'],
            'group_id' => $data['group_id'],
            'photo' => $data['photo'],
        ]);
    }

    public function register(Request $request)
    {
        try {
            $this->validator($request->all())->validate();
        } catch (ValidationException $e) {
            Log::error("register ERROR: $e");
        }

        $need_ldap = DB::table('groups')->select('need_ldap')
            ->where('id', $request->group_id)
            ->first();
        $need_ldap = !is_null($need_ldap) ? $need_ldap->need_ldap : null;
        if (is_null($need_ldap)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['group_id' => 'Invalid Group']);
        }

        if (!empty($need_ldap)) {
            $ldap_values = (new LoginController())->LDAPLogin($request->ais_uid, $request->password, $need_ldap);

            if (!$ldap_values['authenticated']) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['password' => "AIS login error: " . $ldap_values['status']]);
            }
            $request['password'] = env('LDAP_USER_PASSWORD');
            $request['password_confirmation'] = env('LDAP_USER_PASSWORD');
        }

        if (!is_null($request->file)){
            $file_name = "photo_".rand(1000,9999)."_".date("Ymdhis").".".$request->file->getClientOriginalExtension();
            $request['photo'] = "/storage/persons/$file_name";
            $request->file->storeAs("persons", $file_name, 'public');
        }

        $this->create($request->all());

        return redirect("home");
    }
}
