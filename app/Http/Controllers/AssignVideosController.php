<?php

namespace App\Http\Controllers;

use App\Models\AssignVideo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AssignVideosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request){
        $validator=Validator::make($request->all(),[
            'channel_name'=>'required',
            'instructions'=>'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'message'=>'Validation Fails',
                'error'=>$validator->errors(),
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Get the base64 image data from the request
        // $imageData = $request->input('thumbnail');
        // if($imageData){

        // // Remove the data:image/<type>;base64, part from the base64 string
        // $imageData = str_replace('data:image/png;base64,', '', $imageData);
        // $imageData = str_replace(' ', '+', $imageData);

        // // Decode the base64 image data
        // $imageDataDecoded = base64_decode($imageData);

        // // Generate a unique filename for the image
        // $filename = time() . '_' . uniqid() . '.png';

        // // Save the image to the storage directory (public disk in this case)
        // Storage::disk('public')->put($filename, $imageDataDecoded);

        // // Return the URL of the uploaded image
        // $imageUrl = Storage::disk('public')->url($filename);
        // }else{
        //     $imageUrl="";
        // }

        // Thumbnail Upload
        if ($request->hasFile('thumbnail')) {
            $image = $request->file('thumbnail');
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $imageUrl=$image->storeAs('public/thumbnail', $image_name);
              
        }

        $assign_video=AssignVideo::create([
            'channle_name'=>$request->channle_name,
            'instructions'=>$request->instructions,
            'video_link'=>$request->video_link,
            'max_video'=>$request->max_video,
            'thumbnail'=>$image_name
            
        ]);
        return response()->json([
            
            'message'=>'Successful',
            'entity'=>$assign_video
        ]);
    }

    //.................. Get All Videos Data ...............//

    public function get_all_video(Request $request){

        $all_videos=AssignVideo::orderBy('id', 'DESC')->get();
        return response()->json([
            'entities'=>$all_videos
        ]);

    }

    //..............Get Single Video ..............//
    public function get_single_video(Request $request,$id){

        $record = AssignVideo::where('id',$id)->first();
        return response()->json([
            'entities'=>$record
        ]);

    }

    public function change_video_status(Request $request,$id)
    {
        $user = AssignVideo::find($id);
        $user->status = 0;
        $user->save();
  
        return response()->json(['message'=>'Expire Video successfully.']);
    }
}
