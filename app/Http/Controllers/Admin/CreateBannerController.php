<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CreateBannerController extends Controller
{
    //
    public function createBanner()
    {
        return view("admin.banner.createBanner");
    }
}
