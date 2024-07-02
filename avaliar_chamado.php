<?php
session_start();
include('con_bd.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['chamado_id'])) {
    $chamado_id = $_POST['chamado_id'];
    $avaliacao = $_POST['avaliacao'];
    $comentario = $_POST['comentario'];
    $usuario_id = $_SESSION['user_id'];


    $sql = "INSERT INTO avaliacoes (chamado_id, usuario_id, avaliacao, comentario, data_avaliacao) 
            VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iiis', $chamado_id, $usuario_id, $avaliacao, $comentario);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Avaliação salva com sucesso!";
    } else {
        $_SESSION['error_message'] = "Erro ao salvar a avaliação: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    header('Location: dashboard.php');
    exit();
} else {
    $_SESSION['error_message'] = "Dados inválidos.";
    header('Location: dashboard.php');
    exit();
}
?>
