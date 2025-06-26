<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    }
    require_once(__DIR__ . '/../server/voo/get_voo_por_cidade.php');
    require_once(__DIR__ . '/../server/linha_aerea/get_linha_aerea.php');

    

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Destinos de Viagem</title>
  <link rel="shortcut icon" href="Imagens/aviaoicon.ico" type="image/x-icon">
  <link
    href="https://fonts.googleapis.com/css2?family=Inria+Sans&family=Kanit:wght@400;600&family=Playfair+Display&display=swap"
    rel="stylesheet"
  />
  <link rel="stylesheet" href="viagenstyle.css">
  <link rel="stylesheet" href="perfil.css">
</head>
<body>
  <header style="
          background-color: #103778;
          margin-bottom: 30px;">
    <nav style="max-width: 1200px;
          margin: 0 auto;
          display: flex;
          justify-content: center;
          gap: 40px;
          flex-wrap: wrap;" id="menu">
        <a class="opc" href="index.php" styele="color: color: white;
          text-decoration: none;
          font-weight: 600;
          font-size: 1.1rem;
          transition: color 0.3s ease, transform 0.2s ease;
          padding: 8px 16px;
          border-radius: 8px;">Home</a> 
        <a class="opc" href="Passagens.php" styele="color: color: white;
          text-decoration: none;
          font-weight: 600;
          font-size: 1.1rem;
          transition: color 0.3s ease, transform 0.2s ease;
          padding: 8px 16px;
          border-radius: 8px;">Passagens</a>
        <a class="opc" href="viagens.php" styele="color: color: white;
          text-decoration: none;
          font-weight: 600;
          font-size: 1.1rem;
          transition: color 0.3s ease, transform 0.2s ease;
          padding: 8px 16px;
          border-radius: 8px;">Viagens</a>
        <a class="opc" href="Sobre.html" styele="color: color: white;
          text-decoration: none;
          font-weight: 600;
          font-size: 1.1rem;
          transition: color 0.3s ease, transform 0.2s ease;
          padding: 8px 16px;
          border-radius: 8px;">Sobre</a>
        <a class="opc" href="carrinho.php" styele="color: color: white;
          text-decoration: none;
          font-weight: 600;
          font-size: 1.1rem;
          transition: color 0.3s ease, transform 0.2s ease;
          padding: 8px 16px;
          border-radius: 8px;">Carrinho</a>
    </nav>
    </header>

  <h1 id="inicio">Descubra Destinos Incríveis pelo Mundo</h1>

    <section class="container" id="destinos">
                    <?php
                        $i = 0;
                        foreach($todosVoos as $row){
                            if($i > 2 and $i != 0){$i=0;} 
                            echo "<div class='destino'><h3>".$row['destino']."</h3><picture><img src='Imagens/".$row['imagem'].$i.".jpg' alt='Cidade de ".$row['destino']."'></picture>
                            <ul class='infodestino'>
                                <p>".$row['cidade']['pais']."</p>
                                <p>".$row['cidade']['nome']."</li></p>
                                <p>".$row['cidade']['descricao']."</li></p>
                            </ul>
                            <a href='Passagens.php' class='btn'>Explorar</a></div>";
                            $i = $i+1;
                        }
                    ?>
    </section>


</body>

</html>
