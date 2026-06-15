<?php

use App\Http\Controllers\admin\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\ConfigurationController;
use App\Http\Controllers\admin\InvoiceController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ProductSubCategoryController;
use App\Http\Controllers\admin\SeatController;
use App\Http\Controllers\admin\TempImagesController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ArticleController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

//Front pages routes
Route::controller(FrontController::class)->group(function() {    
    Route::get('/area/{areaSlug?}', 'restaurant')->name('front.restaurant');
    Route::get('/page/{slug}', 'page')->name('front.page');
    Route::post('/send-contact-email', 'sendContactEmail')->name('front.sendContactEmail');

    Route::post('/order', 'placeOrder')->name('submit.order');    
    Route::get('/order/success/{orderId}', 'orderSuccess')->name('order.success');
    Route::get('/admin-order/success/{orderId}', 'adminOrderSuccess')->name('admin.order.success');

    Route::get('/checkout/{id}', 'checkout')->name('razorpay.checkout');
    Route::get('/order/payment/{orderId}', 'paymentSuccess')->name('payment.success');
    Route::get('/payment-failed/{orderId}', 'paymentFailed')->name('payment.failed');
    Route::get('/customer/{id}/invoice', 'invoiceCustoerPdf')->name('customer.invoice');

    //add to cart
    Route::get('/cart', 'showCartTable');    
    //Route::get('/cart/add/{id}', 'addToCart')->name('front.addCart');

    Route::get('/cart/add/{id}', 'addToCart')->name('front.addCart');
    Route::get('/admin/cart/add/{id}', 'addToCartAdmin')->name('admin.cart.add'); 
    
    Route::get('/cart/remove/{id}', 'removeCart')->name('cart.removecart');
    
    Route::get('/cart/increase/{id}', 'increaseCart')->name('cart.increase');
    Route::get('/cart/decrease/{id}', 'decreaseCart')->name('cart.decrease');
    Route::get('/cart/increase-main/{id}', 'increaseMain')->name('cart.increase.main');
    Route::post('/cart/increase', 'increase')->name('cart.increase2');
    Route::post('/cart/decrease', 'decrease')->name('cart.decrease2');
    //Route::get('/cart/remove/{id}', 'customerRemoveCart')->name('customer.cart.removecart');
     
    Route::get('/clear-cart', 'clearCart')->name('cart.clear');

    //add to wishlist
    Route::get('/wishlist/{id}', 'addToWish')->name('addwishlist');    
    Route::get('/favorites', 'wishlist')->name('front.wishlist');        
    Route::get('/login', 'login');
    Route::delete('/remove-from-wishlist', 'removeWish');
    Route::get('/wishlist/remove/{id}', 'removeWishlist')->name('remove_wishlist');     
});

Route::group(['prefix' => 'admin'], function(){
    Route::middleware('auth')->group(function () {
        //Category Routes
        Route::controller(CategoryController::class)->group(function() {
            Route::get('/categories', 'index')->name('categories.index');        
            Route::post('/categories', 'store')->name('categories.store');
            Route::post('/category_menu', 'store_menu')->name('categories.store_menu');
            Route::get('/categories/{id}', 'delete_category')->name('category.delete');
            Route::delete('/categories/{category}', 'destroy')->name('categories.delete');   
            
            //Menu
            Route::post('/menus', 'menu_store')->name('menu.store');
            Route::get('/menus/{id}/edit', 'menu_edit')->name('menu.edit');
            Route::post('/menus/{id}', 'menu_update')->name('menu.update');
            Route::get('/menus/{id}', 'menu_delete')->name('menu.delete');            
            Route::delete('/selected-menus', 'menu_deleteAll')->name('menuall.delete');
        });          

        //Product Route     
        Route::controller(ProductController::class)->group(function() {
            Route::get('/products', 'index')->name('products.index');
            Route::get('/products/create', 'product_create')->name('products.create');
            Route::post('/products', 'product_store')->name('products.store');   
            Route::get('/products/{id}/edit', 'edit')->name('products.edit');         
            Route::post('/products/{product}',  'product_update')->name('products.update');                 
            Route::get('/products/delete/{id}', 'product_delete')->name('products.delete');
            Route::delete('/products/image/delete/{id}', 'deleteImage')->name('products.image.delete');
            Route::get('/get-products', 'getProducts')->name('products.getProducts');
            //Route::post('/product_view', 'view_store')->name('products.store');
        });

        //Sub Categories Connect to main Categories
        Route::get('/product-subcategories', [ProductSubCategoryController::class, 'index'])->name('product-subcategories.index');       

        //Table Routes
        Route::controller(SeatController::class)->group(function() {
            Route::get('/tables', 'index')->name('tables.index');
            Route::post('/seatings', 'store')->name('seatings.store');
            Route::get('/tables/{table}/edit', 'edit')->name('tables.edit');
            Route::put('/tables/{table}', 'update')->name('tables.update');
            Route::delete('/tables/{table}', 'destroy')->name('tables.delete');
        });

        //Orders Routes
        Route::controller(OrderController::class)->group(function() {
            Route::get('/orders', 'index')->name('orders.index');
            Route::get('/orders/{id}', 'detail')->name('orders.detail');
            Route::get('/orders/{id}/invoice', 'invoicePdf')->name('orders.invoice');
            Route::post('/order/change-status/{id}', 'changeOrderStatus')->name('orders.changeOrderStatus');            
            Route::post('/order/send-email/{id}', 'sendInvoiceEmail')->name('orders.sendInvoiceEmail');
        });       
       
        //Articles
        Route::get('/configurations/articles', [ArticleController::class, 'index'])->name('articles.index');
        Route::get('/configurations/articles/create', [ArticleController::class, 'create'])->name('articles.create');
        Route::post('/configurations/articles', [ArticleController::class, 'store'])->name('articles.store');
        Route::get('/configurations/articles/{id}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
        Route::post('/configurations/articles/{id}', [ArticleController::class, 'update'])->name('articles.update');
        Route::delete('/configurations/articles', [ArticleController::class, 'destroy'])->name('articles.destroy');        

        //Product Route     
        Route::controller(InvoiceController::class)->group(function() {
            Route::get('/invoice', 'index')->name('invoice.index');
            Route::get('/pos/order/{seat}', 'pos_order')->name('invoice.pos.order');
            Route::post('/invoice', 'branch_view')->name('invoice.branch.store');   
            Route::get('/cart-admin/remove/{id}', 'adminRemoveCart')->name('admin.cart.removecart');   

            //Branch
            Route::post('/branch', 'branch_store')->name('branch.store');
            Route::get('/branch/{branch}/edit', 'branch_edit')->name('branch.edit');
            Route::put('/branch/branch/{area}', 'branch_update')->name('branch.update');
            Route::get('/branch/delete/{id}', 'branch_delete')->name('delete.branch');

            //Table
            Route::post('/table', 'table_store')->name('table.store');            
            Route::get('/table/delete/{id}', 'table_delete')->name('delete.table');   
        });
        
        //Permissions
        Route::controller(ConfigurationController::class)->group(function() { 
            //Dashboard
            Route::get('/dashboard', 'dashboard')->name('dashboard');
            Route::get('/configurations', 'index')->name('configurations.index');
            
            //QR Card table updates
            Route::post('/dinein/change-status/{id}', 'dineinOrderStatus')->name('orderStatus');
            Route::get('/table/{id}/tableQR', 'tablePdf')->name('qr.table');
            
            Route::get('/configurations/create', 'configurations_create')->name('configurations.create');
            Route::post('/configurations', 'configurations_store')->name('configurations.store');
            Route::put('/configurations/restaurant', 'configurations_update')->name('configurations.update');            

            Route::post('/configurations/theme', 'store_theme')->name('configurations.theme');
            Route::get('/configurations/{id}/edit', 'configurations_edit')->name('configurations.edit');            
            //Route::post('/configurations/{id}', 'configurations_update')->name('configurations.update');
            Route::delete('/configurations', 'configurations_destroy')->name('configurations.destroy');
            Route::post('/configurations/payment', 'store_payment')->name('payment.store');

            Route::post("/updateWebsiteLogo", 'update_logo')->name('website.logo');
            
           

            //Pages            
            Route::post('/page', 'page_store')->name('pages.store');            
            Route::put('/page/{page}', 'page_update')->name('pages.update');
            Route::get('/page/delete/{id}', 'page_delete')->name('pages.delete');                                  
            
            //Permissions
            Route::post('/configurations/permissions', 'permission_store')->name('permissions.store');
            Route::get('/configurations/permissions/{id}/edit', 'permission_edit')->name('permissions.edit');
            Route::post('/configurations/permissions/{id}', 'permission_update')->name('permissions.update');
            Route::get('/configurations/permissions/delete/{id}', 'permission_destroy')->name('permissions.destroy');

            //Roles        
            Route::post('/configurations/roles', 'roles_store')->name('roles.store');
            Route::get('/configurations/roles/{id}/edit', 'roles_edit')->name('roles.edit');
            Route::post('/configurations/roles/{id}', 'roles_update')->name('roles.update');        
            Route::get('/configurations/roles/delete/{id}', 'roles_destroy')->name('roles.destroy');

            //Users            
            Route::post('/configurations/users', 'users_store')->name('users.store');
            Route::get('/configurations/users/{id}/edit', 'users_edit')->name('users.edit');
            Route::post('/configurations/users/{id}', 'users_update')->name('users.update');
            Route::get('/configurations/users/delete/{id}', 'users_destroy')->name('users.destroy');
            //Route::get('/logout', [UserController::class, 'logout'])->name('users.logout');

            Route::post('/logout', 'logout')->name('admin.logout');
        }); 

        Route::controller(ProfileController::class)->group(function() { 
            Route::get('/profile', 'index')->name('profile.index');
            Route::put('/profile/update', 'update_profile')->name('profile.update');        
        }); 
    });

    //Temp image controller
    Route::post('/temp-image', [TempImagesController::class, 'store'])->name('temp-images.create');

    Route::get('/getSlug', function(Request $request){
        $slug = '';
        if (!empty($request->title)) {
            $slug = Str::slug($request->title);
        }
        return response()->json([
            'status' => true,
            'slug' => $slug
        ]);
    })->name('getSlug');
});

Route::get('/{categorySlug?}/{menuSlug?}', [FrontController::class, 'index'])->name('front.menu');
Route::get('/table/{branchSlug}/{table}', [FrontController::class, 'tableQr'])->name('table.qr');

require __DIR__.'/auth.php';