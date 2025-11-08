<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\ShopController;
use App\Models\ShopingModule;
use Illuminate\Http\Request;
use function PHPUnit\Framework\returnArgument;

class ModuleController extends Controller
{
    //

    public function module()
    {
        $modules = ShopingModule::all();
        return view("admin.module.module", compact("modules"));
    }

    public function edit_module($id){
        $module = ShopingModule::where('id', $id)->first();
        return view('admin.module.edit', compact('module'));
    }

    public function update(Request $request, $id)
    {

        dd($request->all());

        $request->validate([
            'name' => 'required|max:255',
        ]);
        $module = ShopingModule::find($id);
        $module->update($request->all());
        return redirect()->route('admin.module.module')
            ->with('success', 'Post updated successfully.');
    }
}
