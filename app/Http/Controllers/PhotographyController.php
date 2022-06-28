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
        $competitions = (new EventController())->getAllRunningEvents();

        $competition_id = null;
        if (!is_null($request->competition)){
            foreach ($competitions as $competition){
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
     * Show the form for creating a new resource.
     *
     * @param Request $request
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
    public function results()
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
