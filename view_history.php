<?php
include('header.php');
include('con_bd.php');

// Verifica se o ID do chamado foi fornecido
if (!isset($_GET['id'])) {
    die("ID do chamado não fornecido.");
}

$id = $_GET['id'];

// Consulta para buscar o histórico do chamado
$sql = "SELECT * FROM historico_chamados WHERE chamado_id = ? ORDER BY data_alteracao DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

$historico = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $historico[] = $row;
    }
}

$sql = "SELECT usuario_id FROM chamados WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$chamado = $result->fetch_assoc();


// Verifica se o usuário tem permissão para editar o chamado
if ($chamado['usuario_id'] != $_SESSION['user_id']) {
    $_SESSION['error_message'] = "Você não tem permissão para ver este chamado.";
    header('Location: dashboard.php');
    exit();
}

$stmt->close();
$conn->close();
?>

<div class="container mt-5 card">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h2 class="text-center">Histórico de Alterações do Chamado</h2>
            <div> <!--class="table-responsive"-->
                <table id="tabelaHistorico" class="table table-striped align-middle bg-white">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tipo de Problema Anterior</th>
                        <th>Tipo de Problema Novo</th>
                        <th>Localização Anterior</th>
                        <th>Localização Nova</th>
                        <th>Descrição Anterior</th>
                        <th>Descrição Nova</th>
                        <th>Descrição Solução Anterior</th>
                        <th>Descrição Solução Nova</th>
                        <th>Status Anterior</th>
                        <th>Status Novo</th>
                        <th>Data de Alteração</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($historico as $alteracao): ?>
                        <tr>
                            <td><?php echo $alteracao['id']; ?></td>
                            <td><?php echo $alteracao['tipo_problema_anterior']; ?></td>
                            <td><?php echo $alteracao['tipo_problema_novo']; ?></td>
                            <td><?php echo $alteracao['localizacao_anterior']; ?></td>
                            <td><?php echo $alteracao['localizacao_nova']; ?></td>
                            <td><?php echo $alteracao['descricao_anterior']; ?></td>
                            <td><?php echo $alteracao['descricao_nova']; ?></td>
                            <td><?php echo $alteracao['descricao_solucao_anterior']; ?></td>
                            <td><?php echo $alteracao['descricao_solucao_nova']; ?></td>
                            <td><?php echo $alteracao['status_anterior']; ?></td>
                            <td><?php echo $alteracao['status_novo']; ?></td>
                            <td><?php echo $alteracao['data_alteracao']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div class="table-responsive">
        </div>
    </div>
    <div class="text-center pb-2">
        <a href="view_ticket.php?id=<?php echo $id; ?>" class="btn btn-secondary">Voltar</a>
    </div>
</div>

<script src="http://code.jquery.com/jquery-3.5.1.js"></script>
<script src="http://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="http://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
<script src="http://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#tabelaHistorico').DataTable({
            "responsive": true,
            "autoWidth": true,
            "language": {
                "url": "http://cdn.datatables.net/plug-ins/1.10.21/i18n/Portuguese-Brasil.json"
            },
        });
    });
</script>

<?php
include('footer.php');
?>
