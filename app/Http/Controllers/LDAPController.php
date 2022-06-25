<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use phpDocumentor\Reflection\Types\String_;

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
        $login = $request->login != null ? $request->login : env('LDAP_USER');
        $password = $request->password != null ? $request->password : env('LDAP_PASS');

        $ldap_values = $this->LDAPLogin($login, $password);

        return view('auth.ldap')
            ->with("authenticated", $ldap_values['authenticated'])
            ->with("status", $ldap_values['status'])
            ->with("entries", $ldap_values['entries']);
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
                            if (array_search($v, $user[0]['host']) !== false) {
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
                    $entries['hosts'] = join(',', $user[0]['host']);
                    $entries['group'] = $user[0]['host'][0];
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
