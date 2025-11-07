<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPricing extends Model
{
    use HasFactory;

    protected $table = 'product_pricing';

    protected $fillable = [
        'product_id',
        'mrp_base_price',
        'tax_percentage',
        'discount_type',
        'discount_value',
        'final_price'
    ];

    protected $casts = [
        'mrp_base_price' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'final_price' => 'decimal:2',
    ];

    /**
     * Relationship with Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Calculate final price based on MRP, tax, and discount
     */
    public function calculateFinalPrice()
    {
        $basePrice = $this->mrp_base_price;
        
        // Calculate tax amount
        $taxAmount = ($basePrice * $this->tax_percentage) / 100;
        
        // Calculate price after tax
        $priceAfterTax = $basePrice + $taxAmount;
        
        // Calculate discount amount
        $discountAmount = 0;
        if ($this->discount_type === 'flat') {
            $discountAmount = $this->discount_value;
        } elseif ($this->discount_type === 'percentage') {
            $discountAmount = ($priceAfterTax * $this->discount_value) / 100;
        }
        
        // Calculate final price
        $finalPrice = $priceAfterTax - $discountAmount;
        
        // Ensure final price is not negative
        $this->final_price = max(0, $finalPrice);
        
        return $this->final_price;
    }

    /**
     * Get tax amount
     */
    public function getTaxAmountAttribute()
    {
        return ($this->mrp_base_price * $this->tax_percentage) / 100;
    }

    /**
     * Get discount amount
     */
    public function getDiscountAmountAttribute()
    {
        if ($this->discount_type === 'flat') {
            return $this->discount_value;
        } elseif ($this->discount_type === 'percentage') {
            $priceAfterTax = $this->mrp_base_price + $this->tax_amount;
            return ($priceAfterTax * $this->discount_value) / 100;
        }
        return 0;
    }

    /**
     * Get price after tax
     */
    public function getPriceAfterTaxAttribute()
    {
        return $this->mrp_base_price + $this->tax_amount;
    }
}
