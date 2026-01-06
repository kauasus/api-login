<?php
// ===== HEADERS (CORS + JSON) =====
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

// ===== LEITURA DO JSON =====
$data = json_decode(file_get_contents("php://input"), true);

$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

if (empty($username) || empty($password)) {
    echo json_encode([
        "success" => false,
        "message" => "Preencha todos os campos"
    ]);
    exit;
}

// ===== CONEXÃO COM O BANCO =====
$conn = new mysqli("localhost", "root", "", "sistema_login");

if ($conn->connect_error) {
    echo json_encode([
        "success" => false,
        "message" => "Erro no banco"
    ]);
    exit;
}

// ===== BUSCAR USUÁRIO =====
$stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        "success" => false,
        "message" => "Usuário não encontrado"
    ]);
    exit;
}

$user = $result->fetch_assoc();

// ===== VERIFICAR SENHA =====
if (!password_verify($password, $user['password'])) {
    echo json_encode([
        "success" => false,
        "message" => "Senha incorreta"
    ]);
    exit;
}

// ===== LOGIN OK =====
echo json_encode([
    "success" => true,
    "message" => "Login realizado com sucesso"
]);
