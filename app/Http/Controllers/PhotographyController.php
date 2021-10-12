<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Photography;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class PhotographyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $event_id = DB::table('events')->select('id')->where('url_path', $request->{'event_path'})->get()[0]->id;
        $photoList = DB::table('photographies')->where('event_id', $event_id)->get();
        $tmpPhotoList = $photoList;

        for ($i = 0; $i < count($tmpPhotoList); $i++) {
            $photoList[$i]->photograph = DB::table('users')->select('name')->where('id', $photoList[$i]->user_id)->get()[0]->name;
        }

        return view('photography.gallery')->with('photoList', $photoList);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('photography.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd(date("Y"));
        $user = Auth::user();

        $request->validate([
            'description' => 'required',
            'theme' => 'required',
        ]);


        $photography = Photography::create([
            'user_id' => $user->id,
            'filename' => $request->myfile->getBasename(),
            'description' => $request->description,
            'theme' => $request->theme,
        ]);

        $request->myfile->storeAs(date("Y"), $request->myfile->getBasename());


        return view('home');
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
     * Show results for photoes.
     *
     * @param Request $request
     * @return void
     */
    public function results(Request $request)
    {
        $event_id = DB::table('events')->select('id')->where('url_path', $request->{'event_path'})->get()[0]->id;
        $usersResultList = DB::table('votes')
            ->select(DB::raw('photo_id, count(*) as count'))
            ->where('event_id', $event_id)
            ->where('type', 'users')
            ->groupBy('photo_id')
            ->orderBy('count', 'desc')
            ->limit(12)
            ->get();
        $juryResultList = DB::table('votes')
            ->select(DB::raw('photo_id, count(*) as count'))
            ->where('event_id', $event_id)
            ->where('type', 'jury')
            ->groupBy('photo_id')
            ->orderBy('count', 'desc')
            ->limit(12)
            ->get();

        $tmpUsersResultList = $usersResultList;
        $tmpJuryResultList = $juryResultList;

        for ($i = 0; $i < count($tmpUsersResultList); $i++) {
            $usersResultList[$i]->photo = DB::table('photographies')
                ->where('id', $usersResultList[$i]->photo_id)
                ->get()[0];
            $usersResultList[$i]->photograph = DB::table('users')
                ->select('name')
                ->where('id', $usersResultList[$i]->photo->user_id)
                ->get()[0]->name;
        }

        for ($i = 0; $i < count($tmpJuryResultList); $i++) {
            $juryResultList[$i]->photo = DB::table('photographies')
                ->where('id', $juryResultList[$i]->photo_id)
                ->get()[0];
            $juryResultList[$i]->photograph = DB::table('users')
                ->select('name')
                ->where('id', $juryResultList[$i]->photo->user_id)
                ->get()[0]->name;
        }

        $resultCategoryList = ['Pôvab maličkosti','Farebná príroda','Výpoveď o človeku','M(i)esto, kde práve som'];

        return view('photography.results')
            ->with('resultCategoryList', $resultCategoryList)
            ->with('usersResultList', $usersResultList)
            ->with('juryResultList', $juryResultList);
    }
}
