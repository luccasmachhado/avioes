<?php
require_once(__DIR__ . '/../server/checkout_cache/cheackout_cache_delete.php');
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Pagamento Confirmado - Cartão</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #eef6ff;
      color: #033e7b;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .container {
      background: white;
      border: 2px solid #0360b1;
      border-radius: 10px;
      padding: 30px;
      text-align: center;
      max-width: 500px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    h2 {
      color: #0360b1;
      margin-bottom: 10px;
    }

    p {
      font-size: 16px;
      margin: 10px 0;
    }

    .comprovante {
      margin-top: 20px;
      background: #f3f9ff;
      padding: 15px;
      border: 1px dashed #0360b1;
      border-radius: 5px;
    }

    button {
      margin-top: 20px;
      background: #0360b1;
      color: white;
      padding: 10px 30px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }

    button:hover {
      background: #024f94;
    }
  </style>
</head>
<body>

  <div class="container">
    <h2>Pagamento com Cartão Confirmado 💳</h2>
    <p>Obrigado pela compra!</p>

    <div class="comprovante">
      <strong>Comprovante:</strong><br>
      Pagamento aprovado com sucesso via cartão.<br>
      Código de transação: <code>#CARTAO987654321</code><br>
      Data: <span id="data"></span>
    </div>
    <form action='' method="post">
      <button type="submit" name="deleta_cach" value="compra">Voltar</button>
    </form>
  </div>

  <script>
    const hoje = new Date();
    const formatada = hoje.toLocaleDateString('pt-BR') + ' ' + hoje.toLocaleTimeString('pt-BR');
    document.getElementById('data').textContent = formatada;
  </script>

</body>
</html>
