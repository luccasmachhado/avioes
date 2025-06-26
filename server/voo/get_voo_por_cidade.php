<?php
    require_once (__DIR__. '/../config/database.php');
    $pdo = getConnection();

    $nv = $pdo->query('SELECT * from voo WHERE id_cidade = 4 LIMIT 1');

    $roma = $pdo->query('SELECT * from voo WHERE id_cidade = 2 LIMIT 1');

    $paris = $pdo->query('SELECT * from voo WHERE id_cidade = 3 LIMIT 1');

    $bruxelas = $pdo->query('SELECT * from voo WHERE id_cidade = 5 LIMIT 1');
    
    $todosVoos = [];

    while ($row = $nv->fetch(PDO::FETCH_ASSOC)) {
        $todosVoos[] = $row;
    }

    while ($row = $roma->fetch(PDO::FETCH_ASSOC)) {
        $todosVoos[] = $row;
    }

    while ($row = $paris->fetch(PDO::FETCH_ASSOC)) {
        $todosVoos[] = $row;
    }

    while ($row = $bruxelas->fetch(PDO::FETCH_ASSOC)) {
        $todosVoos[] = $row;
    }

    foreach($todosVoos as &$row){
        $id_cidade = $row['id_cidade'];
        $stmtb = $pdo->prepare('SELECT * FROM cidade WHERE id = :id_cidade ');
        $stmtb->bindParam(':id_cidade', $id_cidade);
        $stmtb->execute();
            if($stmtb){       
                $row['cidade'] = $stmtb->fetch(PDO::FETCH_ASSOC);
            }
    }

    $voosMapeadosPorId = [];
    foreach ($todosVoos as $voo) {
        $voosMapeadosPorId[$voo['id']] = $voo;
    }
    $todosVoos = array_values($voosMapeadosPorId);
?>