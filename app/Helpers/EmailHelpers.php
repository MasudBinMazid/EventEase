<?php

if (!function_exists('getEmailLogo')) {
    function getEmailLogo() {
        $logoPath = public_path('assets/images/logo.png');
        
        // Check if logo file exists
        if (file_exists($logoPath)) {
            try {
                $logoData = file_get_contents($logoPath);
                $base64Logo = base64_encode($logoData);
                return 'data:image/png;base64,' . $base64Logo;
            } catch (Exception $e) {
                return null;
            }
        }
        
        // Try alternative logo
        $altLogoPath = public_path('assets/images/eventease-logo.png');
        if (file_exists($altLogoPath)) {
            try {
                $logoData = file_get_contents($altLogoPath);
                $base64Logo = base64_encode($logoData);
                return 'data:image/png;base64,' . $base64Logo;
            } catch (Exception $e) {
                return null;
            }
        }
        
        return null;
    }
}

if (!function_exists('getTextLogo')) {
    function getTextLogo() {
        return '🎪 EventEase';
    }
}
