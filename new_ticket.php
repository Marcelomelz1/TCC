<?php
include('header.php');
include('con_bd.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario_id = $_SESSION['user_id'];
    $tipo_problema = $_POST['tipo_problema'];
    $localizacao = $_POST['localizacao'];
    $descricao = $_POST['descricao'];
    $status = 'aberto';

    $sql = "INSERT INTO chamados (usuario_id, tipo_problema, localizacao, descricao, status, data_criacao, data_atualizacao) VALUES (?, ?, ?, ?, ?, NOW(), NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('issss', $usuario_id, $tipo_problema, $localizacao, $descricao, $status);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Chamado criado com sucesso!";
        header('Location: dashboard.php');
        exit();
    } else {
        $error_message = "Erro ao criar o chamado: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 card">
                <h2>Criar Novo Chamado</h2>
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="tipo_problema" class="form-label">Tipo de Problema</label>
                        <select class="form-select" id="tipo_problema" name="tipo_problema" required>
                            <option value="" selected disabled>Selecione o tipo de problema</option>
                            <option value="Problema de Rede">Problema de Rede</option>
                            <option value="Erro de Software">Erro de Software</option>
                            <option value="Falha de Hardware">Falha de Hardware</option>
                            <option value="Problema de Impressão">Problema de Impressão</option>
                            <option value="Outros">Outros</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="localizacao" class="form-label">Localização</label>
                        <input type="text" class="form-control" id="localizacao" name="localizacao" required>
                    </div>
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Criar Chamado</button>
                </form>
            </div>
        </div>
    </div>

<?php
include('footer.php');
?>
