<?php

namespace App\Http\Controllers;

use App\Models\Vouchers;
use Illuminate\Http\Request;
use App\Http\Requests\StoreVoucherRequest;
use App\Http\Requests\UpdateVoucherRequest;
use App\Http\Resources\VoucherCollection;
use App\Http\Resources\VoucherResource;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$vouchers = Vouchers::latest()->paginate(10);
		
		$vouchers = Vouchers::all();
		$collection = VoucherResource::collection($vouchers);
		$filtered = $collection->where('status', $request['status']);
		$filtered->all();
		
		return response()->json([
			'status' => true,
			'vouchers' => $filtered
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
    public function store(StoreVoucherRequest $request)
    {
		$vouchers = new Vouchers();
        $vouchers->voucher_code = $request['voucher_code'];
        $vouchers->amount = $request['amount'];
		$vouchers->valid_till_date = $request['valid_till_date'];
		$vouchers->status = $request['status'];
		$vouchers->created_at = $request['created_at'];
		
		$vouchers->save();
		
		return response()->json([
			"status" => true,
			"message" => "Voucher created successfully"
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
    public function update(UpdateVoucherRequest $request, Vouchers $vouchers, $voucher)
    {
		$vouchers = Vouchers::find($voucher);
		
		if($vouchers->status == 'active') {
			
			$vouchers->update([
				'voucher_code'	=> $request['voucher_code'],
				'amount' => $request['amount'],
				'valid_till_date' => $request['valid_till_date'],
				'created_at' => $request['created_at'],
				'status' => $request['status']
			]);
		
			return response()->json([
				"status" => true,
				"message" => "Voucher updated successfully."
			], 200);
			
		} else {
			
			return response()->json([
			"status" => false,
			"message" => "You cannot edit an expired or used voucher."
			], 200);
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vouchers  $vouchers
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vouchers $vouchers, $voucher)
    {
        //$vouchers->delete();
		Vouchers::destroy($voucher);
		
		return response()->json([
				"status" => true,
				"message" => "Voucher deleted successfully."
			], 200);
		
    }
}
