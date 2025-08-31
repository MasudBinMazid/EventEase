<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
protected $fillable = [
  'title','description','location','venue','starts_at','ends_at','capacity',
  'price','banner','banner_path','allow_pay_later','created_by','status','approved_by','approved_at','featured_on_home',
];


    protected $casts = [
        'starts_at'        => 'datetime',
        'ends_at'          => 'datetime',
        'approved_at'      => 'datetime',
        'price'            => 'decimal:2',
        'allow_pay_later'  => 'boolean',
        'featured_on_home' => 'boolean',
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
        // Try banner first, then banner_path
        $bannerField = $this->banner ?? $this->banner_path;
        
        if ($bannerField) {
            // Check if it's already a full URL
            if (str_starts_with($bannerField, 'http')) {
                return $bannerField;
            }
            
            // Handle direct uploads path (most common case in EventEase)
            if (str_starts_with($bannerField, 'uploads/')) {
                return asset($bannerField);
            }
            
            // Check if it's just a filename and exists in uploads/events
            $filename = basename($bannerField);
            if (file_exists(public_path('uploads/events/' . $filename))) {
                return asset('uploads/events/' . $filename);
            }
            
            // Try as a direct filename in the uploads/events folder
            if (!str_contains($bannerField, '/') && file_exists(public_path('uploads/events/' . $bannerField))) {
                return asset('uploads/events/' . $bannerField);
            }
            
            // Default storage path
            return asset('storage/' . ltrim($bannerField, '/'));
        }
        
        return null;
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
