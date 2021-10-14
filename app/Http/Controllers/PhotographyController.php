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
    function getFolderName()
    {
        $month = date("m");

        if ($month >= 1 && $month <=6 ){
            $season = "LJ" . date('Y');
            $name = 'Leto/Jeseň ' . date('Y');
        } else {
            $season = "ZJ" . date('Y');
            $name = 'Zima/Jar '. date('Y');
        }


        $event_id = Event::firstOrCreate([
            'name' => $name,
            'url_path' => $season,
        ])->id;

        return $event_id;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $event_id = $this->getFolderName();
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
        $user = Auth::user();

        $request->validate([
            'description' => 'required',
            'theme' => 'required',
        ]);


        $photography = Photography::create([
            'user_id' => $user->id,
            'event_id' => $this->getFolderName(),
            'filename' => "/storage/".date("Y")."/".$request->file->getBasename(),
            'description' => $request->description,
            'theme' => $request->theme,
        ]);

        $request->file->storeAs(date("Y"), $request->file->getBasename(), 'public');


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

    public function getResponse($event_id, $cat, $type)
    {
        $resultList = DB::table('votes')
            ->select(DB::raw('votes.photo_id, count(*) as count'))
            ->join('photographies', 'photographies.id', '=', 'votes.photo_id')
            ->where('votes.event_id', $event_id)
            ->where('votes.type', $type)
            ->where('photographies.theme', $cat)
            ->groupBy('votes.photo_id')
            ->orderBy('count', 'desc')
            ->limit(12)
            ->get();

        $tmpResultList = $resultList;

        for ($i = 0; $i < count($tmpResultList); $i++) {
            $resultList[$i]->photo = DB::table('photographies')
                ->where('id', $resultList[$i]->photo_id)
                ->get()[0];
            $resultList[$i]->photograph = DB::table('users')
                ->select('name')
                ->where('id', $resultList[$i]->photo->user_id)
                ->get()[0]->name;
        }

        return $resultList;
    }

    /**
     * Show results for photoes.
     *
     * @param Request $request
     * @return void
     */
    public function results(Request $request)
    {
        $resultCategoryList = ['Pôvab maličkosti', 'Farebná príroda', 'Výpoveď o človeku', 'M(i)esto, kde práve som'];
        $event_id = $this->getFolderName();
        $result = [];

        foreach ($resultCategoryList as $res) {
            $result +=
                array($res =>
                    array(
                        "users" => $this->getResponse($event_id, $res, 'users'),
                        'jury' => $this->getResponse($event_id, $res, 'jury')
                    )

            );
        }

        return view('photography.results')
            ->with('resultCategoryList', $resultCategoryList)
            ->with('resultList', $result);
    }


    
}
