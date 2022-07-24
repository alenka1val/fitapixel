<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
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
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return void
     */
    public function adminIndex(Request $request)
    {
        $pageCount = 10;
        $contentsCount = Content::count();
        $maxPage = ceil($contentsCount / $pageCount) ?: 1;
        $page = $request->page;

        if ($page > $maxPage) {
            return redirect(route('admin.contentIndex', ['page' => $maxPage]));
        }

        if ($page < 1) {
            return redirect(route('admin.contentIndex', ['page' => 1]));
        }

        $contents = null;

        if ($contentsCount <= $pageCount) {
            $contents = Content::orderBy('tab', 'ASC')->orderBy('position', 'ASC')->get();
            $page = 1;
            $maxPage = 1;
        } else {
            $contents = Content::orderBy('tab', 'ASC')->orderBy('position', 'ASC')
                ->paginate($pageCount);
        }

        return view('admin.entriesTable')
            ->with('header', "Web kontent")
            ->with('active', 'adminContentActive')
            ->with('entryColumns', array('name', 'tab'))
            ->with('tableColumns', array("Nadpis web kontentu", "Tab"))
            ->with('indexURL', 'admin.contentIndex')
            ->with('editURL', 'admin.contentShow')
            ->with('deleteURL', 'admin.contentDestroy')
            ->with('confirm', 'Určite si prajete odstrániť web kontent?')
            ->with('confirmAttr', 'name')
            ->with('entries', $contents)
            ->with('page', $page ?: 1)
            ->with('maxPage', $maxPage);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $content = null;
        if ($id != "new") {
            $content = Content::where('id', $id)->first();
        } else {
            $content = array(
                'id' => $id,
            );
        }

        return view('admin.entryDetail')
            ->with('header', "Web kontent")
            ->with('active', 'adminContentActive')
            ->with('storeURL', 'admin.contentStore')
            ->with('deleteURL', 'admin.contentDestroy')
            ->with('confirm', 'Určite si prajete odstrániť web kontent?')
            ->with('confirmAttr', 'name')
            ->with('cols', $this->get_cols($this->get_options(0)))
            ->with('entry', $content);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Content $content
     * @return \Illuminate\Http\Response
     */
    public function edit(Content $content)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request['position'] = $request['position'] ?: 0;

        $tmp = $request->validate([
            'name' => ['required', 'string'],
            'position' => ['integer'],
            'tab' => ['required', 'integer'],
            'photo' => ['image'],
        ]);

        if (is_null($this->get_options($request['tab']))) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['tab' => "Unknown tab"]);
        }

        if ($id == "new") {
            $id = null;
            if (is_null($request->photo)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['photo' => "File is required"]);
            }
        }

        if (!is_null($request->photo)) {
            $data = getimagesize($request->photo);
            $width = $data[0];
            $height = $data[1];
            $ratio = round($width / $height, 1);

            if ($ratio != round(3 / 2, 1)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['photo' => "The file has invalid image ratio dimensions, correct is 3x2."]);
            }
        }

        $storage_dir = asset('storage') . "/contents/";

        DB::beginTransaction();
        try {
            if (!is_null($content = DB::table('contents')->where('id', $id)->first())) {

                $file_name = $content->photo;
                if (!is_null($request->photo)) {
                    Storage::disk('public')->delete('sponsors/' . $content->photo);
                    $file_name = substr($file_name, 0, strrpos($file_name, "."))
                        . "."
                        . $request->photo->getClientOriginalExtension();
                }
                Content::where('id', $id)->update([
                    'id' => $id,
                    'name' => $request['name'],
                    'text' => $request['text'],
                    'position' => $request['position'],
                    'tab' => ($this->get_options($request['tab']))['text'],
                    'photo' => $storage_dir . $file_name,
                ]);
            } else {
                $file_name = date("Ymdhis_")
                    . (new EventController())->prepare_event_name($request->name)
                    . "."
                    . $request->photo->getClientOriginalExtension();

                Content::create([
                    'name' => $request['name'],
                    'text' => $request['text'],
                    'position' => $request['position'],
                    'tab' => ($this->get_options($request['tab']))['text'],
                    'photo' => $storage_dir . $file_name,
                ]);
            }

            if (!is_null($request->photo)) $request->photo->storeAs('contents', $file_name, 'public');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
            return redirect()->back()
                ->withInput();
        }
        return redirect(route('admin.contentIndex'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $content = Content::find($id);
            $content->delete();

            Storage::disk('public')->delete('contents/' . $content->photo);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
            return redirect()->back()
                ->withInput();
        }
        return redirect(route('admin.contentIndex'));
    }

    /**
     * Return all Contents by tab
     *
     * @param $tab
     * @return Collection
     */
    public function getContents($tab = null)
    {
        $contents = null;
        if (empty($tab)) {
            $contents = DB::table('contents')->orderBy('position', 'ASC')->get();
        } else {
            $contents = DB::table('contents')->where('tab', $tab)->orderBy('position', 'ASC')->get();
        }

        $contents = is_null($contents) ? array() : $contents;

        return $contents;
    }

    public function getAllJury()
    {
        $jury = DB::table('users')
            ->select(DB::raw('users.*'))
            ->join('groups', 'users.group_id', '=', 'groups.id')
            ->where('permission', 'jury')
            ->get();

        $jury = is_null($jury) ? array() : $jury;

        return $jury;
    }

    public function get_cols($options)
    {
        return array(
            array(
                'name' => 'name',
                'text' => 'Nadpis sekcie',
                'type' => 'text',
                'required' => 'required',
            ),
            array(
                'name' => 'text',
                'text' => 'Text zobrazený na webe',
                'type' => 'textarea',
                'required' => 'required',
                'placeholder' => 'Definujte čo budú používatelia vidieť',
            ),
            array(
                'name' => 'position',
                'text' => 'Pozícia na webe',
                'type' => 'number',
                'required' => '',
            ),
            array(
                'name' => 'tab',
                'text' => 'Tab na ktorom sa má záznam zobraziť',
                'type' => 'select',
                'required' => 'required',
                'options' => $options
            ),
            array(
                'name' => 'photo',
                'text' => 'Obrázok záznamu',
                'type' => 'file',
                'required' => 'required',
                'example' => "Pomer strán musí byť 3x2"
            ),
        );
    }

    public function get_options($id)
    {
        $options = array(
            array(
                'id' => 1,
                'text' => 'home'
            ),
            array(
                'id' => 2,
                'text' => 'competition'
            ),
        );

        if ($id == 0) {
            return $options;
        } else {
            foreach ($options as $o) {
                if ($o['id'] == $id) return $o;
            }
        }

        return null;
    }
}
