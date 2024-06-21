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


    <link href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css">
    <link href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.bootstrap5.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body class="bg-body-secondary">
<div class="container">
    <header class="d-flex flex-wrap align-items-center justify-content-between py-3 mb-4 border-bottom bg-light shadow-sm rounded rounded-1 mt-2">
        <div class="col-md-3 mb-2 mb-md-0">
            <span class="ms-2 fs-4">Staff Assist I.T.</span>
        </div>
        <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
            <li><a href="dashboard.php" class=" btn btn-outline-primary mx-1">Inicio</a></li>
            <li><a href="new_ticket.php" class="btn btn-outline-primary mx-1">Novo Chamado</a></li>
            <?php if ($_SESSION['user_tipo'] != 1): ?>
                <li><a href="add_user.php" class="btn btn-outline-primary mx-1">Adicionar Usuário</a></li>
            <?php endif; ?>
            <?php if ($_SESSION['user_tipo'] == 3): ?>
                <li><a href="view_users.php" class="btn btn-outline-primary mx-1">Gerenciar Usuários</a></li>
            <?php endif; ?>
        </ul>
        <div class="col-md-3 text-end">
            <button class="btn btn-outline-success mx-1"><?php echo $_SESSION['user_nome']; echo " (" . $_SESSION['user_tipo'] . ")"; ?></button>
            <a href="logout.php" class="btn btn-outline-danger mx-1">Sair</a>
        </div>
    </header>
</div>


