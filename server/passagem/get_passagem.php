<?php
    require_once(__DIR__ . '/../config/database.php');
    session_start();
    if (
    !isset($_SESSION['usuario']) ||
    !isset($_SESSION['usuario']['cpf']) ||
    !isset($_SESSION['usuario']['senha']) ||
    !isset($_SESSION['usuario']['id'])
    ) {
    header('Location: http://localhost/skyline/frontend/login.html?msg=erro_nao_logado');
    session_unset(); // limpa toda a sessão
    exit;
    }
    
    $pdo = getConnection();
    $idOusuario = $_SESSION['usuario']['id'];

        try{
            $stmta = $pdo->prepare('SELECT * FROM passagem WHERE idOusuario = :idOusuario AND compra = 0');
            $stmta->bindParam(':idOusuario', $idOusuario, PDO::PARAM_INT);
            $stmta->execute();

            $voosCarUsuario = [];
            while($passagens = $stmta->fetch(PDO::FETCH_ASSOC)){
                $idOvoo = $passagens['idOvoo'];
                $idPassagem = $passagens['id'];
                $stmtb = $pdo->prepare('SELECT * FROM voo WHERE id = :idOvoo');
                $stmtb->bindParam(':idOvoo', $idOvoo, PDO::PARAM_INT);
                $stmtb->execute();
                $voo = $stmtb->fetch(PDO::FETCH_ASSOC);
                
                $id_cidade = $voo['id_cidade'];
                $stmtc = $pdo->prepare('SELECT * FROM cidade WHERE id = :id_cidade ');
                $stmtc->bindParam(':id_cidade', $id_cidade);
                $stmtc->execute();
                $cidade = $stmtc->fetch(PDO::FETCH_ASSOC);
                
                if ($voo) {
                    $voo['idPassagem'] = $idPassagem;
                    $voo['cidade'] = $cidade;
                    $voosCarUsuario[] = $voo; // Adiciona o voo ao array acumulador
                }
            }

            $_SESSION['voos_car_usuario'] = $voosCarUsuario;
        
        }catch(PDOException $e){
            echo json_encode(['error' => 'Erro ao cadastrar'. $e->getMessage()]);
        }
?>