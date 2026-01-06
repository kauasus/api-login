<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$conn = new mysqli("localhost", "root", "", "sistema_login");

$stmt = $conn->prepare(
  "INSERT INTO agendamentos (medico_id, data, hora) VALUES (?, ?, ?)"
);

$stmt->bind_param(
  "iss",
  $data['medico_id'],
  $data['data'],
  $data['hora']
);

$stmt->execute();

echo json_encode(["success" => true]);
