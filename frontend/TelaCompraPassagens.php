<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
  <link rel="stylesheet" href="TelaCompraPassagens.css" />
</head>
<body>
  <div className="container">
    <header className="header">
    <h1>Compra de Passagens</h1>
    <button className="logout-button" onClick={handleLogout}>Logout</button>
    </header>
    <main className="main">
      <div className="user-info">
      <h2>Informações do Usuário</h2>
      <div><strong>ID:</strong> {usuario.id}</div>
      <div><strong>CPF:</strong> {usuario.cpf}</div>
      <div><strong>Senha:</strong> {usuario.senha}</div>
      </div>
    </main>
  </div>
</body>
