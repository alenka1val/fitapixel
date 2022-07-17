<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\EventController;

class ContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function adminIndex()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function show(Content $content)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function edit(Content $content)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Content $content)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function destroy(Content $content)
    {
        //
    }

    /**
     * Return all Contents by tab
     *
     * @param $tab
     * @return Collection
     */
    public function getContents($tab = null){
        $contents = null;
        if (empty($tab)){
            $contents = DB::table('contents')->orderBy('position', 'ASC')->get();
        } else {
            $contents = DB::table('contents')->where('tab', $tab)->orderBy('position', 'ASC')->get();
        }

        $contents = is_null($contents) ? array() : $contents;

        return $contents;
    }

    public function getAllJury(){
        $jury = DB::table('users')
            ->select(DB::raw('users.*'))
            ->join('groups', 'users.group_id', '=', 'groups.id')
            ->where('permission', 'jury')
            ->get();

        $jury = is_null($jury) ? array() : $jury;

        return $jury;
    }
}
