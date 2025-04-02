<?php 
session_start();
include 'functions/redirecionar.php';
include 'functions/sessions.php';
//função que faz o login
login($mysqli);
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <!--Link canonico da página-->
        <link rel="canonical" href=""/>
        <!-- Meta tags -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Título, Ícone, Descrição e Cor de tema p/ navegador -->
        <title>Treino</title>
        <link rel="icon" type="image/x-icon" href="imagens/assets/icon.ico">
        <meta name="description" content="">
        <meta name="theme-color" content="#FFFFFF">
        <!-- Link CSS -->
        <link rel="stylesheet" href="css/style-layout.css">
        <link rel="stylesheet" href="css/style-registro-login.css">
        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <!--Bootstrap 5 JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <!--FONTAWESOME JS-->
        <script src="https://kit.fontawesome.com/6afdaad939.js" crossorigin="anonymous">      </script>
        <!-- Fontes Google -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    </head>
    <body>
        <main>
            <div class="container">
                <div class="row d-flex justify-content-center mt-2">
                    <section id="feed" class="col-11 col-lg-6">
                        <header>
                            <h1 class="ubuntu-bold mb-3">Entre na sua conta</h1>
                        </header>

                        <form method="POST" class="mt-3">
                            <div class="form-group">
                                <input  required type="email" class="form-control" id="email" name="email" placeholder="E-mail">
                                <?php if (!empty($_SESSION['errosLogin']['email'])): ?>
                                    <p class="erro"><?php echo htmlspecialchars($_SESSION['errosLogin']['email']); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <input required type="password" class="form-control" id="senha" name="senha" placeholder="Senha">
                                <?php if (!empty($_SESSION['errosLogin']['senha'])): ?>
                                    <p class="erro"><?php echo htmlspecialchars($_SESSION['errosLogin']['senha']); ?></p>
                                <?php endif; ?>
                            </div>
                            <button class="btn btn-primary w-100" name="submit" type="submit"><span class="ubuntu-bold">Entrar</span></button>
                        </form>

                        <footer class="mt-3 text-left">
                            <span class="ubuntu-light">Não tem uma conta? <a href="registro.php"> Inscreva-se</a></span>
                        </footer>
                    </section>     
                </div>
            </div>  
        </main>
    </body>
</html>