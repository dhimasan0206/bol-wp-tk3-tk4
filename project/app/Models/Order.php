<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function totalItems(): int
    {
        $quantity = 0;
        foreach ($this->items as $item) {
            $quantity += $item->quantity;
        }
        return $quantity;
    }

    public function totalPrice(): int
    {
        $price = 0;
        foreach ($this->items as $item) {
            $price += $item->quantity * $item->price;
        }
        return $price;
    }

    public function fulfillable(): bool
    {
        if ($this->status != "PENDING") {
            return false;
        }

        foreach ($this->items as $item) {
            if (is_null($item->product) || $item->product->stock < $item->quantity) {
                return false;
            }
        }

        return true;
    }

    public function cancellable(): bool
    {
        return $this->status == 'PENDING';
    }
}
