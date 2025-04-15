<?php
try {
    $pdo = new PDO('mysql:host=localhost:3307;dbname=bancomamutes', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    if (isset($_POST['salvar'])) {
        $nome = $_POST['nome'] ?? '';
        $peso = $_POST['peso'] ?? '';
        $altura = $_POST['altura'] ?? '';
        $cor = $_POST['cor'] ?? '';

        if ($nome && $peso && $altura && $cor) {
            $sql = $pdo->prepare("INSERT INTO tabela_mamutes (nome, peso, altura, cor) VALUES (?, ?, ?, ?)");
            $sql->execute([$nome, $peso, $altura, $cor]);

            // Retorna uma mensagem de sucesso como JSON
            header("Location: cadastrar.html?msg=" . urlencode("✅ Mamute $nome cadastrado com sucesso!"));
            exit();
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => '⚠️ Por favor, preencha todos os campos.'
            ]);
            exit();
        }
    }
} catch (PDOException $e) {
    header("Location: cadastrar.html?msg=" . urlencode("❌ Não foi possível cadasdrar $e!"));
    exit();
}
?>