<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'EventEase Notification' }}</title>
    <style>
        /* Email styles */
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px 20px;
            text-align: center;
            color: white;
        }
        
        .logo {
            max-width: 200px;
            height: auto;
            margin-bottom: 15px;
            background: rgba(255, 255, 255, 0.1);
            padding: 10px;
            border-radius: 8px;
        }
        
        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 300;
        }
        
        .email-body {
            padding: 40px 30px;
        }
        
        .greeting {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #2c3e50;
        }
        
        .content-line {
            margin: 15px 0;
            font-size: 16px;
            line-height: 1.6;
        }
        
        .content-line strong {
            color: #2c3e50;
        }
        
        .event-details {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 25px 0;
            border-radius: 8px;
        }
        
        .action-button {
            display: inline-block;
            padding: 15px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
            transition: transform 0.2s;
        }
        
        .action-button:hover {
            transform: translateY(-2px);
        }

        .action-button.payment-button {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
        
        .action-button.payment-button:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
        }
        
        .action-button.view-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        
        .action-button.view-button:hover {
            background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
        }
        
        .important-note {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        
        .email-footer {
            background-color: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        
        .footer-logo {
            max-width: 120px;
            height: auto;
            margin-bottom: 15px;
            opacity: 0.8;
        }
        
        .footer-text {
            color: #6c757d;
            font-size: 14px;
            margin: 5px 0;
        }
        
        @media (max-width: 600px) {
            .email-container {
                margin: 10px;
                border-radius: 8px;
            }
            
            .email-body {
                padding: 20px 15px;
            }
            
            .email-header {
                padding: 20px 15px;
            }
            
            .logo {
                max-width: 150px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header with Logo -->
        <div class="email-header">            
            <!-- EventEase Professional Text Logo -->
            <div style="margin-bottom: 25px;">
                <div style="background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.85) 100%); padding: 25px; border-radius: 20px; display: inline-block; box-shadow: 0 8px 32px rgba(0,0,0,0.1); border: 2px solid rgba(255,255,255,0.3);">
                    <div style="text-align: center;">
                        <div style="color: #667eea; font-size: 42px; font-weight: 900; letter-spacing: 2px; text-shadow: 2px 2px 4px rgba(0,0,0,0.1); line-height: 1;">
                            🎪 EventEase
                        </div>
                        <div style="color: #764ba2; font-size: 14px; margin-top: 8px; font-style: italic; font-weight: 600; letter-spacing: 1px;">
                            EVENT MANAGEMENT PLATFORM
                        </div>
                        <div style="width: 60px; height: 3px; background: linear-gradient(90deg, #667eea, #764ba2); margin: 10px auto; border-radius: 2px;"></div>
                    </div>
                </div>
            </div>
            
            <h1 style="color: white; margin: 0; font-size: 28px; font-weight: 300;">{{ $headerTitle ?? 'Your Event Ticket' }}</h1>
        </div>
        
        <!-- Email Body -->
        <div class="email-body">
            <div class="greeting">{{ $greeting }}</div>
            
            <div class="content-line">{{ $introLine }}</div>
            
            @if(isset($eventDetails))
            <div class="event-details">
                <h3 style="margin-top: 0; color: #2c3e50;">🎪 Event Details</h3>
                {!! $eventDetails !!}
            </div>
            @endif
            
            @foreach($contentLines as $line)
            <div class="content-line">{!! $line !!}</div>
            @endforeach
            
            @if(isset($actionUrl) && isset($actionText))
            <div style="text-align: center; margin: 30px 0;">
                @if(strpos($actionText, 'Complete Payment') !== false)
                    <a href="{{ $actionUrl }}" class="action-button payment-button">💳 {{ $actionText }}</a>
                @else
                    <a href="{{ $actionUrl }}" class="action-button view-button">🎫 {{ $actionText }}</a>
                @endif
            </div>
            @endif
            
            @if(isset($importantNote))
            <div class="important-note">
                <strong>📱 Important Information:</strong><br>
                {!! $importantNote !!}
            </div>
            @endif
            
            <div class="content-line" style="margin-top: 30px;">
                {!! $closingLine !!}
            </div>
        </div>
        
        <!-- Footer -->
        <div class="email-footer">
            <div style="margin-bottom: 20px;">
                <div style="color: #667eea; font-size: 28px; font-weight: 900; text-align: center; letter-spacing: 1px;">
                    🎪 EventEase
                </div>
                <div style="width: 40px; height: 2px; background: linear-gradient(90deg, #667eea, #764ba2); margin: 8px auto; border-radius: 1px;"></div>
            </div>
            
            <div class="footer-text"><strong>EventEase Team</strong></div>
            <div class="footer-text">Your trusted partner for event discovery and ticket booking</div>
            <div class="footer-text">📧 masudranamamun222@gmail.com | 🌐 EventEase Platform</div>
            <div class="footer-text">© {{ date('Y') }} EventEase. All rights reserved.</div>
        </div>
    </div>
</body>
</html>
