<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'user_id',
        'event_id',
        'quantity',
        'amount',
        'currency',
        'payment_method',
        'status',
        'sslcommerz_val_id',
        'sslcommerz_bank_tran_id',
        'sslcommerz_card_type',
        'gateway_response',
        'payment_verified_at',
        'ticket_id',
        'notes'
    ];

    protected $casts = [
        'gateway_response' => 'array',
        'payment_verified_at' => 'timestamp',
        'amount' => 'decimal:2'
    ];

    /**
     * Get the user that owns the payment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the event for this payment
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the ticket created from this payment
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Scope for successful payments
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Check if payment is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Mark payment as completed
     */
    public function markAsCompleted(): self
    {
        $this->update([
            'status' => 'completed',
            'payment_verified_at' => now()
        ]);
        
        return $this;
    }

    /**
     * Mark payment as failed
     */
    public function markAsFailed(string $reason = null): self
    {
        $this->update([
            'status' => 'failed',
            'notes' => $reason
        ]);
        
        return $this;
    }
}
