<?php
    require_once (__DIR__. '/../config/database.php');
    $pdo = getConnection();
    $stmt = $pdo->query('SELECT * from voo ORDER BY assentos_disponiveis ASC LIMIT 3');   
    
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

?>