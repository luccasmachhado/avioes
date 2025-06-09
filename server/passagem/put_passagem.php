<?php
    require_once(__DIR__ . '/../config/database.php');
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
    
    $pdo = getConnection();
    
    if(isset( $_POST['comprar'])){
    $idOusuario = $_SESSION['usuario']['id'];

        try{
            $stmtb = $pdo->prepare('SELECT * FROM passagem WHERE idOusuario = :idOusuario AND compra = 0');
            $stmtb->bindParam(':idOusuario', $idOusuario);
            $stmtb->execute();

            $stmta = $pdo->prepare('UPDATE passagem SET compra = 1 WHERE idOusuario = :idOusuario AND compra = 0');
            $stmta->bindParam(':idOusuario', $idOusuario, PDO::PARAM_INT);
            $stmta->execute();
            
            while($row = $stmtb->fetch(PDO::FETCH_ASSOC)) {
            $idOvoo = $row['idOvoo'];
            $stmtc = $pdo->prepare('UPDATE voo SET assentos_disponiveis = assentos_disponiveis - 1 WHERE id = :idOvoo');
            $stmtc->bindParam(':idOvoo', $idOvoo);
            $stmtc->execute();
            }

            header('Location: http://localhost/skyline/frontend/index.php?mensagem=compra_sucesso');
            exit;
        
        }catch(PDOException $e){
            echo json_encode(['error' => 'Erro ao cadastrar'. $e->getMessage()]);
        }

    }
?>