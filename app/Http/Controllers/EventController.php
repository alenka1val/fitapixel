<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $content_list = (new ContentController())->getContents("competition");

        $event_list = $this->getAllRunningEvents();

        return view('info.competition')
            ->with('contents', $content_list)
            ->with('events', $event_list);
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
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return void
     */
    public function adminIndex(Request $request)
    {
        $pageCount = 10;
        $eventsCount = Event::withoutTrashed()->count();
        $maxPage = ceil($eventsCount / $pageCount) ?: 1;
        $page = $request->page;

        if ($page > $maxPage) {
            return redirect(route('admin.eventIndex', ['page' => $maxPage]));
        }

        if ($page < 1) {
            return redirect(route('admin.eventIndex', ['page' => 1]));
        }

        $events = null;

        if ($eventsCount <= $pageCount) {
            $events = Event::withoutTrashed()->orderBy('name', 'ASC')->get();
            $page = 1;
            $maxPage = 1;
        } else {
            $events = Event::withoutTrashed()->orderBy('name', 'ASC')
                ->paginate($pageCount);
        }

        return view('admin.entriesTable')
            ->with('header', "S????a??e")
            ->with('active', 'adminEventActive')
            ->with('entryColumns', array('name', 'started_at'))
            ->with('tableColumns', array("N??zov s????a??e", "Za??iatok s????a??e"))
            ->with('indexURL', 'admin.eventIndex')
            ->with('editURL', 'admin.eventShow')
            ->with('deleteURL', 'admin.eventDestroy')
            ->with('confirm', 'Ur??ite si prajete odstr??ni?? s????a???')
            ->with('confirmAttr', 'name')
            ->with('entries', $events)
            ->with('page', $page ?: 1)
            ->with('maxPage', $maxPage);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $event = null;
        if ($id != "new") {
            $event = Event::where('id', $id)->first();
        } else {
            $event = array(
                'id' => $id,
                'min_width' => 720,
                'max_width' => 1920,
                'min_height' => 480,
                'max_height' => 1080,
                'allowed_ratios' => '3x2'
            );
        }

        return view('admin.entryDetail')
            ->with('header', "S????a??e")
            ->with('active', 'adminEventActive')
            ->with('storeURL', 'admin.eventStore')
            ->with('deleteURL', 'admin.eventDestroy')
            ->with('confirm', 'Ur??ite si prajete odstr??ni?? s????a???')
            ->with('confirmAttr', 'name')
            ->with('cols', $this->get_cols())
            ->with('entry', $event);
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
        $tmp = $request->validate([
            'name' => ['required', 'string'],
            'started_at' => ['required', 'date'],
            'finished_at' => ['required', 'date', 'after:started_at'],
            'voted_at' => ['required', 'date', 'after:started_at'],
            'voted_to' => ['required', 'date', 'after:voted_at'],
            'min_width' => ['integer'],
            'max_width' => ['integer'],
            'min_height' => ['integer'],
            'max_height' => ['integer'],
            'allowed_ratios' => ['string', 'regex:/^\d+x\d+$/i'],
            'description' => ['required', 'string'],
        ]);

        $url_path = date("Ymdhis_") . $this->prepare_event_name($request->name);
        $id = $id == "new" ? null : $id;

        DB::beginTransaction();
        try {
            if (!is_null($event = DB::table('events')->where('id', $id)->first())) {
                Event::where('id', $id)->update([
                    'id' => $id,
                    'name' => $request['name'],
                    'url_path' => $url_path,
                    'started_at' => $request['started_at'],
                    'finished_at' => $request['finished_at'],
                    'voted_at' => $request['voted_at'],
                    'voted_to' => $request['voted_to'] ?: $request['finished_at'],
                    'image_folder' => $url_path,
                    'min_width' => $request['min_width'],
                    'max_width' => $request['max_width'],
                    'min_height' => $request['min_height'],
                    'max_height' => $request['max_height'],
                    'allowed_ratios' => $request['allowed_ratios'],
                    'description' => $request['description']
                ]);
            } else {
                Event::create([
                    'name' => $request['name'],
                    'url_path' => $url_path,
                    'started_at' => $request['started_at'],
                    'finished_at' => $request['finished_at'],
                    'voted_at' => $request['voted_at'],
                    'voted_to' => $request['voted_to'] ?: $request['finished_at'],
                    'image_folder' => $url_path,
                    'min_width' => $request['min_width'],
                    'max_width' => $request['max_width'],
                    'min_height' => $request['min_height'],
                    'max_height' => $request['max_height'],
                    'allowed_ratios' => $request['allowed_ratios'],
                    'description' => $request['description']
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
            return redirect()->back()
                ->withInput();
        }
        return redirect(route('admin.eventIndex'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $event = Event::find($id);
            $event->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
            return redirect()->back()
                ->withInput();
        }
        return redirect(route('admin.eventIndex'));
    }

    /**
     * Return all running Events in current day
     *
     * @return Collection
     */
    public function getAllRunningEvents()
    {
        $competitions = DB::table('events')
            ->whereRaw('started_at <= DATE(now())')
            ->whereRaw('finished_at > DATE(now())')
            ->get();

        $competitions = is_null($competitions) ? array() : $competitions;

        return $competitions;
    }

    public function prepare_event_name($name)
    {
        $translate_table = Array(
            '??' => 'a',
            '??' => 'A',
            '??' => 'a',
            '??' => 'A',
            '??' => 'a',
            '??' => 'A',
            '??' => 'a',
            '??' => 'A',
            '??' => 'a',
            '??' => 'A',
            '??' => 'c',
            '??' => 'C',
            '??' => 'c',
            '??' => 'C',
            '??' => 'd',
            '??' => 'D',
            '??' => 'e',
            '??' => 'E',
            '??' => 'e',
            '??' => 'E',
            '??' => 'e',
            '??' => 'E',
            '??' => 'e',
            '??' => 'E',
            '??' => 'e',
            '??' => 'E',
            '??' => 'i',
            '??' => 'I',
            '??' => 'i',
            '??' => 'I',
            '??' => 'i',
            '??' => 'I',
            '??' => 'i',
            '??' => 'I',
            '??' => 'l',
            '??' => 'L',
            '??' => 'l',
            '??' => 'L',
            '??' => 'n',
            '??' => 'N',
            '??' => 'n',
            '??' => 'N',
            '??' => 'n',
            '??' => 'N',
            '??' => 'o',
            '??' => 'O',
            '??' => 'o',
            '??' => 'O',
            '??' => 'o',
            '??' => 'O',
            '??' => 'o',
            '??' => 'O',
            '??' => 'o',
            '??' => 'O',
            '??' => 'o',
            '??' => 'O',
            '??' => 'r',
            '??' => 'R',
            '??' => 'r',
            '??' => 'R',
            '??' => 's',
            '??' => 'S',
            '??' => 's',
            '??' => 'S',
            '??' => 't',
            '??' => 'T',
            '??' => 'u',
            '??' => 'U',
            '??' => 'u',
            '??' => 'U',
            '??' => 'u',
            '??' => 'U',
            '??' => 'u',
            '??' => 'U',
            '??' => 'u',
            '??' => 'U',
            '??' => 'u',
            '??' => 'U',
            '??' => 'y',
            '??' => 'Y',
            '??' => 'z',
            '??' => 'Z',
            '??' => 'z',
            '??' => 'Z',
            ' ' => '_',
            '-' => '_',
            '.' => '_',
            '?' => '_',
            '!' => '_',
            ')' => '_',
            '(' => '_',
            ':' => '_',
            '*' => '_',
            '+' => '_',
            '/' => '_',
            '\\' => '_',
        );

        return strtolower(strtr($name, $translate_table));
    }

    public function get_cols()
    {
        return array(
            array(
                'name' => 'name',
                'text' => 'N??zov s????a??e',
                'type' => 'text',
                'required' => 'required',
            ),
            array(
                'name' => 'started_at',
                'text' => 'Za??iatok prid??vania fotografi??',
                'type' => 'date',
                'required' => 'required',
            ),
            array(
                'name' => 'finished_at',
                'text' => 'Ukon??enie prid??vania fotografi??',
                'type' => 'date',
                'required' => 'required',
            ),
            array(
                'name' => 'voted_at',
                'text' => 'Za??iatok hlasovania',
                'type' => 'date',
                'required' => 'required',
            ),
            array(
                'name' => 'voted_to',
                'text' => 'Ukon??enie hlasovania',
                'type' => 'date',
                'required' => 'required',
            ),
            array(
                'name' => 'min_width',
                'text' => 'Minim??lna ????rka fotografie',
                'type' => 'number',
                'required' => 'required',
            ),
            array(
                'name' => 'max_width',
                'text' => 'Maxim??lna ????rka fotografie',
                'type' => 'number',
                'required' => 'required',
            ),
            array(
                'name' => 'min_height',
                'text' => 'Minim??lna v????ka fotografie',
                'type' => 'number',
                'required' => 'required',
            ),
            array(
                'name' => 'max_height',
                'text' => 'Maxim??lna v????ka fotografie',
                'type' => 'number',
                'required' => 'required',
            ),
            array(
                'name' => 'allowed_ratios',
                'text' => 'Pomer fotografie',
                'type' => 'text',
                'required' => 'required',
                'pattern' => "^\d+x\d+$",
                'example' => "napr??klad: 3x2"
            ),
            array(
                'name' => 'description',
                'text' => 'Opis',
                'type' => 'textarea',
                'required' => 'required',
                'placeholder' => 'Nap????te zop??r slov o danej s????a??i',
            ),
        );
    }
}
