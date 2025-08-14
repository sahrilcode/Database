<?php
// api/download.php
// Proxy unduhan agar file diunduh via server kamu (bisa dipakai untuk audio/video)
set_time_limit(0);

$type  = isset($_GET['type']) ? $_GET['type'] : 'file'; // video|audio|file
$url   = isset($_GET['url']) ? $_GET['url'] : '';
$title = isset($_GET['title']) ? $_GET['title'] : 'download';

if (!$url) {
  http_response_code(400);
  echo "Missing url";
  exit;
}

// Tentukan ekstensi default
$ext = ($type === 'audio') ? 'mp3' : (($type === 'video') ? 'mp4' : 'bin');
$fname = preg_replace('/[^a-zA-Z0-9\-\_ ]+/', '_', $title) . '.' . $ext;

// Stream dengan cURL
$ch = curl_init($url);
curl_setopt_array($ch, [
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_RETURNTRANSFER => false,
  CURLOPT_HEADER => false,
  CURLOPT_SSL_VERIFYPEER => true,
  CURLOPT_CONNECTTIMEOUT => 15,
  CURLOPT_TIMEOUT => 0,
]);

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $fname . '"');
header('Cache-Control: no-cache');

curl_exec($ch);

if (curl_errno($ch)) {
  // fallback pesan error di body
  echo "Gagal mengunduh: " . curl_error($ch);
}
curl_close($ch);
