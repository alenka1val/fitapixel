<?php

namespace App\Http\Controllers;

use App\Http\Controllers\EventController;
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
        $event_list = DB::table('events')
            ->select(DB::raw('date_part(\'year\', finished_at) as year, name, id'))
            ->orderBy(DB::raw('1'), 'DESC')
            ->orderBy('finished_at', 'DESC')
            ->orderBy('started_at', 'DESC')
            ->get();

        $events = array();
        foreach ($event_list as $e) {
            if (empty($events[$e->year])) {
                $events[$e->year] = array();
            }
            array_push($events[$e->year], array('name' => $e->name, 'id' => $e->id));
        }

        if (empty($events)) {
            return view('info.gallery')
                ->with('events', $events)
                ->with('photos', array())
                ->with('event_id', $request->event_id)
                ->with('year', $request->year);
        }

        $request['event_id'] = is_null($request->event_id) ? $events[array_keys($events)[0]][0]['id'] : $request->event_id;

        $photos = DB::table('photographies')
            ->select(DB::raw('photographies.*, sum(votes.value) as vote_sum, max(users.name) as user_name'))
            ->leftJoin('users', 'users.id', '=', 'photographies.user_id')
            ->leftJoin('votes', 'photographies.id', '=', 'votes.photo_id')
            ->where('votes.event_id', $request->event_id)
            ->groupBy(DB::raw('photographies.id'))
            ->orderBy('vote_sum', 'DESC')
            ->get();

        return view('info.gallery')
            ->with('events', $events)
            ->with('photos', $photos)
            ->with('event_id', $request->event_id)
            ->with('year', $request->year);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public
    function create(Request $request)
    {
        $competitions = (new EventController())->getAllRunningEvents();

        $competition_id = null;
        if (!is_null($request->competition)) {
            foreach ($competitions as $competition) {
                if ($competition->url_path == $request->competition) {
                    $competition_id = $competition->id;
                    break;
                }
            }
        }

        return view('photography.create')
            ->with('competition_id', $competition_id)
            ->with('competitions', $competitions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public
    function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'description' => ['required', 'max:255'],
            'competition_id' => ['required', 'integer'],
        ]);

        $competition_dir = DB::table('events')->select('image_folder')
            ->where('id', $request->competition_id)->first();
        if (is_null($competition_dir)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['cmp_id' => "Súťaž neexistuje"]);
        }

        $competition_dir = $competition_dir->image_folder;

        $file_name = "photo_"
            . rand(10000, 99999)
            . "_"
            . date("Ymdhis")
            . "."
            . $request->file->getClientOriginalExtension();

        Photography::create([
            'user_id' => $user->id,
            'event_id' => $request->competition_id,
            'filename' => "/storage/$competition_dir/$file_name",
            'description' => $request->description,
        ]);

        $request->file->storeAs($competition_dir, $file_name, 'public');


        return view('info.gallery');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function edit($id)
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
    public
    function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function destroy($id)
    {
        //
    }

    /**
     * Show results for photoes.
     *
     * @param Request $request
     * @return void
     */
    public
    function results()
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
