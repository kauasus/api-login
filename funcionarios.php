<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST");
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "sistema_login");

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Erro no banco"]);
    exit;
}

/* ==========================
   LISTAR FUNCIONÁRIOS (GET)
========================== */
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "
      SELECT f.id, f.nome_completo, f.cpf, f.telefone, c.nome AS cargo
      FROM funcionarios f
      JOIN cargos c ON c.id = f.cargo_id
      ORDER BY f.nome_completo
    ";

    $result = $conn->query($sql);
    $dados = [];

    while ($row = $result->fetch_assoc()) {
        $dados[] = $row;
    }

    echo json_encode($dados);
    exit;
}

/* ==========================
   CADASTRAR FUNCIONÁRIO (POST)
========================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents("php://input"), true);

    if (
        empty($data['nome']) ||
        empty($data['cpf']) ||
        empty($data['cargo']) ||
        empty($data['senha'])
    ) {
        echo json_encode(["success" => false, "message" => "Campos obrigatórios"]);
        exit;
    }

    $senhaHash = password_hash($data['senha'], PASSWORD_DEFAULT);

    // 1️⃣ CRIA USUÁRIO
    $stmtUser = $conn->prepare("
      INSERT INTO users (username, password, role, trocar_senha)
      VALUES (?, ?, ?, 1)
    ");

    $stmtUser->bind_param(
        "sss",
        $data['cpf'],       // login = CPF (padrão profissional)
        $senhaHash,
        $data['cargo']
    );

    if (!$stmtUser->execute()) {
        echo json_encode(["success" => false, "message" => "Erro ao criar usuário"]);
        exit;
    }

    $userId = $conn->insert_id;

    // 2️⃣ CRIA FUNCIONÁRIO
    $stmtFunc = $conn->prepare("
      INSERT INTO funcionarios
      (nome_completo, data_nascimento, telefone, cpf, cargo_id, user_id)
      VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmtFunc->bind_param(
        "ssssii",
        $data['nome'],
        $data['data'],
        $data['telefone'],
        $data['cpf'],
        $data['cargo'],
        $userId
    );

    if ($stmtFunc->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao criar funcionário"]);
    }

    exit;
}