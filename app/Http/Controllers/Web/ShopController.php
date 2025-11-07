<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function shop(Request $request)
    {
        
        $categories = Category::where('parent_id', 0)->where('status', 1)->get();

        $products = Product::with(['categories', 'pricing'])
            ->where('status', 1);

        if ($request->has('category') && $request->category != 'all') {
            $categoryId = $request->category;

            $subcategoryIds = Category::where('parent_id', $categoryId)
                ->where('status', 1)
                ->pluck('id')
                ->toArray();

            $allCategoryIds = array_merge([$categoryId], $subcategoryIds);

            $products->whereHas('categories', function ($query) use ($allCategoryIds) {
                $query->whereIn('categories.id', $allCategoryIds);
            });
        }

        $products = $products->get();

        return view('web.shop.shop', compact('categories', 'products'));
    }
}