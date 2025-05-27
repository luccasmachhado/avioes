<?php 
session_start();
print_r($_SESSION);
if((isset($_SESSION['cpf']) == false) and (isset($_SESSION['senha']) == false)){
    header('Location: login.html');
    unset($_SESSION['cpf']);
    unset($_SESSION['senha']);
}
$logado = $_SESSION['cpf'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>