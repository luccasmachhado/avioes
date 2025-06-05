<?php
    if(isset($_POST['logout'])){
        header('Location: http://localhost/skyline/frontend/login.html?msg=logout_sucesso');
        session_unset(); // limpa toda a sessão
        exit;
    }
?>