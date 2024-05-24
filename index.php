<?php
session_start();
include('con_bd.php');

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica se o usuário foi encontrado
    if ($result->num_rows > 0) {
        // Usuário encontrado
        $user = $result->fetch_assoc();

        // Verifica a senha
        if (password_verify($senha, $user['senha'])) {
            // Senha correta
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nome'] = $user['nome'];
            $_SESSION['user_tipo'] = $user['tipo'];

                if ($user['status'] == 1) {
                    // Redireciona para o painel apropriado
                    header('Location: dashboard.php');
                    exit();
                } else {
                    $error = "Usuário desativado, entre em contato com o administrador.";
                }
        } else {
            // Senha incorreta
            $error = "Credenciais inválidas. Tente novamente.";
        }
    } else {
        // Usuário não encontrado
        $error = "Credenciais inválidas. Tente novamente.";
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Staff Assist I.T.</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
<div class="container d-flex flex-column align-items-center justify-content-center vh-100">
    <h1 class="mb-4 text-uppercase">Staff Assist I.T.</h1>
    <div class="card w-100" style="max-width: 400px;">
        <div class="card-body">
            <h2 class="card-title text-center ">Bem-vindo</h2>
            <p class="card-text text-center">Faça o login para continuar.</p>
            <form method="post" action="index.php">
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="senha" class="form-label">Senha:</label>
                    <input type="password" id="senha" name="senha" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>
</body>
</html>
