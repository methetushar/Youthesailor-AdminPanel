<?php
/*
 *  @Developed By: Oshit Sutra Dar
 */

namespace App\Http\Controllers\Backend;

use Hash;
use Auth;
use App\Model\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Resource;
use Illuminate\Support\Facades\Storage;


class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        if ($request->format() == 'html') {
            return view('layouts.backend_app');
        }
    }

    public function adminInfo()
    {
        $admin = Admin::with('role', 'permisson')->find(Auth::user()->id);
        return $admin;
    }
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
        $query  = Admin::latest()->with('rolename')->where('status', 'a');
        if (!empty($request->field_name) & !empty($request->value)) {
            $query->where($request->field_name, 'like', '%' . $request->value . '%');
        }

        if ($request->allData) {
            return $query->get();
        } else {
            $datas = $query->paginate($request->pagination);
            return new Resource($datas);
        }
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
        if ($this->validateCheck($request)) {
            Admin::create($request->all());
            return response()->json(['message' => 'Create Successfully!'], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Admin $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Admin $admin)
    {
        if ($request->format() == 'html') {
            return view('layouts.backend_app');
        }
        if (Auth::guard('admin')->user()->role_id == 1) {
            return Admin::with('rolename')->find($admin->id);
        }
        return Admin::with('rolename')->find(Auth::guard('admin')->user()->id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Admin $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {
        return view('layouts.backend_app');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Admin $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin)
    {
        $ex = explode('storage/', $admin->profile);
        $oldImage = $ex[1] ?? "";
        if (!empty($request->file('profile'))) {
            if (Storage::disk('public')->exists($oldImage)) :
                Storage::delete($ex[1]);
            endif;
            $imgPath = Storage::putFile('upload/profile', $request->file('profile'));
        }
        $arr = [
            'name'      => $request->name,
            'role_id'   => $request->role_id ?? $admin->role_id,
            'mobile'    => $request->mobile,
            'address'   => $request->address,
            'profile'   => $imgPath ?? $oldImage,
        ];
        $admin->update($arr);
        return response()->json(['message' => 'Information Update Successfully!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        if ($admin->update(['status' => 'd'])) {
            return response()->json(['message' => 'Delete Successfully!'], 200);
        } else {
            return response()->json(['message' => 'Delete Unsuccessfully!'], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Admin $admin
     * @return \Illuminate\Http\Response
     */
    public function checkOldPassword(Request $request)
    {
        if (Auth::guard('admin')->user()->role_id == 1 && Auth::guard('admin')->user()->id != $request->id) {
            return response()->json(true);
        }
        if (Auth::guard('admin')->validate(['password' => $request->old_password, 'id' => $request->id])) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }
    //password change==============
    public function passwordChange(Request $request)
    {
        $request->validate([
            'new_password'      => 'required|min:6|required_with:confirm_password|same:confirm_password',
            'confirm_password'  => 'required|min:6'
        ]);
        $password = Hash::make($request->new_password);
        Admin::where('id', $request->id)->update(['password' => $password]);
        return response()->json(['message' => 'Password change successfully!!'], 200);
    }

    /**
     * Validate form field.
     *
     * @return \Illuminate\Http\Response
     */
    public function validateCheck($request)
    {
        return $request->validate([
            'name'          => 'required',
            'email'         => 'required|min:2|unique:admins',
            'password'      => 'required|min:6',
            'role_id'       => 'required'
        ]);
    }
}
