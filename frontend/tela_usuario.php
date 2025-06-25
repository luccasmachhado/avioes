  <?php 
  session_start();
  require_once(__DIR__ . '/../server/usuario/logout.php');
  require_once(__DIR__ . '/../server/passagem/get_passagem_compra_def.php');
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

  date_default_timezone_set('America/Sao_Paulo');

  $dataAtual = new DateTime(); // Data atual
  $dataNascimento = new DateTime($_SESSION['usuario']['nascimento']); // Data de nascimento vinda da sessão

  $idade = $dataAtual->diff($dataNascimento)->y;

  if(($_SESSION['usuario']['pcd']) == 1){
    $pcd = 'PCD';
  }else{
    $pcd = 'Típico';
  }

  ?>
  <!DOCTYPE html>
  <html lang="pt-BR">
    <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>Perfil</title>
      <style>
        :root {
          --laranja: #f57c00;
          --branco: #ffffff;
          --azul-escuro: #0d1b2a;
        }

        /* Estilo para o cabeçalho e menu */
        header {
          width: 100%;
          background-color: var(--laranja);
          padding: 16px 0;
          box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
          position: sticky;
          top: 0;
          z-index: 1000;
          margin-bottom: 30px;
        }

        #menu {
          max-width: 1252px;
          margin: 0 auto;
          display: flex;
          justify-content: center;
          gap: 40px;
          flex-wrap: wrap;
        }

        .opc {
          color: var(--branco);
          text-decoration: none;
          font-weight: 600;
          font-size: 1.1rem;
          transition: color 0.3s ease, transform 0.2s ease;
          padding: 8px 16px;
          border-radius: 8px;
        }

        .opc:hover {
          background-color: var(--branco);
          color: var(--laranja);
          transform: scale(1.05);
        }

        .opc:active {
          transform: scale(0.95);
        }

        @media (max-width: 600px) {
          #menu {
            flex-direction: column;
            align-items: center;
            gap: 16px;
          }

          .opc {
            font-size: 1rem;
          }
        }

        body {
          margin: 0;
          font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
          background-color: var(--azul-escuro);
          color: var(--branco);
          display: flex;
          flex-direction: column;
          justify-content: center;
          align-items: flex-start;
          padding: 40px 20px;
        }

        .container {
          margin: 10px;
          border-radius: 16px;
          align-items: center;
          background-color: white;
          padding: 10px;
          width: 1280px;
        }
        .passagem{
          display: flex;
          flex-direction: row;
          align-items: flex-start;
        }

        .macroInformacoes {
          display: flex;
          flex-direction: column;
          align-items:flex-start;
        }

        .container-adm {
          width: 1252px;
        }

        .container-adm .info-section {
          background-color: var(--branco);
          border-radius: 16px;
          box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
          color: var(--azul-escuro);
          padding: 20px;
        }

        .welcome-container {
          text-align: center;
          background-color: var(--laranja);
          padding: 40px 60px;
          border-radius: 16px;
          box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
          margin-bottom: 30px;
          width: 1200px;
        }

        .welcome-container h1 {
          margin: 0;
          font-size: 2.5rem;
          color: var(--branco);
        }

        .username {
          font-size: 1.8rem;
          font-weight: 600;
          color: var(--azul-escuro);
          margin-top: 10px;
        }

        .info-section-adm {
          background-color: var(--branco);
          border-radius: 16px;
          box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
          color: var(--azul-escuro);
          width: 100%;
          padding: 10px;
          
        }

        .info-section-adm h2 {
          font-size: 1.8rem;
          margin-bottom: 20px;
          color: var(--laranja);
          border-bottom: 2px solid var(--laranja);
        }

        .info-adm-item- {
          margin-bottom: 12px;
          font-size: 1.1rem;
        }

        .info-adm-item span {
          font-weight: 600;
        }

        .info-section {
          background-color: var(--branco);
          border-radius: 16px;
          box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
          color: var(--azul-escuro);
          
        }

        .info-section h2 {
          font-size: 1.8rem;
          margin-bottom: 20px;
          color: var(--laranja);
          border-bottom: 2px solid var(--laranja);
          padding-bottom: 8px;
        }

        .info-item {
          margin-bottom: 12px;
          font-size: 1.1rem;
        }

        .info-item span {
          font-weight: 600;
        }

        @media (max-width: 600px) {
          .welcome-container,
          .info-section {
            padding: 30px 20px;
          }

          .welcome-container h1 {
            font-size: 2rem;
          }

          .username {
            font-size: 1.5rem;
          }

          .info-section h2 {
            font-size: 1.5rem;
          }
        }

        .passagens-section {
          background-color: var(--branco);
          border-radius: 16px;
          box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
          color: var(--azul-escuro);
          margin-top: 30px;
        }

        .passagens-section h2 {
          font-size: 1.8rem;
          margin-bottom: 20px;
          color: var(--laranja);
          border-bottom: 2px solid var(--laranja);
          padding-bottom: 8px;
        }

        .passagem-card {
          border: 1px solid var(--laranja);
          border-radius: 12px;
          padding: 20px;
          margin-bottom: 20px;
          background-color: #f9f9f9;
        }

        .passagem-card .info-item {
          font-size: 1rem;
          margin-bottom: 8px;
        }

        @media (max-width: 600px) {
          .passagens-section h2 {
            font-size: 1.5rem;
          }

          .passagem-card {
            padding: 15px;
          }

          .passagem-card .info-item {
            font-size: 0.95rem;
          }
        }

        .btn-modificar {
          background-color: var(--laranja);
          color: var(--branco);
          padding: 10px 20px;
          font-size: 1rem;
          border: none;
          border-radius: 8px;
          cursor: pointer;
          transition: background-color 0.3s ease, transform 0.2s ease;
          margin-top: 10px;
        }

        .btn-modificar:hover {
          background-color: #e06b00;
          transform: scale(1.03);
        }

        .btn-modificar:active {
          transform: scale(0.98);
        }

        .passagem-actions {
          text-align: right;
        }

        .acompanhantes-section {
          background-color: var(--branco);
          border-radius: 16px;
          box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
          color: var(--azul-escuro);
          margin-top: 30px;
        }

        .acompanhantes-section h2 {
          font-size: 1.8rem;
          margin-bottom: 20px;
          color: var(--laranja);
          border-bottom: 2px solid var(--laranja);
          padding-bottom: 8px;
        }

        .acompanhante-card {
          border: 1px solid var(--laranja);
          border-radius: 12px;
          padding: 20px;
          margin-bottom: 20px;
          background-color: #f9f9f9;
        }

        .info-row {
          display: flex;
          justify-content: space-between;
          align-items: center;
          font-size: 1rem;
          margin-bottom: 10px;
        }

        .btn-remover {
          background-color: transparent;
          color: var(--laranja);
          border: 1px solid var(--laranja);
          border-radius: 6px;
          padding: 5px 10px;
          font-size: 0.9rem;
          cursor: pointer;
          transition: all 0.3s ease;
        }

        .btn-remover:hover {
          background-color: var(--laranja);
          color: var(--branco);
        }

        @media (max-width: 600px) {
          .info-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
          }

          .btn-remover {
            align-self: flex-end;
          }
        }

        .cadastrar-passageiros {
          display: flex;
          justify-content: flex-start;
          align-items: center;
          gap: 12px;
          margin: 30px 0;
        }

        .input-numero {
          width: 80px;
          padding: 8px 10px;
          font-size: 1rem;
          border-radius: 8px;
          border: 1px solid var(--laranja);
          outline: none;
          transition: border-color 0.3s ease;
        }

        .input-numero:focus {
          border-color: var(--azul-escuro);
        }

        .btn-cadastrar {
          background-color: var(--laranja);
          color: var(--branco);
          padding: 10px 20px;
          font-size: 1rem;
          border: none;
          border-radius: 8px;
          cursor: pointer;
          transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-cadastrar:hover {
          background-color: #e06b00;
          transform: scale(1.03);
        }

        .btn-cadastrar:active {
          transform: scale(0.98);
        }

        @media (max-width: 600px) {
          .cadastrar-passageiros {
            flex-direction: column;
            align-items: stretch;
          }

          .input-numero,
          .btn-cadastrar {
            width: 100%;
          }
        }

        .btn-logout {
          background-color: transparent;
          color: var(--laranja);
          border: 2px solid var(--laranja);
          padding: 10px 20px;
          font-size: 1rem;
          border-radius: 8px;
          cursor: pointer;
          transition: all 0.3s ease;
        }

        .btn-logout:hover {
          background-color: var(--laranja);
          color: var(--branco);
          transform: scale(1.03);
        }

        .btn-logout:active {
          transform: scale(0.97);
        }
      </style>
    </head>
    <body>
      <header>
          <nav id="menu">
            <a class="opc" href="index.php">Home</a>
            <a class="opc" href="Passagens.php">Passagens</a>
            <a class="opc" href="viagens.html">Viagens</a>
            <a class="opc" href="TelaSobreSkyline.php">Sobre</a>
            <a class="opc" href="login.html">Login</a>
            </nav>
      </header>
      <div class="container-adm">
        <div class="welcome-container">
          <h1><?php echo $_SESSION['usuario']['nome']?></strong></div></h1>
          <div class="username"><!-- {{ nome_usuario }} --></div>
        </div>

        <div class="info-section-adm">
          <h2>Usuário Administrador</h2>
          <div class="info-item"><span><?php echo $_SESSION['usuario']['nome'] ?></span> <!-- {{ nome_completo }} --></div>
          <div class="info-item">RG: <span><?php echo $_SESSION['usuario']['rg'] ?></span> <!-- {{ sexo }} --></div>
          <div class="info-item">Idade: <span><?php echo $idade ?></span> <!-- {{ idade }} --></div>
          <div class="info-item">Condição: <span><?php echo $pcd ?></span> <!-- {{ condicao }} --></div>
          <div class="info-item">CPF: <span><?php echo $_SESSION['usuario']['cpf'] ?></span> <!-- {{ condicao }} --></div>
          </div>
            <form action="" method="post">
            <input type='hidden' name='logout' value='htmlspecialchars(logout)'>
            <button type="submit" class="btn-remover">Sair</button>
            </form>
          </div>
        </div>
      </div>
      <div class="passagens">
      <?php
      foreach($voosUsuario as $item){
        $voo = $item['voo'];
        $cidade = $item['cidade'];
        $linha = $item['linha_aerea'];
        $passagem = $item['passagem'];
        
      echo
      '
      <div class="macroInformacoes">
        <div class="container">
          <div style="    font-size: 1.8rem;
      margin-bottom: 20px;
      color: var(--laranja);
      border-bottom: 2px solid var(--laranja);
      padding-bottom: 8px;"><span>Passagem para '.$cidade['nome'].'</span></div>
          <div class="passagens-section">
            <div class="info-section">
              <div class="passagem-card">
                <h2>Passagem Nº '.$passagem['id'].'</h2>
                <h2>Voo Nº '.$voo['id'].'</h2>
                <div class="info-item"><span>Companhia Aérea: '.$linha['nome'].'</span></div>
                <div class="info-item"><span>Origem: '.$voo['origem'].'</span></div>
                <div class="info-item"><span>Destino: '.$voo['destino'].'</span></div>
                <div class="info-item"><span>Data de Partida: '.$voo['data_partida'].'</span></div>
                <div class="info-item"><span>Data de Chegada: '.$voo['data_chegada'].'</span></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      ';} ?>
      </div>
    </body>
  </html>
