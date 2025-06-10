<?php

require_once  '../config/database.php';

$pdo = getConnection();


if(isset(
    $_POST['nome_completo'],
    $_POST['cpf'],
    $_POST['sexo'],
    $_POST['rg'],
    $_POST['email'],
    $_POST['senha'],
    $_POST['data_nascimento']
)){
    $nome_completo = $_POST['nome_completo'];
    $cpf = $_POST['cpf'];
    $rg = $_POST['rg'];
    $sexo = $_POST['sexo'];
    $portador_deficiencia = isset($_POST['portador_deficiencia']) ? $_POST['portador_deficiencia'] : 0;
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $data_nascimento = $_POST['data_nascimento'];

    try{
        $stmt = $pdo->prepare('INSERT INTO usuario (nome_completo, cpf, email, senha, sexo, data_nascimento) VALUES (:nome_completo, :cpf, :email, :senha, :sexo, :data_nascimento)');

        $stmt->bindParam(':nome_completo',$nome_completo);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam(':sexo', $sexo);
        $stmt->bindParam(':data_nascimento', $data_nascimento);
        $stmt->execute();

        $id_usuario = $pdo->lastInsertId();        
        $stmtp = $pdo->prepare('INSERT INTO passageiro (usuario_id, rg, nome_completo, data_nascimento, sexo, portador_deficiencia, hierarquia) VALUES (:usuario_id, :rg, :nome_completo, :data_nascimento, :sexo, :portador_deficiencia, :hierarquia)');

        $stmtp->bindParam(':usuario_id', $id_usuario);
        $stmtp->bindParam(':nome_completo',$nome_completo);
        $stmtp->bindParam(':data_nascimento', $data_nascimento);
        $stmtp->bindParam(':rg', $rg);
        $stmtp->bindParam(':sexo', $sexo);
        $stmtp->bindParam(':portador_deficiencia', $portador_deficiencia);
        $hierarquia = "adm";
        $stmtp->bindParam(':hierarquia', $hierarquia);

        $stmtp-> execute();

        header('Location: ../../frontend/login.html?msg=sucesso');
        exit;

    } catch(PDOException $e){
        echo json_encode(['error' => 'Erro ao cadastrar'. $e->getMessage()]);
    }
}else{
    echo json_encode(["error" => "Os atributos \" Nome Completo \", \" Cpf \", \" Email \", \" Senha \", \" Data de Nascimento \" são igualmente necessários. "]);
}
?>