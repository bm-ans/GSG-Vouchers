<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Vouchers;
use Illuminate\Http\Request;
use App\Http\Requests\StoreOrderRequest;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Orders::latest()->paginate(10);
		
		return response()->json([
			'status' => true,
			'orders' => $orders
		]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderRequest $request)
    {
        $order = new Orders();
        $order->order_amount = $request['order_amount'];
        $order->created_at = $request['created_at'];
		
		
		if(!empty($request['voucher_code'])) {
			
			$vouchers = Vouchers::where('voucher_code', '=', $request['voucher_code'])->firstOrFail();
			
			if($vouchers->status == 'used') {
				return response()->json([
					"status" => false,
					"message" => "The provided voucher is already used. Please enter a new voucher or remove it to place the order."
				], 200);
			} 
			
			if($vouchers->valid_till_date < date('Y-m-d')){
				return response()->json([
					"status" => false,
					"message" => "The provided voucher is expired. Please enter a valid voucher or remove it to place the order."
				], 200);
			}
			
			$order->voucher_id = $vouchers->id;
			$order->order_amount = $order->order_amount - $vouchers->amount ;
			$vouchers->update([
				'status' => 'used'
			]);
		}
		
		$order->save();
		
		return response()->json([
			"status" => true,
			"message" => "Order created successfully."
		], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vouchers  $vouchers
     * @return \Illuminate\Http\Response
     */
    public function show(Vouchers $vouchers)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vouchers  $vouchers
     * @return \Illuminate\Http\Response
     */
    public function edit(Vouchers $vouchers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vouchers  $vouchers
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vouchers $vouchers)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vouchers  $vouchers
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vouchers $vouchers)
    {
        //
    }
}
