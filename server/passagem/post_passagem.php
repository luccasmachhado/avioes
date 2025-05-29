<?php
require_once(__DIR__ . '/../config/database.php');
$pdo = getConnection();

if(isset( $_POST['id'])){
    $id = $_POST['id'];
    try{
        $stmt = $pdo->prepare('INSERT INTO passagem (idOvoo) VALUES (:id)');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        header('Location: ../../frontend/Passagens.php?msg=compra_sucesso');
        exit;
    }catch(PDOException $e){
        echo json_encode(['error' => 'Erro ao cadastrar'. $e->getMessage()]);
    }
}
?>