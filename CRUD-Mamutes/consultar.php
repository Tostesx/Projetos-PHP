<?php
if (isset($_POST['consultar'])) {
    $id = $_POST['id'] ?? '';
    $nome = $_POST['nome'] ?? '';

    try {
        $pdo = new PDO('mysql:host=localhost:3307;dbname=bancomamutes', 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        if (!empty($id)) {
            // Consulta por ID
            $sql = $pdo->prepare("SELECT * FROM tabela_mamutes WHERE id = ?");
            $sql->execute([$id]);
            $resultados = $sql->fetchAll(PDO::FETCH_ASSOC);
            $tipo_busca = "ID $id";
        } elseif (!empty($nome)) {
            // Consulta por nome
            $sql = $pdo->prepare("SELECT * FROM tabela_mamutes WHERE nome LIKE ?");
            $sql->execute(["%$nome%"]);
            $resultados = $sql->fetchAll(PDO::FETCH_ASSOC);
            $tipo_busca = "nome '$nome'";
        } else {
            // Nenhum critério informado
            echo "<p style='text-align: center; color: red;'>Por favor, informe um ID ou nome para consulta.</p>";
            exit;
        }

        echo "<style>
                body {
                  font-family: Arial, sans-serif;
                  padding: 20px;
                }
                h2 {
                  text-align: center;
                  color: #4f5b93;
                }
                table {
                  width: 100%;
                  border-collapse: collapse;
                  margin-top: 20px;
                  background-color: white;
                  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
                }
                th, td {
                  border: 1px solid #ccc;
                  padding: 10px;
                  text-align: center;
                }
                th {
                  background-color: #4f5b93;
                  color: white;
                }
                .nenhum-resultado {
                  text-align: center;
                  font-style: italic;
                  color: #666;
                }
              </style>";

        if (count($resultados) > 0) {
            echo "<h2>Resultados encontrados</h2>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Nome</th><th>Peso</th><th>Altura</th><th>Cor</th></tr>";

            foreach ($resultados as $linha) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($linha['id']) . "</td>";
                echo "<td>" . htmlspecialchars($linha['nome']) . "</td>";
                echo "<td>" . htmlspecialchars($linha['peso']) . "</td>";
                echo "<td>" . htmlspecialchars($linha['altura']) . "</td>";
                echo "<td>" . htmlspecialchars($linha['cor']) . "</td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "<p class='nenhum-resultado'>Nenhum mamute encontrado com o $tipo_busca.</p>";
        }

    } catch (PDOException $e) {
        echo "<p style='color: red; text-align: center;'>Erro na consulta: " . $e->getMessage() . "</p>";
    }
}
?>