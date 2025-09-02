<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
protected $fillable = [
  'title','description','location','venue','starts_at','ends_at','capacity',
  'price','banner','banner_path','allow_pay_later','created_by','status','approved_by','approved_at','featured_on_home','visible_on_site','event_type','event_status',
];


    protected $casts = [
        'starts_at'        => 'datetime',
        'ends_at'          => 'datetime',
        'approved_at'      => 'datetime',
        'price'            => 'decimal:2',
        'allow_pay_later'  => 'boolean',
        'featured_on_home' => 'boolean',
        'visible_on_site'  => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Get all ticket types for this event
     */
    public function ticketTypes(): HasMany
    {
        return $this->hasMany(TicketType::class)->ordered();
    }

    /**
     * Get available ticket types for this event
     */
    public function availableTicketTypes(): HasMany
    {
        return $this->hasMany(TicketType::class)->available()->ordered();
    }

    /**
     * Check if event is free
     */
    public function isFree(): bool
    {
        return $this->event_type === 'free';
    }

    /**
     * Check if event is paid
     */
    public function isPaid(): bool
    {
        return $this->event_type === 'paid';
    }

    /**
     * Check if event is available for booking
     */
    public function isAvailableForBooking(): bool
    {
        return $this->event_status === 'available';
    }

    /**
     * Check if event has limited sell status
     */
    public function hasLimitedSell(): bool
    {
        return $this->event_status === 'limited_sell';
    }

    /**
     * Check if event is sold out
     */
    public function isSoldOut(): bool
    {
        return $this->event_status === 'sold_out';
    }

    /**
     * Get the minimum price for paid events
     */
    public function getMinimumPriceAttribute(): float
    {
        if ($this->isFree()) {
            return 0;
        }

        // If event has ticket types, get minimum price from ticket types
        $ticketTypes = $this->availableTicketTypes;
        if ($ticketTypes->count() > 0) {
            return $ticketTypes->min('price');
        }

        // Fallback to event price
        return (float) $this->price;
    }

    /**
     * Get the maximum price for paid events
     */
    public function getMaximumPriceAttribute(): float
    {
        if ($this->isFree()) {
            return 0;
        }

        // If event has ticket types, get maximum price from ticket types
        $ticketTypes = $this->availableTicketTypes;
        if ($ticketTypes->count() > 0) {
            return $ticketTypes->max('price');
        }

        // Fallback to event price
        return (float) $this->price;
    }

    /**
     * Get price display text
     */
    public function getPriceDisplayAttribute(): string
    {
        if ($this->isFree()) {
            return 'Free';
        }

        $min = $this->minimum_price;
        $max = $this->maximum_price;

        if ($min == $max) {
            return '৳' . number_format($min, 2);
        }

        return '৳' . number_format($min, 2) . ' - ৳' . number_format($max, 2);
    }

    /**
     * Get the total capacity of the event
     * If event has ticket types with quantities, sum them up
     * Otherwise, use the event's capacity field
     */
    public function getTotalCapacityAttribute(): ?int
    {
        // If event has ticket types with defined quantities, sum them
        $ticketTypes = $this->ticketTypes;
        if ($ticketTypes->count() > 0) {
            $totalFromTicketTypes = $ticketTypes->whereNotNull('quantity_available')->sum('quantity_available');
            if ($totalFromTicketTypes > 0) {
                return $totalFromTicketTypes;
            }
        }

        // Fallback to event's capacity field
        return $this->capacity;
    }

    /**
     * Get capacity display text
     */
    public function getCapacityDisplayAttribute(): string
    {
        $totalCapacity = $this->total_capacity;
        return $totalCapacity ? number_format($totalCapacity) : 'Unlimited';
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
                    ->where('visible_on_site', true)
                    ->where('starts_at', '>', now())
                    ->orderBy('starts_at');
    }

    // Scope for events visible on the public site
    public function scopeVisibleOnSite($query)
    {
        return $query->where('visible_on_site', true)
                    ->where('status', 'approved');
    }

    // Scope for available events (not sold out)
    public function scopeAvailableForBooking($query)
    {
        return $query->whereIn('event_status', ['available', 'limited_sell']);
    }
}
