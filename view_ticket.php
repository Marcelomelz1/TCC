<?php
include('header.php');
include('con_bd.php');

// Verifica se o ID do chamado foi fornecido
if (!isset($_GET['id'])) {
    die("ID do chamado não fornecido.");
}

$id = $_GET['id'];

// Consulta para buscar os detalhes do chamado
$sql = "SELECT c.id, c.tipo_problema, c.localizacao, c.descricao, c.status, c.data_criacao, c.data_atualizacao, u.nome AS usuario_nome, c.usuario_id 
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
$conn->close();
?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>Detalhes do Chamado</h2>
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
                <div class="mb-3">
                    <label class="form-label">Data de Criação</label>
                    <input type="text" class="form-control" value="<?php echo $chamado['data_criacao']; ?>" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label">Data de Atualização</label>
                    <input type="text" class="form-control" value="<?php echo $chamado['data_atualizacao']; ?>" disabled>
                </div>
                    <a href="edit_ticket.php?id=<?php echo $chamado['id']; ?>" class="btn btn-primary">Editar</a>
                <a href="dashboard.php" class="btn btn-secondary">Voltar</a>
            </div>
        </div>
    </div>

<?php
include('footer.php');
?>