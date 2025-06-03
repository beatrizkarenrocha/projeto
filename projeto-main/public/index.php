<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="PT-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/slider-.css">
    <link rel="shortcut icon" type="image/x-icon" href="imagens/power pc-icon-2.png">
    <title>POWER PC | Página Inicial</title>
</head>
<body>
    <div class="navbar show-menu">
        <div class="header-inner-content">
            <h1 class="logo">POWER<span>PC</span></h1>
            <nav>
                <ul>
                    <li><a href="index.php">Página Inicial</a></li>
                    <li><a href="produtos.php">Produtos</a></li>
                    <li><a href="minha-conta.php">Minha Conta</a></li>
                </ul>
            </nav>
            <div class="login-cadastro">
                <p>Bem-vindo, <?php echo $_SESSION['usuario']['nome']; ?></p>
                <a href="../includes/logout.php">Sair</a>
            </div>
        </div>
    </div>

    <header>
        <div class="header-inner-content">
            <div class="header-bottom-side">
                <div class="header-bottom-side-left">
                    <h2>Dê Um Novo Estilo A Suas Tecnologias!</h2>
                    <p>Hardwares de última geração com a POWER PC.</p>
                    <button class="botton-ver-agora"><a href="">Ver Agora &#8594;</a></button>
                </div>
                <div class="header-bottom-side-right">
                    <img src="../imagens/gaming-msi-header.png" alt="">
                </div>
            </div>
        </div>
    </header>

    <main>
        <!-- Slider Section -->
        <div class="gray-background-gray">
            <div class="slider">
                <div class="slides">
                    <!-- Inicio Radio Buttons-->
                    <input type="radio" name="radio-btn" id="radio1">
                    <input type="radio" name="radio-btn" id="radio2">
                    <input type="radio" name="radio-btn" id="radio3">
                    <input type="radio" name="radio-btn" id="radio4">
                    <!--Fim Radio Buttons-->

                    <!--Inicio Slider images-->
                    <div class="slide first">
                        <img src="../imagens/placa mãe (slider).jpg" alt="Imagem 1" width="800px" height="500px">
                    </div>
                    <div class="slide">
                        <img src="../imagens/memória ram (slider).jpg" alt="Imagem 2" width="800px" height="500px">
                    </div>
                    <div class="slide">
                        <img src="../imagens/processador -intel-700.png" alt="Imagem 3" width="800px" height="500px">
                    </div>
                    <div class="slide">
                        <img src="../imagens/placa de vídeo 2.png" alt="Imagem 4" width="800px" height="500px">
                    </div>
                    <!--Fim Slider images-->
                </div>
            </div>
        </div>

        <!-- Produtos Section -->
        <div class="page-inner-content">
            <h3 class="section-title">Produtos Selecionados</h3>
            <div class="subtitle-underline"></div>
            <div class="cols cols-4">
                <div class="product">
                    <img src="../imagens/product-4.png" alt="">
                    <p class="product-name">Kit Periféricos Gamer Meetion</p>
                    <p class="rate">&#9733;&#9733;&#9733;&#9733;&#9734;</p>
                    <p class="product-price">147,39 <span>R$</span></p>
                </div>
                <div class="product">
                    <img src="../imagens/product-5.png" alt="">
                    <p class="product-name">Placa de Vídeo GTX 1650 D6 Ventus XS OCV3 MSI NVIDIA GeForce</p>
                    <p class="rate">&#9733;&#9733;&#9733;&#9733;&#9734;</p>
                    <p class="product-price">1.172,99 <span>R$</span></p>
                </div>
                <!-- Adicione outros produtos aqui -->
            </div>
        </div>
    </main>

    <footer>
        <p class="text-footer"> &copy; POWER PC - 2025</p>
    </footer>

    <script src="../js/script.js"></script>
</body>
</html>
