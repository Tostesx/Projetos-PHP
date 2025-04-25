<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <link rel="stylesheet" href="login.css"/>
    </head>
    <body>
        <header>
            <h1>Login de Usuário</h1>
            <img src="new-php-logo.png" alt="Logo PHP">
        </header>
        <main>
            <form method="POST">
                <label for="login">Login:</label><br>
                    <input type="text" id="login" name="login" required><br><br>

                <label for="senha">Senha:</label><br>
                    <input type="password" id="senha" name="senha" required><br><br>

                <button type="submit" name="acao">Entrar</button>
            </form>
            <?php
try {
    $pdo = new PDO('mysql:host=localhost:3307;dbname=bancomamutes', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['acao'])) {
        $login = $_POST['login'];
        $senha = $_POST['senha'];

        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE login = ?");
        $stmt->execute([$login]);

        if ($stmt->rowCount() > 0) {
            $usuario = $stmt->fetch();

            // Verifica a senha (ajuste conforme se usa hash ou não)
            if ($senha === $usuario['senha']) { // ou password_verify($senha, $usuario['senha']) se for hash

                if ($usuario['tipo'] === 'm') {
                    echo "✅ Bem-vindo, administrador!";
                    header('Location: admintela.html');
                } elseif ($usuario['tipo'] === 'c') {
                    echo "✅ Bem-vindo, usuário comum!";
                    header('Location: client_tela.html');
                } else {
                    echo "⚠️ Tipo de usuário inválido.";
                }

            } else {
                echo "❌ Senha incorreta.";
            }

        } else {
            echo "❌ Usuário não encontrado.";
        }
    }
} catch (PDOException $e) {
    echo "Erro ao conectar ou executar consulta: " . $e->getMessage();
}
?>

            
            
        </main>
        <footer>
            <p>Criado por: Matheus Soares Tostes</p>
        </footer>
    </body>
</html>
