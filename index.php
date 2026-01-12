<?php

session_start();

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hair Care</title>
  <link rel="stylesheet" href="styles.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>
    <div class="page-container">
  <header>
    <header class="navbar">
      <div class="nav-logo">
        <img src="imgs/logobeleza.png" alt="Logo" style="height: 80px; margin-left: -70px;">
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

<?php if (isset($_SESSION['usuario'])): ?>
  <div class="user-greeting" style="margin-left: auto; display: flex; align-items: center; gap: 15px; padding: 10px; font-weight: bold;">
    <span>Olá, <?= htmlspecialchars($_SESSION['usuario']) ?></span>
    <a href="logout.php" class="logout-button">Sair</a>

  </div>
<?php endif; ?>
    


    </header>
    
    <div class="header-right">MAKING YOU AND YOUR HAIR SHINE</div>
  </header>

  <section class="hero">
    <div class="hero-text">
      <h1 style="font-family: 'Poppins', sans-serif;"> Seu cabelo, seu brilho<br><span>Tratamentos que revelam a verdadeira saúde dos seus fios</span></h1>
      <p>Mantenha seu cabelo macio e forte com nossos produtos diários de cuidados capilares.</p>
      <button>Mais Informações</button>
      <div class="social-icons">
        <img src="https://img.icons8.com/ios-filled/20/000000/facebook-new.png"/>
        <img src="https://img.icons8.com/ios-filled/20/000000/twitter.png"/>
        <img src="https://img.icons8.com/ios-filled/20/000000/instagram-new.png"/>
      </div>
    </div>

    <div class="hero-image">
      <img src="imgs/fotositeprincipal.png" alt="Imagem Promocional">
    </div>

    
    <div class="hero-login">
       <?php if (isset($_GET['erro']) && $_GET['erro'] == 1): ?>
    <div style="color: red; font-weight: bold; padding: 10px;">
      Usuário ou senha inválidos.
    </div>
  <?php endif; ?>

      <form action="login.php" method="POST" class="login-form">
        <h2 style="font-family: 'Poppins', sans-serif;">Junte-se a nós!</h2>
        <input type="text" name="username" placeholder="Usuário" required>
        <input type="password" name="password" placeholder="Senha" required>
        <button type="submit">Entrar</button>
        <p>Não tem uma conta? <a href="register.html">Cadastre-se</a></p>
      </form>
    </div>
  </section>

  <section class="carousel-and-description-wrapper">
    <div class="product-section">
      <div class="auto-carousel">
        <div class="auto-track">
          <img src="imgs/produtoamarelo_redondo.png" alt="Produto 1" class="auto-slide">
          <img src="imgs/produtoinfantil_redondo.png" alt="Produto 2" class="auto-slide">
          <img src="imgs/produtoverde_redondo.png" alt="Produto 3" class="auto-slide">
          <img src="imgs/belezahair_round.png" alt="Produto 4" class="auto-slide">
          <img src="imgs/produtoamarelo_redondo.png" alt="Produto 1" class="auto-slide">
          <img src="imgs/produtoinfantil_redondo.png" alt="Produto 2" class="auto-slide">
        </div>
      </div>
    </div>
  </section>

  <footer>
  <div class="footer-container">
    <div class="footer-section about">
      <h3>HAIR CARE</h3>
      <p>Seu cabelo, seu brilho. Tratamentos que revelam a verdadeira saúde dos seus fios.</p>
      <div class="social-links-footer">
        <a href="#"><img src="https://img.icons8.com/ios-filled/20/000000/facebook-new.png" alt="Facebook"></a>
        <a href="#"><img src="https://img.icons8.com/ios-filled/20/000000/twitter.png" alt="Twitter"></a>
        <a href="#"><img src="https://img.icons8.com/ios-filled/20/000000/instagram-new.png" alt="Instagram"></a>
      </div>
    </div>

    <div class="footer-section links">
      <h3>Links Rápidos</h3>
      <ul>
        <li><a href="index.php">Início</a></li>
        <li><a href="sobre.html">Sobre</a></li>
        <li><a href="produtos.php">Produtos</a></li>
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
</body>
</html>