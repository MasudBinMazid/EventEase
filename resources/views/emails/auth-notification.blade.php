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
            padding: 40px 20px;
            text-align: center;
            color: white;
        }
        
        .email-header h1 {
            margin: 15px 0 0 0;
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
        
        .action-button {
            display: inline-block;
            padding: 15px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 25px 0;
            transition: transform 0.2s;
        }
        
        .action-button:hover {
            transform: translateY(-2px);
        }
        
        .important-note {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
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
                padding: 30px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header with EventEase Logo -->
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
            
            <h1 style="color: white; margin: 0; font-size: 28px; font-weight: 300;">{{ $headerTitle ?? 'EventEase Notification' }}</h1>
        </div>
        
        <!-- Email Body -->
        <div class="email-body">
            @if(isset($greeting))
            <div class="greeting">{{ $greeting }}</div>
            @endif
            
            @if(isset($introLine))
            <div class="content-line">{{ $introLine }}</div>
            @endif
            
            @if(isset($contentLines))
                @foreach($contentLines as $line)
                <div class="content-line">{!! $line !!}</div>
                @endforeach
            @endif
            
            @if(isset($actionUrl) && isset($actionText))
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $actionUrl }}" class="action-button">{{ $actionText }}</a>
            </div>
            @endif
            
            @if(isset($importantNote))
            <div class="important-note">
                {!! $importantNote !!}
            </div>
            @endif
            
            @if(isset($closingLines))
                @foreach($closingLines as $line)
                <div class="content-line">{!! $line !!}</div>
                @endforeach
            @endif
        </div>
        
        <!-- Footer -->
        <div class="email-footer">
            <div style="margin-bottom: 20px;">
                <div style="color: #667eea; font-size: 24px; font-weight: bold; text-align: center;">
                    🎪 EventEase
                </div>
            </div>
            
            <div class="footer-text"><strong>EventEase Team</strong></div>
            <div class="footer-text">Your trusted partner for event discovery and ticket booking</div>
            <div class="footer-text">📧 Contact: masudranamamun222@gmail.com</div>
            <div class="footer-text">© {{ date('Y') }} EventEase. All rights reserved.</div>
        </div>
    </div>
</body>
</html>
