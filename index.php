<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Staff Assist I.T.</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styleindex.css">
</head>
<body>
<div class="container d-flex flex-column align-items-center justify-content-center vh-100">
    <h1 class="mb-4 text-uppercase">Staff Assist I.T.</h1>
    <div class="card w-100" style="max-width: 400px;">
        <div class="card-body">
            <h2 class="card-title text-center ">Bem-vindo</h2>
            <p class="card-text text-center">Faça o login para continuar.</p>
            <form method="post" action="login_action.php">
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Senha:</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
