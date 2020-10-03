<?php

namespace App\Http\Controllers\Backend\Menu;

use Illuminate\Http\Request;
use App\Model\Menu\Dominion;
use App\Http\Controllers\Controller;
use App\Http\Resources\Resource;

class DominionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->format() == 'html') {
            return view('layouts.backend_app');
        }
        $query = Dominion::latest();
        if (!empty($request->field_name) & !empty($request->value)) {
            $query->where($request->field_name, 'like', '%' . $request->value . '%');
        }
        $datas = $query->with('processes')->paginate($request->pagination);

        return new Resource($datas);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('layouts.backend_app');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Dominion::create($request->all());
        return response()->json(['message' => 'Create Successfully!'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Dominion  $dominion
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Dominion $dominion)
    {
        if ($request->format() == 'html') {
            return view('layouts.backend_app');
        }
        return $dominion;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Dominion  $dominion
     * @return \Illuminate\Http\Response
     */
    public function edit(Dominion $dominion)
    {
        return view('layouts.backend_app');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Dominion  $dominion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dominion $dominion)
    {
        $dominion->update($request->all());
        return response()->json(['message' => 'Update Successfully!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Dominion  $dominion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dominion $dominion)
    {
        if ($dominion->delete()) {
            return response()->json(['message' => 'Delete Successfully!'], 200);
        } else {
            return response()->json(['message' => 'Delete Unsuccessfully!'], 200);
        }
    }
    public function dominionList()
    {
        $datas = Dominion::orderBy('id', 'desc')->pluck('name', 'id')->toArray();
        return $datas;
    }
    public function AllDominion()
    {
        return Dominion::latest()->with('processes')->get();
    }
}
