<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ContentController;
use Illuminate\Http\Request;

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
        $contents = (new ContentController())->getContents("home");

        $contents_dict = array();
        foreach ($contents as $content) {
            $contents_dict[$content->name] = array('text' => $content->text, 'photo' => $content->photo);
        }

        return view('home')->with('contents', $contents_dict);
    }

    public function indexJury()
    {
        $jury_list = (new ContentController())->getAllJury();
        $jury_list = is_null($jury_list) ? array() : $jury_list;

        return view('info.judges')->with('jury_list', $jury_list);
    }
}
