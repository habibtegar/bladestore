<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'category',
        'slug',
        'description',
        'price',
        'discount_percentage',
        'stock',
        'image',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'discount_percentage' => 'integer',
            'stock' => 'integer',
        ];
    }

    public const DEFAULT_FALLBACK_IMAGE = 'data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20400%20400%22%20width%3D%22400%22%20height%3D%22400%22%3E%3Crect%20fill%3D%22%23F1F5F9%22%20width%3D%22400%22%20height%3D%22400%22%2F%3E%3Crect%20x%3D%22150%22%20y%3D%22140%22%20width%3D%22100%22%20height%3D%2280%22%20rx%3D%2210%22%20fill%3D%22%23CBD5E1%22%2F%3E%3Ccircle%20cx%3D%22180%22%20cy%3D%22170%22%20r%3D%2210%22%20fill%3D%22%2394A3B8%22%2F%3E%3Cpath%20d%3D%22M165%20205l20-22%2015%2015%2022-25%2018%2032z%22%20fill%3D%22%2394A3B8%22%2F%3E%3Ctext%20x%3D%2250%25%22%20y%3D%22260%22%20text-anchor%3D%22middle%22%20fill%3D%22%2394A3B8%22%20font-family%3D%22sans-serif%22%20font-size%3D%2213%22%20font-weight%3D%22600%22%3ELaraShop%3C%2Ftext%3E%3C%2Fsvg%3E';

    /**
     * Get the formatted price in IDR currency.
     */
    protected function formattedPrice(): Attribute
    {
        return Attribute::make(
            get: fn (): string => 'Rp '.number_format((float) $this->price, 0, ',', '.')
        );
    }

    /**
     * Get the calculated final price after discount.
     */
    protected function finalPrice(): Attribute
    {
        return Attribute::make(
            get: function (): float {
                $price = (float) $this->price;
                $discount = (int) ($this->discount_percentage ?? 0);

                if ($discount > 0) {
                    $discountAmount = $price * ($discount / 100);

                    return (float) max(0, round($price - $discountAmount, 2));
                }

                return $price;
            }
        );
    }

    /**
     * Get the formatted final price in IDR currency.
     */
    protected function formattedFinalPrice(): Attribute
    {
        return Attribute::make(
            get: fn (): string => 'Rp '.number_format((float) $this->final_price, 0, ',', '.')
        );
    }

    /**
     * Get the product image or safe offline fallback SVG.
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn (): string => ! empty($this->image) ? $this->image : self::DEFAULT_FALLBACK_IMAGE
        );
    }

    /**
     * Get the cart items referencing this product.
     */
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Get the order items referencing this product.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
