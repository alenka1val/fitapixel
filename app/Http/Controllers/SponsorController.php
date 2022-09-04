<?php

namespace App\Http\Controllers;

use App\Models\Sponsor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SponsorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
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
        $sponsorsCount = Sponsor::withoutTrashed()->count();
        $maxPage = ceil($sponsorsCount / $pageCount) ?: 1;
        $page = $request->page;

        if ($page > $maxPage) {
            return redirect(route('admin.sponsorIndex', ['page' => $maxPage]));
        }

        if ($page < 1) {
            return redirect(route('admin.sponsorIndex', ['page' => 1]));
        }

        $sponsors = null;

        if ($sponsorsCount <= $pageCount) {
            $sponsors = Sponsor::withoutTrashed()->orderBy('name', 'ASC')->get();
            $page = 1;
            $maxPage = 1;
        } else {
            $sponsors = Sponsor::withoutTrashed()->orderBy('name', 'ASC')
                ->paginate($pageCount);
        }

        return view('admin.entriesTable')
            ->with('header', "Sponzori")
            ->with('active', 'adminSponsorActive')
            ->with('entryColumns', array('name', 'url_path'))
            ->with('tableColumns', array("Názov sponzora", "Web sponzora"))
            ->with('indexURL', 'admin.sponsorIndex')
            ->with('editURL', 'admin.sponsorShow')
            ->with('deleteURL', 'admin.sponsorDestroy')
            ->with('confirm', 'Určite si prajete odstrániť sponzora?')
            ->with('confirmAttr', 'name')
            ->with('entries', $sponsors)
            ->with('page', $page ?: 1)
            ->with('maxPage', $maxPage);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return void
     */
    public function show($id)
    {

        $sponsor = null;
        if ($id != "new") {
            $sponsor = Sponsor::withoutTrashed()->where('id', $id)->first();
        } else {
            $sponsor = array(
                'id' => $id,
            );
        }

        return view('admin.entryDetail')
            ->with('header', "Sponzori")
            ->with('active', 'adminSponsorActive')
            ->with('storeURL', 'admin.sponsorStore')
            ->with('deleteURL', 'admin.sponsorDestroy')
            ->with('confirm', 'Určite si prajete odstrániť sponzora?')
            ->with('confirmAttr', 'name')
            ->with('cols', $this->get_cols())
            ->with('entry', $sponsor);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return void
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        $tmp = $request->validate([
            'name' => ['required', 'string'],
            'photo_path' => ['image'],
            'url_path' => ['required', 'string'],
        ]);

        if ($id == "new") {
            $id = null;
            if (is_null($request->photo_path)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['photo_path' => "File is required"]);
            }
        }

        DB::beginTransaction();
        try {
            if (!is_null($sponsor = DB::table('sponsors')->where('id', $id)
                ->whereNull('deleted_at')->first())) {
                $file_name = $sponsor->photo_path;
                if (!is_null($request->photo_path)) {
                    Storage::disk('public')->delete('sponsors/' . $sponsor->photo_path);
                    $file_name = substr($file_name, 0, strrpos($file_name, "."))
                        . "."
                        . $request->photo_path->getClientOriginalExtension();
                }

                Sponsor::where('id', $id)->update([
                    'id' => $id,
                    'name' => $request['name'],
                    'photo_path' => "/storage/sponsors/" . $file_name,
                    'url_path' => $request['url_path'],
                ]);
            } else {
                $file_name = date("Ymdhis_")
                    . (new EventController())->prepare_event_name($request->name)
                    . "."
                    . $request->photo_path->getClientOriginalExtension();

                Sponsor::create([
                    'name' => $request['name'],
                    'photo_path' => "/storage/sponsors/" . $file_name,
                    'url_path' => $request['url_path'],
                ]);
            }

            if (!is_null($request->photo_path)) $request->photo_path->storeAs('sponsors', $file_name, 'public');
            DB::commit();
        } catch
        (\Exception $e) {
            DB::rollback();
            Log::error($e);
            return redirect()->back()
                ->withInput();
        }
        return redirect(route('admin.sponsorIndex'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return void
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $sponsor = Sponsor::withoutTrashed()->find($id);
            $sponsor->delete();

            $file_name = substr($sponsor->photo_path,
                strpos($sponsor->photo_path, "/storage/") + strlen("/storage/"),
                strlen($sponsor->photo_path));

            Storage::disk('public')->move($file_name, $file_name . ".bak");

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
            return redirect()->back()
                ->withInput();
        }
        return redirect(route('admin.sponsorIndex'));
    }

    public function get_cols()
    {
        return array(
            array(
                'name' => 'name',
                'text' => 'Názov skupiny',
                'type' => 'text',
                'required' => 'required',
            ),
            array(
                'name' => 'photo_path',
                'text' => 'Logo sponzora',
                'type' => 'file',
                'required' => 'required',
            ),
            array(
                'name' => 'url_path',
                'text' => 'Web sponzora',
                'type' => 'text',
                'required' => 'required',
            ),
        );
    }
}
