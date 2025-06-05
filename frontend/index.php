<?php    
    require_once(__DIR__ . '/../server/voo/get_voo.php');
    require_once(__DIR__ . '/../server/usuario/logout.php');
    session_start()
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skyline</title>
    <link rel="stylesheet" href="perfil.css">
    <link rel="shortcut icon" href="Imagens/Logoi2.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
    <script>
            window.onload = function(){
            const urlParams = new URLSearchParams(window.location.search);
            if(urlParams.get('mensagem') === 'compra_sucesso'){
                alert("Compra efetuada com êxito!");
            }
            if(urlParams.get('mensagem') === 'add_car_sucesso'){
                alert("O item foi adcionado ao Carrinho!");
            }}
            
            function toggleDropdown() {
                const dropdown = document.getElementById("perfil-dropdown");
                dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
            }

            window.addEventListener("click", function(event) {
                const dropdown = document.getElementById("perfil-dropdown");
                const bolinha = document.querySelector(".perfil-bolinha");
                if (dropdown && bolinha && !bolinha.contains(event.target) && !dropdown.contains(event.target)) {
                    dropdown.style.display = "none";
                }
            });
    </script>
</head>
<body>
    <header>
    <nav id="menu"> 
        <a class="opc" href="Passagens.php">Comprar</a>
        <a class="opc" href="#">Viagens</a>
        <a class="opc" href="#">Sobre</a>
        <a class="opc" href="carrinho.php">Carrinho</a>
         <?php if (
            !isset($_SESSION['usuario']) ||
            !isset($_SESSION['usuario']['cpf']) ||
            !isset($_SESSION['usuario']['senha']) ||
            !isset($_SESSION['usuario']['id'])
        ) { 
            echo '<a class="opc" href="login.html">Login</a>';
        }else{
            $inicial = strtoupper(substr($_SESSION['usuario']['nome'], 0, 1));
            echo '<div class="perfil-container">
            <div class="perfil-bolinha" onclick="toggleDropdown()">'.$inicial.'</div>
            <div id="perfil-dropdown" class="perfil-dropdown">
                <a href="tela_usuario.php">Perfil</a>
                <form action="../server/usuario/logout.php" method="post">
                    <input type="hidden" name="logout" value="htmlspecialchars(logout)">
                    <button type="submit">Logout</button>
                </form>
            </div>
            </div>';
        } ?>
    </nav>
    <picture>
        <img src="Imagens/Logo.png" alt="Logo">
    </picture>
    </header>
    <main>
        <section id="Viagens">
                <h2>Seu próximo destino inesquecível começa aqui</h2>
                <section id="ny">
                    <?php
                        $i = "0";
                        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                            echo "<div class='destino'><h3>".$row['destino']."</h3><pictire><img src='Imagens/".$row['imagem'].$i.".jpg'></pictire><p>Aeroporto de ".$row['origem']."</p></div>";
                            $i = $i+1;
                            if($i <= 2){$i = $i+1;}else{$i=0;}
                        }
                    ?>
                </section>
        </section>
        <section id="Passagens">
            <form action="passagens.php" method="get">
                <div class="passg">
                    <div id="origem">
                          <select name="iCity" id="Cityv">
                        <option  value="Op">Origem</option>
                        <option  value="Ft">Fortaleza</option>
                        <option  value="Mn">Maracanau</option>
                        <option  value="Bh">Bahia</option>
                        <option  value="Rj">Rio de Janeiro</option>
                        <option  value="SP">São Paulo</option>
                        </select>
                    </div>
                    <div id="destino">
                        <label for="CityI"></label>
                        <select name="iCityi" id="Cityi">
                        <option value="">Destino</option>
                        <option value="NY">Nova York</option>
                        <option value="RM">Coliseu da Roma</option>
                        <option value="Bg">Belgica</option>
                        <option value="Fç">França</option>
                        <option value="Pr">Paris</option>
                    </select>
                    </div>
                    <div id="idavolta">
                        <input id="datas" placeholder="Ida e Volta">
                        <script>
                        flatpickr("#datas", {
                        mode: "range",
                        dateFormat: "d/m/Y",
                        locale: "pt"
                        });
                        </script>
                    </div>
                    <div id="button">
                        <button type="submit"><img src="Imagens/icon-search.svg" alt=""></button>                            
                    </div>
                </div>

                    </form>
            </section>
        <section id="Familia">
                <h2>Quem viaja com a Skyline, recomenda!</h2>
            <section id="family">
                <div class="family1">
                    <p id="leg">"Viajar com a Skyline foi incrível! Fomos muito bem atendidos desde o início, e o Plano Bem-Estar Especial foi perfeito pra nós. Nossos filhos são autista e ficaram super à vontade graças ao cuidado e à atenção da equipe. Tudo foi feito com carinho, conforto e respeito. Com certeza, a Skyline fez dessa viagem algo inesquecível!" <br> <br> 
                — Família Oliveira, São Paulo/SP</p>
                </div> <
                <div class="family1">
                    <picture>
                        <img id="imgfamily" src="Imagens/familiaautista.webp" alt="">
                    </picture>
                </div>
            </section>
            <section id="family">
                <div class="family1">
                    <picture>
                        <img id="imgfamily" src="Imagens/familia.avif" alt="">
                    </picture>
                </div>
                <div class="family1">
                    <p id="leg">Viajar com a Skyline foi uma experiência incrível! O Plano Bem-Estar Família nos ofereceu conforto e praticidade do início ao fim. Fomos muito bem atendidos, e as crianças aproveitaram cada momento do voo. A equipe foi atenciosa e nos fez sentir acolhidos. Já estamos planejando a próxima viagem com a Skyline! <br> <br>
                    — Família Martins, Curitiba/PR
                    </p>
                </div>
            </section>
        </section>
        <section id="Planos">
            <h2>Skyline – Viagens para todos, com planos pensados para você</h2>
            <section id="planosOpc">
                <div class="planosfic">
                    <h3>Plano Bem-Estar Família</h3>
                    <picture>
                        <img src="Imagens/familia.jpg" alt="">
                    </picture>
                    <ul>
                        <li>Suporte 24h</li>
                        <li>50% off para crianças</li>
                        <li>Descontos para grupos</li>
                        <li>Lazer para todas as idades</li>
                        <li>Hospedagem infantil gratuita</li>
                    </ul>
                </div>
                <div class="planosfic">
                    <h3>Plano Bem-Estar Especial</h3>
                    <picture>
                        <img src="Imagens/Autismo.webp" alt="">
                    </picture>
                     <ul>
                        <li>Suporte 24h</li>
                        <li>Ambientes calmos</li>
                        <li>Atendimento especializado</li>
                        <li>Prioridade no embarque</li>
                        <li>Atividades adaptadas</li>
                    </ul>
                </div>
                <div class="planosfic">
                    <h3>Plano Bem-Estar Conforto</h3>
                    <picture>
                        <img src="Imagens/Obeso.jpg" alt="">
                    </picture>
                     <ul>
                        <li>Assentos espaçosos</li>
                        <li>Hospedagem adaptada</li>
                        <li>Atendimento justo</li>
                        <li>Refeições balanceadas</li>
                        <li>Suporte especializado</li>
                    </ul>
                </div>
            </section>
        </section>
        <section id="Promo">
            <h2>Promoções Imperdíveis – Skyline Viagens</h2>
            <h3>Viajar bem não precisa custar caro!</h3>
          <div id="textpromo0">
            <div id="textpromo1">
                <p class="textp">Na Skyline, você encontra promoções exclusivas para transformar seu sonho de viajar em realidade — com preços que cabem no seu bolso e destinos que vão muito além das suas expectativas.</p>
           </div>
           <div id="textpromo2">
            <p class="textp">Passagens com até 30% de desconto para os destinos mais procurados do mundo.</p>

           </div>
           <div id="textpromo3">
            <p class="textp">Pacotes completos com hospedagem e passeios incluídos por preços especiais.</p>

           </div>
           <div id="textpromo4">
            <p class="textp">Válido por tempo limitado. Promoções sujeitas à disponibilidade e regulamento.</p>
           </div>
          </div>
        </section>
    </main>
</body>
</html>