<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GroupController extends Controller
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
        $groupsCount = Group::withoutTrashed()->count();
        $maxPage = ceil($groupsCount / $pageCount);
        $page = $request->page;

        if ($page > $maxPage) {
            return redirect(route('admin.groupIndex', ['page' => $maxPage]));
        }

        if ($page < 1) {
            return redirect(route('admin.groupIndex', ['page' => 1]));
        }

        $groups = null;

        if ($groupsCount <= $pageCount) {
            $groups = Group::withoutTrashed()->orderBy('name', 'ASC')->get();
            $page = 1;
            $maxPage = 1;
        } else {
            $groups = Group::withoutTrashed()->orderBy('name', 'ASC')
                ->paginate($pageCount);
        }

        return view('admin.entriesTable')
            ->with('header', "Skupiny")
            ->with('title', 'groups')
            ->with('active', 'adminGroupActive')
            ->with('entryColumns', array('name', 'need_ldap'))
            ->with('tableColumns', array("Názov skupiny", "LDAP skupina"))
            ->with('indexURL', 'admin.groupIndex')
            ->with('editURL', 'admin.groupShow')
            ->with('deleteURL', 'admin.groupDestroy')
            ->with('confirm', 'Určite si prajete odstrániť skupinu?')
            ->with('confirmAttr', 'name')
            ->with('entries', $groups)
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

        $group = null;
        if ($id != "new") {
            $group = Group::where('id', $id)->first();
        } else {
            $group = array(
                'id' => $id,
            );
        }

        return view('admin.entryDetail')
            ->with('header', "Skupiny")
            ->with('title', 'groups')
            ->with('active', 'adminGroupActive')
            ->with('storeURL', 'admin.groupStore')
            ->with('deleteURL', 'admin.groupDestroy')
            ->with('confirm', 'Určite si prajete odstrániť skupinu?')
            ->with('confirmAttr', 'name')
            ->with('cols', $this->get_cols($this->get_options(0)))
            ->with('entry', $group);
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
        $request['need_ldap'] = $request['need_ldap'] ?: "";
        $tmp = $request->validate([
            'name' => ['required', 'string'],
            'need_ldap' => ['regex:/^([\p{L}_\-]+,?)*$/i'],
            'permission' => ['required', 'integer'],
        ]);

        if (is_null($this->get_options($request['permission']))) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['permission' => "Unknown permission"]);
        }

        $maxId = DB::table('groups')->select(DB::raw('max(id) as max_id'))->first();
        $id = $id == "new" ? $maxId->max_id + 1 : $id;

        DB::beginTransaction();
        try {
            if (!is_null($group = DB::table('groups')->where('id', $id)->first()))
                Group::where('id', $id)->update([
                    'id' => $id,
                    'name' => $request['name'],
                    'need_ldap' => $request['need_ldap'],
                    'permission' => ($this->get_options($request['permission']))['text'],
                ]);
            else {
                Group::create([
                    'id' => $id,
                    'name' => $request['name'],
                    'need_ldap' => $request['need_ldap'],
                    'permission' => ($this->get_options($request['permission']))['text'],
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
            return redirect()->back()
                ->withInput();
        }
        return redirect(route('admin.groupIndex'));
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
            $group = Group::find($id);
            $group->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
            return redirect()->back()
                ->withInput();
        }
        return redirect(route('admin.groupIndex'));
    }

    public function get_cols($options)
    {
        return array(
            array(
                'name' => 'name',
                'text' => 'Názov skupiny',
                'type' => 'text',
                'required' => 'required',
            ),
            array(
                'name' => 'need_ldap',
                'text' => 'LDAP skupiny',
                'type' => 'text',
                'pattern' => "^([\p{L}_\-]+,?)*$",
                'example' => "viacero skupin zadávať pomocou čiarky, napríklad: 'ext,staff'",
                'required' => '',
            ),
            array(
                'name' => 'permission',
                'text' => 'Práva',
                'type' => 'select',
                'required' => 'required',
                'options' => $options
            ),
        );
    }

    public function get_options($id)
    {
        $options = array(
            array(
                'id' => 1,
                'text' => 'photographer'
            ),
            array(
                'id' => 2,
                'text' => 'admin'
            ),
            array(
                'id' => 3,
                'text' => 'jury'
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
