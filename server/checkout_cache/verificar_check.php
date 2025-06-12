<?php
    require_once(__DIR__ . '/../config/database.php');

    function verificarCheck($idOusuario){
    $pdo = getConnection();
    $checkoutCach = $pdo->prepare('SELECT * FROM checkout_cache WHERE id_usuario = :id_usuario');
    $checkoutCach->bindParam(':id_usuario', $idOusuario);
    $checkoutCach->execute();
    $checkoutEmAndamento = $checkoutCach->fetch(PDO::FETCH_ASSOC);

    return $checkoutEmAndamento;
    
    }
?>