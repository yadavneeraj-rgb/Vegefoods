<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategories;

class ProductCategoryController extends Controller
{
    public function productCategory()
    {
        $products = Product::where('status', 1)->get();
        
        $categories = Category::where('status', 1)
            ->where('parent_id', '!=', 0)
            ->with('parent')
            ->get();

        // Get assigned products with safety checks
        $assignedProducts = ProductCategories::with([
                'product' => function($query) {
                    $query->where('status', 1);
                },
                'category' => function($query) {
                    $query->where('status', 1)->with('parent');
                }
            ])
            ->get()
            ->filter(function($assignment) {
                // Only include assignments where both product and category exist
                return $assignment->product && $assignment->category;
            })
            ->groupBy('product_id');

        return view('admin.productCategory.productCategory', compact(
            'products',
            'categories',
            'assignedProducts'
        ));
    }

    public function assignCategory(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:product,id',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id'
        ]);

        // Remove existing assignments for this product
        ProductCategories::where('product_id', $request->product_id)->delete();

        // Add new assignments
        foreach ($request->category_ids as $category_id) {
            ProductCategories::create([
                'product_id' => $request->product_id,
                'category_id' => $category_id
            ]);
        }

        return redirect()->back()->with('success', 'Categories assigned successfully!');
    }

    public function getProductCategories($productId)
    {
        $assignedCategories = ProductCategories::where('product_id', $productId)
            ->pluck('category_id')
            ->toArray();

        return response()->json($assignedCategories);
    }

    public function removeAssignment($id)
    {
        ProductCategories::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Category assignment removed!');
    }
}