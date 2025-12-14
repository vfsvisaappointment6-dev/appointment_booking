<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\Auditable;
use Illuminate\Support\Str;

class ChatMessage extends Model
{
    use HasFactory, Auditable;

    protected $primaryKey = 'chat_message_id';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'chat_message_id',
        'sender_id',
        'receiver_id',
        'message',
        'seen',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'seen' => 'boolean',
            'created_at' => 'datetime',
        ];
    }

    /**
     * Boot: Generate UUID on create
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = Str::uuid()->toString();
            }
        });
    }

    /**
     * Scope: Get unread messages.
     */
    public function scopeUnread($query)
    {
        return $query->where('seen', false);
    }

    /**
     * Scope: Get messages between two users.
     */
    public function scopeBetween($query, $userId1, $userId2)
    {
        return $query->where(function ($q) use ($userId1, $userId2) {
            $q->where(function ($subQ) use ($userId1, $userId2) {
                $subQ->where('sender_id', $userId1)->where('receiver_id', $userId2);
            })->orWhere(function ($subQ) use ($userId1, $userId2) {
                $subQ->where('sender_id', $userId2)->where('receiver_id', $userId1);
            });
        })->orderBy('created_at', 'asc');
    }

    /**
     * Accessor: Get readable message status.
     */
    public function getStatusLabelAttribute()
    {
        return $this->seen ? 'Read' : 'Unread';
    }

    /**
     * Get the user who sent the message.
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id', 'user_id');
    }

    /**
     * Get the user who received the message.
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id', 'user_id');
    }
}
