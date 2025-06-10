<?php
  require_once(__DIR__ . '/../server/passagem/put_passagem.php');

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

  $voosCarrinho = $_SESSION['voosCarrinho'];

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Checkout</title>
  <link rel="stylesheet" href="checkstyle.css" />
</head>
<body>
  <div class="top-bar">
    <span>✈️ Checkout</span>
    <button class="toggle-dark" onclick="toggleDarkMode()">🌓 Modo Escuro</button>
  </div>

  <div class="container">
    <h1>Checkout</h1>

    <?php
    foreach($voosCarrinho as $item){
      $voo = $item['voo'];
      $passagem = $item['passagem'];
    
    echo
    '<div class="section">
      <div class="section-title">Passagem de Nº '.$passagem['id'].'</div>
      <div class="info-box"><label>Preco Base</label><input type="text" value="'.$passagem['valor'].'" disabled /></div>
      <div class="info-box"><label>Desembarque:</label><input type="text" value="'.$voo['origem'].'" disabled /></div>
      <div class="info-box"><label>Destino</label><input type="text" value="'.$voo['destino'].'" disabled /></div>
      <div class="info-box"><label>Data de Embarque</label><input type="text" value="'.$voo['data_partida'].'" disabled /></div>
      <div class="info-box"><label>Data de Desembarque</label><input type="text" value="'.$voo['data_chegada'].'" disabled /></div>
      <div class="info-box">      
        <label>Voo de Nº '.$voo['id'].' - '.$voo['cidade']['nome'].' - '.$voo['destino'].' ('.$voo['linha_aerea']['nome'].')</label>
        <div class="info-box">
          <label>Origem</label>
          <input type="text" value="'.$voo['linha_aerea']['pais'].'" disabled />
          <input type="text" value="'.$voo['origem'].'" disabled />
        </div>
        <div class="info-box">
          <label>Destino</label>
          <input type="text" value="'.$voo['cidade']['pais'].'" disabled />
          <input type="text" value="'.$voo['cidade']['estado'].'" disabled />
          <input type="text" value="'.$voo['cidade']['nome'].'" disabled />
          <input type="text" value="'.$voo['destino'].'" disabled />
        </div>
      </div>
    </div>';
    }
    ?>

    <form action="bilhetes.html" method="GET">
      <label for="qtdPassageiros">Quantidade de Passageiros:</label>
      <input type="number" id="qtdPassageiros" name="qtd" min="1" max="10" value="1" required />
    </form>

    <div class="section">
      <div class="section-title">Dados dos Passageiros</div>
      <div id="passageirosContainer"></div>
    </div>

    <button id="finalizarBtn" class="btn">Finalizar Compra</button>
  </div>

<script>
  const qtdPassageiros = document.getElementById('qtdPassageiros');
  const passageirosContainer = document.getElementById('passageirosContainer');

  let assentosOcupados = [];
  let dadosPassageiros = [];

  function calcularIdade(dataNascimento) {
    const hoje = new Date();
    const nascimento = new Date(dataNascimento);
    let idade = hoje.getFullYear() - nascimento.getFullYear();
    const m = hoje.getMonth() - nascimento.getMonth();
    if (m < 0 || (m === 0 && hoje.getDate() < nascimento.getDate())) {
      idade--;
    }
    return idade;
  }
  <?php
    $idVooDaCheck; 
    foreach($voosCarrinho as $item){
      $voo = $item['voo'];
      $idVooDaCheck = $item['voo']['id'];
      break; 
    }
    $stmtAssentos = $pdo->prepare('SELECT * FROM voo WHERE id = :idOvoo');
    $stmtAssentos->bindParam(':idOvoo', $idVooDaCheck);
    $stmtAssentos->execute();
    $VooDaCheck = $stmtAssentos->fetch(PDO::FETCH_ASSOC);
  ?>
  
  const NumereoAssentosVoo = <?php echo json_encode($VooDaCheck['assentos_disponiveis']); ?>;
  function assento(index) {
    const div = document.createElement('div');
    div.className = "mapa-assentos";
    div.innerHTML = `<strong>Escolha um assento:</strong>`;
    for (let i = 1; i <= NumereoAssentosVoo ; i++) {
      const btn = document.createElement("button");
      btn.textContent = assentosOcupados.includes(i) ? "X" : i;
      btn.disabled = assentosOcupados.includes(i);
      btn.className = "assento";
      if (!btn.disabled) {
        btn.onclick = () => {
          dadosPassageiros = coletarDados(); // salva antes de atualizar
          assentosOcupados.push(i);
          atualizarPassageiros();
        };
      }
      div.appendChild(btn);
    }
    return div;
  }

  function criarPassageiro(index) {
    const dados = dadosPassageiros[index] || {};
    const box = document.createElement('div');
    box.className = 'passageiro-box';
    box.innerHTML = `<h3>Passageiro ${index + 1}</h3>`;

    const subBox = document.createElement('div');
    subBox.className = 'sub-box';

    subBox.innerHTML = `
      <h4>Dados Pessoais</h4>
      <label>Nome Completo</label>
      <input type="text" value="${dados.nome || ''}" placeholder="Digite seu nome completo" />
      <label>RG</label>
      <input type="text" value="${dados.rg || ''}" placeholder="Digite seu RG" />
      <label>Data de Nascimento</label>
      <input type="date" value="${dados.nascimento || ''}" />
      <div class="idade-text">Idade: ${dados.idade || '-'}</div>
      <label>Possui Deficiência?</label>
      <select>
        <option value="nao" ${dados.deficiencia === 'nao' ? 'selected' : ''}>Não</option>
        <option value="sim" ${dados.deficiencia === 'sim' ? 'selected' : ''}>Sim</option>
      </select>
      <label>Plano Bem-Estar</label>
      <select></select>
      <label>Número do Voo</label>
      <input type="text" value="${dados.voo || ''}" placeholder="Ex: 7654" />
      <label>Classe</label>
      <select>
        <option ${dados.classe === 'Econômica' ? 'selected' : ''}>Econômica</option>
        <option ${dados.classe === 'Executiva' ? 'selected' : ''}>Executiva</option>
        <option ${dados.classe === 'Primeira Classe' ? 'selected' : ''}>Primeira Classe</option>
        <option ${dados.classe === 'Premium' ? 'selected' : ''}>Premium</option>
      </select>
    `;

    const inputs = subBox.querySelectorAll('input');
    const selectDeficiencia = subBox.querySelectorAll('select')[0];
    const selectPlano = subBox.querySelectorAll('select')[1];
    const selectClasse = subBox.querySelectorAll('select')[2];
    const idadeText = subBox.querySelector('.idade-text');
    const inputNascimento = inputs[2];

    inputNascimento.addEventListener('input', () => {
      const idade = calcularIdade(inputNascimento.value);
      idadeText.innerText = isNaN(idade) ? 'Idade: -' : `Idade: ${idade} anos`;
    });

    function atualizarPlano() {
      const temDeficiencia = selectDeficiencia.value === 'sim';
      selectPlano.innerHTML = '';
      const planos = temDeficiencia ? ['Familiar', 'PCD', 'Conforto'] : ['Familiar', 'Conforto'];
      planos.forEach(plano => {
        const opt = document.createElement('option');
        opt.value = plano;
        opt.text = plano;
        if (plano === dados.plano) opt.selected = true;
        selectPlano.appendChild(opt);
      });
    }

    selectDeficiencia.addEventListener('change', atualizarPlano);
    atualizarPlano();

    box.appendChild(subBox);
    box.appendChild(assento(index));
    return box;
  }

  function coletarDados() {
    const caixas = document.querySelectorAll('.passageiro-box');
    const resultado = [];

    caixas.forEach((box, index) => {
      const inputs = box.querySelectorAll('input');
      const selects = box.querySelectorAll('select');
      const nascimento = inputs[2].value;
      const idade = calcularIdade(nascimento);

      resultado.push({
        nome: inputs[0].value,
        rg: inputs[1].value,
        nascimento: nascimento,
        idade: isNaN(idade) ? '-' : idade,
        deficiencia: selects[0].value,
        plano: selects[1].value,
        voo: inputs[3].value,
        classe: selects[2].value,
      });
    });

    return resultado;
  }

  function atualizarPassageiros() {
    passageirosContainer.innerHTML = '';
    const quantidade = parseInt(qtdPassageiros.value) || 1;
    for (let i = 0; i < quantidade; i++) {
      const passageiro = criarPassageiro(i);
      passageirosContainer.appendChild(passageiro);
    }
  }

 qtdPassageiros.addEventListener('input', () => {
  if (qtdPassageiros.value < 1) qtdPassageiros.value = 1;
  else if (qtdPassageiros.value > 10) qtdPassageiros.value = 10;

  dadosPassageiros = coletarDados(); // <- coleta antes de atualizar!
  atualizarPassageiros();
});


  function toggleDarkMode() {
    document.body.classList.toggle('dark');
  }

  atualizarPassageiros();

  // NOVO: adicionar evento para o botão Finalizar Compra
  const btnFinalizar = document.getElementById('finalizarBtn');
  btnFinalizar.addEventListener('click', (e) => {
    e.preventDefault();

    const passageiros = coletarDados();

    if (passageiros.length === 0) {
      alert('Por favor, preencha os dados dos passageiros.');
      return;
    }

    // Salva no localStorage
    localStorage.setItem('passageiros', JSON.stringify(passageiros));

    // Redireciona para a página dos bilhetes
    window.location.href = 'finalização.html';
  });

// Botão Voltar
document.getElementById('voltarBtn').addEventListener('click', () => {
  window.location.href = 'finalização.html'; // ajuste se sua página inicial for diferente
});




</script>
</body>
</html>
