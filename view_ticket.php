<?php
include('header.php');
include('con_bd.php');

// Verifica se o ID do chamado foi fornecido
if (!isset($_GET['id'])) {
    die("ID do chamado não fornecido.");
}

$id = $_GET['id'];

// Consulta para buscar os detalhes do chamado
$sql = "SELECT c.id, c.tipo_problema, c.localizacao, c.descricao, c.descricao_solucao, c.status, c.data_criacao, c.data_atualizacao, u.nome AS usuario_nome, c.usuario_id 
        FROM chamados c 
        JOIN usuarios u ON c.usuario_id = u.id 
        WHERE c.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Chamado não encontrado.");
}
$chamado = $result->fetch_assoc();

// Verifica se o usuário tem permissão para editar o chamado
if ($_SESSION['user_tipo'] == 1 && $chamado['usuario_id'] != $_SESSION['user_id']) {
    $_SESSION['error_message'] = "Você não tem permissão para ver este chamado.";
    header('Location: dashboard.php');
    exit();
}

$stmt->close();

// Consulta para buscar a avaliação do chamado, se existir
$sql_avaliacao = "SELECT avaliacao, comentario, data_avaliacao FROM avaliacoes WHERE chamado_id = ?";
$stmt_avaliacao = $conn->prepare($sql_avaliacao);
$stmt_avaliacao->bind_param('i', $id);
$stmt_avaliacao->execute();
$result_avaliacao = $stmt_avaliacao->get_result();
$avaliacao = $result_avaliacao->fetch_assoc();

$stmt_avaliacao->close();
$conn->close();
?>

<div class="container mt-5 card">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="text-center">Detalhes do Chamado</h2>
            <hr>
            <div class="mb-3">
                <label class="form-label">Usuário</label>
                <input type="text" class="form-control" value="<?php echo $chamado['usuario_nome']; ?>" disabled>
            </div>
            <div class="mb-3">
                <label class="form-label">Tipo de Problema</label>
                <input type="text" class="form-control" value="<?php echo $chamado['tipo_problema']; ?>" disabled>
            </div>
            <div class="mb-3">
                <label class="form-label">Localização</label>
                <input type="text" class="form-control" value="<?php echo $chamado['localizacao']; ?>" disabled>
            </div>
            <div class="mb-3">
                <label class="form-label">Descrição</label>
                <textarea class="form-control" rows="4" disabled><?php echo $chamado['descricao']; ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <input type="text" class="form-control" value="<?php echo $chamado['status']; ?>" disabled>
            </div>
            <?php if (!empty($chamado['descricao_solucao'])) { ?>
                <div class="mb-3">
                    <label class="form-label">Descrição Solução</label>
                    <textarea class="form-control" rows="4" disabled><?php echo $chamado['descricao_solucao']; ?></textarea>
                </div>
            <?php } ?>
            <div class="mb-3">
                <label class="form-label">Data de Criação</label>
                <input type="text" class="form-control" value="<?php echo $chamado['data_criacao']; ?>" disabled>
            </div>
            <div class="mb-3">
                <label class="form-label">Data de Atualização</label>
                <input type="text" class="form-control" value="<?php echo $chamado['data_atualizacao']; ?>" disabled>
            </div>
            <?php if ($chamado['status'] == 'encerrado' && $avaliacao): ?>
                <div class="mb-3">
                    <label class="form-label">Avaliação</label>
                    <div class="row col-12">
                        <div class="col-2"><input type="text" class="form-control" value="<?php echo $avaliacao['avaliacao']; ?>" disabled> </div>
                        <div class="col-10"><input type="text" class="form-control" value="<?php echo $avaliacao['comentario']; ?>" disabled> </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Data da Avaliação</label>
                    <input type="text" class="form-control" value="<?php echo $avaliacao['data_avaliacao']; ?>" disabled>
                </div>
            <?php endif; ?>
            <div class="row-cols-5 text-center pb-3">
                <a class="btn btn-outline-primary" href="view_history.php?id=<?php echo $chamado['id']; ?>">Ver historico</a>
                <a href="edit_ticket.php?id=<?php echo $chamado['id']; ?>" class="btn btn-primary">Editar</a>
                <a href="dashboard.php" class="btn btn-secondary">Voltar</a>
            </div>
        </div>
    </div>
</div>

<?php
include('footer.php');
?>
