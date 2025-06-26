<?php
function verificar_passagem_car($idOusuario, $idOvoo){
    require_once(__DIR__ . '/../config/database.php');
    
    $pdo = getConnection();

    $verificacao = $pdo->prepare('SELECT * FROM passagem WHERE idOusuario = :idOusuario AND compra = 0 AND idOvoo = :idOvoo');
    $verificacao->bindParam(':idOusuario', $idOusuario, PDO::PARAM_INT);
    $verificacao->bindParam('idOvoo', $idOvoo, PDO::PARAM_INT);
    $verificacao->execute();
    
    return $verificacao;
}
function verificar_passagem_check($idOusuario, $idOvoo){
    require_once(__DIR__ . '/../config/database.php');
    
    $pdo = getConnection();

    $verificacao = $pdo->prepare('SELECT * FROM passagem WHERE idOusuario = :idOusuario AND compra = 1 AND idOvoo = :idOvoo');
    $verificacao->bindParam(':idOusuario', $idOusuario, PDO::PARAM_INT);
    $verificacao->bindParam('idOvoo', $idOvoo, PDO::PARAM_INT);
    $verificacao->execute();
    
    return $verificacao;
}
function verificar_passagem_quant($idOusuario){
    require_once(__DIR__ . '/../config/database.php');
    
    $pdo = getConnection();

    $verificacao = $pdo->prepare('SELECT * FROM passagem WHERE idOusuario = :idOusuario AND compra = 1 AND compra_def = 0');
    $verificacao->bindParam(':idOusuario', $idOusuario, PDO::PARAM_INT);
    $verificacao->execute();

    $passagens = [];

    while($row = $verificacao->fetch(PDO::FETCH_ASSOC)) {
        $passagens [] = $row;
    }

    $quantidade = count($passagens);
    
    return $quantidade;
}
?>