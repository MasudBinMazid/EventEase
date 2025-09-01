<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoticeSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_enabled',
        'scroll_speed',
        'background_color',
        'text_color',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    // Get the first (and only) settings record
    public static function getSettings()
    {
        return self::first() ?? self::create([
            'is_enabled' => false,
            'scroll_speed' => 'normal',
            'background_color' => '#1e3a8a',
            'text_color' => '#ffffff',
        ]);
    }
}
