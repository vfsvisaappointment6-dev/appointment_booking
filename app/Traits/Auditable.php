<?php

namespace App\Traits;

use App\Models\AuditLog;

trait Auditable
{
    /**
     * Boot the Auditable trait.
     */
    protected static function bootAuditable()
    {
        static::created(function ($model) {
            AuditLog::create([
                'user_id' => optional(auth())->id(),
                'auditable_type' => get_class($model),
                'auditable_id' => $model->getKey(),
                'action' => 'created',
                'new_values' => $model->getAttributes(),
            ]);
        });

        static::updated(function ($model) {
            $changes = $model->getChanges();
            if (!empty($changes)) {
                AuditLog::create([
                    'user_id' => optional(auth())->id(),
                    'auditable_type' => get_class($model),
                    'auditable_id' => $model->getKey(),
                    'action' => 'updated',
                    'old_values' => collect($model->getOriginal())->only(array_keys($changes)),
                    'new_values' => $changes,
                ]);
            }
        });

        static::deleted(function ($model) {
            AuditLog::create([
                'user_id' => optional(auth())->id(),
                'auditable_type' => get_class($model),
                'auditable_id' => $model->getKey(),
                'action' => 'deleted',
                'old_values' => $model->getAttributes(),
            ]);
        });
    }

    /**
     * Get audit logs for this model.
     */
    public function auditLogs()
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }
}
