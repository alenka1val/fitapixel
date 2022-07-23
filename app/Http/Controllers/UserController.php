<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\LoginController;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $user = Auth::user();
        $group = DB::table('groups')->select('name', 'need_ldap', 'permission')->where('id', $user->group_id)->first();
        $user['group'] = is_null($group) ? "Neznáma" : $group->name;
        $user['need_ldap'] = is_null($group) ? "" : $group->need_ldap;
        $user['permission'] = is_null($group) ? "" : $group->permission;

        return view('users.profile')->with('user', $user);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $groups = DB::table('groups')
            ->where('permission', 'photographer')
            ->orWhere('id', Auth::user()->group_id)
            ->get();

        return view('users/update')->with('groups', $groups)->with('user', Auth::user());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ]);

        DB::beginTransaction();
        try {
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
                        'password' => Hash::make($request['password']),
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
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
            return redirect()->back()
                ->withInput();
        }

        if (empty($need_ldap->need_ldap) && !empty($old_need_ldap->need_ldap)) {
            return redirect(route('users.passwordCreate'));
        } else {
            return redirect(route('users.profile'));
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return void
     */
    public function adminIndex(Request $request)
    {
        $pageCount = 10;
        $usersCount = User::withoutTrashed()->count();
        $maxPage = ceil($usersCount / $pageCount) ?: 1;
        $page = $request->page;

        if ($page > $maxPage) {
            return redirect(route('admin.userIndex', ['page' => $maxPage]));
        }

        if ($page < 1) {
            return redirect(route('admin.userIndex', ['page' => 1]));
        }

        $users = null;

        if ($usersCount <= $pageCount) {
            $users = User::withoutTrashed()->orderBy('name', 'ASC')->get();
            $page = 1;
            $maxPage = 1;
        } else {
            $users = User::withoutTrashed()->orderBy('name', 'ASC')
                ->paginate($pageCount);
        }

        return view('admin.entriesTable')
            ->with('header', "Používatelia")
            ->with('active', 'adminUserActive')
            ->with('entryColumns', array('name', 'email'))
            ->with('tableColumns', array("Nadpis web kontentu", "Email"))
            ->with('indexURL', 'admin.userIndex')
            ->with('editURL', 'admin.userShow')
            ->with('deleteURL', 'admin.userDestroy')
            ->with('confirm', 'Určite si prajete odstrániť používateľa?')
            ->with('confirmAttr', 'name')
            ->with('entries', $users)
            ->with('page', $page ?: 1)
            ->with('maxPage', $maxPage);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return Response
     */
    public function show($id)
    {

        $user = null;
        if ($id != "new") {
            $user = User::where('id', $id)->first();
        } else {
            $user = array(
                'id' => $id,
            );
        }

        return view('admin.entryDetail')
            ->with('header', "Používatelia")
            ->with('active', 'adminUserActive')
            ->with('storeURL', 'admin.userStore')
            ->with('deleteURL', 'admin.userDestroy')
            ->with('confirm', 'Určite si prajete odstrániť používateľa?')
            ->with('confirmAttr', 'name')
            ->with('cols', $this->get_cols($this->get_options(0)))
            ->with('entry', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
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
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $group = User::find($id);
            $group->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
            return redirect()->back()
                ->withInput();
        }
        return redirect(route('admin.userIndex'));
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

        DB::beginTransaction();
        try {
            DB::table('users')->where('id', auth()->user()->id)->update(
                [
                    'password' => Hash::make($request['password']),
                ]
            );
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
            return redirect()->back()
                ->withInput();
        }

        return redirect(route('users.profile'));
    }

    public function get_cols($options)
    {
        return array(
            array(
                'name' => 'name',
                'text' => 'Celé meno',
                'type' => 'text',
                'required' => 'required',
            ),
            array(
                'name' => 'email',
                'text' => 'Email',
                'type' => 'email',
                'required' => 'required',
            ),
            array(
                'name' => 'group_id',
                'text' => 'Skupina',
                'type' => 'select',
                'required' => 'required',
                'options' => $options
            ),
            array(
                'name' => 'phone',
                'text' => 'Telefónne číslo',
                'type' => 'phone',
                'required' => '',
            ),
            array(
                'name' => 'web',
                'text' => 'Webová stránka',
                'type' => 'text',
                'required' => '',
            ),
            array(
                'name' => 'address_street',
                'text' => 'Ulica',
                'type' => 'text',
                'required' => '',
            ),
            array(
                'name' => 'address_city',
                'text' => 'Mesto',
                'type' => 'text',
                'required' => '',
            ),
            array(
                'name' => 'address_zip_code',
                'text' => 'PSČ',
                'type' => 'text',
                'required' => '',
            ),
            array(
                'name' => 'ais_uid',
                'text' => 'AIS ID',
                'type' => 'text',
                'required' => '',
                'example' => '* Povinné iba ak zadáte skupinu s prihlásením cez LDAP',
            ),
            array(
                'name' => 'password',
                'text' => 'Heslo',
                'type' => 'password',
                'required' => '',
                'example' => '* Povinné iba ak zadáte skupinu bez prihlásením cez LDAP',
            ),
            array(
                'name' => 'confirm_password',
                'text' => 'Zopakujte heslo',
                'type' => 'password',
                'required' => '',
                'example' => '* Povinné iba ak zadáte skupinu bez prihlásením cez LDAP',
            ),
            array(
                'name' => 'photo',
                'text' => 'Fotografia',
                'type' => 'file',
                'required' => '',
                'example' => '* Povinné iba ak pracujete s používateľom skupiny "porodca", pomer musí byť 3X2',
            ),
            array(
                'name' => 'description',
                'text' => 'Opis',
                'type' => 'textarea',
                'required' => '',
                'placeholder' => 'Napíšte zopár slov o danej súťaži',
                'example' => '* Povinné iba ak pracujete s používateľom skupiny "porodca"',
            ),
        );
    }

    public function get_options($id)
    {
        $options = Group::select(DB::raw('name AS text, id'))->get();

        if ($id == 0) {
            return $options;
        } else {
            foreach ($options as $o) {
                if ($o['id'] == $id) return $o;
            }
        }

        return null;
    }
}
