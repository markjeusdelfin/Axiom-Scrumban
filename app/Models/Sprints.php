<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Sprints extends Model
{
    use HasUuids;
    protected $guarded = [];

    public function tasks()
    {
        return $this->hasMany(Tasks::class, 'sprint_id');
    }
}
