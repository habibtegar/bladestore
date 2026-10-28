<?php

namespace App\Models;

use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'order_code',
        'user_id',
        'total_price',
        'payment_method',
        'status',
        'shipping_address',
        'customer_phone',
        'notes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'total_price' => 'decimal:2',
        ];
    }

    /**
     * Get the formatted total price in IDR.
     */
    protected function formattedTotalPrice(): Attribute
    {
        return Attribute::make(
            get: fn (): string => 'Rp '.number_format((float) $this->total_price, 0, ',', '.')
        );
    }

    /**
     * Get the Bootstrap badge class for the order status.
     */
    protected function statusBadgeClass(): Attribute
    {
        return Attribute::make(
            get: fn (): string => match ($this->status) {
                'pending' => 'bg-warning text-dark',
                'processing' => 'bg-info text-dark',
                'completed' => 'bg-success',
                'cancelled' => 'bg-danger',
                default => 'bg-secondary',
            }
        );
    }

    /**
     * Get the human-readable Indonesian label for order status.
     */
    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn (): string => match ($this->status) {
                'pending' => 'Menunggu Pembayaran / Konfirmasi',
                'processing' => 'Sedang Diproses',
                'completed' => 'Pesanan Selesai',
                'cancelled' => 'Dibatalkan',
                default => ucfirst((string) $this->status),
            }
        );
    }

    /**
     * Get the user that placed the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items for this order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
