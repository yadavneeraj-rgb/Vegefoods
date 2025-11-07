<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function shop()
    {
        // Get only main categories (where parent_id = 0)
        $categories = Category::where('parent_id', 0)->where('status', 1)->get();

        // Get all products with their categories
        $products = Product::with('categories')
            ->where('status', 1)
            ->get();

        // Build category hierarchy for JavaScript filtering
        $categoryHierarchy = $this->buildCategoryHierarchy();

        return view('web.shop.shop', compact('categories', 'products', 'categoryHierarchy'));
    }

    private function buildCategoryHierarchy()
    {
        $allCategories = Category::where('status', 1)->get();
        $hierarchy = [];

        foreach ($allCategories as $category) {
            $subcategories = Category::where('parent_id', $category->id)
                ->where('status', 1)
                ->pluck('id')
                ->toArray();

            $hierarchy[$category->id] = $subcategories;
        }

        return $hierarchy;
    }
}