<?php

namespace App\Http\Controllers;

use App\Http\Controllers\EventController;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Photography;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


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

        $finished = DB::table('events')
            ->select(DB::raw('(case when DATE(now()) > voted_at then 1 else 0 end) as finished'))
            ->where('id', $request->event_id)
            ->first();
        $finished = is_null($finished) ? 0 : $finished->finished;

        $photos = DB::table('photographies')
            ->select(DB::raw('photographies.*, coalesce(sum(votes.value), 0) as vote_sum, max(users.name) as user_name'))
            ->join('users', 'users.id', '=', 'photographies.user_id')
            ->leftJoin('votes', 'photographies.id', '=', 'votes.photo_id')
            ->where('photographies.event_id', $request->event_id)
            ->groupBy(DB::raw('photographies.id'))
            ->orderBy('vote_sum', 'DESC')
            ->get();

        $place = 0;
        $counter = 1;
        $prev_value = 0;
        foreach ($photos as $photo) {
            if ($prev_value != $photo->vote_sum) {
                $place = $counter;
                $prev_value = $photo->vote_sum;
            }
            $photo->place = $place;
            $counter += 1;
        }

        $sponsors = DB::table('sponsors')
            ->join('sponsor_events', "sponsors.id", '=', "sponsor_events.sponsor_id")
            ->where('sponsor_events.event_id', $request->event_id)
            ->get();
        $sponsors = is_null($sponsors) ? array() : $sponsors;

        return view('info.gallery')
            ->with('events', $events)
            ->with('photos', $photos)
            ->with('event_id', $request->event_id)
            ->with('year', $request->year)
            ->with('finished', $finished)
            ->with('sponsors', $sponsors);
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
        $event = DB::table('events')
            ->where('id', $request->competition_id)->first();

        if (is_null($event)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['cmp_id' => "Súťaž neexistuje"]);
        }

        $request->validate([
            'description' => ['required', 'max:255'],
            'competition_id' => ['required', 'integer'],
            'file' => ['required',
                'image',
                'mimes:jpeg,png,jpg',
                "dimensions:min_width=$event->min_width",
                "dimensions:min_height=$event->min_height",
                "dimensions:max_width=$event->max_width",
                "dimensions:max_height=$event->max_height",
            ]
        ]);

        $ratios = explode("x", $event->allowed_ratios);
        $data = getimagesize($request->file);
        $width = $data[0];
        $height = $data[1];
        $ratio = round($width / $height, 1);

        if ($ratio != round($ratios[0] / $ratios[1], 1) && $ratio != round($ratios[1] / $ratios[0], 1)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['file' => "The file has invalid image ratio dimensions."]);
        }

        DB::beginTransaction();
        try {
            $competition_dir = $event->image_folder;

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

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
            return redirect()->back()
                ->withInput();
        }

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
