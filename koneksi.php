<?php
$host = 'localhost';
$username = 'root';
$password = '123';
$database = 'sistem_kehumasan';

$koneksi = mysqli_connect($host, $username, $password, $database);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Set timezone
date_default_timezone_set('Asia/Jakarta');

// Optional: Google Drive API key for fetching file names (public files).
// Set via environment variable GOOGLE_DRIVE_API_KEY or hardcode here (not recommended for production).
$GOOGLE_DRIVE_API_KEY = getenv('GOOGLE_DRIVE_API_KEY') ?: '';

/**
 * Fetch file name from Google Drive API (public files) with simple file cache.
 * Returns string filename or null.
 */
function fetch_drive_filename($fileId) {
    global $GOOGLE_DRIVE_API_KEY;
    $cacheDir = __DIR__ . '/cache';
    if (!is_dir($cacheDir)) mkdir($cacheDir, 0755, true);
    $safeId = preg_replace('/[^a-zA-Z0-9_-]/','', $fileId);
    $cacheFile = $cacheDir . '/drive_' . $safeId . '.json';
    $ttl = 3600; // seconds
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $ttl)) {
        $data = json_decode(file_get_contents($cacheFile), true);
        if (!empty($data['name'])) return $data['name'];
    }
    if (empty($GOOGLE_DRIVE_API_KEY)) return null;
    $url = 'https://www.googleapis.com/drive/v3/files/' . urlencode($fileId) . '?fields=name&key=' . urlencode($GOOGLE_DRIVE_API_KEY);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $res = curl_exec($ch);
    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($http == 200 && $res) {
        $json = json_decode($res, true);
        if (!empty($json['name'])) {
            @file_put_contents($cacheFile, json_encode($json));
            return $json['name'];
        }
    }
    return null;
}
?>