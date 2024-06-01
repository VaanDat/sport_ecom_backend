<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Visitor;
use Illuminate\Support\Facades\Log;

class VisitorController extends Controller
{
    //add visitor
    public function addVisitor()
    {
        $ip_address = $_SERVER['REMOTE_ADDR'];
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $visit_time = date("h:i:sa");
        $visit_date = date("d-m-Y");

        $data_insert = [
            "ip_address" => $ip_address,
            "visit_time" => $visit_time,
            "visit_date" => $visit_date
        ];

        try {
            Visitor::insert($data_insert);
            $response = [
                'message' => "New visitor added successfully",
                'data' => $data_insert
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());

            return response()->json([
                'error' => 'Internal server error',
                'message' => 'An error occurred while processing your request.',
            ], 500);
        };
    } //end method

    //get all visitor
    public function getAllVisitor()
    {
        try {
            $data = Visitor::all();
            $response = [
                "message" => "Get all visitor successfully",
                "data" => $data
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());

            return response()->json([
                'error' => 'Internal server error',
                'message' => 'An error occurred while processing your request.',
            ], 500);
        }
    }//end method
}
