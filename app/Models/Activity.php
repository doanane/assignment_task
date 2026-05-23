<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activity extends Model
{
    protected $fillable = ['title', 'description', 'category', 'created_by'];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updates(): HasMany
    {
        return $this->hasMany(ActivityUpdate::class)->orderBy('updated_at', 'asc');
    }

    public function latestUpdate()
    {
        return $this->hasOne(ActivityUpdate::class)->latestOfMany('updated_at');
    }

    public function currentStatus(): string
    {
        return $this->latestUpdate?->status ?? 'pending';
    }
}
