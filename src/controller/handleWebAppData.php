<?php
require '../model/database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payload = file_get_contents('php://input');
    $dataArray = json_decode($payload, true);

    parse_str($dataArray['webAppData'], $output);

    // Database
    try {
        $db = new DB();
        $user = json_decode($output['user']);
        $db->addUser($user);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'failed', 'error' => $e->getMessage()]);
    }
}
