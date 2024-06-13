<?php
include('header.php');
include('con_bd.php');

// Inicializa a variável de chamados
$chamados = [];

// Verifica o tipo de usuário logado
$user_id = $_SESSION['user_id'];
$user_tipo = $_SESSION['user_tipo'];

if ($user_tipo == 1) {
    // Se o usuário for de nível 1, filtra os chamados pelo ID do usuário logado
    $sql = "SELECT c.id, c.usuario_id, c.tipo_problema, c.localizacao, c.status, u.nome AS usuario_nome, a.id AS avaliacao_id 
            FROM chamados c 
            JOIN usuarios u ON c.usuario_id = u.id
            LEFT JOIN avaliacoes a ON c.id = a.chamado_id
            WHERE c.usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
} else {
    // Caso contrário, lista todos os chamados
    $sql = "SELECT c.id, c.usuario_id, c.tipo_problema, c.localizacao, c.status, u.nome AS usuario_nome, a.id AS avaliacao_id 
            FROM chamados c 
            JOIN usuarios u ON c.usuario_id = u.id
            LEFT JOIN avaliacoes a ON c.id = a.chamado_id";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $chamados[] = $row;
    }
}

$stmt->close();
$conn->close();
?>

<div class="container">
    <div class="row justify-content-center card">
        <div class="col-12">
            <h2 class="text-center mb-4">Chamados Registrados</h2>
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success" role="alert" id="successMessage">
                    <?php echo $_SESSION['success_message']; ?>
                    <?php unset($_SESSION['success_message']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger" role="alert" id="errorMessage">
                    <?php echo $_SESSION['error_message']; ?>
                    <?php unset($_SESSION['error_message']); ?>
                </div>
            <?php endif; ?>

            <script>
                setTimeout(function() {
                    var successMessage = document.getElementById('successMessage');
                    var errorMessage = document.getElementById('errorMessage');
                    if (successMessage) {
                        successMessage.style.display = 'none';
                    }
                    if (errorMessage) {
                        errorMessage.style.display = 'none';
                    }
                }, 5000);
            </script>
            <div> <!--class="table-responsive"-->
                <table id="tabelaChamados" class="table table-striped align-middle bg-white">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuário</th>
                        <th>Tipo de Problema</th>
                        <th>Localização</th>
                        <th>Status</th>
                        <th>Ações</th>
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
                            <td>
                                <a class="btn btn-outline-primary" href="view_ticket.php?id=<?php echo $chamado['id']; ?>">Ver</a>
                                <?php if($chamado['usuario_id'] == $user_id){ ?>
                                    <?php if(($chamado['status'] == "resolvido" || $chamado['status'] == "encerrado") && !$chamado['avaliacao_id']){ ?>
                                        <a type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal" data-id="<?php echo $chamado['id']; ?>">Avaliar</a>
                                    <?php } ?>
                                    <?php if(($chamado['status'] == "resolvido" || $chamado['status'] == "encerrado") && $chamado['avaliacao_id']){ ?>
                                        <a type="button" class="btn btn-outline-success disabled" data-bs-toggle="modal" data-bs-target="#exampleModal" data-id="<?php echo $chamado['id']; ?>">Avaliado</a>
                                    <?php }
                                } ?>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div class="table-responsive">
        </div>
    </div>
</div>

<!-- Modal de Avaliação -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="avaliar_chamado.php">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Avaliação do Chamado</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="chamado_id" id="chamado_id">
                    <div class="mb-3">
                        <label for="avaliacao" class="form-label">Avaliação</label>
                        <select class="form-select" id="avaliacao" name="avaliacao" required>
                            <option value="1">1 - Muito Insatisfeito</option>
                            <option value="2">2 - Insatisfeito</option>
                            <option value="3">3 - Neutro</option>
                            <option value="4">4 - Satisfeito</option>
                            <option value="5">5 - Muito Satisfeito</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="comentario" class="form-label">Comentário</label>
                        <textarea class="form-control" id="comentario" name="comentario" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">Salvar Avaliação</button>
                </div>
            </form>
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

        $('#exampleModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var chamadoId = button.data('id'); // Extract info from data-* attributes
            var modal = $(this);
            modal.find('.modal-body #chamado_id').val(chamadoId);
        });
    });
</script>

<?php
include('footer.php');
?>
