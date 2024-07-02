<?php
include('header.php');
include('con_bd.php');

if ($_SESSION['user_tipo'] != 3) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $tipo = $_POST['tipo'];
    $status = $_POST['status'];
    if (!empty($_POST['senha'])) {
        $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);
        $sql = "UPDATE usuarios SET nome = ?, email = ?, senha = ?, tipo = ?, status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssiii', $nome, $email, $senha, $tipo, $status, $id);
    } else {
        $sql = "UPDATE usuarios SET nome = ?, email = ?, tipo = ?, status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssiii', $nome, $email, $tipo, $status, $id);
    }

    if ($stmt->execute()) {
        $success_message = "Usuário atualizado com sucesso!";
    } else {
        $error_message = "Erro ao atualizar o usuário: " . $stmt->error;
    }

    $stmt->close();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
    } else {
        die("Usuário não encontrado.");
    }

    $stmt->close();
} else {
    die("ID do usuário não fornecido.");
}

$conn->close();
?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 card">
                <h2 class="text-center">Editar Usuário</h2>
                <hr>
                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                <form method="POST" action="">
                    <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $usuario['nome']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $usuario['email']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha (deixe em branco para não alterar)</label>
                        <input type="password" class="form-control" id="senha" name="senha">
                    </div>
                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo de Usuário</label>
                        <select class="form-select" id="tipo" name="tipo" required>
                            <option value="3" <?php if ($usuario['tipo'] == 3) echo 'selected'; ?>>Administrador</option>
                            <option value="2" <?php if ($usuario['tipo'] == 2) echo 'selected'; ?>>Técnico</option>
                            <option value="1" <?php if ($usuario['tipo'] == 1) echo 'selected'; ?>>Usuário</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status do Usuário</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="1" <?php if ($usuario['status'] == 1) echo 'selected'; ?>>Ativo</option>
                            <option value="0" <?php if ($usuario['status'] == 0) echo 'selected'; ?>>Desativado</option>
                        </select>
                    </div>
                    <div class="text-center pb-2">
                    <a type="button" class="btn btn-primary text-center" href="view_users.php">Voltar</a>
                    <button type="submit" class="btn btn-success">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php
include('footer.php');
?>
