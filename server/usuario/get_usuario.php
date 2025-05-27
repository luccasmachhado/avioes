<?php 
    session_start();    
    require_once  '../config/database.php';
    
    $pdo = getConnection();

    if(isset(
    $_POST['cpf'],
    $_POST['senha']
)){
    $cpf = $_POST['cpf'];
    $senha = $_POST['senha'];

    try{
        
        $stmt = $pdo->prepare('SELECT * FROM usuario WHERE cpf = :cpf AND senha = :senha');

        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':senha', $senha);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if($usuario){
            $_SESSION['cpf'] = $cpf;
            $_SESSION['senha'] = $senha;
            header('Location: ../../frontend/tela_usuario.php');
            exit;
        }else{
            echo json_encode(['error' => 'CPF ou Senha invalidos']);
            header('Location: ../../frontend/login.html?msg=falha');
        }

    } catch(PDOException $e){

        echo json_encode(['error' => $e->getMessage()]);
        unset($_SESSION['cpf']);
        unset($_SESSION['senha']);
    }
}else{
    echo json_encode(["error" => "Preencha o Formulário com \" Cpf \", \" Senha \" para fazer login"]);
}

?>