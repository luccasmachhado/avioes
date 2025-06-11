<?php

require_once (__DIR__. '/../config/database.php');

function get_checkout($idOusuario){

    $pdo = getConnection();
    $pegaCheck = $pdo->prepare("SELECT dados FROM checkout_cache WHERE id_usuario = :id_usuario");
    $pegaCheck->execute([':id_usuario' => $idOusuario]);
    $dados = $pegaCheck->fetchAll(PDO::FETCH_COLUMN);
    $voosCarrinho = array_map(function($json) {
    return json_decode($json, true); // 'true' para retornar array associativo
    }, $dados);

    return $voosCarrinho;
}
?>