<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Models\Name;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('users.index', [
            'users' => User::with('name')->get()
        ]);
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(StoreUserRequest $request)
    {
        $validatedData = $request->validated();

        $user = new User;
        $user->email = $validatedData['email'];
        $user->password = bcrypt($validatedData['password']);
        $user->employee_no = $validatedData['employee_no'];
        $user->joining_date = $validatedData['joining_date'];
        $user->save();

        $name = new Name;
        $name->fn_jp = $validatedData['fn_jp'];
        $name->fn_jp_hira = $validatedData['fn_jp_hira'];
        $name->fn_jp_kata = $validatedData['fn_jp_kata'];
        $name->fn_en = $validatedData['fn_en'];
        $name->ln_jp = $validatedData['ln_jp'];
        $name->ln_jp_hira = $validatedData['ln_jp_hira'];
        $name->ln_jp_kata = $validatedData['ln_jp_kata'];
        $name->ln_en = $validatedData['ln_en'];
        $name->oln_jp = $validatedData['oln_jp'] ?? null;
        $name->oln_jp_hira = $validatedData['oln_jp_hira'] ?? null;
        $name->oln_jp_kata = $validatedData['oln_jp_kata'] ?? null;
        $name->oln_en = $validatedData['oln_en'] ?? null;
        $name->mn_jp = $validatedData['mn_jp'] ?? null;
        $name->mn_jp_hira = $validatedData['mn_jp_hira'] ?? null;
        $name->mn_jp_kata = $validatedData['mn_jp_kata'] ?? null;
        $name->mn_en = $validatedData['mn_en'] ?? null;
        $name->english_notation = $validatedData['display_first_name'] ?? false;
        $name->user_id = $user->id;
        $name->save();

        return redirect()->route('user.index')->with('success', 'ユーザーの登録に成功しました。');
    }
}
