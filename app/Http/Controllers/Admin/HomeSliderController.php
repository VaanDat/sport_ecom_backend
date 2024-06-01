<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSlider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class HomeSliderController extends Controller
{
    public function getAllHomeSlider()
    {
        try {
            $home_slider = HomeSlider::all();
            $count = count($home_slider);

            $data = [
                'message' => "Get total {$count} slider successfully",
                'data' => $home_slider
            ];
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());

            return response()->json([
                'error' => 'Internal server error',
                'message' => 'An error occurred while processing your request.',
            ], 500);
        }
    }

    public function GetAllSlider()
    {
        $slider = HomeSlider::latest()->get();
        return view('backend.slider.slider_view', compact('slider'));
    }


    public function AddSlider()
    {

        return view('backend.slider.slider_add');
    }
    public function StoreSlider(Request $request)
    {

        $request->validate([
            'slider_image' => 'required',
        ], [
            'slider_image.required' => 'Upload Slider Image'

        ]);

        $image = $request->file('slider_image');
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        Image::make($image)->resize(1024, 379)->save('upload/slider/' . $name_gen);
        $save_url = 'http://127.0.0.1:8000/upload/slider/' . $name_gen;

        HomeSlider::insert([
            'slider_image' => $save_url,
        ]);

        $notification = array(
            'message' => 'Slider Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.slider')->with($notification);
    }

    public function EditSlider($id)
    {
        $slider = HomeSlider::findOrFail($id);
        return view('backend.slider.slider_edit', compact('slider'));
    } // End Mehtod 


    public function UpdateSlider(Request $request)
    {

        $slider_id = $request->id;

        $image = $request->file('slider_image');
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        Image::make($image)->resize(1024, 379)->save('upload/slider/' . $name_gen);
        $save_url = 'http://127.0.0.1:8000/upload/slider/' . $name_gen;

        HomeSlider::findOrFail($slider_id)->update([
            'slider_image' => $save_url,
        ]);

        $notification = array(
            'message' => 'Slider Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.slider')->with($notification);
    }

    public function DeleteSlider($id)
    {

        HomeSlider::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Slider Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}
