<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payload = file_get_contents('php://input');
    $dataArray = json_decode($payload, true);

    parse_str($dataArray['webAppData'], $output);

    file_put_contents('../../logs/webhook.log', print_r($output, true), FILE_APPEND);

    echo json_encode($output['user']);
}