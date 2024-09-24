<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Name;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $validatedKeyword = $request->validate([
            'keyword' => 'sometimes|required|string',
        ]);

        $query = Department::whereNull('deleted_at');

        if($validatedKeyword){
            $keywords = explode(' ', str_replace('　', ' ', $validatedKeyword['keyword']));
            foreach ($keywords as $word) {
                $query = $query->where('name_jp', 'like', '%' . $word . '%')
                               ->orWhere('name_en', 'like', '%' . $word . '%');
            }
            $totalCount = $query->count();
        }else{
            $totalCount = null;
        }

        $departments = $query->paginate(7);

        return view('departments.index',compact('departments','totalCount'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('departments.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedNames = $request->validate([
            'name_jp' => 'required|string|max:120',
            'name_en' => 'required|string|max:120',
        ]);

        $errors = [];

        if (Department::where('name_jp', $validatedNames['name_jp'])->exists()) {
            $errors['name_jp'] = 'この部署名はすでに存在しています。';
        }

        if (Department::where('name_en', $validatedNames['name_en'])->exists()) {
            $errors['name_en'] = 'この部署名(英語)はすでに存在しています。';
        }

        if (!empty($errors)) {
            return back()->withInput()->withErrors($errors);
        }

        $department = new Department;
        $department->name_jp = $validatedNames['name_jp'];
        $department->name_en = $validatedNames['name_en'];
        $department->save();

        return redirect()->route('department.index')->with('success', '部署の登録に成功しました。');


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,Department $department)
    {
        $validatedKeyword = $request->validate([
            'keyword' => 'sometimes|required|string',
        ]);

        $userIds = $department->users->pluck('id');
        $query = Name::whereIn('user_id',$userIds);

        if($validatedKeyword){
            $keywords = explode(' ', str_replace('　', ' ', $validatedKeyword['keyword']));
            foreach ($keywords as $word) {
                $query = $query->where('fn_jp', 'like', '%' . $word . '%')
                               ->orWhere('fn_jp_hira', 'like', '%' . $word . '%')
                               ->orWhere('fn_jp_kata', 'like', '%' . $word . '%')
                               ->orWhere('fn_en', 'like', '%' . $word . '%')
                               ->orWhere('ln_jp', 'like', '%' . $word . '%')
                               ->orWhere('ln_jp_hira', 'like', '%' . $word . '%')
                               ->orWhere('ln_jp_kata', 'like', '%' . $word . '%')
                               ->orWhere('ln_en', 'like', '%' . $word . '%');
            }
            $totalCount = $query->count();
        }else{
            $totalCount = null;
        }

        $userNames = $query->paginate(7);

        return view('departments.show',compact('department','userNames','totalCount'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department)
    {
        return view('departments.edit',compact('department'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Department $department)
    {
        $validatedNames = $request->validate([
            'name_jp' => 'required|string|max:120',
            'name_en' => 'required|string|max:120',
        ]);

        $errors = [];

        if (Department::where('name_jp', $validatedNames['name_jp'])->exists()) {
            $errors['name_jp'] = 'この部署名はすでに存在しています。';
        }

        if (Department::where('name_en', $validatedNames['name_en'])->exists()) {
            $errors['name_en'] = 'この部署名(英語)はすでに存在しています。';
        }

        if (!empty($errors)) {
            return back()->withInput()->withErrors($errors);
        }

        $department->name_jp = $validatedNames['name_jp'];
        $department->name_en = $validatedNames['name_en'];
        $department->save();

        return redirect()->route('department.index')->with('success', '部署名の変更に成功しました。');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('department.index')->with('success', '部署の削除に成功しました。');
    }
}
