<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
class PaymentsController extends Controller
{
    public function all_payments(Request $request)
    {
        $payments=Payment::all();
        return response()->json([
            'entities' => $payments
        ]); 
    }

    public function user_payments(Request $request,$id)
    {
        $record = Payment::where('user_id',$id)->get();     
        
            return response()->json([
                'entities'=>$record
            ]);

       
       
    }
}
