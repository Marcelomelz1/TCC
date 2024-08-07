<?php
include('header.php');
include('con_bd.php');

if ($_SESSION['user_tipo'] == 1) {
    header('Location: dashboard.php');
    exit;
}

$sql = "SELECT id, nome, email, tipo, status FROM usuarios";
$result = $conn->query($sql);

$usuarios = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
}

$conn->close();
?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10 card">
                <h2 class="text-center mb-4">Lista de Usuários</h2>
                <hr>
                <div class="table-responsive">
                        <table class="table table-striped align-middle bg-white">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Tipo</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?php echo $usuario['id']; ?></td>
                                <td><?php echo $usuario['nome']; ?></td>
                                <td><?php echo $usuario['email']; ?></td>
                                <td><?php
                                    if ($usuario['tipo'] == 3) {
                                        echo 'Administrador';
                                    } elseif ($usuario['tipo'] == 2) {
                                        echo 'Técnico';
                                    } else {
                                        echo 'Usuário';
                                    }
                                    ?>
                                </td>
                                <td><?php
                                    if ($usuario['status'] == 1) {
                                        echo 'Ativo';
                                    } else {
                                        echo 'Desativado';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <a href="edit_user.php?id=<?php echo $usuario['id']; ?>" class="btn btn-sm btn-primary">Editar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="text-center pb-2">
                        <a type="button" class="btn btn-primary text-center" href="dashboard.php">Voltar</a>
                        <a type="button" class="btn btn-success text-center" href="add_user.php">Adicionar Usuário</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
include('footer.php');
?>
