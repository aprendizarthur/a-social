<?php 
session_start();
include 'functions/ui-padrao.php';
include 'functions/sessions.php';
include 'functions/feed.php';
include 'functions/acesso-redirecionar.php';
//função que controla acesso apenas para usuários logados
acessoLogado();
//função que verifica o ID do perfil enviado pelo GET e se ele existe no DB
verificarIDperfil($mysqli);
//função que atualiza dados do usuário
dadosUsuario($mysqli);
//função que realiza a ação de seguir/deixar de seguir usuários
gerenciarSeguidores($mysqli);
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
                <div class="row d-flex mt-2">
                    <aside id="ferramentas" class="col-2 col-lg-3">
                        <!--FUNÇÃO PHP QUE INJETA A BARRA DE FERRAMENTAS-->
                        <?php barraFerramentas(); ?>
                    </aside>
                    <section id="feed" class="col-10 col-lg-6">
                        <!--FUNÇÃO PHP QUE REALIZA CONSULTA, INJETA O PERFIL DO USUÁRIO E OS POSTS DELE-->
                        <?php exibirPerfil($mysqli); ?>

                        <!--FUNÇÃO PHP QUE EXIBE OS POSTS DO USUÁRIO-->
                        <div id="postagens-perfil">
                            <h2 class="ubuntu-bold">Posts</h2>
                            <?php postsUsuario($mysqli); ?>
                            <small class="ubuntu-light">
                                Até o final do perfil? Você é mais curioso que detetive de série!
                            </small>   
                        </div>

                    </section>
                    <aside id="explorar" class="d-none d-lg-block col-lg-3">
                        
                    </aside>       
                </div>
            </div>  
        </main>
    </body>
</html>