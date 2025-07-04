 <?php
require_once(__DIR__ . '/../server/passagem/get_passagem.php');
require_once(__DIR__ . '/../server/passagem/put_passagem.php');
require_once(__DIR__ . '/../server/linha_aerea/get_linha_aerea.php');

$voosUsuario = $_SESSION['voos_car_usuario'] ?? [];

if (empty($voosUsuario)) {
    echo '<header style="width: 100%;
          background-color: rgb(0, 60, 255);
          padding: 16px 0;
          box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
          position: sticky;
          top: 0;
          z-index: 1000;
          margin-bottom: 30px;">
          <nav style="max-width: 1200px;
          margin: 0 auto;
          display: flex;
          justify-content: center;
          gap: 40px;
          flex-wrap: wrap;">
            <a styele="color: var(--branco);
          text-decoration: none;
          font-weight: 600;
          font-size: 1.1rem;
          transition: color 0.3s ease, transform 0.2s ease;
          padding: 8px 16px;
          border-radius: 8px;" href="index.php">Home</a>
            <a styele="color: var(--branco);
          text-decoration: none;
          font-weight: 600;
          font-size: 1.1rem;
          transition: color 0.3s ease, transform 0.2s ease;
          padding: 8px 16px;
          border-radius: 8px;" href="Passagens.php">Passagens</a>
            <a styele="color: var(--branco);
          text-decoration: none;
          font-weight: 600;
          font-size: 1.1rem;
          transition: color 0.3s ease, transform 0.2s ease;
          padding: 8px 16px;
          border-radius: 8px;" href="viagens.php">Viagens</a>
            <a styele="color: var(--branco);
          text-decoration: none;
          font-weight: 600;
          font-size: 1.1rem;
          transition: color 0.3s ease, transform 0.2s ease;
          padding: 8px 16px;
          border-radius: 8px;" href="Sobre.html">Sobre</a>
            </nav>
      </header><p>Nenhum voo no carrinho.</p>';
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
            if(urlParams.get('mensagem') === 'checkoutJaEmAndamento'){
            alert("Ja há um checkout em andamento");
            }
        }
      
    </script>
 </head>
 <header style="width: 100%;
          background-color: rgb(0, 60, 255);
          padding: 16px 0;
          box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
          position: sticky;
          top: 0;
          z-index: 1000;
          margin-bottom: 30px;">
          <nav style="max-width: 1200px;
          margin: 0 auto;
          display: flex;
          justify-content: center;
          gap: 40px;
          flex-wrap: wrap;">
            <a styele="color: var(--branco);
          text-decoration: none;
          font-weight: 600;
          font-size: 1.1rem;
          transition: color 0.3s ease, transform 0.2s ease;
          padding: 8px 16px;
          border-radius: 8px;" href="index.php">Home</a>
            <a styele="color: var(--branco);
          text-decoration: none;
          font-weight: 600;
          font-size: 1.1rem;
          transition: color 0.3s ease, transform 0.2s ease;
          padding: 8px 16px;
          border-radius: 8px;" href="Passagens.php">Passagens</a>
            <a styele="color: var(--branco);
          text-decoration: none;
          font-weight: 600;
          font-size: 1.1rem;
          transition: color 0.3s ease, transform 0.2s ease;
          padding: 8px 16px;
          border-radius: 8px;" href="viagens.php">Viagens</a>
            <a styele="color: var(--branco);
          text-decoration: none;
          font-weight: 600;
          font-size: 1.1rem;
          transition: color 0.3s ease, transform 0.2s ease;
          padding: 8px 16px;
          border-radius: 8px;" href="TelaSobreSkyline.php">Sobre</a>
            </nav>
      </header>
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