<?php 
    session_start();    
    require_once(__DIR__ . '/../config/database.php');
    
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
            $_SESSION['usuario'] = [   
                'id' => $usuario['id'],
                'cpf' => $usuario['cpf'],
                'senha' => $usuario['senha'],
                'nome' => $usuario['nome_completo']
            ];
            header('Location: ../../frontend/tela_usuario.php');
            exit;
        }else{
            echo json_encode(['error' => 'CPF ou Senha invalidos']);
            header('Location: ../../frontend/login.html?msg=falha');
        }

    } catch(PDOException $e){

        echo json_encode(['error' => $e->getMessage()]);
        unset($_SESSION['usuario']['cpf']);
        unset($_SESSION['usuario']['senha']);
        unset($_SESSION['usuario']['id']);
        unset($_SESSION['usuario']['nome']);
    }
}

?>