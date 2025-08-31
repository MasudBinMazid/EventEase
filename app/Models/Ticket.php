<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'user_id','event_id','quantity','total_amount',
        'payment_option','payment_status','ticket_code','ticket_number','qr_path',
        // new fields for manual payment
        'payment_txn_id','payer_number','payment_proof_path',
        'payment_verified_at','payment_verified_by',
    ];

    protected $casts = [
        'payment_verified_at' => 'datetime',
    ];

    public function user()     { return $this->belongsTo(User::class); }
    public function event()    { return $this->belongsTo(Event::class); }
    public function verifier() { return $this->belongsTo(User::class, 'payment_verified_by'); }
}
