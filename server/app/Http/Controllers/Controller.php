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

    public function responsesList($message, $data, $property, $notif = null){
        return response()->json([
            'message' => $message,
            'column' => $property,
            'data' => $data->getCollection(),
            'property' => [
                "total" => $data->total(),
                "count" => $data->count(),
                "per_page" => $data->perPage(),
                "current_page" => $data->currentPage(),
                "total_pages" => $data->lastPage(),
            ], 
            'notif' => $notif,
        ]);
    }
}
