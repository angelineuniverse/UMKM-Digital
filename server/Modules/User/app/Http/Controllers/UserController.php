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
use Modules\Master\Models\MAccessTab;
use Modules\User\Emails\AuthRegister;
use Modules\User\Models\MUserTab;

class UserController extends Controller
{
    protected $mUserTab;
    protected $controller;
    protected $mAccessTab;
    public function __construct(
        MUserTab $mUserTab,
        Controller $controller,
        MAccessTab $mAccessTab
    ) {
        $this->mUserTab = $mUserTab;
        $this->controller = $controller;
        $this->mAccessTab = $mAccessTab;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->controller->responses(
            'USER ALL',
            $this->mUserTab->where('isactive', 1)->get()
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->controller->responses(
            "FORM USER",
            array(
                [
                    "key" => "name",
                    "name" => null,
                    "type" => 'text',
                    "label" => "Masukan Nama",
                    "placeholder" => "Masukan Nama",
                    "isRequired" => true,
                ],
                [
                    "key" => "email",
                    "email" => null,
                    "type" => 'text',
                    "label" => "Masukan Email",
                    "placeholder" => "Masukan Email Aktif",
                    "isRequired" => true,
                ],
                [
                    "key" => "password",
                    "password" => null,
                    "type" => 'password',
                    "label" => "Masukan Password",
                    "placeholder" => "Masukan password minimal 8 char",
                    "isRequired" => true
                ],
                [
                    "key" => "m_access_tabs_id",
                    "m_access_tabs_id" => null,
                    "type" => 'select',
                    "label" => "Tentukan Akses",
                    "placeholder" => "Pilih minimal 1 Akses",
                    "isRequired" => true,
                    "list" => [
                        "keyValue" => "id",
                        "keyoption" => "title",
                        "options" => $this->mAccessTab->where('id', '>', 2)->get()
                    ]
                ],
                [
                    "key" => "phone",
                    "phone" => null,
                    "type" => 'number',
                    "label" => "Masukan No Whatsapp",
                    "placeholder" => "Masukan No Whatsapp",
                ],
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->controller->validasi($request, [
            'name' => 'required',
            'email' => 'required|email|max:50|unique:m_user_tabs,email',
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
            return $this->controller->responses(
                'USER CREATED',
                $users,
                [
                    "title" => "Pengguna baru berhasil dibuat",
                    "body" => "Informasi pengguna berhasil disimpan, periksa email untuk aktivasi akun",
                    "theme" => 'success'
                ]
            );
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
            abort(400, 'Informasi akun yang anda masukan salah !');
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
        $user = $this->mUserTab->where('id', $id)->with('mAccessTab')->first();
        return $this->controller->responses(
            "FORM EDIT USER",
            array(
                [
                    "key" => "name",
                    "name" => $user->name,
                    "type" => 'text',
                    "label" => "Masukan Nama",
                    "placeholder" => "Masukan Nama",
                    "isRequired" => true,
                ],
                [
                    "key" => "email",
                    "email" => $user->email,
                    "type" => 'text',
                    "label" => "Masukan Email",
                    "placeholder" => "Masukan Email Aktif",
                    "isRequired" => true,
                ],
                [
                    "key" => "m_access_tabs_id",
                    "m_access_tabs_id" => $user->m_access_tabs_id,
                    "type" => 'select',
                    "label" => "Tentukan Akses",
                    "placeholder" => $user->mAccessTab->title,
                    "isRequired" => true,
                    "list" => [
                        "keyValue" => "id",
                        "keyoption" => "title",
                        "options" => $this->mAccessTab->where('id', '>', 2)->get()
                    ]
                ],
                [
                    "key" => "phone",
                    "phone" => $user->phone,
                    "type" => 'number',
                    "label" => "Masukan No Whatsapp",
                    "placeholder" => "Masukan No Whatsapp",
                ],
            )
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->controller->validasi($request, [
            'email' => 'required|email|max:50',
            'm_access_tabs_id' => 'required',
            'name' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $user = $this->mUserTab->find($id);
            $user->update($request->all());
            DB::commit();
            return $this->controller->responses('USER UPDATE', null, [
                "title" => "Pengguna berhasil diupdate",
                "body" => "Informasi pengguna berhasil diganti dan disimpan",
                "theme" => 'success'
            ]);
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
            return $this->controller->responses('USER DELETE', null, [
                "title" => "Pengguna berhasil dihapus",
                "body" => "Informasi pengguna sepenuhnya dihapus dari system",
                "theme" => 'success'
            ]);
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

    public function userList(Request $request)
    {
        return $this->controller->responsesList(
            "PROFILE INDEX",
            $this->mUserTab->where('id', '!=', auth()->user()->id)
                ->with('mAccessTab')
                ->paginate(10),
            array(
                [
                    'key' => 'name',
                    'className' => 'font-interbold uppercase max-w-[50px]',
                    'name' => "Nama Pengguna",
                    'type' => "string"
                ],
                [
                    'key' => 'email',
                    'name' => "Nama Email",
                    'className' => 'min-w-10 w-10 max-w-10',
                    'type' => "string"
                ],
                [
                    'key' => 'phone',
                    'name' => "No Whatsapp",
                    'type' => "string"
                ],
                [
                    'key' => 'm_access_tab.title',
                    'name' => "Akses",
                    'type' => "string"
                ],
                [
                    'key' => 'status',
                    'name' => "Status",
                    'type' => "string"
                ],
                [
                    'key' => 'action',
                    'type' => "action",
                    'ability' => ['SHOW', 'DELETE']
                ],
            )
        );
    }
}
