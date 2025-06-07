<?php
    require_once (__DIR__. '/../config/database.php');
    $pdo = getConnection();

    if(isset($_POST['pesq'])){
        try{
            $destino = $_POST['pesq'];
            $stmta = $pdo->prepare('SELECT * FROM voo WHERE destino = :destino');
            $stmta->bindParam(':destino', $destino);
            $stmta->execute();

        }catch(PDOException $e){
                echo json_encode(['error' => 'Erro ao cadastrar'. $e->getMessage()]);
        }
    }
?>