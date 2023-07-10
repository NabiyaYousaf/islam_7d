<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;
    
   protected $fillable = ['name','username','email','password','phone','account_name','account_number','joining_date','trx_id','refferal','referance_no','fee_image','package'];
}
