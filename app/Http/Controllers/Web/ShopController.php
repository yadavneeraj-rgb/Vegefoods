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
        $catQuery = Category::query();
        $products = Product::query();

        $moduleId = session("module_id");
        // dd($moduleId);
        if ($moduleId) {
            $categories = $catQuery->where('parent_id', 0)->where('module_id', $moduleId)->where('status', 1)->get();
            $pro = $products->whereHas('categories', function ($query) use ($moduleId) {
                $query->where('module_id', $moduleId)->where('status', 1);
            })->with('categories')->get();

        } else {
            $categories = $catQuery->where('parent_id', 0)->where('status', 1)->get();
        }

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