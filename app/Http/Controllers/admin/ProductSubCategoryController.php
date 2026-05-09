<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class ProductSubCategoryController extends Controller {
    public function index(Request $request) {
        if (!empty($request->category_id)) {

            $subCategories = Menu::whereHas('categories', function($query) use ($request) {
                    $query->where('categories.id', $request->category_id);
                })
                ->orderBy('name', 'ASC')
                ->get();

            return response()->json([
                'status' => true,
                'subCategories' => $subCategories
            ]);

        } else {

            return response()->json([
                'status' => true,
                'subCategories' => []
            ]);
        }
    }
}
