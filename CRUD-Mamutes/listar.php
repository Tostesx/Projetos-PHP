<?php
try {
    $pdo = new PDO('mysql:host=localhost:3307;dbname=bancomamutes', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    echo "
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: stretch;
            height: 100%;
            font-family: 'Times New Roman', Times, serif;
            background-color: #f5f5f5;
        }

        .tabela-container {
            width: 90%;
            max-width: 1000px;
            height: 100%;
            display: flex;
            justify-content: center;
        }

        table {
            width: 100%;
            height: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 1px solid #ccc;
            padding: 16px 12px;
            text-align: center;
            font-size: 1rem;
        }

        th {
            background-color: #e6e6fa;
            font-weight: bold;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
    ";

    echo "<div class='tabela-container'>";
    echo "<table>";
    echo "<tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Peso</th>
            <th>Altura</th>
            <th>Cor</th>
          </tr>";

    foreach ($pdo->query("SELECT * FROM tabela_mamutes") as $linha) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($linha['id']) . "</td>";
        echo "<td>" . htmlspecialchars($linha['nome']) . "</td>";
        echo "<td>" . htmlspecialchars($linha['peso']) . "</td>";
        echo "<td>" . htmlspecialchars($linha['altura']) . "</td>";
        echo "<td>" . htmlspecialchars($linha['cor']) . "</td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "</div>";
} catch (PDOException $e) {
    echo "Erro ao listar os produtos: " . $e->getMessage();
}
?>
