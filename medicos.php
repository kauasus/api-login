<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "sistema_login");

$result = $conn->query("SELECT * FROM medicos");

$medicos = [];
while ($row = $result->fetch_assoc()) {
  $medicos[] = $row;
}

echo json_encode($medicos);
