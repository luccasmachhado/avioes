import React from "react";
import "./TelaCompraPassagens.css";

export default function TelaCompraPassagens() {
  const usuario = {
    id: "123456",
    cpf: "123.456.789-00",
    senha: "********",
  };

  const handleLogout = () => {
    console.log("Usuário deslogado");
  };

  return (
    <div className="container">
      <header className="header">
        <h1>Compra de Passagens</h1>
        <button className="logout-button" onClick={handleLogout}>
          Logout
        </button>
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
  );
}
