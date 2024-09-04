<?php

namespace Modules\Master\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Master\Models\MMenuTab;

class MenuController extends Controller
{

    protected $controller;
    protected $mMenuTab;
    public function __construct(
        Controller $controller,
        MMenuTab $mMenuTab
    ) {
        $this->mMenuTab = $mMenuTab;
        $this->controller = $controller;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->controller->responses('MENU ALL', 
            $this->mMenuTab
            ->where('isactive',1)
            ->where('parent_id',0)
            ->with('children')
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
            'name' => 'required',
            'url' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $this->mMenuTab->create($request->all());
            DB::commit();
            return $this->controller->responses('MENU CREATED', $request->all());
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
        return view('master::show');
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
            'name' => 'required',
            'url' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $this->mMenuTab->where('id',$id)->update($request->all());
            DB::commit();
            return $this->controller->responses('MENU UPDATED', $request->all());
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
        //
    }
}
