<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LDAPController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // TODO: validation name, password, need_ldap_groups
        $user_name = $request->user or env('LDAP_USER');
        $ldap_dn = "uid=$user_name," . env('LDAP_DN');
        $ldap_password = $request->password or env('LDAP_PASS');

        $need_ldap_groups = null;
        $entries = array();
        $ldap_con = @ldap_connect(env('LDAP_HOSTNAME'), env('LDAP_PORT'));
        if ($ldap_con) {
            ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, env('LDAP_OPT_PROTOCOL_VERSION'));
            ldap_set_option($ldap_con, LDAP_OPT_REFERRALS, env('LDAP_OPT_REFERRALS'));

            $ldap_bind = @ldap_bind($ldap_con, $ldap_dn, $ldap_password);
            if ($ldap_bind) {
                $status = "Authenticated";

                $results = ldap_search($ldap_con, env('LDAP_DN'), "(uid=" . $user_name . ")", explode(",", env('LDAP_FIELDS')));
                if ($results === false) {
                    $status = "Problem finding your data: " . ldap_error($ldap_con);
                } else {
                    $user = ldap_get_entries($ldap_con, $results);
                    if (!empty($need_ldap_groups)) {
                        $need_ldap_groups = explode(',', $need_ldap_groups);
                        $_right = false;
                        foreach ($need_ldap_groups as $v) {
                            if (array_search($v, $user[0]['host']) !== false) {
                                $_right = true;
                                break;
                            }
                        }
                        if (!$_right) {
                            $status = "At AIS STU, you are not a member of the required group.";
                            return view('auth.ldap')->with("status", $status)->with("entries", $entries);
                        }
                    }
                    $entries['surname'] = $user[0]['sn'][0];
                    $entries['name'] = $user[0]['givenname'][0];
                    $entries['web'] = 'http://is.stuba.sk/lide/clovek.pl?id=' . $user[0]['uisid'][0] . '&lang=sk';
                }
            } else {
                $status = "Invalid Name or Password";
            }
        } else {
            $status = "LDAP server connection lost";
        }

        ldap_close($ldap_con);
        return view('auth.ldap')->with("status", $status)->with("entries", $entries);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
}
