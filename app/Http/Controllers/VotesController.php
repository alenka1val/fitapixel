<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VotesController extends Controller
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

    public function voteList()
    {
        $competitions = DB::table('events')
            ->select('id', 'name', 'description', 'url_path')
            ->whereRaw('started_at <= DATE(now())')
            ->whereRaw('voted_to > DATE(now())')
            ->get();
        $competitions = is_null($competitions) ? array() : $competitions;

        foreach ($competitions as $c) {
            $count = DB::table('votes')->select(DB::raw('count(*) AS count'))->where('event_id', $c->id)->first();
            $c->voted = !is_null($count) && $count->count > 0 ? "ÁNO" : "NIE";
        }

        return view('info.voteList')->with('events', $competitions);
    }

    public function voteIndex(Request $request)
    {
        $event = DB::table('events')->where('url_path', $request->competition)->first();
        if (is_null($event)) {
            return redirect(route('info.voteList'));
        }

        $photos = DB::table('photographies')
            ->join('events', 'photographies.event_id', '=', 'events.id')
            ->where('events.url_path', $request->competition)
            ->get();
        $photos = is_null($photos) ? array() : $photos;

        $votedPhotos = DB::table('photographies')
            ->select(DB::raw('max(filename) AS filename, max(photo_id) AS photo_id, max(events.id) AS event_id, max(votes.user_id) AS user_id, max(url_path) AS url_path, sum(value) AS value'))
            ->join('votes', 'votes.photo_id', '=', 'photographies.id')
            ->join('events', 'votes.event_id', '=', 'events.id')
            ->where('events.url_path', $request->competition)
            ->orderBy('value', 'DESC')
            ->groupBy('photo_id')
            ->get();
        $votedPhotos = is_null($votedPhotos) ? array() : $votedPhotos;

        return view('info.vote')
            ->with('votedPhotos', $votedPhotos)
            ->with('photos', $photos)
            ->with('eventName', $event->name);
    }

    public function voteStore(Request $request)
    {
        $value = 12;
        foreach ($request->votedPhotos as $votedPhoto) {
            DB::table('votes')->updateOrInsert([
                'user_id' => $votedPhoto->user_id,
                'photo_id' => $votedPhoto->photo_id,
                'event_id' => $votedPhoto->event_id,
                'value' => $value
            ]);
            $value -= 1;
        }

        return redirect(route('home'));
    }

}
