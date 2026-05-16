<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Task extends Model
{
    protected $fillable = [
        'local_id', 'remote_id', 'title', 'is_completed',
        'sync_status', 'last_synced_at', 'photo_path',
        'location_lat', 'location_lng'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->local_id)) {
                $model->local_id = (string) Str::uuid();
            }
        });
    }

    public function markPending(): void
    {
        $this->update(['sync_status' => 'pending']);
    }

    public function markSynced(): void
    {
        $this->update(['sync_status' => 'synced', 'last_synced_at' => now()]);
    }

    public function markError(): void
    {
        $this->update(['sync_status' => 'error']);
    }

    public string $status {
        get => $this->is_completed ? 'Selesai! ✅' : 'Belum Siap ⏳';
    }
}