<?php
include('header.php');
include('con_bd.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $tipo_problema = $_POST['tipo_problema'];
    $localizacao = $_POST['localizacao'];
    $descricao = $_POST['descricao'];
    $descricao_solucao = $_POST['descricao_solucao'];
    $status = $_POST['status'];

    $sql = "SELECT * FROM chamados WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $chamado_anterior = $result->fetch_assoc();
    $stmt->close();

    $sql_historico = "INSERT INTO historico_chamados (chamado_id, tipo_problema_anterior, tipo_problema_novo, localizacao_anterior, localizacao_nova, descricao_anterior, descricao_nova, descricao_solucao_anterior, descricao_solucao_nova, status_anterior, status_novo, data_alteracao)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt_historico = $conn->prepare($sql_historico);
    $stmt_historico->bind_param('issssssssss',
        $id,
        $chamado_anterior['tipo_problema'], $tipo_problema,
        $chamado_anterior['localizacao'], $localizacao,
        $chamado_anterior['descricao'], $descricao,
        $chamado_anterior['descricao_solucao'], $descricao_solucao,
        $chamado_anterior['status'], $status
    );
    $stmt_historico->execute();
    $stmt_historico->close();

    $sql = "UPDATE chamados SET tipo_problema = ?, localizacao = ?, descricao = ?, descricao_solucao = ?, status = ?, data_atualizacao = NOW() WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssi', $tipo_problema, $localizacao, $descricao, $descricao_solucao, $status, $id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Chamado atualizado com sucesso!";
        echo "<script>window.location.href='dashboard.php';</script>";
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

        if ($_SESSION['user_tipo'] == 1 && $chamado['usuario_id'] != $_SESSION['user_id']) {
            $_SESSION['error_message'] = "Você não tem permissão para editar este chamado.";
            echo "<script>window.location.href='dashboard.php';</script>";
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

    <div class="container mt-5 card">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="text-center">Editar Chamado</h2>
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
                            <?php if ($_SESSION['user_tipo'] != 1){ ?>
                                <option value="encerrado" <?php if ($chamado['status'] == 'encerrado') echo 'selected'; ?>>Encerrado</option>
                            <?php } ?>
                        </select>
                    </div>
                    <?php if ($_SESSION['user_tipo'] != 1){ ?>
                    <div class="mb-3" id="descricao-solucao-container" style="display: none;">
                        <label for="descricao_solucao" class="form-label">Descrição Solução</label>
                        <select class="form-select" id="descricao_solucao_select" name="descricao_solucao_select">
                            <option value="" disabled selected>Selecione uma opção</option>
                            <option value="Reiniciar o dispositivo">Reiniciar o dispositivo</option>
                            <option value="Atualizar o software">Atualizar o software</option>
                            <option value="Substituir o hardware">Substituir o hardware</option>
                            <option value="Resetar as configurações">Resetar as configurações</option>
                            <option value="Outro">Outro</option>
                        </select>
                        <textarea class="form-control mt-3" id="descricao_solucao" name="descricao_solucao" rows="3" style="display:none;" placeholder="Descreva a solução"></textarea>
                    </div>
                    <?php }?>

                    <div class="text-center pb-2">
                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        <a href="view_ticket.php?id=<?php echo $chamado['id']; ?>" class="btn btn-secondary">Voltar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script>
    document.getElementById('status').addEventListener('change', function () {
        var descricaoSolucaoContainer = document.getElementById('descricao-solucao-container');
        if (this.value === 'resolvido' || this.value === 'encerrado') {
            descricaoSolucaoContainer.style.display = 'block';
        } else {
            descricaoSolucaoContainer.style.display = 'none';
        }
    });

    document.getElementById('descricao_solucao_select').addEventListener('change', function () {
        var textarea = document.getElementById('descricao_solucao');
        if (this.value === 'Outro') {
            textarea.style.display = 'block';
            textarea.required = true;
        } else {
            textarea.style.display = 'none';
            textarea.value = this.value;
            textarea.required = false;
        }
    });

    window.addEventListener('load', function () {
        var status = document.getElementById('status');
        var descricaoSolucaoContainer = document.getElementById('descricao-solucao-container');
        if (status.value === 'resolvido' || status.value === 'encerrado') {
            descricaoSolucaoContainer.style.display = 'block';
        } else {
            descricaoSolucaoContainer.style.display = 'none';
        }

        var select = document.getElementById('descricao_solucao_select');
        var textarea = document.getElementById('descricao_solucao');
        if (select.value === 'Outro') {
            textarea.style.display = 'block';
            textarea.required = true;
        } else {
            textarea.style.display = 'none';
        }
    });
</script>
<?php
include('footer.php');
?>
