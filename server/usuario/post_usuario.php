<?php

require_once  '../config/database.php';

$pdo = getConnection();


if(isset(
    $_POST['nome_completo'],
    $_POST['cpf'],
    $_POST['rg'],
    $_POST['email'],
    $_POST['senha'],
    $_POST['data_nascimento']
)){
    $nome_completo = $_POST['nome_completo'];
    $cpf = $_POST['cpf'];
    $rg = $_POST['rg'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $data_nascimento = $_POST['data_nascimento'];

    try{
        $stmt = $pdo->prepare('INSERT INTO usuario (nome_completo, cpf, rg, email, senha, data_nascimento) VALUES (:nome_completo, :cpf, :rg, :email, :senha, :data_nascimento)');

        $stmt->bindParam(':nome_completo',$nome_completo);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':rg', $rg);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam(':data_nascimento', $data_nascimento);
        $stmt->execute();

        $id_usuario = $pdo->lastInsertId();        
    
        header('Location: ../../frontend/login.html?msg=sucesso');
        exit;

    } catch(PDOException $e){
        echo json_encode(['error' => 'Erro ao cadastrar'. $e->getMessage()]);
    }
}else{
    echo json_encode(["error" => "Os atributos \" Nome Completo \", \" Cpf \", \" Email \", \" Senha \", \" Data de Nascimento \" são igualmente necessários. "]);
}
?>