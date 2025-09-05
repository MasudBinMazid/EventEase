<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NotificationTemplate;

class NotificationTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing templates to avoid duplicates
        NotificationTemplate::truncate();
        
        $templates = [
            // Welcome Messages
            [
                'name' => 'Welcome New User',
                'title' => 'Welcome to EventEase! 🎉',
                'message' => 'Hello {{user_name}}! Welcome to EventEase, your premier destination for discovering and booking amazing events. We\'re excited to have you join our community! Start exploring events, book tickets, and create unforgettable memories.',
                'type' => 'announcement',
                'category' => 'welcome',
                'variables' => ['user_name'],
                'is_active' => true
            ],
            [
                'name' => 'Account Verification Success',
                'title' => 'Account Verified Successfully! ✅',
                'message' => 'Congratulations {{user_name}}! Your account has been successfully verified. You now have full access to all EventEase features. Start browsing events and book your first ticket today!',
                'type' => 'announcement',
                'category' => 'welcome',
                'variables' => ['user_name'],
                'is_active' => true
            ],
            
            // Payment Reminders
            [
                'name' => 'Payment Reminder - Gentle',
                'title' => 'Payment Reminder for Your Ticket 💳',
                'message' => 'Hi {{user_name}}, this is a friendly reminder that you have a pending payment for your ticket to "{{event_name}}". Please complete your payment to secure your spot at this amazing event!',
                'type' => 'reminder',
                'category' => 'payment',
                'variables' => ['user_name', 'event_name'],
                'is_active' => true
            ],
            [
                'name' => 'Payment Reminder - Urgent',
                'title' => 'Urgent: Complete Your Payment ⚠️',
                'message' => 'Dear {{user_name}}, your payment for "{{event_name}}" is still pending. This event is filling up fast! Please complete your payment within 24 hours to avoid losing your spot.',
                'type' => 'urgent',
                'category' => 'payment',
                'variables' => ['user_name', 'event_name'],
                'is_active' => true
            ],
            [
                'name' => 'Payment Confirmed',
                'title' => 'Payment Confirmed - You\'re All Set! ✅',
                'message' => 'Great news {{user_name}}! Your payment for "{{event_name}}" has been confirmed. Your ticket is ready for download. We can\'t wait to see you at the event!',
                'type' => 'announcement',
                'category' => 'payment',
                'variables' => ['user_name', 'event_name'],
                'is_active' => true
            ],
            
            // Site Maintenance
            [
                'name' => 'Scheduled Maintenance',
                'title' => 'Scheduled Site Maintenance 🔧',
                'message' => 'Dear EventEase users, we will be performing scheduled maintenance on {{maintenance_date}} from {{maintenance_time}}. During this time, the site may be temporarily unavailable. We appreciate your patience as we work to improve your experience!',
                'type' => 'announcement',
                'category' => 'maintenance',
                'variables' => ['maintenance_date', 'maintenance_time'],
                'is_active' => true
            ],
            [
                'name' => 'Emergency Maintenance',
                'title' => 'Emergency Maintenance Notice ⚠️',
                'message' => 'We are currently experiencing technical issues and need to perform emergency maintenance. The site may be unavailable for a short period. We\'re working hard to resolve this quickly. Thank you for your patience!',
                'type' => 'urgent',
                'category' => 'maintenance',
                'variables' => [],
                'is_active' => true
            ],
            [
                'name' => 'Maintenance Complete',
                'title' => 'Maintenance Complete - We\'re Back! 🎉',
                'message' => 'Good news! Our scheduled maintenance is now complete. All systems are running smoothly with improved performance and new features. Thank you for your patience during the maintenance window!',
                'type' => 'announcement',
                'category' => 'maintenance',
                'variables' => [],
                'is_active' => true
            ],
            
            // Event Updates
            [
                'name' => 'Event Reminder',
                'title' => 'Your Event is Coming Up! 📅',
                'message' => 'Hi {{user_name}}, just a friendly reminder that "{{event_name}}" is happening on {{event_date}}. Don\'t forget to bring your ticket and arrive 30 minutes early. We\'re looking forward to seeing you there!',
                'type' => 'reminder',
                'category' => 'events',
                'variables' => ['user_name', 'event_name', 'event_date'],
                'is_active' => true
            ],
            [
                'name' => 'Event Cancelled',
                'title' => 'Important: Event Cancellation Notice ❌',
                'message' => 'Dear {{user_name}}, we regret to inform you that "{{event_name}}" scheduled for {{event_date}} has been cancelled due to unforeseen circumstances. Full refunds will be processed within 3-5 business days. We apologize for any inconvenience.',
                'type' => 'urgent',
                'category' => 'events',
                'variables' => ['user_name', 'event_name', 'event_date'],
                'is_active' => true
            ],
            [
                'name' => 'Event Rescheduled',
                'title' => 'Event Rescheduled - New Date Announced 📅',
                'message' => 'Hi {{user_name}}, "{{event_name}}" has been rescheduled from {{old_date}} to {{new_date}}. Your existing ticket remains valid for the new date. If you cannot attend the new date, please contact us for a full refund.',
                'type' => 'announcement',
                'category' => 'events',
                'variables' => ['user_name', 'event_name', 'old_date', 'new_date'],
                'is_active' => true
            ],
            
            // General Announcements
            [
                'name' => 'New Features Available',
                'title' => 'Exciting New Features Just Launched! 🚀',
                'message' => 'Hello {{user_name}}! We\'ve just launched exciting new features on EventEase including improved search, better mobile experience, and faster checkout. Update your app or refresh your browser to enjoy these enhancements!',
                'type' => 'announcement',
                'category' => 'general',
                'variables' => ['user_name'],
                'is_active' => true
            ],
            [
                'name' => 'Special Promotion',
                'title' => 'Special Offer Just for You! 🎁',
                'message' => 'Hi {{user_name}}, we have a special promotion running! Get {{discount_percent}}% off your next ticket purchase. Use code "{{promo_code}}" at checkout. This offer expires on {{expiry_date}}. Don\'t miss out!',
                'type' => 'announcement',
                'category' => 'general',
                'variables' => ['user_name', 'discount_percent', 'promo_code', 'expiry_date'],
                'is_active' => true
            ],
            [
                'name' => 'Account Security Alert',
                'title' => 'Security Alert: Review Your Account 🔐',
                'message' => 'Hi {{user_name}}, we noticed unusual activity on your account. Please review your recent activity and change your password if necessary. Contact our support team if you notice anything suspicious. Your account security is our priority.',
                'type' => 'urgent',
                'category' => 'security',
                'variables' => ['user_name'],
                'is_active' => true
            ]
        ];

        foreach ($templates as $template) {
            NotificationTemplate::create($template);
        }
    }
}
