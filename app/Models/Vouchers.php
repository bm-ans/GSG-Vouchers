<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vouchers extends Model
{
    use HasFactory;
	public $timestamps = false;
	protected $fillable = ['voucher_code','amount','valid_till_date', 'created_at', 'status'];
}
