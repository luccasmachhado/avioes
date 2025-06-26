<?php 
    require_once(__DIR__ . '/../server/passagem/put_passagem.php');
  require_once(__DIR__ . '/../server/checkout_cache/checkout_cache_get.php');
require_once(__DIR__ . '/../server/checkout_cache/cheackout_cache_delete.php');

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

    $idOusuario = $_SESSION['usuario']['id'];
  $stmt = $pdo->prepare("SELECT * FROM passageiro WHERE usuario_id = :id AND hierarquia='adm'");
  $stmt->bindParam(':id', $$idOusuario);
  $stmt->execute();
  $passageiroAdm = $stmt->fetchAll(PDO::FETCH_ASSOC);

  if (empty($_SESSION['usuario']['voosCarrinho'])) { 
    $voosCarrinho = get_checkout($_SESSION['usuario']['id']);
    $_SESSION['usuario']['voosCarrinho'] = $voosCarrinho;
    
  }else{
    $voosCarrinho = $_SESSION['usuario']['voosCarrinho'];
  }
  $NVoo = [];
foreach($voosCarrinho as $item){
    $voo = $item['voo'];
    $NVoo[] = $voo;
    break;
}

?>  
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Boleto de Passagem Aérea</title>
  <style>
    body {
      font-family: 'Courier New', Courier, monospace;
      background: #eee;
      padding: 40px;
    }

    .boleto {
      max-width: 800px;
      margin: auto;
      background: #fff;
      border: 1px solid #000;
      padding: 20px;
      box-shadow: 0 0 10px rgba(0,0,0,0.3);
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    .linha-digitavel {
      font-size: 18px;
      font-weight: bold;
      text-align: center;
      border: 1px dashed #000;
      padding: 10px;
      margin-bottom: 20px;
    }

    .dados-boleto {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      gap: 10px;
      font-size: 14px;
    }

    .campo {
      width: 48%;
      border-bottom: 1px solid #000;
      padding: 5px 0;
    }

    .campo span {
      display: block;
      font-size: 12px;
      color: #555;
    }

    .codigo-barras {
      margin-top: 30px;
      text-align: center;
    }

    .barra-falsa {
      background: repeating-linear-gradient(
        to right,
        #000,
        #000 2px,
        transparent 2px,
        transparent 4px
      );
      height: 50px;
      margin: auto;
      width: 100%;
    }

    button {
      margin-top: 30px;
      display: block;
      width: 100%;
      padding: 12px;
      font-size: 16px;
      background-color: #103778;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    button:hover {
      background-color: #0b2c5e;
    }
  </style>
</head>
<header style="width: 100%;
          background-color: rgb(0, 60, 255);
          padding: 16px 0;
          box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
          position: sticky;
          top: 0;
          z-index: 1000;
          margin-bottom: 50px;">
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
          border-radius: 8px;" href="viagens.html">Viagens</a>
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

  <div class="boleto">
    <h2>Boleto Bancário - Passagem Aérea</h2>

    <div class="linha-digitavel">23790.12345 60000.123456 70000.123456 1 91230000079900</div>

    <div class="dados-boleto">
      <div class="campo"><strong ><?php echo $_SESSION['usuario']['nome'] ?></strong><span>Nome do Passageiro Administrador</span></div>
      <div class="campo"><strong><?php echo $_SESSION['usuario']['rg'] ?></strong><span>RG</span></div>
      <div class="campo"><strong><?php echo $_SESSION['usuario']['nascimento'] ?></strong><span>Data de Nascimento</span></div>
      <div class="campo"><strong id="voo"><?php echo "Nº ".$NVoo[0]['id']; ?></strong><span>Voo</span></div>
      <div class="campo"><strong><?php echo $NVoo[0]['data_chegada']; ?></strong><span>Data de Partida</span></div>
      <div class="campo"><strong><?php echo $NVoo[0]['data_chegada']; ?></strong><span>Data Chegada</span></div>
      <div class="campo"><strong>Econômica/VIP</strong><span>Classe</span></div>
      <div class="campo"><strong id="valorTotal"></strong><span>Valor</span></div>
      <div class="campo"><strong>06/06/2025</strong><span>Data de Vencimento</span></div>
    </div>

    <div class="codigo-barras">
      <p>Código de Barras</p>
      <div class="barra-falsa"></div>
    </div>

    <button onclick="window.print()">Imprimir Boleto</button>
  </div>

  <form action='' method="post">
      <button type="submit" name="deleta_cach" value="compra">Voltar</button>
  </form>

  <script>
    // Pega os dados da URL
    const params = new URLSearchParams(window.location.search);
    document.getElementById('valorTotal').textContent = params.get('valorTotal') || '';

  </script>

</body>
</html>
