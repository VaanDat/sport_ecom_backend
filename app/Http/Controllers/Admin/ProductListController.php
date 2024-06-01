<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Category;
use App\Models\Subcategory;
use Intervention\Image\Facades\Image;
use App\Models\ProductDetail;

class ProductListController extends Controller
{
    public function GetAllProduct()
    {
        $products = ProductList::latest()->paginate(10);
        return view('backend.product.product_all', compact('products'));
    }
    public function AddProduct()
    {
        $category = Category::orderBy('category_name', 'ASC')->get();
        $subcategory = Subcategory::orderBy('subcategory_name', 'ASC')->get();
        return view('backend.product.product_add', compact('category', 'subcategory'));
    }
    public function StoreProduct(Request $request)
    {

        $request->validate([
            'product_code' => 'required',
        ], [
            'product_code.required' => 'Input Product Code'

        ]);

        $image = $request->file('image');
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        Image::make($image)->resize(711, 960)->save('upload/product/' . $name_gen);
        $save_url = 'http://127.0.0.1:8000/upload/product/' . $name_gen;

        $product_id = ProductList::insertGetId([
            'title' => $request->title,
            'price' => $request->price,
            'special_price' => $request->special_price,
            'category' => $request->category,
            'subcategory' => $request->subcategory,
            'remark' => $request->remark,
            'brand' => $request->brand,
            'product_code' => $request->product_code,
            'image' => $save_url,
            'star' => 5
        ]);

        /////// Insert Into Product Details Table ////// 
        $image1 = $request->file('image_one');
        $name_gen1 = hexdec(uniqid()) . '.' . $image1->getClientOriginalExtension();
        Image::make($image1)->resize(711, 960)->save('upload/productdetails/' . $name_gen1);
        $save_url1 = 'http://127.0.0.1:8000/upload/productdetails/' . $name_gen1;


        $image2 = $request->file('image_two');
        $name_gen2 = hexdec(uniqid()) . '.' . $image2->getClientOriginalExtension();
        Image::make($image2)->resize(711, 960)->save('upload/productdetails/' . $name_gen2);
        $save_url2 = 'http://127.0.0.1:8000/upload/productdetails/' . $name_gen2;


        $image3 = $request->file('image_three');
        $name_gen3 = hexdec(uniqid()) . '.' . $image3->getClientOriginalExtension();
        Image::make($image1)->resize(711, 960)->save('upload/productdetails/' . $name_gen3);
        $save_url3 = 'http://127.0.0.1:8000/upload/productdetails/' . $name_gen3;


        ProductDetail::insert([
            'product_id' => $product_id,
            'image_one' => $save_url1,
            'image_two' => $save_url2,
            'image_three' => $save_url3,
            'short_desc' => $request->short_description,
            'color' =>  $request->color,
            'size' =>  $request->size,
            'long_desc' => $request->long_description,

        ]);


        $notification = array(
            'message' => 'Product Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.product')->with($notification);
    }
    public function getProductListByRemark(Request $request)
    {
        try {
            $remark = $request->remark;
            $product_list = ProductList::where('remark', $remark)->get();
            $count = count($product_list);
            $data = [
                'message' => "Get total {$count} products by remark successfully",
                'data' => $product_list
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

    public function getProductListByCategory(Request $request)
    {
        try {
            $category = $request->category;
            $product_list = ProductList::where('category', $category)->get();
            $count = count($product_list);
            $data = [
                'message' => "Get total {$count} products by category successfully",
                'data' => $product_list
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

    public function getProductListBySubCategory(Request $request)
    {
        try {
            $category = $request->category;
            $subcategory = $request->subcategory;
            $product_list = ProductList::where('category', $category)->where('subcategory', $subcategory)->get();
            $count = count($product_list);
            $data = [
                'message' => "Get total {$count} products by sub category successfully",
                'data' => $product_list
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

    public function getProductBySearch(Request $request)
    {
        try {
            $key = $request->key;
            $products = ProductList::where('title', 'LIKE', "%{$key}%")->get();

            $response = [
                "message" => "Get product by key successfully",
                'data' => $products
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

    public function similarProduct(Request $request)
    {
        $subcategory = $request->subcategory;
        $product_list = ProductList::where('subcategory', $subcategory)->orderBy('id', 'desc')->limit(6)->get();
        return $product_list;
    }
    public function EditProduct($id)
    {
        $category = Category::orderBy('category_name', 'ASC')->get();
        $subcategory = Subcategory::orderBy('subcategory_name', 'ASC')->get();
        $product = ProductList::findOrFail($id);
        $details = ProductDetail::where('product_id', $id)->get();
        return view('backend.product.product_edit', compact('category', 'subcategory', 'product', 'details'));
    }

    public function DeleteProduct($id)
    {
        ProductList::findOrFail($id)->delete();
        // ProductDetail::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Product Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}
