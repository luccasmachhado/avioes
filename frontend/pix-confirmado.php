<?php
require_once(__DIR__ . '/../server/checkout_cache/cheackout_cache_delete.php');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Pagamento Confirmado - Pix</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #e3ffe6;
      color: #044d1c;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .container {
      background: white;
      border: 2px solid #0f8d3c;
      border-radius: 10px;
      padding: 30px;
      text-align: center;
      max-width: 500px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    h2 {
      color: #0f8d3c;
      margin-bottom: 10px;
    }

    p {
      font-size: 16px;
      margin: 10px 0;
    }

    .comprovante {
      margin-top: 20px;
      background: #f0fff3;
      padding: 15px;
      border: 1px dashed #0f8d3c;
      border-radius: 5px;
    }

    button {
      margin-top: 20px;
      background: #0f8d3c;
      color: white;
      padding: 10px 30px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }

    button:hover {
      background: #0c6d30;
    }
  </style>
</head>
<body>

  <div class="container">
    <h2>Pagamento via Pix Confirmado ✅</h2>
    <p>Obrigado pela preferência!</p>

    <div class="comprovante">
      <strong>Comprovante:</strong><br>
      Pagamento realizado com sucesso via Pix.<br>
      Código de transação: <code>#PIX123456789</code><br>
      Data: <span id="data"></span>
    </div>

    <form action='' method="post">
      <button type="submit" name="deleta_cach" value="compra">Voltar</button>
    </form>

  </div>

  <script>
    // Coloca a data atual no comprovante
    const hoje = new Date();
    const formatada = hoje.toLocaleDateString('pt-BR') + ' ' + hoje.toLocaleTimeString('pt-BR');
    document.getElementById('data').textContent = formatada;
  </script>

</body>
</html>
