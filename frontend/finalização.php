<?php
  require_once(__DIR__ . '/../server/passagem/put_passagem.php');
  require_once(__DIR__ . '/../server/checkout_cache/checkout_cache_get.php');

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

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Bilhetes de Embarque</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #e6e9f8;
      padding: 40px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .boarding-pass {
      background-color: #fff;
      border-radius: 16px;
      width: 700px;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
      padding: 20px;
      margin-bottom: 20px;
    }

    .header {
      background-color: #3b7eff;
      color: white;
      padding: 10px 20px;
      border-radius: 12px 12px 0 0;
      display: flex;
      justify-content: space-between;
      font-weight: bold;
    }

    .main {
      display: flex;
      justify-content: space-between;
      margin-top: 20px;
    }

    .section {
      width: 48%;
    }

    .section h2 {
      margin: 0;
      font-size: 24px;
      color: #3b7eff;
    }

    .info {
      margin: 8px 0;
      font-size: 14px;
    }

    .label {
      font-weight: bold;
      color: #555;
    }

    .value {
      color: #000;
    }

    .barcode {
      margin-top: 20px;
      height: 60px;
      background: repeating-linear-gradient(
        90deg,
        #000,
        #000 2px,
        transparent 2px,
        transparent 4px
      );
      border-radius: 4px;
    }

    .footer {
      text-align: center;
      margin-top: 20px;
      font-size: 12px;
      color: #999;
    }

    .pagamento {
      background: #fff;
      padding: 20px;
      border-radius: 16px;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
      width: 700px;
    }

    .pagamento h2 {
      color: #3b7eff;
      margin-bottom: 15px;
    }

    label {
      display: block;
      margin-top: 10px;
    }

    input[type="text"], select {
      width: 100%;
      padding: 8px;
      margin-top: 5px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

    .qr-code {
      margin-top: 15px;
      display: none;
    }

    .valor-final {
      margin-top: 20px;
      font-size: 18px;
      font-weight: bold;
    }

    #botao-boleto {
      display: none;
      margin-top: 15px;
      padding: 10px 20px;
      background-color: #3b7eff;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
    }
  </style>
</head>
<body>

<script>
  const passageiros = JSON.parse(localStorage.getItem('passageiros')) || [];
  
  const voosCarrinho = <?php echo json_encode($voosCarrinho); ?>;
  const usuario = <?php echo json_encode($_SESSION['usuario']) ?>;
  let valorBaseTotal = 0;
  
  voosCarrinho.forEach((p, index) => {
    const voo = voosCarrinho[index]?.voo || {};
    valorBaseTotal += parseFloat(voo.preco) || 0;
  });

  const bilheteUsuario = document.createElement('div');
bilheteUsuario.className = 'boarding-pass';

const vooUsuario = voosCarrinho[0]?.voo || {}; // assume 1º voo pro usuário

bilheteUsuario.innerHTML = `
  <div class="header">
    <div>PASSAGEM AÉREA</div>
    <div>SKLINE</div>
  </div>

  <div class="main">
    <div class="section">
      <div class="info"><span class="label">Nome:</span> <span class="value">${usuario.nome}</span></div>
      <div class="info"><span class="label">RG:</span> <span class="value">${usuario.rg || 'N/A'}</span></div>
      <div class="info"><span class="label">Assento:</span> <span class="value">1</span></div>
      <div class="info"><span class="label">Classe:</span> <span class="value">${usuario.classe || 'Econômica'}</span></div>
      <div class="info"><span class="label">Plano:</span> <span class="value">${usuario.plano || 'Padrão'}</span></div>
    </div>

    <div class="section">
      <div class="info"><span class="label">Linha:</span> <span class="value">${vooUsuario.linha_aerea?.nome || 'N/A'}</span></div>
      <div class="info"><span class="label">Voo Nº:</span> <span class="value">${vooUsuario.id || 'N/A'}</span></div>
      <div class="info"><span class="label">Cidade:</span> <span class="value">${vooUsuario.cidade?.nome || 'N/A'}</span></div>
      <div class="info"><span class="label">Destino:</span> <span class="value">${vooUsuario.destino || 'N/A'}</span></div>
      <div class="info"><span class="label">Preço:</span> <span class="value">${vooUsuario.preco || 'N/A'}</span></div>
    </div>
  </div>

  <div class="barcode"></div>
  <div class="footer">Obrigado por voar com a SKLINE</div>
`;

document.body.appendChild(bilheteUsuario);

  if(passageiros && passageiros.length > 0){
  passageiros.forEach((p, index) => {
    const bilhete = document.createElement('div');
    bilhete.className = 'boarding-pass';

    const voo = voosCarrinho[index]?.voo || {}; // segurança contra erro de índice

    bilhete.innerHTML = `
      <div class="header">
        <div>PASSAGEM AÉREA</div>
        <div>SKLINE</div>
      </div>

      <div class="main">
        <div class="section">
          <div class="info"><span class="label">Nome:</span> <span class="value">${p.nome}</span></div>
          <div class="info"><span class="label">RG:</span> <span class="value">${p.rg}</span></div>
          <div class="info"><span class="label">Assento:</span> <span class="value">${index + 2}</span></div>
          <div class="info"><span class="label">Classe:</span> <span class="value">${p.classe}</span></div>
          <div class="info"><span class="label">Plano:</span> <span class="value">${p.plano}</span></div>
        </div>

        <div class="section">
            <div class="info"><span class="label">Linha: </span> <span class="value">${voo.linha_aerea.nome || 'N/A'}</span></div>
            <div class="info"><span class="label">Voo Nº: </span> <span class="value">${voo.id || 'N/A'}</span></div>
            <div class="info"><span class="label">Cidade: </span> <span class="value">${voo.cidade.nome || 'N/A'}</span></div>
            <div class="info"><span class="label">Destino: </span> <span class="value">${voo.destino || 'N/A'}</span></div>
            <div class="info"><span class="label">Preco: </span> <span class="value">${voo.preco || 'N/A'}</span></div>
        </div>
      </div>

      <div class="barcode"></div>
      <div class="footer">Obrigado por voar com a SKLINE</div>
    `;
    document.body.appendChild(bilhete);
  });
  }
  
</script>

<div class="pagamento">
  <h2>Forma de Pagamento</h2>
  <label><input type="radio" name="pagamento" value="cartao"> Cartão</label>
  <label><input type="radio" name="pagamento" value="pix"> Pix</label>
  <label><input type="radio" name="pagamento" value="boleto"> Boleto</label>

  <div id="cartaoFields" style="display:none;">
    <label>Número do Cartão:
      <input type="text" id="numeroCartao" maxlength="16" pattern="[0-9]{16}" />
    </label>
    <label>Tipo:
      <select id="tipoCartao">
        <option value="debito">Débito</option>
        <option value="credito">Crédito</option>
      </select>
    </label>
    <div id="parcelasField" style="display:none;">
      <label>Parcelas:
        <select id="parcelas">
          <option value="1">À vista</option>
          <option value="2">2x</option>
          <option value="3">3x</option>
        </select>
      </label>
    </div>
  </div>

  <div class="qr-code" id="qrCode">
 <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=PagamentoPixSKLINE" alt="QR Code Pix">
    <br>
    <button id="confirmarPixBtn" style="margin-top: 10px;">Confirmar Pix</button>
  </div>

  <button id="confirmarCartaoBtn" style="display:none; margin-top: 15px; background:#3b7eff; color:white; padding:10px 20px; border:none; border-radius:8px; cursor:pointer;">Confirmar Pagamento</button>

  <button id="botao-boleto">Gerar Boleto</button>

  <div class="valor-final" id="valorFinal"></div>
</div>

<script>

  const radioPagamentos = document.getElementsByName("pagamento");
  const cartaoFields = document.getElementById("cartaoFields");
  const tipoCartao = document.getElementById("tipoCartao");
  const parcelasField = document.getElementById("parcelasField");
  const qrCode = document.getElementById("qrCode");
  const valorFinal = document.getElementById("valorFinal");
  const botaoBoleto = document.getElementById("botao-boleto");
  const confirmarPixBtn = document.getElementById("confirmarPixBtn");
  const confirmarCartaoBtn = document.getElementById("confirmarCartaoBtn");

  radioPagamentos.forEach(radio => {
    radio.addEventListener("change", () => {
      cartaoFields.style.display = radio.value === "cartao" ? "block" : "none";
      parcelasField.style.display = "none";
      qrCode.style.display = radio.value === "pix" ? "block" : "none";
      botaoBoleto.style.display = radio.value === "boleto" ? "block" : "none";
      confirmarCartaoBtn.style.display = radio.value === "cartao" ? "block" : "none";
      atualizarValor();
    });
  });

  tipoCartao.addEventListener("change", () => {
    parcelasField.style.display = tipoCartao.value === "credito" ? "block" : "none";
    atualizarValor();
  });

  document.getElementById("parcelas").addEventListener("change", atualizarValor);

  botaoBoleto.addEventListener("click", () => {
    const passageiro = passageiros[0];
    const url = `boleto.php?valorTotal=${valorBaseTotal}`;
    window.location.href = url;
  });

  confirmarPixBtn.addEventListener("click", () => {
    window.location.href = "pix-confirmado.php";
  });

  confirmarCartaoBtn.addEventListener("click", () => {
    const numero = document.getElementById("numeroCartao").value;
    const tipo = tipoCartao.value;

    if (numero.length !== 16 || isNaN(numero)) {
      alert("Número do cartão inválido.");
      return;
    }

    if (!tipo) {
      alert("Selecione o tipo do cartão.");
      return;
    }

    window.location.href = "CARTAO_CONFIRMAÇÃO.php";
  });
  
  function atualizarValor() {
    let total = valorBaseTotal;
    const tipo = tipoCartao.value;
    const parcelas = document.getElementById("parcelas").value;
    const metodo = Array.from(radioPagamentos).find(r => r.checked)?.value;

    if (metodo === "cartao" && tipo === "credito") {
      if (parcelas === "2" || parcelas === "3") {
        const vezes = parseInt(parcelas);
        const parcela = (valorBaseTotal / vezes).toFixed(2);
        valorFinal.innerText = `Valor: R$ ${parcela} x${vezes}`;
        return;
      }
    }

    valorFinal.innerText = `Valor: R$ ${valorBaseTotal.toFixed(2)} à vista`;
  }
</script>

</body>
</html>
