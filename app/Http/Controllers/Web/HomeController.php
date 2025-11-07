<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function home()
    {
        $categories = Category::where('parent_id', 0)
            ->where('status', 1)
            ->take(4)
            ->get();

        $featuredProducts = Product::where('is_featured', true)
            ->where('status', 1)
            ->take(8)
            ->get();

        return view('web.home', compact('categories', 'featuredProducts'));
    }

}
