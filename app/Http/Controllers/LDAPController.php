<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LDAPController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = "newton";

        $ldap_dn = env('LDAP_DN');
        $ldap_dn = str_replace('user', $user, $ldap_dn);
        $ldap_password = "password";

        $ldap_con = ldap_connect(env('LDAP_HOSTNAME'));
        ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, env('LDAP_OPT_PROTOCOL_VERSION'));
        ldap_set_option($ldap_con, LDAP_OPT_REFERRALS, env('LDAP_OPT_REFERRALS'));

        $entries = null;
        $result = null;
        if (@ldap_bind($ldap_con, $ldap_dn, $ldap_password)) {
            $status = "Authenticated";

            $result=ldap_search($ldap_con, $ldap_dn, "(objectclass=*)", explode( ",", env('LDAP_FIELDS')));
            $entries = ldap_get_entries($ldap_con, $result);
        } else {
            $status = "Invalid Name or Password";
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
