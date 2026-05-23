<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityUpdate extends Model
{
    public $timestamps = false;

    protected $fillable = ['activity_id', 'updated_by', 'status', 'remark', 'updated_at'];

    protected $casts = [
        'updated_at' => 'datetime',
    ];

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
