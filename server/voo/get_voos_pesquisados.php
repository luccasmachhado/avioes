<?php
    require_once (__DIR__. '/../config/database.php');
    $pdo = getConnection();

    if(isset($_POST['pesq'])){
        try{
            $destinoPesquisado = $_POST['pesq'];
            $stmtc = $pdo->prepare('SELECT * FROM voo');
            $stmtc->execute();

            $voos = [];

            while ($row = $stmtc->fetch(PDO::FETCH_ASSOC)) {
                $voos[] = $row;
            }

            foreach($voos as &$row){
                $id_cidade = $row['id_cidade'];
                $stmtd = $pdo->prepare('SELECT * FROM cidade WHERE id = :id_cidade ');
                $stmtd->bindParam(':id_cidade', $id_cidade);
                $stmtd->execute();
                    if($stmtd){       
                        $row['cidade'] = $stmtd->fetch(PDO::FETCH_ASSOC);
                    }
            }

            $voosPesquisa = [];

            foreach($voos as $voo){
                if( isset($voo['cidade']) 
                && $voo['cidade']['nome'] === $destinoPesquisado
                || $voo['destino'] === $destinoPesquisado
                || $voo['cidade']['pais'] === $destinoPesquisado){
                    $voosPesquisa [] = $voo;
                }
            }

        }catch(PDOException $e){
                echo json_encode(['error' => 'Erro ao cadastrar'. $e->getMessage()]);
        }
    }
?>