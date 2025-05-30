<?php
    require_once(__DIR__ . '/../voo/get_voo.php');
    require_once(__DIR__ . '/../passagem/post_passagem.php');
    session_start();
    print_r($_SESSION);
  if (
    !isset($_SESSION['usuario']) ||
    !isset($_SESSION['usuario']['cpf']) ||
    !isset($_SESSION['usuario']['senha']) ||
    !isset($_SESSION['usuario']['id'])
    ) {
    header('Location: http://localhost/skyline/frontend/login.html?msg=erro_addCar');
    session_unset(); // limpa toda a sessão
    exit;
} 
        require_once(__DIR__ . '/../config/database.php');
        $pdo = getConnection();

        if(isset( $_POST['id'])){
            $idOvoo = $_POST['id'];
            $idOusuario = $_SESSION['usuario']['id'];
            try{
                $stmt = $pdo->prepare('INSERT INTO passagem (idOvoo, idOusuario, compra) VALUES (:idOvoo, :idOusuario, 0)');
                $stmt->bindParam(':idOvoo', $idOvoo);
                $stmt->bindParam(':idOusuario', $idOusuario);
                $stmt->execute();

                $stmtC = $pdo->prepare('UPDATE voo SET assentos_disponiveis = assentos_disponiveis - 1 WHERE id = :idOvoo');
                $stmtC->bindParam(':idOvoo', $idOvoo);
                $stmtC->execute();

                header('Location: http://localhost/skyline/frontend/index.php?menssagem=compra_sucesso');
                exit;
            }catch(PDOException $e){
                echo json_encode(['error' => 'Erro ao cadastrar'. $e->getMessage()]);
            }
        }
?>