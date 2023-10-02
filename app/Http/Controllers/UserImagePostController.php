<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserImagePost;
use App\Models\AssignVideo;
use App\Models\Payment;
use App\Models\Package;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class UserImagePostController extends Controller
{
    //Add multiple Screenshots from user side
    public function add_user_image(Request $request,$user_id,$video_id){

        $validator=Validator::make($request->all(),[
            'video_image' => 'required',
            'video_image.*' => 'image',
        ]);

        if($validator->fails()){
            return response()->json([
                'message'=>'Validation Fails',
                'error'=>$validator->errors(),
            ]);
        }
       
        //post multiple images

        $user=User::find($user_id);
        $video=AssignVideo::find($video_id);
        $post=new UserImagePost();

        $existing_data=DB::table('user_image_posts')->where('user_id', $user)
                                                    ->where('video_id',$video_id)
                                                    ->get();
        if($existing_data){
            return response()->json([
                'message'=>'Already exists',
            ], 403);
        }else{                                          

        $images = [];

        foreach($request->file('video_image') as $image)
        {
            $imageName=$image->getClientOriginalName();
            $image->move(public_path('screenshots'),$imageName);  
            $fileNames[] = $imageName;
        }

        $images = json_encode($fileNames);
        
        // Store $images image in DATABASE from HERE 
        UserImagePost::create(['video_image' => $images,'video_id'=>$video->id,'user_id'=>$user->id,'user_name'=>$user->username,'post'=>1]);
        
        // $user->userImagePost()->save($post);
        
        // $mergedObject = (object) array_merge((array) $post, $images);
        return response()->json([
            'message'=>'Successful',
        ], 201);
    }
    }

    

    //Get All Screenshots Data

    public function get_screenshots_data($id = null)
    {
        $s_id=UserImagePost::find($id);
        if($s_id){
            $all_screenshots = UserImagePost::where('id',$id)->get();
        }else{
            $all_screenshots = UserImagePost::orderBy('id', 'DESC')->get();
        } 
        return response()->json([
            'entities' => $all_screenshots
        ]);
    }

    // SS with DATE FILTER

    public function screenshots_with_date($startDate, $endDate){
            $all_screenshots = UserImagePost::where('date', [$startDate, $endDate])->get();
            return response()->json([
                        'entities' => $all_screenshots
                    ]); 
    }

    // SS WITH STATUS FILTER
    public function screenshots_with_status($status){
        $all_screenshots = UserImagePost::where('status', $status)->get();
       
        return response()->json([
                    'entities' => $all_screenshots
                ]); 
    }

    // SS WITH DATE AND STATUS FILTER
    public function get_screenshots($startDate, $endDate,$status){
        $all_screenshots = UserImagePost::where('status', $status)
                                        ->where('date', [$startDate, $endDate])
                                        ->get();
       
        return response()->json([
                    'entities' => $all_screenshots
                ]); 
    }


    //Change User uploaded Screenshots Status And Message

    // public function change_image_status(Request $request,$id)
    // {
    //     //$user_post_image=UserImagePost::find($id); // video id
    //     //$user_post_image->status = $request->status; // change status
    //     DB::table('user_image_posts')
    //                 ->where('id', $id)
    //                 ->update(['status' => $request->status]);
                    
    //     $userId=DB::table('user_image_posts')->where('id', $id)->value('user_id');

    //     $package_id=DB::table('users')->where('id', $userId)->value('package');

    //     $price_each_video=DB::table('packages')->where('package_name', $package_id)->value('price_per_video');
       
    //     $find_user_id=DB::table('payments')->where('user_id', $userId)->value('user_id');
        
    //     if($request->status == 'approve'){
    //         if(!$find_user_id){

    //             $data=[
    //                 'user_id'=>$userId,
    //                 'pending_income'=>$price_each_video
    //             ];
    //             DB::table('payments')->insert($data);
                
                
    //         }else{     

    //             $pendingIncome=DB::table('payments')->where('user_id', $find_user_id)->value('pending_income');
    //             $update_pending_income=$pendingIncome + $price_each_video;
    //             DB::table('payments')
    //                 ->where('user_id', $find_user_id)
    //                 ->update(['pending_income' => $update_pending_income]);
                
    //         }
            
    //     }
        
    //     if($request->message)
    //     {
    //         $user_post_image->message = $request->message;
    //     }else{
    //         $user_post_image->message= 'No';
    //     }
    //     $user_post_image->save();
  
    //     return response()->json(['message'=>'Status change successfully.']);
    // }


    public function change_image_status(Request $request, $id)
{
    // Check if $request->status is not null
    if($request->has('status')) {
        // Update the status column only if $request->status is not null
        DB::table('user_image_posts')
            ->where('id', $id)
            ->update(['status' => $request->status]);
    }
        $status = $request->input('status');
        // $image_id = UserImagePost::find($id);
        // $image_id->status = $request->status;
    
    
    $user_post_image = UserImagePost::find($id); // Assuming you have an Eloquent model named UserImagePost

    $userId = $user_post_image->user_id; // Use the model object to get user_id
    $userName=$user_post_image->user_name;

    $package_id = DB::table('users')->where('id', $userId)->value('package');
    $account_name=DB::table('users')->where('id', $userId)->value('account_name');
    $account_number=DB::table('users')->where('id', $userId)->value('account_number');

    $price_each_video = DB::table('packages')->where('package_name', $package_id)->value('price_per_video');

    $find_user_id = DB::table('payments')->where('user_id', $userId)->value('user_id');

    if($request->status === 'approve') {
        if (!$find_user_id) {
            $data = [
                'user_id' => $userId,
                'pending_income' => $price_each_video,
                'user_name'=>$userName,
                'account_name'=>$account_name,
                'account_number'=>$account_number
            ];
            DB::table('payments')->insert($data);
        } else {
            $pendingIncome = DB::table('payments')->where('user_id', $find_user_id)->value('pending_income');
            $update_pending_income = $pendingIncome + $price_each_video;
            DB::table('payments')
                ->where('user_id', $find_user_id)
                ->update(['pending_income' => $update_pending_income]);
        }
    }

    if ($request->has('message')) {
        $user_post_image->message = $request->message;
    } else {
        $user_post_image->message = 'No';
    }
    $user_post_image->save();

    return response()->json(['message' => $status]);
}



}
