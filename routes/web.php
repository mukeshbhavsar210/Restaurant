<?php

use App\Http\Controllers\admin\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\ConfigurationController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ProductSubCategoryController;
use App\Http\Controllers\admin\SeatController;
use App\Http\Controllers\admin\SettingController;
use App\Http\Controllers\admin\TempImagesController;
use App\Http\Controllers\FrontController;

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

//Front pages routes
Route::controller(FrontController::class)->group(function() {
    Route::get('/', 'index')->name('front.home');    
    Route::get('/menu/{slug?}/{subSlug?}', 'category')->name('front.menu');

    // In your routes/web.php
    Route::post('order', 'placeOrder')->name('submit.order');
    Route::post('/cart/increase', 'increase')->name('cart.increase');
    Route::post('/cart/decrease', 'decrease')->name('cart.decrease');    
    Route::get('area/{areaSlug?}', 'restaurant')->name('front.restaurant');
    Route::post('/add-to-wishlist', 'addToWishlist')->name('front.addToWishlist');
    Route::get('/page/{slug}', 'page')->name('front.page');
    Route::post('/send-contact-email', 'sendContactEmail')->name('front.sendContactEmail');

    //add to cart
    Route::get('cart', 'showCartTable');
    //Route::get('add-to-cart/{id}', 'addToCart')->name('front.addCart');
    Route::get('/cart/add/{id}', 'addToCart')->name('front.addCart');
    Route::get('/cart/increase/{id}', 'increaseCart')->name('cart.increase');
    Route::get('/cart/decrease/{id}', 'decreaseCart')->name('cart.decrease');
    Route::get('/cart/remove/{id}', 'removeCart');    
    Route::get('clear-cart', 'clearCart');

    //add to wishlist
    Route::get('favorites', 'wishlist')->name('front.wishlist');
    Route::get('add-to-wishlist/{id}', 'addToWish')->name('addwishlist');
    Route::delete('remove-from-wishlist', 'removeWishlistItem');
    Route::get('clear-wishlist', 'clearWishlist')->name('clear_wishlist');
});

//Route::get('/menu/{categorySlug?}',[ShopController::class,'category'])->name('front.category');

Route::get('/dashboard', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::group(['prefix' => 'admin'], function(){
    // Route::group(['middleware' => 'admin.guest'], function(){
    //     Route::controller(AdminLoginController::class)->group(function() {
    //         Route::get('/login', 'index')->name('admin.login');
    //         Route::post('/authenticate', 'authenticate')->name('admin.authenticate');
    //     });
    // });

    //Route::group(['middleware' => 'admin.auth'], function(){
    Route::middleware('auth')->group(function () {

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        //Permissions
        Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
        Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
        Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
        Route::get('/permissions/{id}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
        Route::post('/permissions/{id}', [PermissionController::class, 'update'])->name('permissions.update');
        Route::delete('/permissions', [PermissionController::class, 'destroy'])->name('permissions.destroy');

        //Roles
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
        Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::post('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('/roles', [RoleController::class, 'destroy'])->name('roles.destroy');

        //Articles
        Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
        Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
        Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
        Route::get('/articles/{id}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
        Route::post('/articles/{id}', [ArticleController::class, 'update'])->name('articles.update');
        Route::delete('/articles', [ArticleController::class, 'destroy'])->name('articles.destroy');

        //Users
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::post('/users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users', [UserController::class, 'destroy'])->name('users.destroy');
        Route::get('/logout', [UserController::class, 'logout'])->name('users.logout');

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
            Route::put('/products/{product}',  'product_update')->name('products.update');                 
            Route::get('/products/delete/{id}', 'product_delete')->name('products.delete');
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
            Route::post('/order/change-status/{id}', 'changeOrderStatus')->name('orders.changeOrderStatus');
            Route::post('/order/send-email/{id}', 'sendInvoiceEmail')->name('orders.sendInvoiceEmail');
        });

        //Settings Routes
        Route::controller(SettingController::class)->group(function() {
            Route::get('/settings', 'index')->name('settings.index');
            Route::post('/settings/website_information', 'websiteInformation')->name('settings.websiteInformation');                
            Route::post('/settings/branch', 'branch')->name('settings.branch');                
            //Route::post("/updateWebsiteLogo",'update_logo')->name('website.logo');
        });
       
        //Profile
        Route::controller(ProfileController::class)->group(function() {
            Route::get('/profile', 'edit')->name('profile.edit');
            Route::patch('/profile', 'update')->name('profile.update');
            Route::delete('/profile', 'destroy')->name('profile.destroy');
        });
        
        //Permissions
        Route::controller(ConfigurationController::class)->group(function() { 
            Route::get('/configurations', 'index')->name('configurations.index');
            Route::get('/configurations/create', 'configurations_create')->name('configurations.create');
            Route::post('/configurations', 'configurations_store')->name('configurations.store');
            Route::post('/configurations/theme', 'store_theme')->name('configurations.theme');
            Route::get('/configurations/{id}/edit', 'configurations_edit')->name('configurations.edit');
            Route::post('/configurations/{id}', 'configurations_update')->name('configurations.update');
            Route::delete('/configurations', 'configurations_destroy')->name('configurations.destroy');
            Route::post('/configurations/payment', 'store_payment')->name('payment.store');

            Route::post("/updateWebsiteLogo", 'update_logo')->name('website.logo');
            
            //Branch
            Route::post('/branch', 'branch_store')->name('branch.store');
            Route::get('/branch/{branch}/edit', 'branch_edit')->name('branch.edit');
            Route::put('/branch/branch/{area}', 'branch_update')->name('branch.update');
            Route::get('/branch/delete/{id}', 'branch_delete')->name('delete.branch');

            //Table
            Route::post('/table', 'table_store')->name('table.store');            
            Route::get('/table/delete/{id}', 'table_delete')->name('delete.table');   

            //Pages            
            Route::post('/page', 'page_store')->name('pages.store');            
            Route::put('/page/{page}', 'page_update')->name('pages.update');
            Route::get('/page/delete/{id}', 'page_delete')->name('pages.delete');
                        
            //Permissions
            // Route::post('/permission_old', 'permissions_store')->name('permissions.store');            
            // Route::put('/permission_old/{permission}', 'permissions_update')->name('permissions.update');
            // Route::get('/permission_old/delete/{id}', 'permission_delete')->name('permissions.delete');            
        
            //Roles
            // Route::post('/role_old', 'role_store')->name('roles.store');
            // Route::get('/role_old/{role}/edit', 'role_edit')->name('roles.edit');
            // Route::put('/role_old/{role}', 'role_update')->name('roles.update');  
            // Route::get('/role_old/delete/{id}', 'role_delete')->name('roles.delete');          
            
            //Users
            // Route::get('/users_old/create', 'user_create')->name('users.create');
            // Route::post('/users_old',  'user_store')->name('users.store');
            // Route::get('/users_old/{id}/edit', 'user_edit')->name('users.edit');
            // Route::post('/users_old/{id}', 'user_update')->name('users.update');
            // Route::delete('/user_old/delete/{id}', 'user_delete')->name('users.delete');    
            
            Route::get('/logout', 'logout')->name('users.logout');
        });
        
        
        //Temp image controller
        Route::post('/upload-temp-image', [TempImagesController::class, 'create'])->name('temp-images.create');
    });

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

require __DIR__.'/auth.php';