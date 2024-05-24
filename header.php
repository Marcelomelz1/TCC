<?php
include('valida_login.php');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Assist I.T.</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
    <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
        <div class="col-md-3 mb-2 mb-md-0">
            <a href="dashboard.php" class="d-inline-flex link-body-emphasis text-decoration-none">
                <svg class="bi" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"></use></svg>
            </a>
        </div>

        <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
            <li><a href="dashboard.php" class="nav-link">Inicio</a></li>
            <li><a href="new_ticket.php" class="nav-link">Novo Chamado</a></li>
            <?php if ($_SESSION['user_tipo'] != 1): ?>
                <li><a href="add_user.php" class="nav-link">Adicionar Usuário</a></li>
            <?php endif; ?>
            <?php if ($_SESSION['user_tipo'] == 3): ?>
                <li><a href="view_users.php" class="nav-link">Gerenciar Usuários</a></li>
            <?php endif; ?>

        </ul>

        <div class="col-md-3 text-end">
            <button class="btn btn-outline-success"> <?php echo $_SESSION['user_nome']; echo $_SESSION['user_tipo']; ?></button>
            <a href="logout.php" type="button" class="btn btn-outline-danger">Sair</a>
        </div>
    </header>
</div>


