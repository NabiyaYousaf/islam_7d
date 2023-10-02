<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\AdminPayment;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class AdminPaymentsController extends Controller
{
    //Admin send payment to the specific member
    public function create_admin_payment(Request $request, $id)
    {
        $validator=Validator::make($request->all(),[
            'trx_id'=>'required',
            'payment_ss'=>'required',
            'payment'=>'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'message'=>'Validation Fails',
                'error'=>$validator->errors(),
            ]);
        }

        $user_id=DB::table('payments')->where('user_id', $id)->value('user_id');
        $user_name=DB::table('payments')->where('user_id', $id)->value('user_name');

        $pending_income=DB::table('payments')->where('user_id', $user_id)->value('pending_income');
        $total_income=DB::table('payments')->where('user_id', $user_id)->value('total_income');

        $update_pending_income=$pending_income - $request->payment;
        $update_total_income=$total_income + $request->payment;
        

        $data=[
            'pending_income'=>$update_pending_income,
            'total_income'=>$update_total_income,
        ];   
            DB::table('payments')
                ->where('user_id', $user_id) 
                ->update($data);

        if ($request->hasFile('payment_ss')) {
            $image = $request->file('payment_ss');
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $image_url=$image->storeAs('public/admin_payment', $image_name);
              
        }
        
        $payment=AdminPayment::create([
            'payment'=>$request->payment,
            'trx_id'=>$request->trx_id,
            'user_id'=>$user_id,
            'user_name'=>$user_name,
            'payment_ss'=>$image_name,
        ]);

        return response()->json([
            'message'=>'Payment Send Successfuly',
            'entities'=>$payment
        ]);
    

    }

    //Get all payments
    public function get_admin_payments(Request $request){
        $all_payments=AdminPayment::orderBy('id', 'DESC')->get();;
        return response()->json([
            'entities'=>$all_payments
        ]);
    }

    //Get payments of specific id

    public function get_payments(Request $request,$id){

        $user_id=DB::table('admin_payments')->where('user_id', $id)->value('user_id');

        $all_payments=AdminPayment::find($user_id)
                                    ->get();
        return response()->json([
            'entities'=>$all_payments
        ]);
    }
}
