<?php
// Atualize estas variáveis com as credenciais do seu banco de dados
$host = '127.0.0.1';
$db   = 'llsecurity';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=llsecurity;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    echo 'Erro de conexão: ' . htmlspecialchars($e->getMessage());
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // REMOVEMOS O PRINT_R DAQUI PARA O CÓDIGO PODER CONTINUAR!

    // Ajustado exatamente para ler o que vem do seu HTML
    $Nome = trim($_POST['nome'] ?? '');
    $Email = trim($_POST['email'] ?? '');
    $data_de_nascimento = $_POST['data_nascimento'] ?? ''; // Ajustado sem o "_de_" para casar com o HTML
    $endereco = trim($_POST['endereco'] ?? '');
    $Cpf = trim($_POST['Cpf'] ?? '');

    if ($Nome === '' || $Email === '' || $data_de_nascimento === '' || $endereco === '' || $Cpf === '') {
        echo 'Por favor, preencha todos os campos.';
        exit;
    }

    // O SQL envia para a coluna CPF (em maiúsculo) do seu banco
    $sql = 'INSERT INTO candidatos (Nome, Email, data_de_nascimento, endereco, CPF) VALUES (:Nome, :Email, :data_de_nascimento, :endereco, :cpf)';
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute([
            ':Nome' => $Nome,
            ':Email' => $Email,
            ':data_de_nascimento' => $data_de_nascimento,
            ':endereco' => $endereco,
            ':cpf' => $Cpf, 
        ]);
    } catch (PDOException $e) {
        echo 'Erro ao salvar: ' . htmlspecialchars($e->getMessage());
        exit;
    }

    echo '<p>Cadastro enviado com sucesso! Obrigado por se candidatar.</p>';
    echo '<p><a href="trabalheconosco.html">Voltar</a></p>';
    exit;
}