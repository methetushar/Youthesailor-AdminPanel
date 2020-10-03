<?php
/**
 * @Oshit Sutra Dhar
 */
namespace App\Http\Controllers\Backend;

use App\Model\Order;
use App\Http\Resources\Resource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
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
        $query  = Order::latest();
        if(!empty($request->field_name) && !empty($request->value)){
            $query->where($request->field_name,'like','%'.$request->value.'%');
        }

        $datas  = $query->paginate($request->pagination);
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
        Order::create($request->all());
        return response()->json(['message' => 'Create Successfully!'],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Order $order)
    {
        if ($request->format() == 'html') {
            return view('layouts.backend_app');
        }
        return $order;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        return view('layouts.backend_app');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $order->update($request->all());
        return response()->json(['message' => 'Update Successfully!'],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        if($order->delete()){
            return response()->json(['message' => 'Delete Successfully!'],200);
        }else{
            return response()->json(['message' => 'Delete Unsuccessfully!'],200);
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
