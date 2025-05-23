<?php 
session_start();
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
        <link rel="stylesheet" href="../css/style-layout.css">
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
                            <hgroup>
                                <h1 class="ubuntu-bold mb-3">Acesso negado</h1>
                                <h2 class="ubuntu-bold mb-3">Você não tem permissão para acessar esta página.</h2>
                            </hgroup>
                        </header>
                            <p class="ubuntu-light">
                                Desculpe, mas parece que você não possui as credenciais necessárias para visualizar este conteúdo. Isso pode acontecer se:
                            </p>

                            <ul class="ubuntu-light">
                                <li>Você não está logado(a) ou sua sessão expirou.</li>
                                <li>Esta área é restrita a determinados usuários ou administradores.</li>
                            </ul>
                        
                            <a class="mt-3 mb-2 btn btn-primary w-100" href="../login.php"><span class="ubuntu-bold">Voltar</span></a>
                        <footer class="mt-3 text-left">
                            <small class="ubuntu-light">Agradecemos pela compreensão!</small>
                        </footer>
                    </section>     
                </div>
            </div>  
        </main>
    </body>
</html>