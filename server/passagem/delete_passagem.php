<?php
    require_once(__DIR__ . '/../config/database.php');
    session_start();
    if (
    !isset($_SESSION['usuario']) ||
    !isset($_SESSION['usuario']['cpf']) ||
    !isset($_SESSION['usuario']['senha']) ||
    !isset($_SESSION['usuario']['id'])
    ) {
    header('Location: http://localhost/skyline/frontend/login.html?msg=erro_nao_logado');
    session_unset(); // limpa toda a sessão
    exit;
    }

    if(isset( $_POST['remover'])){
    
        $pdo = getConnection();
        $idOusuario = $_SESSION['usuario']['id'];
        $idPassagem = $_POST['remover'];

        try{
            $stmta = $pdo->prepare('DELETE FROM passagem WHERE id = :idPassagem');
            $stmta->bindParam(':idPassagem', $idPassagem, PDO::PARAM_INT);
            $stmta->execute();

            header('Location: http://localhost/skyline/frontend/carrinho.php?mensagem=passagem_deletada');
            exit;

        
        }catch(PDOException $e){
            echo json_encode(['error' => 'Erro ao cadastrar'. $e->getMessage()]);
        }
    }
?>