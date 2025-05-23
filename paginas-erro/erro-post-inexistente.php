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
                    <header class="mb-3 d-flex justify-content-between align-items-center">
                        <a class="d-inline me-1 voltar-perfil p-1" href="../home.php"><i class="fa-solid px-1 fa-arrow-left fa-md" style="color: #FFFFFF;"></i></a>
                        <h1 class="ubuntu-bold d-inline m-0 p-0">Post Inexistente</h1>
                        <small class="ubuntu-light d-none d-md-inline"> 0000-00-00</small>
                    </header>

                    <article class="post p-3">
                        <header>
                            <section class="d-flex align-items-center">
                                <figure>
                                    <img class="avatar-perfil-postagem" src="../images/avatars/default.webp" alt="Avatar do usuário">
                                </figure>
                                <h3 class="ubuntu-bold">Post Não Encontrado </h3>                                        
                            </section>
                        </header>
                        <section class="mb-3">
                            <blockquote class="ubuntu-regular">
                                <p class="ubuntu-regular">
                                    Possíveis motivos: 
                                </p>    
                                <ul> 
                                    <li>O post pode ter sido excluído pelo autor.</li> 
                                    <li>O link pode estar incorreto ou ter expirado.</li> 
                                </ul>
                            </blockquote>
                            <a class="mt-3 mb-2 btn btn-primary w-100" href="../home.php"><span class="ubuntu-bold">Voltar</span></a>
                        </section>
                        <footer class="d-flex justify-content-between">
                            <small class="ubuntu-light">0000-00-00</small>
                            <span class="ubuntu-light"><i class="fa-solid fa-eye fa-md me-2" style="color: #979797;"></i>0</span>
                        </footer> 
                    </article>
                    </section>     
                </div>
            </div>  
        </main>
    </body>
</html>