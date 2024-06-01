<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function getAllNotification()
    {
        try {
            $data = Notification::all();
            $response = [
                "message" => "Get all notifications successfully",
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
    }
}
