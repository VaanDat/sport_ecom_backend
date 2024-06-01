<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Favourites;
use Illuminate\Http\Request;
use App\Models\ProductList;

class FavouriteController extends Controller
{
    public function addFavourite(Request $request)
    {
        $product_code = $request->product_code;
        $email = $request->email;

        $product_detail = ProductList::where('product_code', $product_code)->get();

        $result = Favourites::insert([
            'product_name' => $product_detail[0]['title'],
            'image' => $product_detail[0]['image'],
            'product_code' => $product_code,
            'email' => $email
        ]);

        return $result;
    }

    public function getFavourite(Request $request)
    {
        $email = $request->email;
        $result = Favourites::where('email', $email)->get();
        return $result;
    }

    public function removeFavourite(Request $request)
    {
        $product_code = $request->product_code;
        $email = $request->email;


        $result = Favourites::where('product_code', $product_code)->where('email', $email)->delete();

        if (!$result) {
            return "Not existed product";
        }

        $response = [
            'message' => 'Remove product successfully',
            'status' => $result
        ];
        return $response;
    }
}
