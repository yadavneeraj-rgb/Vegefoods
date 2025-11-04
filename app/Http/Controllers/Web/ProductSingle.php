<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductSingle extends Controller
{
    public function productSingle(){
        return view('web.product_single.productSingle');
    }
}
