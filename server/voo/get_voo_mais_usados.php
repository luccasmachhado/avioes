<?php
    require_once (__DIR__. '/../config/database.php');
    $pdo = getConnection();
    $stmt = $pdo->query('SELECT * from voo');   
    
    $todosVoos = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $todosVoos[] = $row;
    }

    // Ordenar por assentos disponíveis (ordem crescente)
    usort($todosVoos, function ($a, $b) {
        return $a['assentos_disponiveis'] <=> $b['assentos_disponiveis'];
    });

    // Pegar os 3 primeiros (menores valores)
    $menores3Voos = array_slice($todosVoos, 0, 3);
?>