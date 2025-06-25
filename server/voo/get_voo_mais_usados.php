<?php
    require_once (__DIR__. '/../config/database.php');
    $pdo = getConnection();
    $stmt = $pdo->prepare('SELECT * FROM voo ORDER BY assentos_disponiveis ASC, id ASC LIMIT 3');   
    $stmt->execute();
    $todosVoos = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $todosVoos[] = $row;

    }

    foreach ($todosVoos as $i => $row) {
        $id_cidade = $row['id_cidade'];
        $stmtb = $pdo->prepare('SELECT * FROM cidade WHERE id = :id_cidade');
        $stmtb->bindParam(':id_cidade', $id_cidade);
        $stmtb->execute();

        $cidade = $stmtb->fetch(PDO::FETCH_ASSOC);
        $todosVoos[$i]['cidade'] = $cidade ?: null;
    }

?>