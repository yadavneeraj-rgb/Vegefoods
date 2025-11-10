<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ShopingModule;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(Request $request)
    {
        $catQuery = Category::query();
        $productQuery = Product::query();

        if (isset($request->moduleId)) {
           session(['module_id' => $request->moduleId]);
            
            $categories = $catQuery->where("module_id", $request->moduleId)->where('parent_id', 0)
                ->where('status', 1)
                ->take(4)
                ->get();

            $featuredProducts = $productQuery->whereHas('categories', function ($q) use ($request) {
                $q->where('module_id', $request->moduleId);
            })->with('categories')->get();

        } else {
            $categories = Category::where('parent_id', 0)
                ->where('status', 1)
                ->take(4)
                ->get();
            $featuredProducts = Product::with('pricing')
                ->where('is_featured', true)
                ->where('status', 1)
                ->take(8)
                ->get();
        }
        $modules = ShopingModule::all();

        return view('web.home', compact('categories', 'featuredProducts', 'modules'));
    }
}