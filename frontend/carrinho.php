 <?php
require_once(__DIR__ . '/../server/passagem/get_passagem.php');
require_once(__DIR__ . '/../server/passagem/put_passagem.php');

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
    <script>
      window.onload = function(){
      const urlParams = new URLSearchParams(window.location.search);
      if(urlParams.get('mensagem') === 'passagem_deletada'){
         alert("Passagem removida do carrinho com êxito!");
      }}
    </script>
 </head>
 <body>
    <?php
      $valor_total = null;
        foreach ($voosUsuario as $voo) {
                echo "<div class='butdest'>
                <h1>".$voo['destino']."</h1>
                <h2>".$voo['preco']."</h2>
                <h3> Assentos Disponíveis: ".$voo['assentos_disponiveis']."</h3>
                <h3> Aeroporto Internacional do ".$voo['origem']."</h3>
                <h3>Partida: ".$voo['data_partida']."</h3>
                <h3>Chegada: ".$voo['data_chegada']."</h3><br>";
               echo "
               <form action='http://localhost/skyline/server/passagem/delete_passagem.php' method='post'>
                  <input type='hidden' name='remover' value='" . htmlspecialchars($voo['idPassagem']) . "'>
                  <button type='submit'>Remover</button>
               </form>";

            $valor_total = $valor_total + $voo['preco'];
        }
    ?>
<div>
<form action='' method='post'>
<?php echo '<label>VALOR TOTAL: '.$valor_total.'</label>' ?><br>;
<input type='radio' name='comprar' value='1'/>
<label>Selecionar tudo</label>
<input type='submit' value='Comfirmar'/>
</form>
</div>"; 
 </body>
 </html>