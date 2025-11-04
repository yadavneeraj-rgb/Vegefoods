<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WhislistController extends Controller
{
    public function wishlist(){
        return view('web.wishlist.wishlist');
    }
}
