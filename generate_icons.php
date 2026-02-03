<?php
/**
 * Generate PWA Icons (tanpa GD extension)
 * Menggunakan base64 encoded PNG images
 * Jalankan sekali: php generate_icons.php
 */

$iconDir = __DIR__ . '/assets/icons';

// Ensure directory exists
if (!is_dir($iconDir)) {
    mkdir($iconDir, 0755, true);
    echo "Created directory: $iconDir\n";
}

// Minimal PNG icons (1x1 blue pixel, base64 encoded)
// Format: PNG with blue background (#2196F3)
$icons = [
    'icon-192x192.png' => [
        'size' => 192,
        'desc' => '192x192 standard icon'
    ],
    'icon-512x512.png' => [
        'size' => 512,
        'desc' => '512x512 standard icon'
    ]
];

// Buat minimal PNG (solid blue)
function createMinimalPNG($size) {
    // PNG signature
    $png = "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A";
    
    // IHDR chunk (image header) - width x height, 8-bit RGBA
    $width = $size;
    $height = $size;
    $ihdr_data = pack('N', $width) . pack('N', $height) . 
                 "\x08\x06\x00\x00\x00"; // 8-bit, RGBA, deflate
    $ihdr_crc = crc32("\x49\x48\x44\x52" . $ihdr_data);
    $png .= pack('N', 13) . "\x49\x48\x44\x52" . $ihdr_data . pack('N', $ihdr_crc);
    
    // IDAT chunk (image data) - compressed blue pixels (#2196F3)
    $scanlines = '';
    for ($y = 0; $y < $height; $y++) {
        $scanlines .= "\x00"; // filter type none
        for ($x = 0; $x < $width; $x++) {
            // RGBA: #2196F3 with full opacity
            $scanlines .= "\x21\x96\xF3\xFF"; // R=33, G=150, B=243, A=255
        }
    }
    
    $compressed = gzcompress($scanlines, 9);
    $idat_crc = crc32("\x49\x44\x41\x54" . $compressed);
    $png .= pack('N', strlen($compressed)) . "\x49\x44\x41\x54" . $compressed . pack('N', $idat_crc);
    
    // IEND chunk (end)
    $iend_crc = crc32("\x49\x45\x4E\x44");
    $png .= pack('N', 0) . "\x49\x45\x4E\x44" . pack('N', $iend_crc);
    
    return $png;
}

// Generate icons
try {
    $created = 0;
    foreach ($icons as $filename => $config) {
        $filepath = "$iconDir/$filename";
        
        if (file_exists($filepath)) {
            echo "⚠ Skipped (already exists): $filename\n";
            continue;
        }
        
        $png_data = createMinimalPNG($config['size']);
        
        if (file_put_contents($filepath, $png_data)) {
            echo "✓ Created: $filename ({$config['size']}x{$config['size']} - {$config['desc']})\n";
            $created++;
        } else {
            echo "❌ Failed to create: $filename\n";
        }
    }
    
    if ($created > 0) {
        echo "\n✓ Icons generated successfully!\n";
    } elseif ($created === 0 && count($icons) > 0) {
        echo "\n⚠ No new icons created (already exist)\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>

