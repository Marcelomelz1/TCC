<?php
include('header.php');
include('con_bd.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);
    $tipo = $_POST['tipo'];
    $tipo = $_POST['status'];

    $sql = "INSERT INTO usuarios (nome, email, senha, tipo, status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssii', $nome, $email, $senha, $tipo, $status);

    if ($stmt->execute()) {
        $success_message = "Usuário adicionado com sucesso!";
    } else {
        $error_message = "Erro ao adicionar o usuário: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2>Adicionar Usuário</h2>
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
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="senha" name="senha" required>
                    </div>
                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo de Usuário</label>
                        <select class="form-select" id="tipo" name="tipo" required>
                            <option value="" selected disabled>Selecione o tipo de usuário</option>
                            <option value="3">Administrador</option>
                            <option value="2">Técnico</option>
                            <option value="1">Usuário</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status de Usuário</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="" selected disabled>Selecione o status do usuário</option>
                            <option value="1">Ativo</option>
                            <option value="0">Desativado</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Adicionar Usuário</button>
                </form>
            </div>
        </div>
    </div>

<?php
include('footer.php');
?>
