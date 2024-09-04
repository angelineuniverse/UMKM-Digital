<?php

namespace Modules\Master\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Master\Models\MAccesMenuTab;
use Modules\Master\Models\MAccessTab;
use Modules\Master\Models\MMenuTab;

class AccessMenuController extends Controller
{
    protected $controller;
    protected $mMenuTab;
    protected $mAccessTab;
    protected $mAccesMenuTab;
    public function __construct(
        Controller $controller,
        MMenuTab $mMenuTab,
        MAccesMenuTab $mAccesMenuTab,
        MAccessTab $mAccessTab
    ) {
        $this->mMenuTab = $mMenuTab;
        $this->mAccessTab = $mAccessTab;
        $this->mAccesMenuTab = $mAccesMenuTab;
        $this->controller = $controller;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->controller->responses('MENU ACCESS ALL', 
            $this->mAccessTab
            ->with(['access_menu' => function($a){
                $a->with(['menu' => function($b){
                    $b->with('children');
                }]);
            }])
            ->get()
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->controller->validasi($request, [
            'm_menu_tabs_id' => 'required|exists:m_menu_tabs,id',
            'm_access_tabs_id' => 'required',
        ]);

        try {
            DB::beginTransaction();
            if($this->mMenuTab->where('id', $request->m_menu_tabs_id)->where('parent_id','!=',0)->first()){
                return $this->controller->responses('MENU ACCESS NOT CREATED', null);
            }
            $this->mAccesMenuTab->create($request->all());
            DB::commit();
            return $this->controller->responses('MENU ACCESS CREATED', $request->all());
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400, $th->getMessage());
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return $this->controller->responses('MENU ACCESS DETAIL', 
            $this->mAccesMenuTab->where('m_access_tabs_id',$id)->with(['menu' => function ($a){
                $a->with('children');
            }])->get()
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('master::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->controller->validasi($request, [
            'm_menu_tabs_id' => 'required',
            'm_access_tabs_id' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $this->mAccesMenuTab->where('id',$id)->update($request->all());
            DB::commit();
            return $this->controller->responses('MENU ACCESS UPDATED', $request->all());
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400, $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $this->mAccesMenuTab->where('id',$id)->delete();
            DB::commit();
            return $this->controller->responses('MENU ACCESS DELETED', null);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400, $th->getMessage());
        }
    }
}
