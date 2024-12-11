<?php


header('Content-Type: application/json');
http_response_code(200);

$response = [
    "status" => 200,
    "message" => "API em funcionamento"
];

echo json_encode($response);
exit;
