<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\TestImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Hash;

class UserController extends Controller
{
   
    //...................User Registration ........................... //

    public function register(Request $request){
        $validator=Validator::make($request->all(),[
            'name'=>'required|min:3|max:100',
            'username'=>'required|unique:users|min:3|max:100',
            'email'=>'required|min:10|unique:users',
            'password'=>'required|min:6|max:100',
            'phone'=>'required|numeric|min:10',
            'account_name'=>'required|min:3|max:100',
            'account_number'=>'required|min:3|max:100',
            'trx_id'=>'required',
            // 'fee_image'=>'required',
            'package'=>'required|min:3|max:100',
        ]);

        if($validator->fails()){
            return response()->json([
                'message'=>'Validation Fails',
                'error'=>$validator->errors(),
            ]);
        }
                //upload Image

    //     $file=$request->file('fee_image'); 
    //     $extension=$file->getClientOriginalExtension();
    //     $filename=time().'.'.$extension;
    //     $file->move('uploads/feeImage/',$filename);
    //     if ($request->fee_image) {
    //     $folderPath = "uploads/feeImage";
    //     $base64Image = explode(";base64,", $request->fee_image);
    //     $explodeImage = explode("image/", $base64Image[0]);
    //     $imageType = $explodeImage[1];
    //     $image_base64 = base64_decode($base64Image[1]);
    //     $filename = $folderPath . uniqid() . '. '.$imageType;
    //     file_put_contents($filename, $image_base64);
    // }

    // Get the base64 image data from the request
        // $imageData = $request->input('fee_image');

        // Remove the data:image/<type>;base64, part from the base64 string
        // $imageData = str_replace('data:image/png;base64,', '', $imageData);
        // $imageData = str_replace(' ', '+', $imageData);

        // Decode the base64 image data
        // $imageDataDecoded = base64_decode($imageData);

        // Generate a unique filename for the image
        // $filename = time() . '_' . uniqid() . '.png';

        // Save the image to the storage directory (public disk in this case)
        // Storage::disk('public')->put($filename, $imageDataDecoded);

        // Return the URL of the uploaded image
        // $imageUrl = Storage::disk('public')->url($filename);
        // Assuming you have already moved the uploaded file to the correct location.
        // Storage::disk('public')->put($filename, $imageDataDecoded);


        // return response()->json(['url' => $imageUrl], 201);

        // Fee Image Upload
        if ($request->hasFile('fee_image')) {
            $image = $request->file('fee_image');
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $image_url=$image->storeAs('public/products', $image_name);
              
        }

            // Referral Number
        $refferal_number=$request->package.''.$request->username;

        if(!$request->referance_no){
            $greferral="";
        }


        if($request->referance_no){
        $get_referral = DB::table('users')->where('refferal', $request->referance_no)->get();
        $greferral=$request->referance_no;   
            
            }
        if(($request->referance_no) && ($get_referral == null)){
            exit();
        }

        //adding referal reward to payment table

        if($request->referance_no){
            $user_id=DB::table('users')->where('refferal', $request->referance_no)->value('id');   
            $package_id=DB::table('users')->where('id', $user_id)->value('package');
            $referal_reward=DB::table('packages')->where('package_name', $package_id)->value('referal_reward');
            $pendingIncome=DB::table('payments')->where('user_id', $user_id)->value('pending_income');
            $update_pending_income=$pendingIncome + $referal_reward;
            $data=[
                'pending_income'=>$update_pending_income,
            ];   
                DB::table('payments')
                    ->where('user_id', $user_id) 
                    ->update($data);
        }

        $today_date=date('Y-m-d');
        $user=User::create([
            'name'=>$request->name,
            'username'=>$request->username,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'phone'=>$request->phone,
            'account_name'=>$request->account_name,
            'account_number'=>$request->account_number,
            'trx_id'=>$request->trx_id,
            'joining_date'=>$today_date,
            'refferal'=>$refferal_number,
            'referance_no'=>$greferral,
            'fee_image'=>$image_name,
            'package'=>$request->package,
            
        ]);
         $token=$user->createToken($request->email)->plainTextToken;
         
        return response()->json([
            'token'=>$token,
            'message'=>'Registration Successful',
            'entity'=>$user
        ]);
    
    }

    //................Get All Registered Members...........//
    public function getmember(Request $request){
        $all_users=User::orderBy('id', 'DESC')->get();;
        return response()->json([
            'entities'=>$all_users
        ]);
    }

    //..............Get Single Register Member..............//

    public function get_single_member(Request $request,$id){
        $record = User::where('id',$id)->first();
        return response()->json([
            'entities'=>$record
        ]);
        // return $all_members;
    }

     //...................User Login ........................... //

     public function userlogin(Request $request){
        $request->validate([
            'email'=>'required',
            'password'=>'required'
        ]);

        $user = User::where('email', $request->email)->first();
 
    if ($user && Hash::check($request->password, $user->password)) {
        
        if($request->email == 'admin@gmail.com'){

            return response()->json([
                'token'=>$user->createToken($request->email)->plainTextToken,
                'message'=>'Admin Login Successful',
                'entities'=>$user,
            ]);

        }else{
    
            return response()->json([
                'token'=>$user->createToken($request->email)->plainTextToken,
                'message'=>'User Login Successful',
                'entities'=>$user,
            ]);
        }
    }else{
            return response()->json([
                    'message'=>'Login Failed'
                ]);
    }
 
    
     }

     //...........Update User Status...............//

     public function change_status(Request $request,$id)
    {
        $user = User::find($id);
        $user->status = 1;
        $user->save();
  
        return response()->json(['message'=>'Status change successfully.']);
    }


     
}
