<?php

namespace App\Services;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class QrCodeService
{
    /**
     * Generate QR code as PNG base64 data URL
     */
    public static function generateBase64Png(string $data, int $size = 200): string
    {
        $options = new QROptions([
            'version'      => 7, // Increased version for more data capacity
            'outputType'   => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'     => QRCode::ECC_L, // Lower error correction for more data space
            'scale'        => 6,
            'imageBase64'  => true,
        ]);

        $qrcode = new QRCode($options);
        return $qrcode->render($data);
    }

    /**
     * Generate QR code as PNG binary data
     */
    public static function generatePngBinary(string $data, int $size = 200): string
    {
        $options = new QROptions([
            'version'      => 7, // Increased version for more data capacity
            'outputType'   => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'     => QRCode::ECC_L, // Lower error correction for more data space
            'scale'        => 6,
            'imageBase64'  => false,
        ]);

        $qrcode = new QRCode($options);
        return $qrcode->render($data);
    }

    /**
     * Generate QR code as SVG
     */
    public static function generateSvg(string $data): string
    {
        $options = new QROptions([
            'version'      => 7, // Increased version for more data capacity
            'outputType'   => QRCode::OUTPUT_MARKUP_SVG,
            'eccLevel'     => QRCode::ECC_L, // Lower error correction for more data space
        ]);

        $qrcode = new QRCode($options);
        return $qrcode->render($data);
    }
}
