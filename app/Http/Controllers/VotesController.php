<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VotesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        DB::beginTransaction();
        try {
            $competitions = DB::table('events')
                ->select('id', 'name', 'description', 'url_path')
                ->whereRaw('voted_at <= DATE(now())')
                ->whereRaw('voted_to > DATE(now())')
                ->whereNull('deleted_at')
                ->get();
            $competitions = is_null($competitions) ? array() : $competitions;

            foreach ($competitions as $c) {
                $count = DB::table('votes')
                    ->select(DB::raw('count(*) AS count'))
                    ->where('event_id', $c->id)
                    ->where('user_id', auth()->user()->id)
                    ->first();
                $c->voted = !is_null($count) && $count->count > 0 ? "ÁNO" : "NIE";
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
            return redirect()->back()
                ->withInput();
        }

        return view('info.voteList')->with('events', $competitions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $votesIds = explode(",", $request->votes);
        $value = 12;

        DB::beginTransaction();
        try {
            $deletes = Vote::where('user_id', auth()->user()->id)->where('event_id', $request->eventId)->delete();

            foreach ($votesIds as $votePhotoId) {
                $photo = DB::table('photographies')->where('id', $votePhotoId)
                    ->whereNull('deleted_at')->first();
                Vote::create([
                    'user_id' => auth()->user()->id,
                    'photo_id' => $photo->id,
                    'event_id' => $photo->event_id,
                    'value' => $value
                ]);

                $value -= 1;
                if ($value <= 0) break;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
            return redirect()->back()
                ->withInput();
        }

        return redirect(route('info.voteList'));
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return Response
     */
    public function show(Request $request)
    {
        DB::beginTransaction();
        try {
            $event = DB::table('events')->where('url_path', $request->competition)
                ->whereNull('deleted_at')->first();
            if (is_null($event)) {
                return redirect(route('info.voteList'));
            }

            $photos = DB::table('photographies')
                ->select(DB::raw('max(photographies.filename) AS filename, 
            max(photographies.id) AS id, 
            max(photographies.description) AS description, 
            sum(coalesce( CASE WHEN votes.user_id = ' . auth()->user()->id . ' THEN votes.value ELSE 0 END ,0)) as value'))
                ->join('events', 'photographies.event_id', '=', 'events.id')
                ->leftjoin('votes', 'votes.photo_id', '=', 'photographies.id')
                ->where('events.url_path', $request->competition)
                ->whereNull('events.deleted_at')
                ->whereNull('photographies.deleted_at')
                ->orderBy('value', 'DESC')
                ->groupBy('photographies.id')
                ->get();
            $photos = is_null($photos) ? array() : $photos;

            $votesList = array();
            foreach ($photos as $photo) {
                array_push($votesList, $photo->id);
            }
            $votes = join(",", $votesList);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
            return redirect()->back()
                ->withInput();
        }

        return view('info.vote')
            ->with('photos', $photos)
            ->with('votes', $votes)
            ->with('eventName', $event->name)
            ->with('eventId', $event->id);
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
     * @param Request $request
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
        //
    }

}
