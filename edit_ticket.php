<?php
include('header.php');
include('con_bd.php');

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $tipo_problema = $_POST['tipo_problema'];
    $localizacao = $_POST['localizacao'];
    $descricao = $_POST['descricao'];
    $status = $_POST['status'];

    // Obtém o estado atual do chamado antes da atualização
    $sql = "SELECT * FROM chamados WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $chamado_anterior = $result->fetch_assoc();
    $stmt->close();

    // Insere o histórico da alteração
    $sql_historico = "INSERT INTO historico_chamados (chamado_id, tipo_problema_anterior, tipo_problema_novo, localizacao_anterior, localizacao_nova, descricao_anterior, descricao_nova, status_anterior, status_novo, data_alteracao)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt_historico = $conn->prepare($sql_historico);
    $stmt_historico->bind_param('issssssss', $id,
        $chamado_anterior['tipo_problema'], $tipo_problema,
        $chamado_anterior['localizacao'], $localizacao,
        $chamado_anterior['descricao'], $descricao,
        $chamado_anterior['status'], $status
    );
    $stmt_historico->execute();
    $stmt_historico->close();

    // Atualiza o chamado
    $sql = "UPDATE chamados SET tipo_problema = ?, localizacao = ?, descricao = ?, status = ?, data_atualizacao = NOW() WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssi', $tipo_problema, $localizacao, $descricao, $status, $id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Chamado atualizado com sucesso!";
        header('Location: dashboard.php');
        exit();
    } else {
        $error_message = "Erro ao atualizar o chamado: " . $stmt->error;
    }

    $stmt->close();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM chamados WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $chamado = $result->fetch_assoc();

        // Verifica se o usuário tem permissão para editar o chamado
        if ($_SESSION['user_tipo'] == 1 && $chamado['usuario_id'] != $_SESSION['user_id']) {
            $_SESSION['error_message'] = "Você não tem permissão para editar este chamado.";
            header('Location: dashboard.php');
            exit();
        }
    } else {
        die("Chamado não encontrado.");
    }

    $stmt->close();
} else {
    die("ID do chamado não fornecido.");
}

$conn->close();
?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>Editar Chamado</h2>
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                <form method="POST" action="">
                    <input type="hidden" name="id" value="<?php echo $chamado['id']; ?>">
                    <div class="mb-3">
                        <label for="tipo_problema" class="form-label">Tipo de Problema</label>
                        <select class="form-select" id="tipo_problema" name="tipo_problema" required>
                            <option value="Problema de Conexão" <?php if ($chamado['tipo_problema'] == 'Problema de Conexão') echo 'selected'; ?>>Problema de Conexão</option>
                            <option value="Erro de Software" <?php if ($chamado['tipo_problema'] == 'Erro de Software') echo 'selected'; ?>>Erro de Software</option>
                            <option value="Falha de Hardware" <?php if ($chamado['tipo_problema'] == 'Falha de Hardware') echo 'selected'; ?>>Falha de Hardware</option>
                            <option value="Problema de Impressão" <?php if ($chamado['tipo_problema'] == 'Problema de Impressão') echo 'selected'; ?>>Problema de Impressão</option>
                            <option value="Outros" <?php if ($chamado['tipo_problema'] == 'Outros') echo 'selected'; ?>>Outros</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="localizacao" class="form-label">Localização</label>
                        <input type="text" class="form-control" id="localizacao" name="localizacao" value="<?php echo $chamado['localizacao']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="4" required><?php echo $chamado['descricao']; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="aberto" <?php if ($chamado['status'] == 'aberto') echo 'selected'; ?>>Aberto</option>
                            <option value="em_andamento" <?php if ($chamado['status'] == 'em_andamento') echo 'selected'; ?>>Em Andamento</option>
                            <option value="resolvido" <?php if ($chamado['status'] == 'resolvido') echo 'selected'; ?>>Resolvido</option>
                            <option value="encerrado" <?php if ($chamado['status'] == 'encerrado') echo 'selected'; ?>>Encerrado</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </form>
            </div>
        </div>
    </div>

<?php
include('footer.php');
?>
