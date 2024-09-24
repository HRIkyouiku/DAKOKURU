<?php

namespace App\Http\Controllers;

use App\Models\WorkPlace;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class WorkPlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $query = WorkPlace::query()->where('deleted_at', null);

        if ($keyword !== null) {
            $keywords = explode(' ', str_replace('　', ' ', $keyword));
            foreach ($keywords as $word) {
                $query = $query->where('name', 'like', '%' . $word . '%');
            }
        }

        $places = $query->get();

        return view('work_places.index', [
            'places' => $places
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('work_places.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->input('name');
        $query = WorkPlace::query()->where('name', $name);
        $place = $query->first();

        if ($place !== null) {
            return back()->withInput();
        }

        $wordPlace = new WorkPlace;
        $wordPlace->name = $name;
        $wordPlace->save();

        return redirect(route('work_places.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $place =  WorkPlace::query()->where('id', $id)->first();

        return view('work_places.edit', [
            'place' => $place
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = $request->input('id');

        $workPlace = WorkPlace::find($id);
        $workPlace->name = $request->input('name');
        $workPlace->save();

        return redirect(route('work_places.edit', [
            'id' => $id
        ]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        WorkPlace::find($request->input('id'))->delete();
        return redirect(route('work_places.index'));
    }
}
