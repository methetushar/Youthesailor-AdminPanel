<?php

namespace App\Http\Controllers;

use App\Http\Resources\Resource;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Activity::latest();
        if (!empty($request->field_name) && !empty($request->value)) {
            $query->where($request->field_name, 'like', '%' . $request->value . '%');
        }
        if (!empty($request->description)) {
            $query->where('description', 'like', '%' . $request->description . '%');
        }
        if (!empty($request->dominion)) {
            $query->where('log_name', $request->dominion);
        }
        if (!empty($request->action)) {
            $query->where('description', 'like', '%' . $request->action . '%');
        }
        $datas = $query->paginate($request->pagination);
        return new Resource($datas);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Activity $activity
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if ($request->format() == 'html') {
            return view('layouts.backend_app');
        }
        Activity::find($id)->update(['status' => 'r']);
        return Activity::find($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $activity = Activity::find($id);
        if ($activity->delete()) {
            return response()->json(['message' => 'Delete Successfully!'], 200);
        } else {
            return response()->json(['message' => 'Delete Unsuccessfully!'], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Activity $activity
     * @return \Illuminate\Http\Response
     */
    public function allRead()
    {
        Activity::where('status', 'ur')->update(['status' => 'r']);
        return response()->json(['message' => 'Read Successfully!'], 200);
    }
}
