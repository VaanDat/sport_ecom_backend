<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteInfo;
use Illuminate\Support\Facades\Log;

class SiteInfoController extends Controller
{
    public function getAllSiteInfo(){
        try{
            $data = SiteInfo::all();
            $reponse = [
                "message" => "Get all site info successfully",
                "data" => $data
            ];
            return response()->json($reponse);
        }catch(\Exception $e){
            Log::error('An error occurred: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Internal server error',
                'message' => 'An error occurred while processing your request.',
            ], 500);
        }
        }//end method
      
}
