<?php
    require_once(__DIR__ . '/../server/voo/get_voos_pesquisados.php');
    require_once(__DIR__ . '/../server/voo/get_voo.php');
    require_once(__DIR__ . '/../server/passagem/post_passagem.php');

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
</head>
<body>
    <header>
       <nav id="menu">
        <a class="opc" href="Passagens.php">Passagens</a>
        <a class="opc" href="viagens.html">Viagens</a>
        <a class="opc" href="TelaSobreSkyline.php">Sobre</a>
        <a class="opc" href="login.html">Login</a>
        </nav>
    </header>
    <main>
        <form action="#" method="post">
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
        <form type='submit 'method="post">
        <?php
            if (isset($stmta) && $stmta){
                echo "<h3>Resultados: <h3><br>";
            while($row = $stmta->fetch(PDO::FETCH_ASSOC)) {
                echo "<div class='butdest'>
                <div>
                <label><b>".$row['destino']."</b></label>
                <h1>".$row['preco']."</h1>
                <h3>(Valor base)</h3>
                <h3> Assentos Disponíveis: ".$row['assentos_disponiveis']."</h3>
                <h3> Aeroporto Internacional do ".$row['origem']."</h3>
                <h3> Linha Aerea: ";
                foreach ($linhas as $linha) { 
                                    if($linha['id'] == $row['linha_aerea_id']){
                                        echo $linha['nome'];
                                    }
                                 }                                 
                echo "</h3><h3>Partida: ".$row['data_partida']."</h3>
                <h3>Chegada: ".$row['data_chegada']."</h3>  "; 
                if($row['assentos_disponiveis']>0){echo "<button class='buttonSub' type='submit' name='id' value='".$row['id']."'>Adcionar ao Carrinho</button>";
                }else{echo "<p class='passagem-esgotada'>Passagens esgotadas</p>";
                }
                echo "</div>
                </div>";
                }
            }
        ?>
        <div class="destopc"> 
            <?php
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<div class='butdest'>
                            <div>
                            <label><b>".$row['destino']."</b></label>
                            <h1>".$row['preco']."</h1>
                            <h3>(Valor base)</h3>
                            <h3> Assentos Disponíveis: ".$row['assentos_disponiveis']."</h3>
                            <h3> Aeroporto Internacional do ".$row['origem']."</h1>
                            <h3>Linha Aérea: ";
                            foreach ($linhas as $linha) { 
                                    if($linha['id'] == $row['linha_aerea_id']){
                                        echo $linha['nome'];
                                    }
                            } 
                            echo "</h3><h3>Partida: ".$row['data_partida']."</h3>
                            <h3>Chegada: ".$row['data_chegada']."</h3>  "; 
                            if($row['assentos_disponiveis']>0){echo "<button class='buttonSub' type='submit' name='id' value='".$row['id']."'>Adcionar ao Carrinho</button>";
                            }else{echo "<p  class='passagem-esgotada'>Passagens esgotadas</p>";
                            }
                            echo "</div/>
                            </div>";
                    }
            ?>
        </form>
        </div>
    </main>
</body>
</html>