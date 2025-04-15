<?php
$pdo = new PDO('mysql:host=localhost:3307;dbname=bancomamutes', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// EXCLUSÃO
if (isset($_POST['excluir_id'])) {
    $idExcluir = $_POST['excluir_id'];

    $stmt = $pdo->prepare("DELETE FROM tabela_mamutes WHERE id = ?");
    $stmt->execute([$idExcluir]);

    echo "<p style='color: green; text-align: center;'>✅ Mamute com ID $idExcluir foi excluído com sucesso!</p><hr>";
}

// CONSULTA
if (isset($_POST['consultar'])) {
    $id = $_POST['id'] ?? '';
    $nome = $_POST['nome'] ?? '';

    if (!empty($id)) {
        $sql = $pdo->prepare("SELECT * FROM tabela_mamutes WHERE id = ?");
        $sql->execute([$id]);
    } else {
        $sql = $pdo->prepare("SELECT * FROM tabela_mamutes WHERE nome LIKE ?");
        $sql->execute(["%$nome%"]);
    }

    $resultados = $sql->fetchAll(PDO::FETCH_ASSOC);

    // Início do documento HTML com estilos
    echo '<!DOCTYPE html>
    <html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 20px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }
            th, td {
                border: 1px solid #ddd;
                padding: 12px;
                text-align: center;
            }
            th {
                background-color: #4f5b93;
                color: white;
                position: sticky;
                top: 0;
            }
            tr:nth-child(even) {
                background-color: #f9f9f9;
            }
            .excluir-btn {
                background-color: #e74c3c;
                color: white;
                padding: 8px 16px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 14px;
                transition: background-color 0.3s;
            }
            .excluir-btn:hover {
                background-color: #c0392b;
            }
            form {
                margin: 0;
                padding: 0;
                display: inline;
            }
            .mensagem {
                text-align: center;
                margin: 20px 0;
                font-size: 1.1em;
            }
        </style>
    </head>
    <body>';

    if ($resultados) {
        echo '<table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Peso</th>
                        <th>Altura</th>
                        <th>Cor</th>
                        <th>Exclusão</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($resultados as $mamute) {
            echo '<tr>
                    <td>' . htmlspecialchars($mamute['id']) . '</td>
                    <td>' . htmlspecialchars($mamute['nome']) . '</td>
                    <td>' . htmlspecialchars($mamute['peso']) . '</td>
                    <td>' . htmlspecialchars($mamute['altura']) . '</td>
                    <td>' . htmlspecialchars($mamute['cor']) . '</td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="excluir_id" value="' . $mamute['id'] . '">
                            <button type="submit" class="excluir-btn" onclick="return confirm(\'Tem certeza que deseja excluir este mamute?\')">Excluir</button>
                        </form>
                    </td>
                </tr>';
        }

        echo '</tbody></table>';
    } else {
        echo '<p class="mensagem">🔍 Nenhum mamute encontrado com esses dados.</p>';
    }

    // Fechamento do documento HTML
    echo '</body></html>';
}
?>