<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\LoginController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $group = DB::table('groups')->select('name', 'need_ldap')->where('id', $user->group_id)->first();
        $user['group'] = is_null($group) ? "Neznáma" : $group->name;
        $user['need_ldap'] = is_null($group) ? "" : $group->need_ldap;

        return view('users.profile')->with('user', $user);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = DB::table('groups')
            ->select(['id', 'name'])
            ->where('permission', 'photographer')
            ->orWhere('id', Auth::user()->group_id)
            ->get();

        return view('users/update')->with('groups', $groups)->with('user', Auth::user());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ]);

        $user = DB::table('users')->where('email', $request['email'])->first();
        if (!is_null($user) && $user->email != auth()->user()->email) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['email' => "Email is already in use"]);
        }

        if (!is_null($request->file)) {
            $file_name = "photo_" . rand(1000, 9999) . "_" . date("Ymdhis") . "." . $request->file->getClientOriginalExtension();
            $request['photo'] = "/storage/persons/$file_name";
            $request->file->storeAs("persons", $file_name, 'public');
        } else {
            $request['photo'] = "";
        }

        $old_need_ldap = DB::table('groups')
            ->select('need_ldap')
            ->where('id', auth()->user()->group_id)
            ->first();

        $need_ldap = DB::table('groups')
            ->select('need_ldap')
            ->where('id', $request['group_id'])
            ->first();

        if (is_null($need_ldap)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['message' => "Logged user have no valid Group"]);
        }

        if (!empty($need_ldap->need_ldap) && auth()->user()->group_id != $request['group_id']) {
            if (empty($request->ais_uid)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['ais_uid' => 'AIS ID is required']);
            }
            if (empty($request->password)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['password' => 'Password is required']);
            }
            if (empty($request->password_confirmation)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['password_confirmation' => 'Confirm password is required']);
            }

            if ($request->password != $request->password_confirmation) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['password' => 'Passwords do not match!']);
            }

            $ldap_values = (new LoginController())->LDAPLogin($request->ais_uid, $request->password, $need_ldap->need_ldap);

            if (!$ldap_values['authenticated']) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['password' => "AIS login error: " . $ldap_values['status']]);
            }
            $request['password'] = env('LDAP_USER_PASSWORD');
            $request['password_confirmation'] = env('LDAP_USER_PASSWORD');

            DB::table('users')->where('id', auth()->user()->id)->update(
                [
                    'ais_uid' => $request['ais_uid'],
                    'password' => $request['password']
                ]
            );
        }

        DB::table('users')->where('id', auth()->user()->id)->update(
            [
                'name' => $request['name'],
                'email' => $request['email'],
                'phone' => $request['phone'],
                'web' => $request['web'],
                'address_street' => $request['address_street'],
                'address_city' => $request['address_city'],
                'address_zip_code' => $request['address_zip_code'],
                'description' => $request['description'],
                'group_id' => $request['group_id'],
                'photo' => $request['photo'],
            ]
        );

        if (empty($need_ldap->need_ldap) && !empty($old_need_ldap->need_ldap)) {
            return redirect(route('users.passwordCreate'));
        } else {
            return redirect(route('users.profile'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return void
     */
    public function photos()
    {
        $photo_list = DB::table('photographies')
            ->where('user_id', Auth::user()->id)
            ->orderBy('event_id', 'DESC')
            ->get();

        $photos = array();
        foreach ($photo_list as $photo) {
            if (!isset($photos[$photo->event_id])) {
                $event_name = DB::table('events')
                    ->select('name')
                    ->where('id', $photo->event_id)
                    ->first();
                $event_name = is_null($event_name) ? "Neznáme" : $event_name->name;

                $photos[$photo->event_id] = array(
                    'name' => $event_name,
                    'photos' => array()
                );
            }
            array_push($photos[$photo->event_id]['photos'], $photo);
        }

        return view('users/photos')->with('photos', $photos);
    }

    function passwordCreate()
    {
        $need_ldap = DB::table('groups')
            ->select('need_ldap')
            ->where('id', auth()->user()->group_id)
            ->first();

        if (is_null($need_ldap)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['message' => "Logged user have no valid Group"]);
        }

        if (!empty($need_ldap->need_ldap)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['message' => 'Your group cannot change it`s password!']);
        }

        return view('users/passwordUpdate')->with('user', Auth::user());
    }

    function passwordStore(Request $request)
    {
        Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($request->password != $request->password_confirmation) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['password' => 'Passwords do not match!']);
        }

        $need_ldap = DB::table('groups')
            ->select('need_ldap')
            ->where('id', auth()->user()->group_id)
            ->first();
        $need_ldap = !is_null($need_ldap) ? $need_ldap->need_ldap : null;

        if (!empty($need_ldap->need_ldap)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['email' => 'Your group cannot change it`s password!']);
        }

        DB::table('users')->where('id', auth()->user()->id)->update(
            [
                'password' => Hash::make($request['password']),
            ]
        );

        return redirect(route('users.profile'));
    }
}
