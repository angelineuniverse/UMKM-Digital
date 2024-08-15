<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;

class Controller
{
    public function validasi($request, $items){
        $validator = Validator::make($request->all(), $items);
        if($validator->fails()){
            abort(400, implode(',', $validator->errors()->all()));
        }
        return null;
    }

    public function responses($message,$data,$notif = null){
        return response()->json([
            'message' => $message,
            'data' => $data,
            'notif' => $notif,
        ]);
    }
}
