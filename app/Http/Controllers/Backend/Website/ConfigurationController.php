<?php

/**
 * @Oshit Sutra Dhar
 */

namespace App\Http\Controllers\Backend\Website;

use App\Http\Resources\Resource;
use App\Model\Configuration;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ConfigurationController extends Controller
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
        $query  = Configuration::latest();
        if (!empty($request->field_name) & !empty($request->value)) {
            $query->where($request->field_name, 'like', '%' . $request->value . '%');
        }

        $datas  = $query->paginate(10);
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
        $confg = Configuration::first();
        $data = $request->all();
        if (!empty($confg)) {
            $type = "Update";
            // image=========
            $ex         = explode('storage/', $confg->logo);
            $oldImage   = $ex[1] ?? "";
            if (Storage::disk('public')->exists($oldImage)) :
                Storage::delete($oldImage);
            endif;
            $data['logo'] = Storage::putFile('upload/logo', $request->file('logo'));

            $confg->update($data);
        } else {
            $type = "Create";
            if (!empty($request->file('logo'))) {
                $data['logo'] = Storage::putFile('upload/logo', $request->file('logo'));
            }
            Configuration::create($data);
        }
        return response()->json(['message' => $type . ' Successfully!'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Configuration  $configuration
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Configuration $configuration)
    {
        if ($request->format() == 'html') {
            return view('layouts.backend_app');
        }
        return Configuration::first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Configuration  $configuration
     * @return \Illuminate\Http\Response
     */
    public function edit(Configuration $configuration)
    {
        return view('layouts.backend_app');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Configuration  $configuration
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Configuration $configuration)
    {
        return view('layouts.backend_app');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Configuration  $configuration
     * @return \Illuminate\Http\Response
     */
    public function destroy(Configuration $configuration)
    {
        $ex         = explode('storage/', $configuration->logo);
        $oldImage   = $ex[1] ?? "";
        if (Storage::disk('public')->exists($oldImage)) :
            Storage::delete($oldImage);
        endif;
        if ($configuration->delete()) {
            return response()->json(['message' => 'Delete Successfully!'], 200);
        } else {
            return response()->json(['message' => 'Delete Unsuccessfully!'], 200);
        }
    }

    /**
     * Validate form field.
     *
     * @return \Illuminate\Http\Response
     */
    public function validateCheck($request)
    {
        return $request->validate([
            //ex: 'name' => 'required|email|nullable|date|string|min:0|max:191',
        ]);
    }
}
