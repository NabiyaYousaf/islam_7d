<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignVideo extends Model
{
    use HasFactory;
    protected $fillable = ['channle_name','thumbnail','video_link','instructions','status','max_video'];
}
