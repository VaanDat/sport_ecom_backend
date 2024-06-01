<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCart;
use App\Models\ProductList;
use App\Models\CartOrder;

class ProductCartController extends Controller
{
    public function addtoCart(Request $request)
    {
        $email = $request->input('email');
        $size = $request->input('size');
        $color = $request->input('color');
        $quantity = $request->input('quantity');
        $product_code = $request->input('product_code');

        $product_details = ProductList::where('product_code', $product_code)->get();

        $price = $product_details[0]['price'];
        $special_price = $product_details[0]['special_price'];

        if ($special_price == "na") {
            $total_price = $price * $quantity;
            $unit_price = $price;
        } else {
            $total_price = $special_price * $quantity;
            $unit_price = $special_price;
        }

        $result = ProductCart::insert([
            'email' => $email,
            'image' => $product_details[0]['image'],
            'product_name' => $product_details[0]['title'],
            'product_code' => $product_details[0]['product_code'],
            'size' => "Size: " . $size,
            'color' => "Color: " . $color,
            'quantity' => $quantity,
            'unit_price' => $unit_price,
            'total_price' => $total_price
        ]);

        return $result;
    }

    public function cartCount(Request $request)
    {
        $email = $request->email;
        $result = ProductCart::where('email', $email)->count();
        return $result;
    }

    public function getCartByEmail(Request $request)
    {
        $email = $request->email;
        $result = ProductCart::where('email', $email)->get();
        return $result;
    }

    public function removeCartById(Request $request)
    {
        $id = $request->id;
        $result = ProductCart::where('id', $id)->delete();
        return $result;
    }

    public function plusItemCart(Request $request)
    {
        $id = $request->id;
        $item = ProductCart::where('id', $id)->get();
        $quantity = $item[0]['quantity'];
        $price = $item[0]['unit_price'];
        $newQuantity = $quantity + 1;
        $total_price = $newQuantity * $price;

        $result = ProductCart::where('id', $id)->update(['quantity' => $newQuantity, 'total_price' => $total_price]);
        return $result;
    }

    public function minusItemCart(Request $request)
    {
        $id = $request->id;
        $item = ProductCart::where('id', $id)->get();
        $quantity = $item[0]['quantity'];
        $price = $item[0]['unit_price'];
        $newQuantity = $quantity - 1;
        $total_price = $newQuantity * $price;

        $result = ProductCart::where('id', $id)->update(['quantity' => $newQuantity, 'total_price' => $total_price]);

        return $result;
    }

    public function CartOrder(Request $request)
    {

        $city = $request->input('city');
        $paymentMethod = $request->input('payment_method');
        $yourName = $request->input('name');
        $email = $request->input('email');
        $DeliveryAddress = $request->input('delivery_address');
        $invoice_no = $request->input('invoice_no');
        $DeliveryCharge = $request->input('delivery_charge');

        date_default_timezone_set("Asia/Dhaka");
        $request_time = date("h:i:sa");
        $request_date = date("d-m-Y");

        $CartList = ProductCart::where('email', $email)->get();

        foreach ($CartList as $CartListItem) {
            $cartInsertDeleteResult = "";

            $resultInsert = CartOrder::insert([
                'invoice_no' => "Easy" . $invoice_no,
                'product_name' => $CartListItem['product_name'],
                'product_code' => $CartListItem['product_code'],
                'size' => $CartListItem['size'],
                'color' => $CartListItem['color'],
                'quantity' => $CartListItem['quantity'],
                'unit_price' => $CartListItem['unit_price'],
                'total_price' => $CartListItem['total_price'],
                'email' => $CartListItem['email'],
                'name' => $yourName,
                'payment_method' => $paymentMethod,
                'delivery_address' => $DeliveryAddress,
                'city' => $city,
                'delivery_charge' => $DeliveryCharge,
                'order_date' => $request_date,
                'order_time' => $request_time,
                'order_status' => "Pending",
            ]);

            if ($resultInsert == 1) {
                $resultDelete = ProductCart::where('id', $CartListItem['id'])->delete();
                if ($resultDelete == 1) {
                    $cartInsertDeleteResult = 1;
                } else {
                    $cartInsertDeleteResult = 0;
                }
            }
        }
        return $cartInsertDeleteResult;
    }

    public function OrderListByUser(Request $request)
    {
        $email = $request->email;
        $result = CartOrder::where('email', $email)->orderBy('id', 'DESC')->get();
        return $result;
    }
    public function PendingOrder()
    {

        $orders = CartOrder::where('order_status', 'Pending')->orderBy('id', 'DESC')->get();
        return view('backend.orders.pending_orders', compact('orders'));
    }
    public function ProcessingOrder()
    {
        $orders = CartOrder::where('order_status', 'Processing')->orderBy('id', 'DESC')->get();
        return view('backend.orders.processing_orders', compact('orders'));
    }


    public function CompleteOrder()
    {
        $orders = CartOrder::where('order_status', 'Complete')->orderBy('id', 'DESC')->get();
        return view('backend.orders.complete_orders', compact('orders'));
    }

    public function OrderDetails($id)
    {
        $order = CartOrder::findOrFail($id);
        return view('backend.orders.order_details', compact('order'));
    }
    public function PendingToProcessing($id)
    {

        CartOrder::findOrFail($id)->update(['order_status' => 'Processing']);

        $notification = array(
            'message' => 'Order Processing Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('pending.order')->with($notification);
    } // End Method 


    public function ProcessingToComplete($id)
    {

        CartOrder::findOrFail($id)->update(['order_status' => 'Complete']);

        $notification = array(
            'message' => 'Order Complete Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('processing.order')->with($notification);
    }
}
