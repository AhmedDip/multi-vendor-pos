<?php

use App\Models\Product;
use App\Mail\WelcomeMail;
use App\Mail\ConfirmOrderMail;
use App\Mail\PasswordResetMail;

use App\Mail\ConfirmAppointmentMail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\LanguageMiddleware;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShopOwnerController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleAssignController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\MediaGalleryController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\CustomerGroupController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AttributeValueController;
use App\Http\Controllers\MembershipCardController;
use App\Http\Controllers\SalesExecutiveController;
use App\Http\Controllers\ShippingMethodController;
use App\Http\Controllers\MembershipCardTypeController;
use App\Http\Controllers\RolePermissionAssociationController;

Route::middleware(LanguageMiddleware::class)->group(function () {
    Route::post('switch-language', [DashboardController::class, 'switchLanguage'])->name('dashboard.switch-language');
    Route::group(['middleware' => 'auth'], static function () {
        Route::post('switch-theme', [DashboardController::class, 'switchTheme'])->name('dashboard.switch-theme');
        Route::group(['prefix' => 'my-account'], static function () {
            Route::get('/', [UserController::class, 'create'])->name('profile.create')->middleware('permission:profile.create');
            Route::post('/', [UserController::class, 'store'])->name('profile.store')->middleware('permission:profile.store');
            Route::get('change-password', [PasswordController::class, 'changePassword'])->name('change-password');
            Route::post('update-password', [PasswordController::class, 'updatePassword'])->name('update-password');
        });

        Route::post('get_media_library', [MediaGalleryController::class, 'get_media_library'])->name('get_media_library')->middleware('permission:get_media_library');
        Route::post('create_new_directory', [MediaGalleryController::class, 'create_new_directory'])->name('create_new_directory')->middleware('permission:create_new_directory');
        Route::post('upload_media_library', [MediaGalleryController::class, 'upload_media_library'])->name('upload_media_library')->middleware('permission:upload_media_library');
        Route::post('delete_media_library', [MediaGalleryController::class, 'delete_media_library'])->name('delete_media_library')->middleware('permission:delete_media_library');
       // Route::get('show-file', [FileManagerController::class, 'show_file'])->name('show-file')->middleware('permission:show-file');
       // Route::post('delete-file', [FileManagerController::class, 'delete_file'])->name('delete-file')->middleware('permission:delete-file');



        Route::get('/', [HomeController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard.index');

        Route::get('/sales-data', [HomeController::class, 'getSalesData']);
        Route::get('/shop-data', [HomeController::class, 'getShopData']);



        Route::get('/top-customers', [DashboardController::class, 'getTopCustomers']);
        Route::get('/top-products', [DashboardController::class, 'getTopProducts']);
        Route::get('/date-wise-sale', [DashboardController::class, 'getDatewiseSales']);


        Route::resource('role', RoleController::class);
        Route::resource('permission', PermissionController::class);
        Route::resource('role-assign', RoleAssignController::class);
        Route::resource('role-permission-association', RolePermissionAssociationController::class);
        Route::resource('menu', MenuController::class);

        Route::resource('customer-group', CustomerGroupController::class);
        Route::resource('customer', CustomerController::class);
        Route::resource('membership-card-type', MembershipCardTypeController::class);
     
     
        

        // for csv export
        Route::group(['prefix' => 'export'], static function () {
            Route::get('category/export', [CategoryController::class, 'export'])->name('category.export');
            Route::get('product/export', [ProductController::class, 'export'])->name('product.export');
            Route::get('inventory/export', [ProductController::class, 'exportInventoryCsv'])->name('inventory.export');
       
            Route::get('order/export', [OrderController::class, 'exportOrderCSV'])->name('order.export');
            Route::get('appointment/export', [AppointmentController::class, 'exportAppointmentCSV'])->name('appointment.export');
        
            // Route::resource('appointment', AppointmentController::class);
        
        
        });
        // for pdf export
        Route::get('category/export-pdf',[CategoryController::class, 'exportPDF'])->name('category.export-pdf');
        Route::get('product/export-pdf',[ProductController::class, 'exportPDF'])->name('product.export-pdf');
        Route::get('inventory/export-pdf',[ProductController::class, 'exportInventoryPDF'])->name('inventory.export-pdf');
        Route::get('order/export-pdf',[OrderController::class, 'exportOrderPDF'])->name('order.export-pdf');
        Route::get('appointment/export-pdf',[AppointmentController::class, 'exportAppointmentPDF'])->name('appointment.export-pdf');


        Route::resource('membership-card', MembershipCardController::class);
        Route::resource('expense', ExpenseController::class);
        Route::resource('appointment', AppointmentController::class);

        Route::post('appointment/get-appointments-invoice-for-download-Pdf', [AppointmentController::class,'getAppointmentsInvoiceForDownloadPdf'])->name('appointment.getAppointmentsInvoiceForDownloadPdf');
        Route::get('appointments/download-appointment-invoice', [AppointmentController::class, 'downloadAppointmentInvoice'])->name('appointment.download-appointment-invoice');


        Route::resource('shop', ShopController::class);

        Route::resource('sales-executive', SalesExecutiveController::class);


        Route::resource('category',CategoryController::class);
        Route::resource('tag',TagController::class);
        Route::resource('brand',BrandController::class);
        Route::resource('attribute',AttributeController::class);
        Route::resource('attribute-value',AttributeValueController::class);
        Route::resource('discount',DiscountController::class);
        Route::resource('payment-method',PaymentMethodController::class);
        Route::resource('manufacturer',ManufacturerController::class);
        Route::resource('blog', BlogController::class);
        Route::resource('blog-category', BlogCategoryController::class);
        Route::resource('feature',FeatureController::class);
        Route::resource('package',PackageController::class);
        Route::resource('warehouse',WarehouseController::class);
        Route::resource('shipping-method',ShippingMethodController::class);
        Route::resource('product', ProductController::class);

        Route::resource('order', OrderController::class);

        Route::post('order/get-orders-invoice-for-download-Pdf', [OrderController::class,'getOrdersInvoiceForDownloadPdf'])->name('order.getOrdersInvoiceForDownloadPdf');

        Route::get('orders/download-invoice-pdf', [OrderController::class, 'downloadOrderInvoicePdf'])->name('order.download-invoice-pdf');

     
        Route::get('inventory',[ProductController::class,'inventoy']);
        Route::get('inventory',[ProductController::class,'inventoy'])->name('inventory'); //for search

        Route::resource('shop-owner', ShopOwnerController::class);
        
     
        Route::get('/get-user-name', [CustomerController::class, 'getCustomerName'])->name('get-customer-name');

        Route::get('/appointment/{id}/pay', [AppointmentController::class, 'showPaymentForm'])->name('appointment.pay');
        Route::post('/appointment/{id}/pay', [AppointmentController::class, 'processPaymentForm'])->name('appointment.processPayment');

        Route::get('/sales-report', [ReportController::class, 'salesReport'])->name('sales-report');

        Route::get('/profit-loss-report', [ReportController::class, 'profitLossReport'])->name('profit-loss-report');

        Route::get('/top-customer-report', [ReportController::class, 'topCustomerReport'])->name('top-customer-report');

        // Route::get('/calculate-balance-view', [AccountController::class, 'showCalculateBalanceView'])->name('calculate-balance-view');
        
        Route::get('/add-balance', [AccountController::class, 'addBalanceView'])->name('add-balance-view');
        Route::post('/add-balance', [AccountController::class, 'addBalance'])->name('add-balance');
        Route::get('balance', [AccountController::class, 'balanceReport'])->name('balance_report_data');

    });
});


require __DIR__ . '/auth.php';