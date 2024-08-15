<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\User\Models\MUserTab;

class UserController extends Controller
{
    protected $mUserTab;
    protected $controller;
    public function __construct(MUserTab $mUserTab, Controller $controller) {
        $this->mUserTab = $mUserTab;
        $this->controller = $controller;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->controller->responses('USER ALL', $this->mUserTab->all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->controller->validasi($request, [
            'email' => 'required|email|max:50',
            'password' => 'required|min:8',
        ]);

        try {
            DB::beginTransaction();
            $request['password'] = Hash::make($request->password);
            $users = $this->mUserTab->create($request->all());
            DB::commit();
            $tokens = $users->createToken('Angeline-UMKM');
            return $this->controller->responses('USER CREATED', [
                'token' => $tokens->plainTextToken
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400, $th->getMessage());
        }
    }

    public function login(Request $request){
        $this->controller->validasi($request, [
            'email' => 'required|email|max:50',
            'password' => 'required|min:8',
        ]);

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return $this->controller->responses('EMAIL/PASSWORD SALAH', null);
        }

        $tokenResult = auth()->user()->createToken('Angeline-UMKM');
        return $this->controller->responses('LOGIN SUKSES', [
            'token' => $tokenResult->plainTextToken
        ]);
    }

    /**
     * Show the specified resource.
     */
    public function show()
    {
        return $this->controller->responses('ME', auth()->user());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('user::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
