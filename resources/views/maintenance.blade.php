<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings->title ?? 'Site Under Maintenance' }} • EventEase</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            line-height: 1.6;
        }

        .maintenance-container {
            text-align: center;
            max-width: 600px;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .maintenance-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 2rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2s infinite;
        }

        .maintenance-icon svg {
            width: 60px;
            height: 60px;
            stroke: #ffffff;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .maintenance-message {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
            line-height: 1.7;
        }

        .completion-time {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .completion-time h3 {
            font-size: 1rem;
            margin-bottom: 0.5rem;
            opacity: 0.8;
        }

        .completion-time .time {
            font-size: 1.1rem;
            font-weight: 600;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }

        .feature {
            background: rgba(255, 255, 255, 0.05);
            padding: 1rem;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            margin: 0 auto 0.5rem;
            display: block;
        }

        .feature h4 {
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }

        .feature p {
            font-size: 0.8rem;
            opacity: 0.8;
        }

        .contact-info {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .contact-info p {
            opacity: 0.8;
            font-size: 0.9rem;
        }

        .admin-notice {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.8rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        @media (max-width: 768px) {
            .maintenance-container {
                margin: 1rem;
                padding: 1.5rem;
            }

            h1 {
                font-size: 2rem;
            }

            .maintenance-message {
                font-size: 1rem;
            }

            .features {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="maintenance-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
        </div>

        <h1>{{ $settings->title ?? 'Site Under Maintenance' }}</h1>
        
        <div class="maintenance-message">
            {!! nl2br(e($settings->message ?? 'We are currently performing maintenance on our website. We will be back online shortly!')) !!}
        </div>

        @if($settings->estimated_completion)
        <div class="completion-time">
            <h3>Estimated Completion</h3>
            <div class="time">{{ $settings->estimated_completion->format('F j, Y \a\t g:i A') }}</div>
        </div>
        @endif

        <div class="features">
            <div class="feature">
                <svg class="feature-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <h4>Performance</h4>
                <p>Optimizing for speed</p>
            </div>
            <div class="feature">
                <svg class="feature-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h4>Security</h4>
                <p>Enhanced protection</p>
            </div>
            <div class="feature">
                <svg class="feature-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
                <h4>Experience</h4>
                <p>Better user interface</p>
            </div>
        </div>

        <div class="contact-info">
            <p><strong>Need immediate assistance?</strong></p>
            <p>Please contact (+8801568292032) our support team if you have any urgent inquiries.</p>
        </div>
    </div>

    @if(auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isManager()))
    <div class="admin-notice">
        Admin Access: You can still access the <a href="{{ route('admin.index') }}" style="color: #ffffff; text-decoration: underline;">admin panel</a>
    </div>
    @endif

    <script>
        // Add some simple interactivity
        document.addEventListener('DOMContentLoaded', function() {
            // Auto refresh every 5 minutes to check if maintenance is over
            setTimeout(function() {
                window.location.reload();
            }, 300000); // 5 minutes
        });
    </script>
</body>
</html>
