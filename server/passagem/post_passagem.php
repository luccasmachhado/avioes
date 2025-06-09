<?php
    require_once(__DIR__ . '/../voo/get_voo.php');
    require_once(__DIR__ . '/../passagem/post_passagem.php');
    session_start();

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

        if(isset($_POST['id']) && isset($_POST['quant'])){
            $idOvoo = (int) $_POST['id'];
            $quant = (int) $_POST['quant'];
            $idOusuario = $_SESSION['usuario']['id'];
            try{
                for($i = 0; $i < $quant; $i++){
                    $stmt = $pdo->prepare('INSERT INTO passagem (idOvoo, idOusuario, compra) VALUES (:idOvoo, :idOusuario, 0)');
                    $stmt->bindParam(':idOvoo', $idOvoo);
                    $stmt->bindParam(':idOusuario', $idOusuario);
                    $stmt->execute();
                }
                header('Location: http://localhost/skyline/frontend/index.php?mensagem=add_car_sucesso');
                exit;
            }catch(PDOException $e){
                echo json_encode(['error' => 'Erro ao cadastrar'. $e->getMessage()]);
            }
        }
?>