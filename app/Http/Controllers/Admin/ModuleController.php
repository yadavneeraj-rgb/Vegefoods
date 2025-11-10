<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShopingModule;
use Illuminate\Http\Request;
use Storage;

class ModuleController extends Controller
{

    public function module()
    {
        $modules = ShopingModule::all();
        return view("admin.module.module", compact("modules"));
    }

    public function edit_module($id)
    {
        $module = ShopingModule::where('id', $id)->first();
        return view('admin.module.edit', compact('module'));
    }

    public function update_module(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
           'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $module = ShopingModule::findOrFail($id);
        $module->name = $request->name;

        if ($request->hasfile('image')) {
            if ($module->image && Storage::disk('public')->exists('modules/' . $module->image)) {
                Storage::disk('public')->delete('' . $module->image);
            }
            $path = $request->file('image')->store('modules', 'public');
            $module->image = basename($path);
        }

        $module->save();

        return redirect()->route('module')->with('success', 'Module updated successfully.');

    }
}
