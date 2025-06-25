<?php
    require_once(__DIR__ . '/../config/database.php');
    
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    }
    
    $pdo = getConnection();
    $idOusuario = $_SESSION['usuario']['id'];

    if(isset( $_POST['deleta_cach'])){
      $deOndeVeio = $_POST['deleta_cach'];

    $deOnde = $_POST['deleta_cach'];
    $checkoutCachDelete = $pdo->prepare('DELETE FROM checkout_cache WHERE id_usuario = :id_usuario');
    $checkoutCachDelete->bindParam(':id_usuario', $idOusuario);
    $checkoutCachDelete->execute();

    if($deOndeVeio == "compra"){

    $finalizarCompra = $pdo->prepare('UPDATE passagem SET compra_def = 1 WHERE idOusuario = :idOusuario AND compra = 1');
    $finalizarCompra->bindParam(':idOusuario', $idOusuario, PDO::PARAM_INT);
    $finalizarCompra->execute();

    header('Location: http://localhost/skyline/frontend/index.php?mensagem=compra_finalizada');
    exit;
    }else{
      $voosReajusteAssent = $pdo->prepare('SELECT * FROM passagem WHERE idOusuario = :idOusuario AND compra = 1');
      $voosReajusteAssent->bindParam(':idOusuario', $idOusuario, PDO::PARAM_INT);
      $voosReajusteAssent->execute();
      
      $deletaCompra = $pdo->prepare('DELETE FROM passagem WHERE idOusuario = :idOusuario AND compra = 1');
      $deletaCompra->bindParam(':idOusuario', $idOusuario, PDO::PARAM_INT);
      $deletaCompra->execute();

      while($row = $voosReajusteAssent->fetch(PDO::FETCH_ASSOC)) {
        $idOvoo=$row['idOvoo'];

        $voosParaReajust = $pdo->prepare('UPDATE voo SET assentos_disponiveis = assentos_disponiveis + 1 WHERE id = :idOvoo');
        $voosParaReajust->bindParam(':idOvoo', $idOvoo, PDO::PARAM_INT);
        $voosParaReajust->execute();
      
      }

      header('Location: http://localhost/skyline/frontend/index.php?mensagem=compra_cancelada');
      exit;
    }

    }
?>