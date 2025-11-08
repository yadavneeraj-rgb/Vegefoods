<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ShopingModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function category()
    {
        $categories = Category::mainCategories()
            ->withCount('children')
            ->with('module')
            ->get();

        return view('admin.category.category', compact('categories'));
    }
    public function create()
    {
        $mainCategories = Category::mainCategories()->get();
        $modules = ShopingModule::all();

        return view("admin.category.create", compact('mainCategories', 'modules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'module_id' => 'required|exists:shoping_modules,id',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        try {
            $categoryData = [
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'module_id' => $request->module_id,
                'parent_id' => $request->parent_id ?? 0,
                'status' => 1,
            ];

            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('categories', 'public');
                $categoryData['image'] = $imagePath;
            }

            $category = Category::create($categoryData);

            return response()->json([
                'success' => true,
                'message' => $request->parent_id ? 'Subcategory created successfully!' : 'Category created successfully!',
                'category' => $category
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating category: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $mainCategories = Category::mainCategories()->where('id', '!=', $id)->get();
        $modules = ShopingModule::all();

        return response()->json([
            'category' => $category,
            'mainCategories' => $mainCategories,
            'modules' => $modules
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'module_id' => 'required|exists:shoping_modules,id',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        try {
            $category = Category::findOrFail($id);

            $updateData = [
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'module_id' => $request->module_id,
                'parent_id' => $request->parent_id ?? 0,
            ];

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($category->image) {
                    Storage::disk('public')->delete($category->image);
                }

                // Store new image
                $imagePath = $request->file('image')->store('categories', 'public');
                $updateData['image'] = $imagePath;
            }

            $category->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully!',
                'category' => $category
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating category: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);

            // Check if category has subcategories
            if ($category->children()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete category. It has subcategories. Please delete subcategories first.'
                ], 422);
            }

            // Delete image file if exists
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting category: ' . $e->getMessage()
            ], 500);
        }
    }

    public function subcategories($id)
    {
        $category = Category::with('children')->findOrFail($id);
        $subcategories = $category->children;

        return view('admin.category.subcategories.list', compact('category', 'subcategories'));
    }

    public function storeSubcategory(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'module_id' => 'required|exists:shoping_modules,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        try {
            $parentCategory = Category::findOrFail($id);

            $subcategoryData = [
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'module_id' => $request->module_id,
                'parent_id' => $id,
                'status' => 1,
            ];

            // Handle image upload for subcategory
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('categories', 'public');
                $subcategoryData['image'] = $imagePath;
            }

            $subcategory = Category::create($subcategoryData);

            return response()->json([
                'success' => true,
                'message' => 'Subcategory created successfully!',
                'subcategory' => $subcategory
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating subcategory: ' . $e->getMessage()
            ], 500);
        }
    }
}