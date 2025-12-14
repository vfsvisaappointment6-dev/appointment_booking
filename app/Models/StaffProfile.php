<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;

class StaffProfile extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    protected $primaryKey = 'staff_profile_id';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'staff_profile_id',
        'user_id',
        'specialty',
        'bio',
        'rating',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'rating' => 'float',
        ];
    }

    /**
     * Scope: Get active staff.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: Get top-rated staff.
     */
    public function scopeTopRated($query, $minRating = 4.0)
    {
        return $query->where('rating', '>=', $minRating);
    }

    /**
     * Get the user associated with the staff profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get reviews for this staff profile via the user's id.
     * Reviews are stored against `reviews.staff_id` which references `users.user_id`.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'staff_id', 'user_id');
    }

    /**
     * Get blocked times for this staff profile.
     */
    public function blockedTimes(): HasMany
    {
        return $this->hasMany(BlockedTime::class, 'staff_profile_id', 'staff_profile_id');
    }
}
