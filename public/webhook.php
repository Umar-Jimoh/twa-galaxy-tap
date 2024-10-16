<?php 
$payload = file_get_contents('php://input');

$data = json_decode($payload, true);

file_put_contents('../logs/webhook.log', print_r($data, true), FILE_APPEND);