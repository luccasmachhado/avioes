<?php
require_once(__DIR__ . '/../config/database.php');
$pdo = getConnection();

$idOusuario = $_SESSION['usuario']['id'];

$pegarPassagensComp = $pdo->prepare('SELECT * FROM passagem WHERE idOusuario = :idOusuario AND compra = 1 AND compra_def = 1');
$pegarPassagensComp->bindParam(':idOusuario', $idOusuario, PDO::PARAM_INT);
$pegarPassagensComp->execute();

$voosUsuario = [];

while($passagens = $pegarPassagensComp->fetch(PDO::FETCH_ASSOC)){
    $idOvoo = $passagens['idOvoo'];
    $idPassagem = $passagens['id'];
    
    $vooStmt = $pdo->prepare('SELECT * FROM voo WHERE id = :idOvoo');
    $vooStmt->bindParam(':idOvoo', $idOvoo, PDO::PARAM_INT);
    $vooStmt->execute();
    $voo = $vooStmt->fetch(PDO::FETCH_ASSOC);
    
    $id_cidade = $voo['id_cidade'];
    $linha_aera_id = $voo['linha_aerea_id'];
                
    $pegaCidade = $pdo->prepare('SELECT * FROM cidade WHERE id = :id_cidade ');
    $pegaCidade->bindParam(':id_cidade', $id_cidade);
    $pegaCidade->execute();
    $cidade = $pegaCidade->fetch(PDO::FETCH_ASSOC);

    $pegaLinha = $pdo->prepare('SELECT * FROM linha_aerea WHERE id = :linha_aerea_id ');
    $pegaLinha->bindParam(':linha_aerea_id', $linha_aera_id);
    $pegaLinha->execute();
    $linha = $pegaLinha->fetch(PDO::FETCH_ASSOC);
    
    $pegaPassagemEsp = $pdo->prepare('SELECT * FROM passagem WHERE id = :idPassagem ');
    $pegaPassagemEsp->bindParam(':idPassagem', $idPassagem);
    $pegaPassagemEsp->execute();
    $passagemDeAgora = $pegaPassagemEsp->fetch(PDO::FETCH_ASSOC);
                
    if ($voo && $cidade && $linha) {
        $voosUsuario[] = [
        'voo' => $voo,
        'cidade' => $cidade,
        'linha_aerea' => $linha,
        'passagem' => $passagemDeAgora
        ];
    }

}

?>