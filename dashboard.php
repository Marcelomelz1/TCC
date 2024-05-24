<?php
include('header.php');
include('con_bd.php');


// Consulta para buscar chamados com base nos filtros
$sql = "SELECT c.id, c.tipo_problema, c.localizacao, c.status, u.nome AS usuario_nome 
        FROM chamados c 
        JOIN usuarios u ON c.usuario_id = u.id";
$result = $conn->query($sql);

$chamados = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $chamados[] = $row;
    }
}
$conn->close();
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <h2 class="text-center mb-4">Chamados Registrados</h2>
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success" role="alert" id="successMessage">
                    <?php echo $_SESSION['success_message']; ?>
                    <?php unset($_SESSION['success_message']); ?>
                </div>
                <script>
                    setTimeout(function() {
                        document.getElementById('successMessage').style.display = 'none';
                    }, 5000);
                </script>
            <?php endif; ?>
            <div> <!--class="table-responsive"-->
                <table id="tabelaChamados" class="table table-striped align-middle bg-white">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuário</th>
                        <th>Tipo de Problema</th>
                        <th>Localização</th>
                        <th>Status</th>
                        <?php if ($_SESSION['user_tipo'] != 1): ?>
                            <th>Ações</th>
                        <?php endif; ?>

                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($chamados as $chamado): ?>
                        <tr>
                            <td><?php echo $chamado['id']; ?></td>
                            <td><?php echo $chamado['usuario_nome']; ?></td>
                            <td><?php echo $chamado['tipo_problema']; ?></td>
                            <td><?php echo $chamado['localizacao']; ?></td>
                            <td><?php echo $chamado['status']; ?></td>
                            <?php if ($_SESSION['user_tipo'] != 1): ?>
                                <td><a href="edit_ticket.php?id=<?php echo $chamado['id']; ?>">Editar</a></td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div class="table-responsive">
        </div>
    </div>
</div>

<script src="http://code.jquery.com/jquery-3.5.1.js"></script>
<script src="http://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="http://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
<script src="http://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#tabelaChamados').DataTable({
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
