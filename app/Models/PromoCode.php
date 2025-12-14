<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Auditable;

class PromoCode extends Model
{
    use HasFactory, Auditable;

    protected $primaryKey = 'promo_code_id';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'promo_code_id',
        'code',
        'discount_percentage',
        'discount_type',
        'discount_amount',
        'description',
        'expires_at',
        'status',
        'usage_limit',
        'times_used',
        'applicable_to',
        'service_id',
        'minimum_order_value',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'discount_percentage' => 'integer',
            'discount_amount' => 'decimal:2',
            'minimum_order_value' => 'decimal:2',
            'expires_at' => 'date',
        ];
    }

    /**
     * Scope: Get active promo codes.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('expires_at', '>=', now());
    }

    /**
     * Scope: Get expired promo codes.
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }

    /**
     * Scope: Get promo codes by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Accessor: Get the discount value as a percentage string.
     */
    public function getDiscountLabelAttribute()
    {
        if ($this->discount_type === 'percentage') {
            return "{$this->discount_percentage}% off";
        } else {
            return "₵" . number_format($this->discount_amount, 2) . " off";
        }
    }

    /**
     * Accessor: Check if code is still valid.
     */
    public function getIsValidAttribute()
    {
        return $this->status === 'active' && $this->expires_at >= now();
    }

    /**
     * Get the promo code usages.
     */
    public function usages(): HasMany
    {
        return $this->hasMany(PromoCodeUsage::class, 'promo_code_id', 'promo_code_id');
    }
}
