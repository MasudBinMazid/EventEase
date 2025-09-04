# Background Video for Promotional Banner

## Required Video Files

Place your promotional background video files in this directory with the following names:

1. **promo-bg.mp4** - Main video file (recommended)
2. **promo-bg.webm** - Alternative format for better browser compatibility

## Video Specifications

### Recommended Settings:
- **Resolution**: 1920x1080 (Full HD) or 1280x720 (HD)
- **Aspect Ratio**: 16:9
- **Duration**: 10-30 seconds (loops automatically)
- **Format**: MP4 (H.264) and WebM for broader compatibility
- **File Size**: Keep under 5MB for optimal loading

### Video Content Suggestions:
- Event-related footage (crowds, stages, performances)
- Abstract motion graphics
- Professional corporate visuals
- Subtle animations that don't distract from text

### Technical Requirements:
- **Autoplay-friendly**: No audio required (muted by default)
- **Mobile-optimized**: Should look good on small screens
- **Performance**: Optimized for web streaming

## Implementation Details

The video will:
- Autoplay on desktop and mobile (muted)
- Loop continuously
- Pause when not in viewport (performance optimization)
- Fall back to gradient background if video fails to load
- Be overlaid with semi-transparent gradient for text readability

## File Structure:
```
assets/videos/
├── promo-bg.mp4    (Primary video file)
├── promo-bg.webm   (Alternative format)
└── README.md       (This file)
```

## Mobile Responsiveness Features:
- Video scales properly on all screen sizes
- Optimized loading for mobile networks
- Automatic fallback to static background if needed
- Responsive text sizing and button placement
