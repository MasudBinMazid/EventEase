<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotificationTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'title', 
        'message',
        'type',
        'category',
        'variables',
        'is_active',
        'usage_count'
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Scope for active templates
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope by category
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Scope by type
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Replace variables in template
    public function renderTemplate($variables = [])
    {
        $title = $this->title;
        $message = $this->message;

        foreach ($variables as $key => $value) {
            $title = str_replace('{{' . $key . '}}', $value, $title);
            $message = str_replace('{{' . $key . '}}', $value, $message);
        }

        return [
            'title' => $title,
            'message' => $message,
            'type' => $this->type
        ];
    }

    // Render title with variables
    public function renderTitle($variables = [])
    {
        $title = $this->title;
        
        foreach ($variables as $key => $value) {
            $title = str_replace('{{' . $key . '}}', $value, $title);
        }

        return $title;
    }

    // Render message with variables
    public function renderMessage($variables = [])
    {
        $message = $this->message;
        
        foreach ($variables as $key => $value) {
            $message = str_replace('{{' . $key . '}}', $value, $message);
        }

        return $message;
    }

    // Increment usage count
    public function incrementUsage()
    {
        $this->increment('usage_count');
    }

    // Get popular templates
    public static function getPopular($limit = 5)
    {
        return static::active()
            ->orderBy('usage_count', 'desc')
            ->limit($limit)
            ->get();
    }
}
