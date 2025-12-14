<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class BlockedTime extends Model
{
    use HasUuids;

    protected $table = 'blocked_times';
    protected $primaryKey = 'blocked_time_id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'staff_profile_id',
        'start_date',
        'end_date',
        'reason'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Relationship with StaffProfile
     */
    public function staffProfile()
    {
        return $this->belongsTo(StaffProfile::class, 'staff_profile_id', 'staff_profile_id');
    }
}
