<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketType extends Model
{
    protected $fillable = [
        'event_id',
        'name',
        'price',
        'description',
        'quantity_available',
        'quantity_sold',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity_available' => 'integer',
        'quantity_sold' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Get the event that owns this ticket type
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get all tickets sold for this ticket type
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Scope for available ticket types
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope for ordering by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('price');
    }

    /**
     * Check if ticket type has quantity available
     */
    public function hasQuantityAvailable(int $requestedQuantity = 1): bool
    {
        if ($this->status === 'sold_out') {
            return false;
        }

        if ($this->quantity_available === null) {
            return true; // Unlimited
        }

        return ($this->quantity_available - $this->quantity_sold) >= $requestedQuantity;
    }

    /**
     * Get remaining quantity
     */
    public function getRemainingQuantityAttribute(): ?int
    {
        if ($this->quantity_available === null) {
            return null; // Unlimited
        }

        return max(0, $this->quantity_available - $this->quantity_sold);
    }

    /**
     * Check if ticket type is sold out
     */
    public function getIsSoldOutAttribute(): bool
    {
        return $this->status === 'sold_out' || 
               ($this->quantity_available !== null && $this->remaining_quantity <= 0);
    }

    /**
     * Auto-update status when quantity sold changes
     */
    public function updateStatus(): void
    {
        if ($this->quantity_available !== null && 
            $this->quantity_sold >= $this->quantity_available) {
            $this->update(['status' => 'sold_out']);
        }
    }
}
