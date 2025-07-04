<?php 
    if (session_status() === PHP_SESSION_NONE) {
    session_start();
    }    
    require_once(__DIR__ . '/../config/database.php');
    require_once(__DIR__ . '/../checkout_cache/checkout_cache_get.php');
    
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
            $voosCarrinho = get_checkout($usuario['id']);
            $_SESSION['usuario'] = [   
                'id' => $usuario['id'],
                'cpf' => $usuario['cpf'],
                'senha' => $usuario['senha'],
                'nome' => $usuario['nome_completo'],
                'rg' => $usuario['rg'],
                'sexo' => $usuario['sexo'],
                'nascimento' => $usuario['data_nascimento'],
                'pcd' => $usuario['portador_def'],
                'voosCarrinho' => $voosCarrinho
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