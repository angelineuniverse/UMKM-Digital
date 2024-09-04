<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Master\Models\MAccessTab;
use Modules\Master\Models\MMenuTab;
use Modules\User\Models\MUserTab;
use Modules\User\Models\TAccesMenuUserTab;

class AccessMenuUserController extends Controller
{
    protected $controller;
    protected $tAccesMenuUserTab;
    public function __construct(
        Controller $controller,
        TAccesMenuUserTab $tAccesMenuUserTab,
    ) {
        $this->tAccesMenuUserTab = $tAccesMenuUserTab;
        $this->controller = $controller;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->controller->responses('MENU ACCESS ALL', 
            $this->tAccesMenuUserTab
            ->with(['menu' => function($b){
                $b->with('children');
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
            'm_user_tabs_id' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $this->tAccesMenuUserTab->create($request->all());
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
            $this->tAccesMenuUserTab->where('m_user_tabs_id',$id)->with(['menu' => function ($a){
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
            'm_user_tabs_id' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $this->tAccesMenuUserTab->where('id',$id)->update($request->all());
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
            $this->tAccesMenuUserTab->where('id',$id)->delete();
            DB::commit();
            return $this->controller->responses('MENU ACCESS DELETED', null);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400, $th->getMessage());
        }
    }
}
