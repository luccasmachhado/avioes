<?php

function getConnection(){

    $host = 'localhost';
    $db = 'sistema_passagens';
    $user = 'root';
    $pass = '';
    $charset = 'utf8';

    $dns = "mysql:host=$host;dbname=$db;charset=$charset";

    try{
        $pdo = new PDO($dns, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }catch(Exception $e){
        echo json_encode(["erro" => "Error de Conexão: ". $e->getMessage()]);
        exit;
    }
}

?>