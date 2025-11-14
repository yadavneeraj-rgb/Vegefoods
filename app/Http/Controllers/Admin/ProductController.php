<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductPricing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function product()
    {
        $products = Product::with('pricing')->get();
        return view('admin.product.product', compact('products'));
    }

    public function createProduct()
    {
        return view('admin.product.createProduct');
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:product,name',
            'description' => 'nullable|string',
            'search_tag' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'quantity' => 'required|integer|min:0',
            'mrp_base_price' => 'required|numeric|min:0',
            'tax_percentage' => 'required|numeric|min:0|max:100',
            'discount_type' => 'nullable|in:flat,percentage',
            'discount_value' => 'nullable|numeric|min:0',
        ]);

        try {
            $productData = [
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'search_tag' => $request->search_tag,
                'quantity' => $request->quantity,
                'status' => 1,
                'is_featured' => $request->is_featured ?? 0,
            ];

            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('products', 'public');
                $productData['image'] = $imagePath;
            }

            // Create product
            $product = Product::create($productData);

            // Create pricing
            $pricingData = [
                'product_id' => $product->id,
                'mrp_base_price' => $request->mrp_base_price,
                'tax_percentage' => $request->tax_percentage,
                'discount_type' => $request->discount_type,
                'discount_value' => $request->discount_value ?? 0,
            ];

            $productPricing = new ProductPricing($pricingData);
            $productPricing->calculateFinalPrice();
            $productPricing->save();

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully!',
                'product' => $product->load('pricing')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating product: ' . $e->getMessage()
            ], 500);
        }
    }

    public function editProduct($id)
    {
        $product = Product::with('pricing')->findOrFail($id);
        return response()->json($product);
    }

    public function updateProduct(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:product,name,' . $id,
            'description' => 'nullable|string',
            'search_tag' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'quantity' => 'required|integer|min:0',
            'mrp_base_price' => 'required|numeric|min:0',
            'tax_percentage' => 'required|numeric|min:0|max:100',
            'discount_type' => 'nullable|in:flat,percentage',
            'discount_value' => 'nullable|numeric|min:0',
        ]);

        try {
            $product = Product::findOrFail($id);
            
            $updateData = [
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'search_tag' => $request->search_tag,
                'quantity' => $request->quantity,
                'is_featured' => $request->is_featured ?? 0,
            ];

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                
                // Store new image
                $imagePath = $request->file('image')->store('products', 'public');
                $updateData['image'] = $imagePath;
            }

            $product->update($updateData);

            // Update or create pricing
            $pricingData = [
                'mrp_base_price' => $request->mrp_base_price,
                'tax_percentage' => $request->tax_percentage,
                'discount_type' => $request->discount_type,
                'discount_value' => $request->discount_value ?? 0,
            ];

            $productPricing = $product->pricing ?? new ProductPricing(['product_id' => $product->id]);
            $productPricing->fill($pricingData);
            $productPricing->calculateFinalPrice();
            $productPricing->save();

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully!',
                'product' => $product->load('pricing')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating product: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyProduct($id)
    {
        try {
            $product = Product::findOrFail($id);
            
            // Delete image file if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            
            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting product: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleFeatured($id)
    {
        try {
            $product = Product::findOrFail($id);
            
            // Toggle the featured status
            $product->update([
                'is_featured' => !$product->is_featured
            ]);

            return response()->json([
                'success' => true,
                'message' => $product->is_featured 
                    ? 'Product marked as featured!' 
                    : 'Product removed from featured!',
                'is_featured' => $product->is_featured
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating featured status: ' . $e->getMessage()
            ], 500);
        }
    }
}