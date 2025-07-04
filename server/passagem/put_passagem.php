<?php
    require_once(__DIR__ . '/../config/database.php');
    require_once(__DIR__ . '/../checkout_cache/verificar_check.php');
    if (session_status() === PHP_SESSION_NONE) {
    session_start();
    }
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
    
    if(isset( $_POST['comprar'])){
    $idOusuario = $_SESSION['usuario']['id'];

        try{
            
            $checkoutEmAndamento = verificarCheck($idOusuario);
            
            if (!empty($checkoutEmAndamento)) {
            header('Location: http://localhost/skyline/frontend/carrinho.php?mensagem=checkoutJaEmAndamento');
            exit;
            }

            $stmtb = $pdo->prepare('SELECT * FROM passagem WHERE idOusuario = :idOusuario AND compra = 0');
            $stmtb->bindParam(':idOusuario', $idOusuario);
            $stmtb->execute();
            
            $ids_voos = [];
            $voosCarrinho = [];
            $qntPassagens = 0;
            while($row = $stmtb->fetch(PDO::FETCH_ASSOC)) {  
                    $ids_voos [] = $row['idOvoo'];     
                    $idOvoo = $row['idOvoo'];
        
                    $stmtf = $pdo->prepare('SELECT * FROM voo WHERE id = :idOvoo');
                    $stmtf->bindParam(':idOvoo', $idOvoo);
                    $stmtf->execute();
                    $voo = $stmtf->fetch(PDO::FETCH_ASSOC);

                    if ($voo && $row) {
                        // Buscar cidade relacionada ao voo
                        $id_cidade = $voo['id_cidade'];
                        $id_linha_aerea = $voo['linha_aerea_id'];
                        
                        $stmtd = $pdo->prepare('SELECT * FROM cidade WHERE id = :id_cidade');
                        $stmtd->bindParam(':id_cidade', $id_cidade);
                        $stmtd->execute();
                        $cidade = $stmtd->fetch(PDO::FETCH_ASSOC);

                        $stmte = $pdo->prepare('SELECT * FROM linha_aerea WHERE id = :id_linha_aerea');
                        $stmte->bindParam(':id_linha_aerea', $id_linha_aerea);
                        $stmte->execute();
                        $linha_aerea = $stmte->fetch(PDO::FETCH_ASSOC);

                        $inserio_check = $pdo->prepare("INSERT INTO checkout_cache (id_usuario, voo_id, passagem_id, dados) VALUES (:id_usuario, :voo_id, :passagem_id, :dados)");


                        if ($cidade && $linha_aerea) {
                            $voo['cidade'] = $cidade;
                            $voo['linha_aerea'] = $linha_aerea;
                        }

                        // Monta estrutura com voo e passagem
                        $voosCarrinho[] = [
                            'voo' => $voo,
                            'passagem' => $row
                        ];
                        $inserio_check->execute([
                        ':id_usuario' => $idOusuario,
                        ':voo_id' => $voo['id'],
                        ':passagem_id' => $row['id'],
                        ':dados' => json_encode(['voo' => $voo, 'passagem' => $row])
                        ]);
                    }
                $qntPassagens++;
            }

            if (count(array_unique($ids_voos)) > 1) {
            header('Location: http://localhost/skyline/frontend/carrinho.php?mensagem=passagem_diferente');
            exit;
            }

            $DecrementarAssento = $pdo->prepare('UPDATE voo SET assentos_disponiveis = assentos_disponiveis - :qntPassagens WHERE id = :id_voo');
            $DecrementarAssento->bindParam(':qntPassagens', $qntPassagens);
            $DecrementarAssento->bindParam(':id_voo', $ids_voos[0], PDO::PARAM_INT);
            $DecrementarAssento->execute();

            $stmta = $pdo->prepare('UPDATE passagem SET compra = 1 WHERE idOusuario = :idOusuario AND compra = 0');
            $stmta->bindParam(':idOusuario', $idOusuario, PDO::PARAM_INT);
            $stmta->execute();
            
            $_SESSION['usuario']['voosCarrinho'] = $voosCarrinho;

            header('Location: http://localhost/skyline/frontend/novo_check.php');
            exit;
        
        }catch(PDOException $e){
            echo json_encode(['error' => 'Erro ao cadastrar'. $e->getMessage()]);
        }

    }
?>