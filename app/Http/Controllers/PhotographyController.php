<?php

namespace App\Http\Controllers;

use App\Http\Controllers\EventController;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Photography;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class PhotographyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
       
        $request->validate([
            'selected_year' => ['required', 'string', 'max:255'],
            'selected_event' => ['required', 'string', 'max:255'],
        ]);

        // GET years and events
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

        $selected_year = is_null($request['selected_year'])? key($events):$request['selected_year'];
        $selected_event = is_null($request['selected_event'])? $events[$selected_year][0]: array("id" => $request['selected_event'], 'name' => getEventNameFromId($event_list, $request['selected_event']));

        if (empty($events)) {
            return view('info.gallery')
                ->with('events', $events)
                ->with('photos', array())
                ->with('selected_year', $selected_year)
                ->with('selected_event', $selected_event);

        }


        $finished = DB::table('events')
            ->select(DB::raw('(case when DATE(now()) > voted_at then 1 else 0 end) as finished'))
            ->where('id', $request->selected_event)
            ->first();
        $finished = is_null($finished) ? 0 : $finished->finished;

        $photos = DB::table('photographies')
            ->select(DB::raw('photographies.*, coalesce(sum(votes.value), 0) as vote_sum, max(users.name) as user_name'))
            ->join('users', 'users.id', '=', 'photographies.user_id')
            ->leftJoin('votes', 'photographies.id', '=', 'votes.photo_id')
            ->where('photographies.event_id', $selected_event['id'])
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
            ->with('sponsors', $sponsors)
            ->with('finished', $finished)
            ->with('selected_year', $selected_year)
            ->with('selected_event', $selected_event);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Response
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
     * @param Request $request
     * @return Response
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
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return void
     */
    public function adminIndex(Request $request)
    {
        $pageCount = 10;
        $photosCount = Photography::withoutTrashed()->count();
        $maxPage = ceil($photosCount / $pageCount) ?: 1;
        $page = $request->page;

        if ($page > $maxPage) {
            return redirect(route('admin.photoIndex', ['page' => $maxPage]));
        }

        if ($page < 1) {
            return redirect(route('admin.photoIndex', ['page' => 1]));
        }

        $photos = null;

        if ($photosCount <= $pageCount) {
            $photos = Photography::withoutTrashed()
                ->select(DB::raw("*, CONCAT('user:', user_id, ', event:', event_id) AS column_2"))
                ->orderBy('user_id', 'ASC')->orderBy('event_id', 'ASC')
                ->get();
            $page = 1;
            $maxPage = 1;
        } else {
            $photos = Photography::withoutTrashed()
                ->select(DB::raw("*, CONCAT('user:', user_id, ', event:', event_id) AS column_2"))
                ->orderBy('user_id', 'ASC')
                ->orderBy('event_id', 'ASC')
                ->paginate($pageCount);
        }

        return view('admin.entriesTable')
            ->with('header', "Fotografie")
            ->with('active', 'adminPhotoActive')
            ->with('entryColumns', array('filename', 'column_2'))
            ->with('tableColumns', array("Názov súboru", "Id"))
            ->with('indexURL', 'admin.photoIndex')
            ->with('editURL', 'admin.photoShow')
            ->with('deleteURL', 'admin.photoDestroy')
            ->with('confirm', 'Určite si prajete odstrániť fotografiu?')
            ->with('confirmAttr', 'filename')
            ->with('entries', $photos)
            ->with('page', $page ?: 1)
            ->with('maxPage', $maxPage);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return Response
     */
    public function show($id)
    {
        $photo = null;
        if ($id != "new") {
            $photo = Photography::where('id', $id)->first();
        } else {
            $photo = array(
                'id' => $id,
            );
        }

        return view('admin.entryDetail')
            ->with('header', "Fotografie")
            ->with('active', 'adminPhotoActive')
            ->with('storeURL', 'admin.photoStore')
            ->with('deleteURL', 'admin.photoDestroy')
            ->with('confirm', 'Určite si prajete odstrániť fotografiu?')
            ->with('confirmAttr', 'filename')
            ->with('cols', $this->get_cols($this->get_options('user'), $this->get_options('event')))
            ->with('entry', $photo);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public
    function edit($id)
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
    public
    function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => ['required', 'integer'],
            'event_id' => ['required', 'integer'],
        ]);

        $user = DB::table('users')
            ->where('id', $request->user_id)->first();
        $event = DB::table('events')
            ->where('id', $request->event_id)->first();

        if (is_null($user)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['user_id' => "Fotograf neexistuje"]);
        }

        if (is_null($event)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['event_id' => "Súťaž neexistuje"]);
        }

        $request->validate([
            'description' => ['required', 'max:255'],
            'file' => ['image',
                'mimes:jpeg,png,jpg',
                "dimensions:min_width=$event->min_width",
                "dimensions:min_height=$event->min_height",
                "dimensions:max_width=$event->max_width",
                "dimensions:max_height=$event->max_height",
            ]
        ]);

        if ($id == "new") {
            $id = null;
            if (is_null($request->filename)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['filename' => "File is required"]);
            }

            $ratios = explode("x", $event->allowed_ratios);
            $data = getimagesize($request->filename);
            $width = $data[0];
            $height = $data[1];
            $ratio = round($width / $height, 1);

            if ($ratio != round($ratios[0] / $ratios[1], 1) && $ratio != round($ratios[1] / $ratios[0], 1)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['filename' => "The file has invalid image ratio dimensions."]);
            }
        }

        $competition_dir = $event->image_folder;

        DB::beginTransaction();
        try {
            if (!is_null($photo = DB::table('photographies')->where('id', $id)->first())) {
                $file_name = substr($photo->filename, strrpos($photo->filename, "/"), strlen($photo->filename));
                if (!is_null($request->filename)) {
                    Storage::disk('public')->delete($competition_dir . $file_name);
                    $file_name = "photo_"
                        . rand(10000, 99999)
                        . "_"
                        . date("Ymdhis")
                        . "."
                        . $request->filename->getClientOriginalExtension();
                }

                Photography::where('id', $id)->update([
                    'user_id' => $request->user_id,
                    'event_id' => $request->event_id,
                    'filename' => "/storage/$competition_dir/$file_name",
                    'description' => $request->description,
                ]);
            } else {
                $file_name = "photo_"
                    . rand(10000, 99999)
                    . "_"
                    . date("Ymdhis")
                    . "."
                    . $request->filename->getClientOriginalExtension();

                Photography::create([
                    'user_id' => $request->user_id,
                    'event_id' => $request->event_id,
                    'filename' => "/storage/$competition_dir/$file_name",
                    'description' => $request->description,
                ]);
            }

            if (!is_null($request->filename)) $request->filename->storeAs($competition_dir, $file_name, 'public');

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
            return redirect()->back()
                ->withInput();
        }

        return redirect(route('admin.photoIndex'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public
    function destroy($id)
    {
        DB::beginTransaction();
        try {
            $photo = Photography::find($id);
            $photo->delete();

            $file_name = substr($photo->filename,
                strpos($photo->filename, "/storage/") + strlen("/storage/"),
                strlen($photo->filename));

            Storage::disk('public')->move($file_name, $file_name . ".bak");
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
            return redirect()->back()
                ->withInput();
        }
        return redirect(route('admin.photoIndex'));
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

    public
    function get_cols($optionsUsers, $optionsEvents)
    {
        return array(
            array(
                'name' => 'filename',
                'text' => 'Fotografia',
                'type' => 'file',
                'required' => 'required',
            ),
            array(
                'name' => 'description',
                'text' => 'Opis',
                'type' => 'textarea',
                'required' => 'required',
                'placeholder' => 'Napíšte zopár slov o danej fotografii',
                'maxlength' => 255
            ),
            array(
                'name' => 'user_id',
                'text' => 'Fotograf',
                'type' => 'select',
                'required' => 'required',
                'options' => $optionsUsers
            ),
            array(
                'name' => 'event_id',
                'text' => 'Súťaž',
                'type' => 'select',
                'required' => 'required',
                'options' => $optionsEvents
            ),
        );
    }

    public
    function get_options($type)
    {
        if ($type == "user") {
            $options = User::withTrashed()
                ->select(DB::raw("CONCAT(name, '-' , email) AS text, id"))
                ->orderBy('name', 'ASC')
                ->get();

            return $options;
        } elseif ($type == "event") {
            $options = Event::withTrashed()
                ->select(DB::raw("CONCAT(name, '-' , started_at) AS text, id"))
                ->orderBy('name', 'ASC')
                ->get();

            return $options;
        }

        return null;
    }
}

function getEventNameFromId($event_list, $id){
    foreach ($event_list as $event) {
        if ($event->id == intval($id)){
            return $event->name;
        }
    }
}
