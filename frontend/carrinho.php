 <?php
require_once(__DIR__ . '/../server/passagem/get_passagem.php');
require_once(__DIR__ . '/../server/passagem/put_passagem.php');
require_once(__DIR__ . '/../server/linha_aerea/get_linha_aerea.php');

$voosUsuario = $_SESSION['voos_car_usuario'] ?? [];

if (empty($voosUsuario)) {
    echo "<p>Nenhum voo no carrinho.</p>";
    exit;
}

 ?>
 <!DOCTYPE html>
 <html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="passagens.css">
    <script>
      window.onload = function(){
         const urlParams = new URLSearchParams(window.location.search);
            if(urlParams.get('mensagem') === 'passagem_deletada'){
               alert("Passagem removida do carrinho com êxito!");
            }
            if(urlParams.get('mensagem') === 'passagem_diferente'){
            alert("Só é possível comprar passagens de um mesmo voo por vez.");
            }
        }
      
    </script>
 </head>
 <body>
   <div class="destopc">
    <?php
      $valor_total = null;
        foreach ($voosUsuario as $voo) {
                echo "<div class='butdest'>
                <h1>".$voo['cidade']['nome']."</h1>
                <h2>".$voo['preco']."</h2>
                <h3> Assentos Disponíveis: ".$voo['assentos_disponiveis']."</h3>
                <h3>Embarque: ".$voo['origem']."</h3>
                <h3>Desembarque: ".$voo['destino']."</h3>
                <h3>".$voo['cidade']['pais']."</h3>
                <h3>Linha Aérea: ";
                            foreach ($linhas as $linha) { 
                                    if($linha['id'] == $voo['linha_aerea_id']){
                                        echo $linha['nome'];
                                    }
                            }
                echo "<h3>Partida: ".$voo['data_partida']."</h3>
                <h3>Chegada: ".$voo['data_chegada']."</h3><br>";
               echo "
               <form action='http://localhost/skyline/server/passagem/delete_passagem.php' method='post'>
                  <input type='hidden' name='remover' value='" . htmlspecialchars($voo['idPassagem']) . "'>
                  <button class='buttonSub' type='submit'>Remover</button>
               </form></div>";

            $valor_total = $valor_total + $voo['preco'];
        }
    ?>
   </div>
<div>
<form action='' method='post' id="menu">
<?php echo '<label>VALOR BASE TOTAL: '.$valor_total.'</label>' ?><br>;
<input type='radio' name='comprar' value='1'/>
<label>Selecionar tudo</label>
<input class='buttonSub' type='submit' value='Continuar'/>
</form>
</div>"; 
 </body>
 </html>