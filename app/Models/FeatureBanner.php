<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeatureBanner extends Model
{
    protected $fillable = [
        'title',
        'image',
        'link',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get active banners ordered by sort_order
     */
    public static function getActiveBanners()
    {
        return self::where('is_active', true)
                   ->orderBy('sort_order')
                   ->orderBy('id')
                   ->get();
    }

    /**
     * Scope for active banners
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
