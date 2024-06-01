<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\VisitorController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\SiteInfoController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductListController;
use App\Http\Controllers\Admin\HomeSliderController;
use App\Http\Controllers\Admin\ProductDetailController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\ProductCartController;
use App\Http\Controllers\Admin\FavouriteController;

use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\ForgetController;
use App\Http\Controllers\User\ResetController;
use App\Http\Controllers\User\UserController;
use App\Models\Favourites;
use App\Models\ProductCart;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//Auth API
Route::post('/login', [AuthController::class, 'Login']);
Route::post('/register', [AuthController::class, 'Register']);
Route::post('/forget-password', [ForgetController::class, 'ForgetPassword']);
Route::post('/reset-password', [ResetController::class, 'ResetPassword']);
Route::get('/user', [UserController::class, 'User'])->middleware('auth:api');

//Visitor API
Route::post('/visitor', [VisitorController::class, 'addVisitor']);
Route::get('/visitor', [VisitorController::class, 'getAllVisitor']);

//Contact API
Route::post('/contact', [ContactController::class, 'postContactDetail']);
Route::get('/contact', [ContactController::class, 'getAllContactDetail']);

//Site Info API
Route::get('/siteinfo', [SiteInfoController::class, 'getAllSiteInfo']);

//Category API
Route::get('/category', [CategoryController::class, 'getCategoryByGroup']);

//Product API
Route::get('/product-list-by-remark/{remark}', [ProductListController::class, 'getProductListByRemark']);
Route::get('/product-list-by-category/{category}', [ProductListController::class, 'getProductListByCategory']);
Route::get('/product-list-by-subcategory/{category}/{subcategory}', [ProductListController::class, 'getProductListBySubCategory']);

//Home Slider API
Route::get('/homeslider', [HomeSliderController::class, 'getAllHomeSlider']);

//Product detail API
Route::get('product-detail/{id}', [ProductDetailController::class, 'getProductDetail']);

//Notification API
Route::get('notifications', [NotificationController::class, 'getAllNotification']);

//Search product API
Route::get('/search/{key}', [ProductListController::class, 'getProductBySearch']);

//Similar product API
Route::get('/similar/{subcategory}', [ProductListController::class, 'similarProduct']);

//Review API
Route::get('/review-list/{id}', [ReviewController::class, 'reviewList']);

//Product cart API
Route::post('/addtocart', [ProductCartController::class, 'addtoCart']);
Route::get('/cartcount/{email}', [ProductCartController::class, 'cartCount']);
Route::get('/cartlist/{email}', [ProductCartController::class, 'getCartByEmail']);
Route::delete('/remove-cart-item/{id}', [ProductCartController::class, 'removeCartById']);
Route::get('/plus-cart-item/{id}', [ProductCartController::class, 'plusItemCart']);
Route::get('/minus-cart-item/{id}', [ProductCartController::class, 'minusItemCart']);

//Favourite API
Route::post('/favourite/{product_code}/{email}', [FavouriteController::class, 'addFavourite']);
Route::get('/favourite/{email}', [FavouriteController::class, 'getFavourite']);
Route::delete('/favourite/{product_code}/{email}', [FavouriteController::class, 'removeFavourite']);

//Cart order API
Route::post('/cart-order', [ProductCartController::class, 'CartOrder']);
Route::get('/orderlistbyuser/{email}', [ProductCartController::class, 'OrderListByUser']);

// Post Product Review Route
Route::post('/postreview', [ReviewController::class, 'PostReview']);
