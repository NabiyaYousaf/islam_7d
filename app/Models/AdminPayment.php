<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminPayment extends Model
{
    use HasFactory;
    protected $fillable = ['trx_id','user_id','payment_ss','payment','date','user_name'];
}
