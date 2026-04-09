<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title', 'is_completed'];

    // Menggunakan PHP 8.4 Property Hooks
    // Kita buat virtual property 'status' untuk paparan yang lebih funky
    public string $status {
        get => $this->is_completed ? 'Selesai! ✅' : 'Belum Siap ⏳';
    }
}
