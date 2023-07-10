<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Hash;
class MemberController extends Controller
{
    public function register(Request $request){
        $validator=Validator::make($request->all(),[
            'name'=>'required|min:3|max:100',
            'username'=>'required|unique:members|min:3|max:100',
            'email'=>'required|min:10|unique:members',
            'password'=>'required|min:6|max:100',
            'phone'=>'required|numeric|min:10',
            'account_name'=>'required|min:3|max:100',
            'account_number'=>'required|min:3|max:100',
            'joining_date'=>'required',
            'trx_id'=>'required',
            'fee_image'=>'required',
            'package'=>'required|min:3|max:100',
        ]);

        if($validator->fails()){
            return response()->json([
                'message'=>'Validation Fails',
                'error'=>$validator->errors(),
            ]);
        }
        $file=$request->file('fee_image'); 
        $extension=$file->getClientOriginalExtension();
        $filename=time().'.'.$extension;
        $file->move('uploads/feeImage/',$filename);
        $refferal_number=$request->package.''.$request->username;
        if(!$request->refferal){
            $greferral="";
        }
        if($request->refferal){
        $get_referral = DB::table('members')->where('refferal', $request->refferal)->get();
        $greferral=$request->refferal;
        }
        if($request->refferal && !$get_referral){
            exit();
        }

        $member=Member::create([
            'name'=>$request->name,
            'username'=>$request->username,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'phone'=>$request->phone,
            'account_name'=>$request->account_name,
            'account_number'=>$request->account_number,
            'trx_id'=>$request->trx_id,
            'joining_date'=>date('Y-m-d H:i:s'),
            'refferal'=>$refferal_number,
            'referance_no'=>$greferral,
            'fee_image'=>$filename,
            'package'=>$request->package,
        ]);
        return response()->json([
            'message'=>'Registration Successful',
            'entity'=>$member
        ]);
    
    }

    public function getmember(Request $request){
        $all_members=Member::all();
        return response()->json([
            'entities'=>$all_members
        ]);
        // return $all_members;
    }
}
