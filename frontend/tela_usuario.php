<?php 
session_start();
require_once(__DIR__ . '/../server/usuario/logout.php');
 if (
    !isset($_SESSION['usuario']) ||
    !isset($_SESSION['usuario']['cpf']) ||
    !isset($_SESSION['usuario']['senha']) ||
    !isset($_SESSION['usuario']['id'])
    ) {
    header('Location: http://localhost/skyline/frontend/login.html?msg=erro_addCar');
    session_unset(); // limpa toda a sessão
    exit;
}
$logado = $_SESSION['usuario']['cpf'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="TelaCompraPassagens.css" />
</head>
<body>
  <div className="container">
    <header className="header">
    </header>
    <main className="main">
        <div className="user-info">
        <?php echo "<div><strong>Bem vindo senhor(a) ".$_SESSION['usuario']['nome']."</strong></div>";?>
        <form action="" method="post">
        <input type='hidden' name='logout' value='htmlspecialchars(logout)'>
        <button type='submit'>Logout</button>
        </form>
    </main>
  </div>
</body>
