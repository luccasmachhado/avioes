<?php
    require_once (__DIR__. '/../config/database.php');
    $pdo = getConnection();

    $stmt = $pdo->query('SELECT * from voo');
    
    $todosVoos = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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