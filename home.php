<?php 
session_start();
include 'functions/feed.php';
include 'functions/ui-padrao.php';
include 'functions/sessions.php';
include 'functions/feedback.php';
include 'functions/acesso-redirecionar.php';
//função que recebe a pesquisa pelo get e encaminha para o explorar.php
resultadoPesquisaHOME($mysqli);
//função que controla acesso apenas para usuários logados
acessoLogado();
//função que atualiza dados do usuário
dadosUsuario($mysqli);
//função que recebe dados do form de nova postagem e adiciona no db
adicionaPostagemDB($mysqli);
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
                <div class="row d-flex mt-2">
                    <aside id="ferramentas" class="col-2 col-lg-3">
                        <!--FUNÇÃO PHP QUE INJETA A BARRA DE FERRAMENTAS-->
                        <?php barraFerramentas(); ?>
                    </aside>
                    <section id="feed" class="col-10 col-lg-6">

                    <!--FEEDBACK USUÁRIO-->
                        <!--FUNÇÃO QUE MOSTRA FEEDBACKS DO SISTEMA-->
                        <section id="feedback-usuario" class="text-center p-2 mb-2">
                            <?php feedbackSistema(); ?>
                        </section>

                        <!--SCRIPT JS QUE FAZ A BARRA DE FEEDBACK SUMIR APÓS 3s-->
                        <script src="functions/barra-feedback.js"></script>

                    <!--ENVIAR NOVA POSTAGEM-->
                        <!--FUNÇÃO PHP QUE INJETA O FORM PARA NOVA POSTAGEM-->
                        <?php novaPostagem(); ?>

                    <!--FEED-->
                        <!--FUNÇÃO PHP QUE INJETA O FEED DOS POSTS DE QUEM EU SIGO-->
                        <?php feedSigo($mysqli);?>
                        <!--FUNÇÃO PHP QUE INJETA PERFIS SUGERIDOS PARA SEGUIR-->
                        
                            
                    </section>
                    <aside id="explorar" class="d-none d-lg-block col-lg-3">
                        <!--FUNÇÃO QUE INJETA A BARRA DE PESQUISA ESPECIAL DA HOME - QUE ENCAMINHA PARA O EXPLORAR + PESQUISA NO GET-->
                        <?php novaPesquisaHOME($mysqli);?>
                    </aside>       
                </div>
            </div>  
        </main>
    </body>
</html>