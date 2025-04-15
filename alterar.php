<?php
$pdo = new PDO('mysql:host=localhost:3307;dbname=bancomamutes', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// ALTERAÇÃO
if (isset($_POST['alterar'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $peso = $_POST['peso'];
    $altura = $_POST['altura'];
    $cor = $_POST['cor'];

    $stmt = $pdo->prepare("UPDATE tabela_mamutes SET nome = ?, peso = ?, altura = ?, cor = ? WHERE id = ?");
    $stmt->execute([$nome, $peso, $altura, $cor, $id]);

    echo "<p style='color: green; text-align: center;'>✅ Mamute com ID $id foi alterado com sucesso!</p><hr>";
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
            .alterar-btn {
                background-color: #3498db;
                color: white;
                padding: 8px 16px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 14px;
                transition: background-color 0.3s;
            }
            .alterar-btn:hover {
                background-color: #2980b9;
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
            .form-popup {
                display: none;
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                border: 3px solid #f1f1f1;
                z-index: 9;
                background-color: white;
                padding: 20px;
                box-shadow: 0 4px 8px rgba(0,0,0,0.2);
                width: 80%;
                max-width: 500px;
            }
            .form-container {
                max-width: 100%;
                padding: 10px;
                background-color: white;
            }
            .form-container input {
                width: 100%;
                padding: 10px;
                margin: 5px 0 15px;
                border: 1px solid #ddd;
                border-radius: 4px;
            }
            .form-container .btn {
                background-color: #4CAF50;
                color: white;
                padding: 10px 15px;
                border: none;
                cursor: pointer;
                width: 100%;
                margin-bottom: 10px;
                opacity: 0.8;
            }
            .form-container .cancel {
                background-color: #e74c3c;
            }
            .form-container .btn:hover {
                opacity: 1;
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
                        <th>Ação</th>
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
                        <button onclick="openForm(' . $mamute['id'] . ', \'' . htmlspecialchars($mamute['nome']) . '\', ' . htmlspecialchars($mamute['peso']) . ', ' . htmlspecialchars($mamute['altura']) . ', \'' . htmlspecialchars($mamute['cor']) . '\')" class="alterar-btn">Alterar</button>
                    </td>
                </tr>';
        }

        echo '</tbody></table>';

        // Formulário popup para alteração
        echo '<div class="form-popup" id="myForm">
                <form method="POST" class="form-container">
                    <h2>Alterar Mamute</h2>
                    
                    <input type="hidden" id="edit_id" name="id">
                    
                    <label for="edit_nome"><b>Nome</b></label>
                    <input type="text" id="edit_nome" name="nome" required>
                    
                    <label for="edit_peso"><b>Peso</b></label>
                    <input type="number" id="edit_peso" name="peso" required>
                    
                    <label for="edit_altura"><b>Altura</b></label>
                    <input type="number" step="0.01" id="edit_altura" name="altura" required>
                    
                    <label for="edit_cor"><b>Cor</b></label>
                    <input type="text" id="edit_cor" name="cor" required>
                    
                    <button type="submit" name="alterar" class="btn">Confirmar</button>
                    <button type="button" class="btn cancel" onclick="closeForm()">Cancelar</button>
                </form>
              </div>';

        // JavaScript para o popup
        echo '<script>
                function openForm(id, nome, peso, altura, cor) {
                    document.getElementById("edit_id").value = id;
                    document.getElementById("edit_nome").value = nome;
                    document.getElementById("edit_peso").value = peso;
                    document.getElementById("edit_altura").value = altura;
                    document.getElementById("edit_cor").value = cor;
                    document.getElementById("myForm").style.display = "block";
                }
                
                function closeForm() {
                    document.getElementById("myForm").style.display = "none";
                }
              </script>';
    } else {
        echo '<p class="mensagem">🔍 Nenhum mamute encontrado com esses dados.</p>';
    }

    echo '</body></html>';
}
?>