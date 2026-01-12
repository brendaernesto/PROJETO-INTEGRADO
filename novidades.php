<?php
$conn = new mysqli("localhost", "root", "", "loja");
if ($conn->connect_error) {
  die("Erro na conexão: " . $conn->connect_error);
}

$sql = "SELECT nome, descricao, imagem FROM produtos WHERE novidade = 0";
$resultado = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Novidades - Hair Care</title>
  <link rel="stylesheet" href="styles.css" />
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet" />
</head>

<body>
<div class="page-container">
  <!-- Cabeçalho -->
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
    <div class="header-right">CONHEÇA NOSSAS NOVIDADES</div>
</header>

 
  <!-- Conteúdo -->
  <main>
    <section class="about-section">
      <div class="about-container" style="flex-wrap: wrap; gap: 60px; justify-content: center;">
        <?php if ($resultado && $resultado->num_rows > 0): ?>
          <?php while ($row = $resultado->fetch_assoc()): ?>
            <div style="flex: 0 0 300px; background: #fff8f2; border-radius: 16px; padding: 20px; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
              <img src="imgs/<?php echo htmlspecialchars($row['imagem']); ?>" alt="Imagem do produto" style="width: 200px; height: 200px; object-fit: cover; border-radius: 50%; margin-bottom: 20px;" />
              <h2 style="color: #5a2c1a; font-size: 20px;"><?php echo htmlspecialchars($row['nome']); ?></h2>
              <p style="color: #555; font-size: 14px;"><?php echo nl2br(htmlspecialchars($row['descricao'])); ?></p>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p style="text-align:center;">Nenhuma novidade encontrada.</p>
        <?php endif; ?>
      </div>
    </section>
  </main>

  <!-- Rodapé -->
  <footer>
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
          <li><a href="index.php">Início</a></li>
          <li><a href="sobre.html">Sobre</a></li>
          <li><a href="produtos4.php">Produtos</a></li>
          <li><a href="novidades.php">Novidades</a></li>
          <li><a href="contatos.html">Contatos</a></li>
        </ul>
      </div>
      <div class="footer-section contact">
        <h3>Contatos</h3>
        <p>Rua da Beleza, 123 - Rio de Janeiro, RJ</p>
        <p>(21) 98765-4321</p>
        <p>contato@haircare.com</p>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; 2025 HAIR CARE. Todos os direitos reservados.</p>
    </div>
  </footer>
</div>
</body>
</html>
<?php $conn->close(); ?>