<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property Table|null $table
 * @property User $user
 * @property User|null $kasir
 */
class Order extends Model
{
    protected $fillable = [
        'order_code', 'user_id', 'kasir_id', 'table_id', 'order_type',
        'total_price', 'status', 'payment_status', 'payment_method', 'snap_token'
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function kasir(): BelongsTo { return $this->belongsTo(User::class, 'kasir_id'); }
    public function table(): BelongsTo { return $this->belongsTo(Table::class); }
    public function items(): HasMany { return $this->hasMany(OrderItem::class); }
}