<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ContentController;
use App\Http\Controllers\EventController;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isNull;

class HomeController extends Controller
{
//    /**
//     * Create a new controller instance.
//     *
//     * @return void
//     */
//    public function __construct()
//    {
//        $this->middleware('auth');
//    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $content_list = (new ContentController())->getContents("home");
        $content_list = !is_null($content_list) ? $content_list : array();

        $event_list = (new EventController())->getAllRunningEvents();
        $event_list = !is_null($event_list) ? $event_list : array();

        return view('home')
            ->with('contents', $content_list)
            ->with('events', $event_list);
    }

    public function indexJury()
    {
        $jury_list = (new ContentController())->getAllJury();
        $jury_list = is_null($jury_list) ? array() : $jury_list;

        $event_list = (new EventController())->getAllRunningEvents();
        $event_list = !is_null($event_list) ? $event_list : array();
        return view('info.judges')
            ->with('jury_list', $jury_list)
            ->with('events', $event_list);
    }
}
