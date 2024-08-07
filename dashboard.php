<?php
include('header.php');
include('con_bd.php');


$chamados = [];

$user_id = $_SESSION['user_id'];
$user_tipo = $_SESSION['user_tipo'];

if ($user_tipo == 1) {
    $sql = "SELECT c.id, c.usuario_id, c.tipo_problema, c.localizacao, c.status, u.nome AS usuario_nome, a.id AS avaliacao_id 
            FROM chamados c 
            JOIN usuarios u ON c.usuario_id = u.id
            LEFT JOIN avaliacoes a ON c.id = a.chamado_id
            WHERE c.usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
} else {
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
            <h2 class="text-center pt-2 mb-4">Chamados Registrados</h2>
            <hr>
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
            <div>
                <table id="tabelaChamados" class="table table-striped align-middle bg-white">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuário</th>
                        <th>Tipo de Problema</th>
                        <th>Localização</th>
                        <th>Status</th>
                        <th>Ações</th>
                        <th>Avaliação</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($chamados as $chamado): ?>
                        <tr>
                            <td><?php echo $chamado['id']; ?></td>
                            <td><?php echo $chamado['usuario_nome']; ?></td>
                            <td><?php echo $chamado['tipo_problema']; ?></td>
                            <td><?php echo $chamado['localizacao']; ?></td>
                            <td>
                                <?php
                                switch ($chamado['status']) {
                                    case 'aberto':
                                        ?> <span class="badge rounded-pill text-bg-danger">ABERTO</span> <?php
                                        break;
                                    case 'em_andamento':
                                        ?> <span class="badge rounded-pill text-bg-primary">EM ANDAMENTO</span> <?php
                                        break;
                                    case 'resolvido':
                                        ?> <span class="badge rounded-pill text-bg-warning">RESOLVIDO</span> <?php
                                        break;
                                    case 'encerrado':
                                        ?> <span class="badge rounded-pill text-bg-success">ENCERRADO</span> <?php
                                        break;
                                    default:
                                        ?> <span class="badge rounded-pill text-bg-primary"></span> <?php
                                        break;
                                }
                                ?>
                            </td>
                            <td>
                                <a class="btn btn-outline-primary" href="view_ticket.php?id=<?php echo $chamado['id']; ?>">Ver</a>
                            </td>
                            <td>
                                <?php if($chamado['usuario_id'] == $user_id){ ?>
                                    <?php if(($chamado['status'] == "resolvido" || $chamado['status'] == "encerrado") && !$chamado['avaliacao_id']){ ?>
                                        <a type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal" data-id="<?php echo $chamado['id']; ?>">Avaliar</a>
                                    <?php } ?>
                                    <?php if(($chamado['status'] == "resolvido" || $chamado['status'] == "encerrado") && $chamado['avaliacao_id']){ ?>
                                        <a type="button" class="btn btn-outline-success disabled">Avaliado</a>
                                    <?php }
                                } elseif (($chamado['status'] == "resolvido" || $chamado['status'] == "encerrado") && !$chamado['avaliacao_id']){?>
                                    <a type="button" class="btn btn-outline-dark disabled">Não avaliado</a>
                                <?php } elseif(($chamado['status'] == "resolvido" || $chamado['status'] == "encerrado") && $chamado['avaliacao_id']){ ?>
                                    <a type="button" class="btn btn-outline-success disabled">Avaliado</a>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div class="table-responsive">
        </div>
    </div>
</div>

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

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.bootstrap5.js"></script>
<script>
    $(document).ready(function() {
        $('#tabelaChamados').DataTable({
            "responsive": true,
            "autoWidth": true,
            "paging":   false,
            "order": [[4, 'asc']],
            "rowReorder": {
                selector: 'td:nth-child(4)'
            },
            "columnDefs": [
                { targets: 0, responsivePriority: 6},
                { targets: 1, responsivePriority: 5},
                { targets: 2, responsivePriority: 4},
                { targets: 3, responsivePriority: 3},
                { targets: 4, responsivePriority: 2},
                { targets: 5, responsivePriority: 1},
                { targets: 6, responsivePriority: 7},

            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Portuguese-Brasil.json"
            },
        });

        $('#exampleModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var chamadoId = button.data('id');
            var modal = $(this);
            modal.find('.modal-body #chamado_id').val(chamadoId);
        });
    });
</script>

<?php
include('footer.php');
?>
