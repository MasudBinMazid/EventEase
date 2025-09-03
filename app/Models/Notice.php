<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'is_active',
        'priority',
        'order',
        'start_date',
        'end_date',
        'bg_color',
        'text_color',
        'font_family',
        'font_size',
        'font_weight',
        'text_style',
        'type',
        'is_marquee',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_marquee' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    // Scope for active notices
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for current notices (within date range)
    public function scopeCurrent($query)
    {
        $now = Carbon::now();
        return $query->where(function ($q) use ($now) {
            $q->whereNull('start_date')
              ->orWhere('start_date', '<=', $now);
        })->where(function ($q) use ($now) {
            $q->whereNull('end_date')
              ->orWhere('end_date', '>=', $now);
        });
    }

    // Scope for ordered notices
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('created_at', 'desc');
    }
}
