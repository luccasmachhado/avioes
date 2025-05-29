<?php
    require_once (__DIR__. '/../config/database.php');
    $pdo = getConnection();

    $stmt = $pdo->query('SELECT * from voo');   
?>