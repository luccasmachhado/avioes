<?php
    require_once (__DIR__. '/../config/database.php');
    $pdo = getConnection();

    $stmta = $pdo->query('SELECT * FROM linha_aerea');
    $stmta->execute(); 
    $linhas = []; 
    while($linha = $stmta->fetch(PDO::FETCH_ASSOC)){
        if ($linha) {
            $linhas[] = $linha; // Adiciona o voo ao array acumulador
        }
    } 
?>