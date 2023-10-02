<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserImagePost extends Model
{
    use HasFactory;
    protected $fillable = ['video_image','user_id','video_id','status','user_name','post'];

}
