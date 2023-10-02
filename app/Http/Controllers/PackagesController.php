<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;
use Illuminate\Support\Facades\Validator;
class PackagesController extends Controller
{
    //admin create packages
    public function create_package(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'package_name'=>'required',
            'price_per_video'=>'required',
            'referal_reward'=>'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'message'=>'Validation Fails',
                'error'=>$validator->errors(),
            ]);
        }

        $package=Package::create([
            'package_name'=>$request->package_name,
            'price_per_video'=>$request->price_per_video,
            'referal_reward'=>$request->referal_reward,
        ]);

        return response()->json([
            'message'=>'Package Created',
            'entities'=>$package
        ]);
    }

    //Get All Packages

    public function get_packages(Request $request)
    {
        $packages=Package::all();
        return response()->json([
            'message'=>'All Packages',
            'entities'=>$packages
        ]);
    }
}
