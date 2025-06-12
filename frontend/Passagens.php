<?php
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
    require_once(__DIR__ . '/../server/voo/get_voos_pesquisados.php');
    require_once(__DIR__ . '/../server/voo/get_voo.php');
    require_once(__DIR__ . '/../server/passagem/post_passagem.php');
    require_once(__DIR__ . '/../server/passagem/verifica_passagem.php');


    require_once (__DIR__. '/../server/config/database.php');
    $pdo = getConnection();

    $linhas_aereas = $pdo->query('SELECT * FROM linha_aerea');
    $linhas_aereas->execute(); 
    $linhas = []; 
    while($linha = $linhas_aereas->fetch(PDO::FETCH_ASSOC)){
        if ($linha) {
            $linhas[] = $linha; // Adiciona o voo ao array acumulador
        }
    } 
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passagens</title>
    <link rel="stylesheet" href="passagens.css">
    <script>
        window.onload = function(){
            const urlParams = new URLSearchParams(window.location.search);
            if(urlParams.get('mensagem') === 'passagem_diferente'){
                alert("Existem passagens de voos difentes no seu carrinho. Efetue a Compra para comprar de voos diferentes");
            }
        }
    </script>
</head>
<body>
    <header>
       <nav id="menu">
        <a class="opc" href="index.php">Home</a>
        <a class="opc" href="Passagens.php">Passagens</a>
        <a class="opc" href="viagens.html">Viagens</a>
        <a class="opc" href="Sobre.html">Sobre</a>
        <a class="opc" href="login.html">Login</a>
        </nav>
    </header>
    <main>
        <form type='submit' method='post'>
                <div class="search">
                <label for="pesq"></label>
                <input class="inputSear" type="text"  placeholder="Digite seu Destino" id="pesq" name="pesq"/>
                <button class="buttonSear" type="submit">
                    <picture>
                        <img src="Imagens/icon-search.svg" alt="">
                    </picture>
                </button>
                </div>
        </form>
        <div class="destopc">
        <?php
        if (isset($_POST['pesq'])){
            echo "<h3>Resultados: ";
            if (empty($voosPesquisa)) {
                echo '<p>Nenhum resultado encontrado.</p>';
            } else {
            foreach($voosPesquisa as $row) {
                echo "<div class='butdest'><form type='submit' method='post'>
                <label><b>".$row['cidade']['nome']."</b></label>
                <h1>".$row['preco']."</h1>
                <h3>(Valor base)</h3>
                <h3> Assentos Disponíveis: ".$row['assentos_disponiveis']."</h3>
                <h3>Embarque: ".$row['origem']."</h3>
                <h3>Desembarque: ".$row['destino']."</h3>
                <h3>".$row['cidade']['pais']."</h3>
                <h3> Linha Aerea: ";
                foreach ($linhas as $linha) { 
                                    if($linha['id'] == $row['linha_aerea_id']){
                                        echo $linha['nome'];
                                    }
                                 }                                 
                echo "</h3><h3>Partida: ".$row['data_partida']."</h3>
                <h3>Chegada: ".$row['data_chegada']."</h3>  "; 
                if($row['assentos_disponiveis']>0){ 
                                $jaTemNoCarrinho = verificar_passagem_car($_SESSION['usuario']['id'], $row['id']);
                                $quantidade_passagens_compradas = 0;

                                $passagensJaNoCarrinho = [];
                                while($pass = $jaTemNoCarrinho->fetch(PDO::FETCH_ASSOC)){
                                    $quantidade_passagens_compradas++;
                                    if($pass){
                                        $passagensJaNoCarrinho [] = $pass;
                                    }
                                }

                                foreach($passagensJaNoCarrinho as $passag){
                                    if($quantidade_passagens_compradas == $row['assentos_disponiveis']){
                                        echo "<p class='passagem-carrinho'>O valor máximo de passagens (".$quantidade_passagens_compradas. ") já está em processo de compra</p>";
                                        break;
                                    }elseif($quantidade_passagens_compradas<$row['assentos_disponiveis']){
                                        echo "<input type='number' class='quantidade' min='0' value='0' max='".$row['assentos_disponiveis']-$quantidade_passagens_compradas."' name='quant'/><button class='buttonSub' type='submit' name='id' value='".$row['id']."'>Adcionar ao Carrinho</button>";
                                        if($quantidade_passagens_compradas>1){ 
                                            echo "<p class='passagem-carrinho'>".$quantidade_passagens_compradas." passagens foram adicionadas ao processo de compra</p>";
                                            break;
                                        }else{
                                            echo "<p class='passagem-carrinho'>".$quantidade_passagens_compradas. " passagem foi adicionada ao processo de compra</p>";
                                            break;
                                        }
                                    }   
                                }if($passagensJaNoCarrinho == null){
                                    echo "<input type='number' class='quantidade' min='0' value='0' max='".$row['assentos_disponiveis']."' name='quant'/><button class='buttonSub' type='submit' name='id' value='".$row['id']."'>Adcionar ao Carrinho</button>";
                                }
                            }else{
                                echo "<p class=passagem-esgotada>Passagens esgotadas</p>";
                            }
                echo "
                </form></div>";
                }
            }
        }
        ?>
        </div>
        <div class="destopc"> 
            <?php
                $idsJaExibidos = isset($voosPesquisa) ? array_column($voosPesquisa, 'id') : [];
                    foreach($todosVoos as $row) {
                    if (in_array($row['id'], $idsJaExibidos)) {
                        continue;
                    }
                        echo "<div class='butdest'><form type='submit'  method='post'>
                            <label><b>".$row['cidade']['nome']."</b></label>
                            <h1>".$row['preco']."</h1>
                            <h3>(Valor base)</h3>
                            <h3> Assentos Disponíveis: ".$row['assentos_disponiveis']."</h3>
                            <h3>Embarque: ".$row['origem']."</h3>
                            <h3>Desembarque: ".$row['destino']."</h3>
                            <h3>".$row['cidade']['pais']."</h3>
                            <h3>Linha Aérea: ";
                            foreach ($linhas as $linha) { 
                                    if($linha['id'] == $row['linha_aerea_id']){
                                        echo $linha['nome'];
                                    }
                            } 
                            echo "</h3><h3>Partida: ".$row['data_partida']."</h3>
                            <h3>Chegada: ".$row['data_chegada']."</h3>  "; 
                            if($row['assentos_disponiveis']>0){ 
                                $jaTemNoCarrinho = verificar_passagem_car($_SESSION['usuario']['id'], $row['id']);
                                $quantidade_passagens_compradas = 0;

                                $passagensJaNoCarrinho = [];
                                while($pass = $jaTemNoCarrinho->fetch(PDO::FETCH_ASSOC)){
                                    $quantidade_passagens_compradas++;
                                    if($pass){
                                        $passagensJaNoCarrinho [] = $pass;
                                    }
                                }

                                foreach($passagensJaNoCarrinho as $passag){
                                    if($quantidade_passagens_compradas == $row['assentos_disponiveis']){
                                        echo " <p class='passagem-carrinho'>O valor máximo de passagens (".$quantidade_passagens_compradas. ") já foi adicionada ao processo de compra</p>";
                                        break;
                                    }elseif($quantidade_passagens_compradas<$row['assentos_disponiveis']){
                                        echo "<input type='number' class='quantidade' min='0' value='0' max='".$row['assentos_disponiveis']-$quantidade_passagens_compradas."' name='quant'/><button class='buttonSub' type='submit' name='id' value='".$row['id']."'>Adcionar ao Carrinho</button>";
                                        if($quantidade_passagens_compradas>1){ 
                                            echo "<p class='passagem-carrinho'>".$quantidade_passagens_compradas." passagens foram adicionadas ao processo de compra</p>";
                                            break;
                                        }else{
                                            echo "<p class='passagem-carrinho'>".$quantidade_passagens_compradas. " passagem foi adicionada ao processo de compra</p>";
                                            break;
                                        }
                                    }   
                                }if($passagensJaNoCarrinho == null){
                                    echo "<input type='number' class='quantidade' min='0' value='0' max='".$row['assentos_disponiveis']."' name='quant'/><button class='buttonSub' type='submit' name='id' value='".$row['id']."'>Adcionar ao Carrinho</button>";
                                }
                            }else{
                                echo "<p class=passagem-esgotada>Passagens esgotadas</p>";
                            }
                        echo "</form></div>";
                    }
            ?>
        </div>
    </main>
</body>
<script>
    const inputs = document.querySelectorAll('input.quantidade');

    inputs.forEach(input => {
    input.addEventListener('input', (e) => {
        const valor = parseInt(e.target.value) || 0;

        if (valor > 0) {
            inputs.forEach(outroInput => {
                if (outroInput !== e.target) {
                    outroInput.value = 0;
                }
            });
        }
    });
    });
</script>
</html>