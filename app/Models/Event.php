<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
protected $fillable = [
  'title','description','location','starts_at','ends_at','capacity',
  'price','banner_path','created_by','status','approved_by','approved_at','featured_on_home',
];


    protected $casts = [
        'starts_at'   => 'datetime',
        'ends_at'     => 'datetime',
        'approved_at' => 'datetime',
        'price'       => 'decimal:2',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Optional: always get a usable URL for banner
    public function getBannerUrlAttribute(): ?string
    {
        return $this->banner_path ? asset(ltrim($this->banner_path, '/')) : null;
    }

    // Scope for featured events on home page
    public function scopeFeaturedOnHome($query)
    {
        return $query->where('featured_on_home', true)
                    ->where('status', 'approved')
                    ->where('starts_at', '>', now())
                    ->orderBy('starts_at');
    }
}
