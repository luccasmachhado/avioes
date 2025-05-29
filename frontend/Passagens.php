<?php
    require_once(__DIR__ . '/../server/voo/get_voo.php');
    require_once(__DIR__ . '/../server/passagem/post_passagem.php');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passagens</title>
    <link rel="stylesheet" href="passgstyle.css">
    <script>
            window.onload = function(){
            const urlParams = new URLSearchParams(window.location.search);
            if(urlParams.get('msg') === 'compra_sucesso'){
                alert("Cadastrado com sucesso! Faça seu Login!");
            }}
    </script>
</head>
<body>
    <header>
       <nav id="menu">
        <a class="opc" href="Passagens.html">Passagens</a>
        <a class="opc" href="#">Viagens</a>
        <a class="opc" href="#">Sobre</a>
        <a class="opc" href="login.html">Login</a>
        </nav>
    </header>
    <main>
        <form action="#" method="post">
                <div class="search">
                <label for="pesq"></label>
                <input type="text"  placeholder="Digite seu Destino" id="pesq">
                <button type="submit">
                    <picture>
                        <img src="Imagens/icon-search.svg" alt="">
                    </picture>
                </button>
                </div>
        </form>
        <div class="destopc">
            <?php
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<div class='butdest'>
                            <form type='submit' method='post'>
                            <label><b>".$row['destino']."</b></label>
                            <input type='radio' name='id' value='".$row['id']."'><br>
                            <h2>".$row['preco']."</h1>
                            <h3> Assentos Disponíveis: ".$row['assentos_disponiveis']."</h3>
                            <h3> Aeroporto Internacional do ".$row['origem']."</h1>
                            <h3>Partida: ".$row['data_partida']."</h3>
                            <h3>Chegada: ".$row['data_chegada']."</h3>   
                                <input type='submit' value='Comprar' id='botao".$row['id']."'>
                            </form>
                            </div>";
                    }
            ?>
        </div>
    </main>
</body>
</html>