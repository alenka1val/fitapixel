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
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $request->session()->forget('webAdmin');
        $content_list = (new ContentController())->getContents("home");

        $event_list = (new EventController())->getAllRunningEvents();

        return view('home')
            ->with('contents', $content_list)
            ->with('events', $event_list);
    }

    public function indexJury()
    {
        $jury_list = (new ContentController())->getAllJury();

        $event_list = (new EventController())->getAllRunningEvents();
        return view('info.judges')
            ->with('jury_list', $jury_list)
            ->with('events', $event_list);
    }

    public function adminIndex(Request $request)
    {
        $request->session()->push('webAdmin', 1);

        return redirect(route('admin.eventIndex'));
    }
}
