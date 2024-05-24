<?php
include('header.php');
include('con_bd.php');

// Verifica se o ID do chamado foi fornecido
if (!isset($_GET['id'])) {
    die("ID do chamado não fornecido.");
}

$id = $_GET['id'];

// Consulta para buscar os detalhes do chamado
$sql = "SELECT c.id, c.tipo_problema, c.localizacao, c.descricao, c.status, c.data_criacao, c.data_atualizacao, u.nome AS usuario_nome 
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
$stmt->close();
$conn->close();
?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>Detalhes do Chamado</h2>
                <table class="table table-bordered">
                    <tr>
                        <th>ID</th>
                        <td><?php echo $chamado['id']; ?></td>
                    </tr>
                    <tr>
                        <th>Usuário</th>
                        <td><?php echo $chamado['usuario_nome']; ?></td>
                    </tr>
                    <tr>
                        <th>Tipo de Problema</th>
                        <td><?php echo $chamado['tipo_problema']; ?></td>
                    </tr>
                    <tr>
                        <th>Localização</th>
                        <td><?php echo $chamado['localizacao']; ?></td>
                    </tr>
                    <tr>
                        <th>Descrição</th>
                        <td><?php echo $chamado['descricao']; ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td><?php echo $chamado['status']; ?></td>
                    </tr>
                    <tr>
                        <th>Data de Criação</th>
                        <td><?php echo $chamado['data_criacao']; ?></td>
                    </tr>
                    <tr>
                        <th>Data de Atualização</th>
                        <td><?php echo $chamado['data_atualizacao']; ?></td>
                    </tr>
                </table>
                <?php if ($_SESSION['user_tipo'] != 1): ?>
                    <a href="edit_ticket.php?id=<?php echo $chamado['id']; ?>" class="btn btn-primary">Editar</a>
                <?php endif; ?>
                <a href="dashboard.php" class="btn btn-secondary">Voltar</a>
            </div>
        </div>
    </div>

<?php
include('footer.php');
?>
