<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    //Post contact form
    public function postContactDetail(Request $request)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $message = $request->input('message');
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $contact_time = date("h:i:sa");
        $contact_date = date("d-m-Y");

        $data_insert = [
            'name' => $name,
            'email' => $email,
            'message' => $message,
            'contact_date' => $contact_date,
            'contact_time' => $contact_time
        ];

        try {
            Contact::insert($data_insert);
            $response = [
                "message" => "New contact detail added succesfully",
                'date' => $data_insert
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());

            return response()->json([
                'error' => 'Internal server error',
                'message' => 'An error occurred while processing your request.',
            ], 500);
        };
    }
    //end method

    //Get all contact 
    public function getALLContactDetail()
    {
        try {
            $data = Contact::all();
            $response = [
                "message" => "Get all contact successfully",
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


    public function GetAllMessage()
    {

        $message = Contact::latest()->get();
        return view('backend.contact.contact_all', compact('message'));
    }

    public function DeleteMessage($id)
    {

        Contact::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Message Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // End Method
}
