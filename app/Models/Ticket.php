<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'user_id','event_id','ticket_type_id','quantity','total_amount','unit_price',
        'payment_option','payment_status','ticket_code','ticket_number','qr_path',
        // manual payment fields
        'payment_txn_id','payer_number','payment_proof_path',
        'payment_verified_at','payment_verified_by',
        // SSLCommerz payment fields
        'sslcommerz_val_id','sslcommerz_bank_tran_id','sslcommerz_card_type','payment_method',
    ];

    protected $casts = [
        'payment_verified_at' => 'datetime',
        'total_amount' => 'decimal:2',
        'unit_price' => 'decimal:2',
    ];

    public function user()     { return $this->belongsTo(User::class); }
    public function event()    { return $this->belongsTo(Event::class); }
    public function verifier() { return $this->belongsTo(User::class, 'payment_verified_by'); }
    
    /**
     * Get the ticket type for this ticket
     */
    public function ticketType() { 
        return $this->belongsTo(TicketType::class); 
    }

    /**
     * Get the ticket type name or fallback to event title
     */
    public function getTicketTypeNameAttribute(): string
    {
        if ($this->ticketType) {
            return $this->ticketType->name;
        }

        // Fallback for tickets without ticket type
        return 'General Admission';
    }
}
