/* ======================================================
   BANCO DE DADOS - SISTEMA CLÍNICO
   ====================================================== */

CREATE DATABASE IF NOT EXISTS sistema_login




/* ======================================================
   1. USUÁRIOS (LOGIN / AUTENTICAÇÃO)
   ====================================================== */
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(30) NOT NULL,
  trocar_senha TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

/* ======================================================
   2. CARGOS / PERFIS
   ====================================================== */
CREATE TABLE cargos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(50) NOT NULL
);

INSERT INTO cargos (nome) VALUES
('Administrador'),
('Médico'),
('Call Center'),
('Financeiro');

/* ======================================================
   3. FUNCIONÁRIOS
   ====================================================== */
CREATE TABLE funcionarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  nome_completo VARCHAR(100) NOT NULL,
  data_nascimento DATE,
  telefone VARCHAR(20),
  cpf VARCHAR(14) NOT NULL UNIQUE,
  cargo_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (cargo_id) REFERENCES cargos(id)
);

/* ======================================================
   4. MÉDICOS
   ====================================================== */
CREATE TABLE medicos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  funcionario_id INT NOT NULL,
  especialidade VARCHAR(100) NOT NULL,

  FOREIGN KEY (funcionario_id) REFERENCES funcionarios(id)
);

/* ======================================================
   5. PACIENTES
   ====================================================== */
CREATE TABLE pacientes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  cpf VARCHAR(14) UNIQUE,
  telefone VARCHAR(20),
  data_nascimento DATE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

/* ======================================================
   6. CONVÊNIOS
   ====================================================== */
CREATE TABLE convenios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL
);

/* ======================================================
   7. AGENDAMENTOS
   ====================================================== */
CREATE TABLE agendamentos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  medico_id INT NOT NULL,
  paciente_id INT NOT NULL,
  data DATE NOT NULL,
  hora TIME NOT NULL,
  status VARCHAR(20) DEFAULT 'Agendado',

  FOREIGN KEY (medico_id) REFERENCES medicos(id),
  FOREIGN KEY (paciente_id) REFERENCES pacientes(id)
);

/* ======================================================
   8. PRONTUÁRIO
   ====================================================== */
CREATE TABLE prontuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  paciente_id INT NOT NULL,
  medico_id INT NOT NULL,
  descricao TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (paciente_id) REFERENCES pacientes(id),
  FOREIGN KEY (medico_id) REFERENCES medicos(id)
);

/* ======================================================
   9. ANAMNESE
   ====================================================== */
CREATE TABLE anamneses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  paciente_id INT NOT NULL,
  respostas TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (paciente_id) REFERENCES pacientes(id)
);

/* ======================================================
   10. FINANCEIRO
   ====================================================== */
CREATE TABLE financeiro (
  id INT AUTO_INCREMENT PRIMARY KEY,
  paciente_id INT,
  descricao VARCHAR(255),
  valor DECIMAL(10,2),
  tipo ENUM('Entrada','Saída'),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (paciente_id) REFERENCES pacientes(id)
);


