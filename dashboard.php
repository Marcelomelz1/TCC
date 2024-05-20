<?php
include('header.php');
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Conexão com o banco de dados
$host = 'localhost';
$db = 'staff_assist_it';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);

// Verifica a conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
                <div> <!--class="table-responsive"-->
                    <table id="tabelaChamados" class="table table-striped align-middle bg-white">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuário</th>
                            <th>Tipo de Problema</th>
                            <th>Localização</th>
                            <th>Status</th>
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
