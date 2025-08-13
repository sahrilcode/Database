<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['sessionId'])) {
    $_SESSION['sessionId'] = uniqid(rand(), true);
}

if (!isset($_POST['q']) || trim($_POST['q']) === '') {
    echo json_encode(["status" => false, "message" => "Pertanyaan kosong"]);
    exit;
}

$query = $_POST['q'];
$sessionId = $_SESSION['sessionId'];
$apiKey = "GetsuzoCp7";

$url = "https://api.neoxr.eu/api/gpt4-session?q=" . urlencode($query) .
       "&session=" . urlencode($sessionId) .
       "&apikey=" . urlencode($apiKey);

$response = file_get_contents($url);
if ($response === false) {
    echo json_encode(["status" => false, "message" => "Gagal menghubungi API"]);
    exit;
}

$data = json_decode($response, true);
if (isset($data['data']['message'])) {
    echo json_encode(["status" => true, "message" => $data['data']['message']]);
} else {
    echo json_encode(["status" => false, "message" => "Tidak ada jawaban"]);
}
