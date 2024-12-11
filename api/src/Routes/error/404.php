<?php


header('Content-Type: application/json');
http_response_code(404);

$response = [
    "status" => 404,
    "message" => "Rota não encontrada"
];

echo json_encode($response);
exit;