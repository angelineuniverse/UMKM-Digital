<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\PersonalAccessToken;
use Modules\User\Emails\AuthRegister;
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
        return $this->controller->responses('USER ALL', $this->mUserTab->where('isactive',1)->get());
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
            'm_access_tabs_id' => 'required',
            'password' => 'required|min:8',
        ]);

        try {
            DB::beginTransaction();
            $request['password'] = Hash::make($request->password);
            $users = $this->mUserTab->create($request->all());
            DB::commit();
            $tokens = $users->createToken('Angeline-UMKM');
            Mail::to($request->email)->send(new AuthRegister($users, $tokens->plainTextToken));
            return $this->controller->responses('USER CREATED', $users);
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
            abort(401, 'Informasi akun yang anda masukan salah !');
        }

        if ($this->mUserTab->where('email', $request->email)
            ->where('isactive', 0)->first()
        ) {
            abort(400, "Akun anda belum diaktivasi");
        }

        $tokenResult = auth()->user()->createToken('Angeline-UMKM');
        return $this->controller->responses('LOGIN SUKSES', 
        [ 'token' => $tokenResult->plainTextToken ], 
        [
            'theme' => 'success',
            'title' => 'Login berhasil !',
            'body' => 'Selamat datang kembali di UMKM Digital',
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
        $this->controller->validasi($request, [
            'email' => 'required|email|max:50',
            'password' => 'required|min:8',
        ]);

        try {
            DB::beginTransaction();
            $request['password'] = Hash::make($request->password);
            $user = $this->mUserTab->find($id);
            $user->update($request->all());
            DB::commit();
            return $this->controller->responses('USER UPDATE', null);
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
            $user = $this->mUserTab->find($id);
            $user->delete();
            DB::commit();
            return $this->controller->responses('USER DELETE', null);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400, $th->getMessage());
        }
    }

    public function logout(){
        Auth::user()->tokens()->delete();
        return $this->controller->responses('LOGOUT', null);
    }

    public function activateAccount($token)
    {
        $tokens = PersonalAccessToken::findToken($token);
        $user = $tokens->tokenable;
        if ($user) {
            DB::beginTransaction();
            $user->update([
                'isactive' => 1,
            ]);
            DB::commit();
            return view('user::emailactive');
        }
        abort(404, 'Token yang anda masukan salah');
    }
}
