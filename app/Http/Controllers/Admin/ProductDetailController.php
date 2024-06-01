<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductDetail;
use App\Models\ProductList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductDetailController extends Controller
{
    public function getProductDetail(Request $request)
    {
        try {
            $id = $request->id;
            $product_detail = ProductDetail::where('product_id', $id)->get();
            $product_list = ProductList::where('id', $id)->get();

            $data = [
                'product_list' => $product_list,
                'product_detail' => $product_detail
            ];

            $response = [
                'message' => 'Get product {$id} detail successfully',
                'data' => $data
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
