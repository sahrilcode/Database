<?php
// api/search.php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$count = isset($_GET['count']) ? (int)$_GET['count'] : 50;
if ($count < 1 || $count > 100) $count = 50;

if ($q === '') {
  http_response_code(400);
  echo json_encode(['status'=>false,'message'=>'Query kosong']);
  exit;
}

$api = "https://laurine.site/api/search/tiktoksearch?query=" . urlencode($q) . "&count=" . $count;

$ch = curl_init();
curl_setopt_array($ch, [
  CURLOPT_URL => $api,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_TIMEOUT => 20,
  CURLOPT_SSL_VERIFYPEER => true,
  CURLOPT_HTTPHEADER => [
    'Accept: application/json',
    'User-Agent: Getsuzo-TTSearch/1.0'
  ],
]);

$res = curl_exec($ch);
$err = curl_error($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($err || $code >= 400 || !$res) {
  http_response_code(502);
  echo json_encode(['status'=>false,'message'=>'Gagal proxy API','error'=>$err,'http'=>$code]);
  exit;
}

echo $res;
