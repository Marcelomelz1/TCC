<?php
include('valida_login.php');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Assist I.T.</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.bootstrap5.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</head>
<body class="bg-body-secondary">
<div class="container">
    <header class="py-3 mb-4 border-bottom bg-light shadow-sm rounded mt-2">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <a class="navbar-brand ms-2 fs-4 fw-bold" href="dashboard.php">Staff Assist I.T.</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mb-2 mb-lg-0">
                        <li class="nav-item p-1">
                            <a class="nav-link btn btn-outline-primary border border-gray mx-1" href="dashboard.php">Início</a>
                        </li>
                        <li class="nav-item p-1">
                            <a class="nav-link btn btn-outline-primary border border-gray mx-1" href="new_ticket.php">Novo Chamado</a>
                        </li>
                        <?php if ($_SESSION['user_tipo'] != 1): ?>
                            <li class="nav-item p-1">
                                <a class="nav-link btn btn-outline-primary border border-gray mx-1" href="add_user.php">Adicionar Usuário</a>
                            </li>
                        <?php endif; ?>
                        <?php if ($_SESSION['user_tipo'] == 3): ?>
                            <li class="nav-item p-1">
                                <a class="nav-link btn btn-outline-primary border border-gray mx-1" href="view_users.php">Gerenciar Usuários</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <div class="d-flex ms-auto">
                        <button class="btn btn-outline-success mx-1"><?php echo $_SESSION['user_nome']; echo " (" . $_SESSION['user_tipo'] . ")"; ?></button>
                        <a href="logout.php" class="btn btn-outline-danger mx-1">Sair</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>
</div>

