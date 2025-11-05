<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function category()
    {
        $categories = Category::mainCategories()->withCount('children')->get();
        return view('admin.category.category', compact('categories'));
    }

    public function create()
    {
        $mainCategories = Category::mainCategories()->get();
        return view("admin.category.create", compact('mainCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        try {
            $category = Category::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'parent_id' => $request->parent_id ?? 0,
                'status' => 1,
            ]);

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

        return response()->json([
            'category' => $category,
            'mainCategories' => $mainCategories
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        try {
            $category = Category::findOrFail($id);
            $category->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'parent_id' => $request->parent_id ?? 0,
            ]);

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

        // Return only the HTML content for offcanvas
        return view('admin.category.subcategories.list', compact('category', 'subcategories'));
    }

    public function storeSubcategory(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name'
        ]);

        try {
            $parentCategory = Category::findOrFail($id);

            $subcategory = Category::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'parent_id' => $id,
                'status' => 1,
            ]);

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