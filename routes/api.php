<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BlogApiController;
use App\Http\Controllers\Api\ShopApiController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\MediaGalleryController;
use App\Http\Controllers\Api\BrandApiCrontroller;
use App\Http\Controllers\Api\StatusApiController;
use App\Http\Controllers\Api\AccountApiController;
use App\Http\Controllers\Api\ExpenseApiController;
use App\Http\Controllers\Api\FeatureApiController;
use App\Http\Controllers\Api\PackageApiController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\CustomerApiController;
use App\Http\Controllers\Api\DiscountApiController;
use App\Http\Controllers\Api\EmployeeApiController;
use App\Http\Controllers\Api\AttributeApiController;
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\WarehouseApiController;
use App\Http\Controllers\Api\AppointmentApiController;
use App\Http\Controllers\api\BlogCategoryApiController;
use App\Http\Controllers\Api\GetAttributeApiController;
use App\Http\Controllers\Api\ManufacturerApiController;
use App\Http\Controllers\Api\CustomerGroupApiController;
use App\Http\Controllers\Api\PaymentMethodApiController;
use App\Http\Controllers\Api\AttributeValueApiController;
use App\Http\Controllers\Api\MembershipCardApiController;
use App\Http\Controllers\Api\SalesExecutiveApiController;
use App\Http\Controllers\Api\ShippingMethodApiController;
use App\Http\Controllers\Api\ProductDependancyApiController;
use App\Http\Controllers\Api\MembershipCardTypeApiController;
use App\Http\Controllers\Api\BlogCategoryCategoryApiController;
use App\Http\Controllers\Api\AppointmentDependencyApiController;
use App\Http\Controllers\Api\ReportApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::post('register', [ApiAuthController::class, 'registration']);
Route::post('login', [ApiAuthController::class, 'login']);


Route::middleware(['auth:sanctum', 'role:Shop Owner|Sales Executive|Super Admin'])->group(function () {
    Route::apiResource('shop', ShopApiController::class)->names('shop-api');
    Route::get('get-assigned-shop', [ShopApiController::class, 'get_assigned_shop'])->name('get-assigned-shop-api');
    Route::post('logout', [ApiAuthController::class, 'logout']);
    Route::post('change-password', [ApiAuthController::class, 'changePassword']);
    Route::put('update-profile/{id}', [ApiAuthController::class, 'update_profile']);
    Route::get('get-profile', [ApiAuthController::class, 'get_profile_by_auth_user']);

    Route::middleware(['shop_id'])->group(function () {

    Route::apiResource('sales-executive', SalesExecutiveApiController::class)->names('sales-executive-api');

    Route::get('sales-executive-dependency-data', [SalesExecutiveApiController::class, 'get_sales_executive_dependency_data']);

    Route::get('product-dependency-data', [ProductDependancyApiController::class, 'index']);

    Route::get('get-employee-data', [SalesExecutiveApiController::class,'get_employee_data']);


    Route::apiResource('category', CategoryApiController::class)->names('category-api');
    Route::apiResource('brand',BrandApiCrontroller::class)->names('brand-api');
    Route::apiResource('product', ProductApiController::class)->names('product-api');
    Route::post('status/{slug}/{id}',[StatusApiController::class,'store']);




    Route::apiResource('attribute',AttributeApiController::class)->names('attribute-api');
    Route::apiResource('product-dependency',AppointmentDependencyApiController::class)->names('product-dependency');
    Route::apiResource('attribute-value',AttributeValueApiController::class)->names('attribute-value-api');
    Route::apiResource('get-attribute',GetAttributeApiController::class)->names('get-attribute');

    Route::get('dashboard',[DashboardApiController::class,'index'])->name('dashboard-api');

    Route::apiResource('appointment',AppointmentApiController::class)->names('appointment-api');

    Route::get('get-appointment-by-date/{date}',[AppointmentApiController::class,'get_appointment_by_date']);

    Route::apiResource('payment-method', PaymentMethodApiController::class)->names('payment-method-api');

    Route::apiResource('membership-card-type', MembershipCardTypeApiController::class)->names('membership-card-type-api');

    Route::apiResource('membership-card', MembershipCardApiController::class)->names('membership-card-api');

    Route::apiResource('customer', CustomerApiController::class)->names('customer-api');
    Route::apiResource('customer-group', CustomerGroupApiController::class)->names('customer-group-api');


    Route::apiResource('expense', ExpenseApiController::class)->names('expense-api');
    Route::apiResource('discount', DiscountApiController::class)->names('discount-api');


    Route::apiResource('manufacturer', ManufacturerApiController::class)->names('manufacturer-api');
    Route::apiResource('feature', FeatureApiController::class)->names('feature-api');
    Route::apiResource('blog', BlogApiController::class)->names('blog-api');
    Route::apiResource('blog-category', BlogCategoryApiController::class)->names('blog-category-api');
    Route::apiResource('package', PackageApiController::class)->names('package-api');
    Route::apiResource('warehouse', WarehouseApiController::class)->names('warehouse-api');
    Route::apiResource('shipping-method', ShippingMethodApiController::class)->names('shipping-method-api');


    // Route::apiResource('product', ProductApiController::class)->names('product-api');

    Route::get('get-transaction-data/{invoice_no}', [OrderApiController::class, 'get_transaction_data']);

    Route::put('transaction/{invoice_no}', [OrderApiController::class, 'transaction']);

    Route::get('get-order-dependency-data', [OrderApiController::class, 'get_order_dependency_data']);


    Route::get('appointment-transaction/{invoice_no}', [AppointmentApiController::class, 'get_transaction_data']);

    Route::get('get-services-data', [ProductApiController::class, 'get_services_data']);

    Route::get('get-appointment-dependancy-data',[AppointmentApiController::class,'get_appointment_dependency_data']);

    Route::get('get-customer-info-by-phone/{phone}',[CustomerApiController::class,'get_customer_info_by_phone']);

    Route::post('add-balance', [AccountApiController::class, 'add_balance']);

    Route::get('balance-report', [AccountApiController::class, 'getBalanceReport']);


    Route::apiResource('order', OrderApiController::class)->names('order-api');

    Route::put('order-transaction/{invoice_no}', [OrderApiController::class, 'transaction']);

    Route::get('get-customer-dependency-data', [CustomerApiController::class, 'get_customer_dependency_data']);

    Route::get('get-sales-report', [ReportApiController::class, 'get_sales_report']);

    Route::get('get-profit-loss-report', [ReportApiController::class, 'get_profit_loss_report']);

    Route::get('get-top-customer-report', [ReportApiController::class, 'get_top_customer_report']);


    //todo: media gallery need to set permission according to shop
    Route::post('get_media_library', [MediaGalleryController::class, 'get_media_library']);
    Route::post('create_new_directory', [MediaGalleryController::class, 'create_new_directory']);
    Route::post('upload_media_library', [MediaGalleryController::class, 'upload_media_library']);
    Route::post('delete_media_library', [MediaGalleryController::class, 'delete_media_library']);
    });
});
Route::post('password/forget', [ApiAuthController::class, 'send_reset_link_email']);
Route::post('reset-password', [ApiAuthController::class, 'reset'])->name('password.reset-api');
