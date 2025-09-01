@php
    $settings = \App\Models\NoticeSettings::getSettings();
    $notices = [];
    
    if ($settings->is_enabled) {
        $notices = \App\Models\Notice::where('is_active', true)
            ->where(function ($query) {
                $now = now();
                $query->where(function ($q) use ($now) {
                    $q->whereNull('start_date')
                      ->orWhere('start_date', '<=', $now);
                })->where(function ($q) use ($now) {
                    $q->whereNull('end_date')
                      ->orWhere('end_date', '>=', $now);
                });
            })
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }
@endphp

@if($settings->is_enabled && $notices->count() > 0)
    <div class="notice-bar-sticky" style="
        position: sticky;
        top: 0;
        background: linear-gradient(135deg, {{ $settings->background_color }}ee 0%, {{ $settings->background_color }}dd 100%);
        backdrop-filter: blur(10px);
        border-bottom: 2px solid {{ $settings->background_color }};
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    ">
        <div class="notice-bar-wrapper" style="
            color: {{ $settings->text_color }};
            overflow: hidden;
            white-space: nowrap;
            position: relative;
            padding: 16px 0;
            font-weight: 600;
            font-size: 16px;
            background: linear-gradient(90deg, 
                transparent 0%, 
                rgba(255,255,255,0.1) 50%, 
                transparent 100%);
        ">
            <!-- Breaking News Badge -->
            <div style="
                position: absolute;
                left: 20px;
                top: 50%;
                transform: translateY(-50%);
                background: linear-gradient(45deg, #ff6b6b, #ee5a24);
                color: white;
                padding: 8px 16px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 1px;
                z-index: 10;
                box-shadow: 0 2px 10px rgba(255,107,107,0.3);
                animation: pulse 2s infinite;
            ">
                🔥 LIVE
            </div>

            <!-- Scrolling Content -->
            <div class="notice-bar-content" style="
                display: inline-block;
                padding-left: 120px;
                animation: scroll-{{ $settings->scroll_speed }} 
                {{ $settings->scroll_speed == 'slow' ? '80s' : ($settings->scroll_speed == 'fast' ? '30s' : '50s') }} 
                linear infinite;
                animation-fill-mode: both;
            ">
                @foreach($notices as $index => $notice)
                    <span class="notice-item" style="
                        padding-right: 60px;
                        position: relative;
                        display: inline-block;
                    ">
                        <span style="
                            color: {{ $notice->text_color ?? $settings->text_color }};
                            font-family: {{ $notice->font_family ?? 'Inter, sans-serif' }};
                            font-size: {{ ($notice->font_size ?? 16) }}px;
                            font-weight: {{ $notice->font_weight ?? '600' }};
                            font-style: {{ $notice->text_style ?? 'normal' }};
                            background: {{ $notice->bg_color ?? 'transparent' }};
                            padding: 6px 12px;
                            border-radius: 6px;
                            display: inline-block;
                            box-shadow: {{ $notice->bg_color ? '0 2px 8px rgba(0,0,0,0.1)' : 'none' }};
                            border: {{ $notice->bg_color ? '1px solid rgba(255,255,255,0.2)' : 'none' }};
                            margin-right: 8px;
                        ">
                            @if($notice->priority >= 80)
                                🚨
                            @elseif($notice->priority >= 50)
                                ⚠️
                            @else
                                📢
                            @endif
                            {{ $notice->content }}
                        </span>
                        @if($index < $notices->count() - 1)
                            <span style="
                                margin: 0 30px;
                                color: rgba(255,255,255,0.6);
                                font-size: 20px;
                                display: inline-block;
                            ">●</span>
                        @endif
                    </span>
                @endforeach
            </div>

            <!-- Gradient Fade Effects -->
            <div style="
                position: absolute;
                top: 0;
                left: 0;
                width: 100px;
                height: 100%;
                background: linear-gradient(90deg, {{ $settings->background_color }} 0%, transparent 100%);
                z-index: 5;
            "></div>
            <div style="
                position: absolute;
                top: 0;
                right: 0;
                width: 100px;
                height: 100%;
                background: linear-gradient(270deg, {{ $settings->background_color }} 0%, transparent 100%);
                z-index: 5;
            "></div>
        </div>
    </div>

    <style>
        @keyframes scroll-slow {
            0% { transform: translate3d(0, 0, 0); }
            100% { transform: translate3d(-100%, 0, 0); }
        }
        
        @keyframes scroll-normal {
            0% { transform: translate3d(0, 0, 0); }
            100% { transform: translate3d(-100%, 0, 0); }
        }
        
        @keyframes scroll-fast {
            0% { transform: translate3d(0, 0, 0); }
            100% { transform: translate3d(-100%, 0, 0); }
        }

        @keyframes pulse {
            0%, 100% { transform: translateY(-50%) scale(1); }
            50% { transform: translateY(-50%) scale(1.05); }
        }

        .notice-bar-wrapper:hover .notice-bar-content {
            animation-play-state: paused;
        }

        .notice-bar-sticky {
            transition: all 0.3s ease;
            z-index: 1000; /* Default z-index for desktop */
        }

        /* Enhanced Mobile Responsiveness */
        @media (max-width: 768px) {
            .notice-bar-sticky {
                z-index: 999; /* Lower z-index than sidebar on mobile */
            }
            
            .notice-bar-wrapper {
                padding: 12px 0;
            }
            
            .notice-item span {
                font-size: calc(1em * 0.9) !important;
                padding: 4px 8px !important;
                margin-right: 6px !important;
            }
            
            .notice-bar-sticky div[style*="left: 20px"] {
                left: 10px !important;
                padding: 6px 12px !important;
                font-size: 10px !important;
            }
            
            .notice-bar-content {
                padding-left: 100px !important;
            }
        }

        @media (max-width: 480px) {
            .notice-bar-sticky {
                z-index: 999; /* Lower z-index than sidebar on mobile */
            }
            
            .notice-bar-wrapper {
                padding: 10px 0;
            }
            
            .notice-item span {
                font-size: calc(1em * 0.8) !important;
                padding: 3px 6px !important;
                margin-right: 4px !important;
            }
            
            .notice-bar-sticky div[style*="left: 20px"] {
                left: 5px !important;
                padding: 4px 8px !important;
                font-size: 9px !important;
            }
            
            .notice-bar-content {
                padding-left: 80px !important;
            }
        }
    </style>
@endif
