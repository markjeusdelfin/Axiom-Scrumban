<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Tasks extends Model
{
    use HasUuids;

    protected $guarded = [];

    // Use jsonb for metadata to allow efficient PostgreSQL filtering
    protected $casts = [
        'metadata' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
