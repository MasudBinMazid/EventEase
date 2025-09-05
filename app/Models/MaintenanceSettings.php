<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceSettings extends Model
{
    protected $fillable = [
        'is_enabled',
        'title',
        'message',
        'estimated_completion',
        'allowed_ips',
        'updated_by'
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'allowed_ips' => 'array',
        'estimated_completion' => 'datetime'
    ];

    protected $attributes = [
        'is_enabled' => false,
        'title' => 'Site Under Maintenance',
        'message' => 'We are currently performing maintenance on our website. We will be back online shortly!',
    ];

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public static function isEnabled()
    {
        $settings = self::first();
        return $settings ? $settings->is_enabled : false;
    }

    public static function getSettings()
    {
        $settings = self::first();
        
        if (!$settings) {
            $settings = new self([
                'is_enabled' => false,
                'title' => 'Site Under Maintenance',
                'message' => 'We are currently performing maintenance on our website. We will be back online shortly!',
            ]);
        }
        
        // Ensure message has a default value if it's null
        if (empty($settings->message)) {
            $settings->message = 'We are currently performing maintenance on our website. We will be back online shortly!';
        }
        
        return $settings;
    }

    public function isIpAllowed($ip)
    {
        if (!$this->allowed_ips) {
            return false;
        }
        
        return in_array($ip, $this->allowed_ips);
    }
}
