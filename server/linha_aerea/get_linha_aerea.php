<?php
    require_once (__DIR__. '/../config/database.php');
    $pdo = getConnection();

    $linhas_aereas = $pdo->query('SELECT * FROM linha_aerea');
    $linhas_aereas->execute(); 
    $linhas = []; 
    while($linha = $linhas_aereas->fetch(PDO::FETCH_ASSOC)){
        if ($linha) {
            $linhas[] = $linha; // Adiciona o voo ao array acumulador
        }
    } 
?>