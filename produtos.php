<?php
include 'conexaoloja.php'; // conexão mantida

// Puxa produtos do banco
$result = $conn_loja->query("SELECT nome, quantidade, imagem FROM produtos");
$produtos = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Hair Care</title>
  <link rel="stylesheet" href="styles.css"/>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet" />

  <style>
    /* Imagem maior e estilizada */
    .carousel-image {
      width: 320px;
      height: auto;
      border-radius: 12px;
      box-shadow: 0 6px 12px rgba(0,0,0,0.15);
      transition: transform 0.3s ease;
      margin: 0 auto;
      display: block;
    }
    .carousel-image:hover {
      transform: scale(1.05);
    }

    /* Botões de navegação estilosos */
    .btn-carousel {
      background-color: #5a381e;
      color: white;
      border: none;
      font-size: 2.5rem;
      width: 60px;
      height: 60px;
      border-radius: 50%;
      cursor: pointer;
      display: inline-flex;
      justify-content: center;
      align-items: center;
      box-shadow: 0 4px 8px rgba(90, 56, 30, 0.6);
      border: 2px solid #4b2e15;
      transition: background-color 0.3s ease, box-shadow 0.3s ease;
      user-select: none;
      margin: 0 10px;
    }
    .btn-carousel:hover {
      background-color: #7b5331;
      box-shadow: 0 6px 12px rgba(123, 83, 49, 0.8);
    }
  </style>
</head>

<body>
  <div class="page-container" style="min-height: 100vh; display: flex; flex-direction: column;">
    <header>
      <header class="navbar">
        <div class="nav-logo">
          <img src="imgs/logobeleza.png" alt="Logo" style="height: 80px; margin-left: -70px;" />
          <div class="logo-text">BELEZA HAIR CARE</div>
        </div>

        <nav>
          <ul class="nav-links" id="nav-links">
            <li><a href="index.php">Início</a></li>
            <li><a href="sobre.html">Sobre</a></li>
            <li><a href="produtos.php">Produtos</a></li>
            <li><a href="novidades.php">Novidades</a></li>
            <li><a href="contatos.html">Contatos</a></li>
          </ul>
        </nav>
        <div class="nav-toggle" id="nav-toggle">&#9776;</div>
      </header>

      <div class="header-right">MAKING YOU AND YOUR HAIR SHINE</div>
    </header>

    <main style="flex-grow: 1;">
      <section class="hero" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
        <div class="hero-text" style="flex: 1; min-width: 300px;">
          <h1 style="font-family: 'Poppins', sans-serif;">Conheça nossos Produtos</h1>
          <p>Veja aqui os produtos disponíveis e suas quantidades em estoque.</p>
          <br> <br>
          <button onclick="window.location.href='index.html'">Voltar para o Início</button>
          <div class="social-icons">
            <img src="https://img.icons8.com/ios-filled/20/000000/facebook-new.png" alt="Facebook" />
            <img src="https://img.icons8.com/ios-filled/20/000000/twitter.png" alt="Twitter" />
            <img src="https://img.icons8.com/ios-filled/20/000000/instagram-new.png" alt="Instagram" />
          </div>
        </div>

        
      
<div class="carousel-produtos" style="flex: 1; min-width: 300px; text-align: center;">
  <img id="carouselImage" src="" alt="Produto" style="width: 500px; height: auto; border-radius: 50%; background: transparent; box-shadow: none; display: block; margin: 0 auto;" />
  <br><br>
  <h3 id="carouselNome" style="margin-top: 10px;"></h3>
  <br><br>
  <p id="carouselQuantidade"></p>
  <br><br><br>
  <div style="margin-top: 10px;">
    <button class="btn-carousel btn-prev" onclick="mudarProduto(-1)">&lt;</button>
    <button class="btn-carousel btn-next" onclick="mudarProduto(1)">&gt;</button>
  </div>
</div>

        </div>
      </section>
    </main>

    <footer style="flex-shrink: 0;">
      <div class="footer-container">
        <div class="footer-section about">
          <h3>HAIR CARE</h3>
          <p>Seu cabelo, seu brilho. Tratamentos que revelam a verdadeira saúde dos seus fios.</p>
          <div class="social-links-footer">
            <a href="#"><img src="https://img.icons8.com/ios-filled/20/000000/facebook-new.png" alt="Facebook" /></a>
            <a href="#"><img src="https://img.icons8.com/ios-filled/20/000000/twitter.png" alt="Twitter" /></a>
            <a href="#"><img src="https://img.icons8.com/ios-filled/20/000000/instagram-new.png" alt="Instagram" /></a>
          </div>
        </div>

        <div class="footer-section links">
          <h3>Links Rápidos</h3>
          <ul>
            <li><a href="index.html">Início</a></li>
            <li><a href="sobre.html">Sobre</a></li>
            <li><a href="produtos.php">Produtos</a></li>
            <li><a href="#novidades">Novidades</a></li>
            <li><a href="contatos.html">Contatos</a></li>
          </ul>
        </div>

        <div class="footer-section contact">
          <h3>Contatos</h3>
          <p><i class="fas fa-map-marker-alt"></i> Rua da Beleza, 123 - Rio de Janeiro, RJ</p>
          <p><i class="fas fa-phone"></i> (21) 98765-4321</p>
          <p><i class="fas fa-envelope"></i> contato@haircare.com</p>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; 2025 HAIR CARE. Todos os direitos reservados.</p>
      </div>
    </footer>
  </div>

  <script>
    const produtos = <?= json_encode($produtos) ?>;
    let indice = 0;

    function exibirProduto(i) {
      const p = produtos[i];
      document.getElementById("carouselImage").src = "imgs/" + p.imagem;
      document.getElementById("carouselNome").textContent = p.nome;
      document.getElementById("carouselQuantidade").textContent = "Quantidade em estoque: " + p.quantidade;
    }

    function mudarProduto(direcao) {
      indice = (indice + direcao + produtos.length) % produtos.length;
      exibirProduto(indice);
    }

    window.onload = () => {
      if (produtos.length > 0) {
        exibirProduto(0);
      } else {
        document.getElementById("carouselImage").style.display = "none";
        document.getElementById("carouselNome").textContent = "Nenhum produto disponível.";
        document.getElementById("carouselQuantidade").textContent = "";
      }
    };
  </script>
</body>
</html>