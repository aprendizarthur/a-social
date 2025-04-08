<?php 
include 'conn.php';
//ARQUIVO EXCLUSIVO PARA GERENCIAR ENVIO POSTAGEM E FEED DO USUÁRIO

    //FUNÇÃO QUE IMPRIME O FORM PARA PESQUISA NA PAGINA HOME
    function novaPesquisaHOME($mysqli){
        echo '
            <section>
                <div class="row d-flex p-2 justify-content-between">
                    <div id="form-pesquisa" class="col-12 m-0">
                        <h1 class="ubuntu-bold mb-3">Explorar</h1>
                        <form method="GET">
                            <div class="d-inline form-group">
                                <input class="p-2 d-inline ubuntu-light" type="text" style="border: solid 2px #e3e7e9;" name="pesquisa" id="pesquisa" placeholder="Pesquise algo">
                            </div>
                            <button id="confirma-pesquisa" class="d-inline btn btn-primary" style="width:16%;" type="submit" name="submit-pesquisa-home"><i class="fas fa-search fa-lg"></i></button>
                        </form>   
                    </div>
                </div>
            </section>
        ';    
    }

    function resultadoPesquisaHOME($mysqli){
        if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['submit-pesquisa-home'])){
            $pesquisa = $mysqli->real_escape_string($_GET['pesquisa']);

            header("Location: explorar.php?pesquisa=" . $pesquisa);
        }
    }

    //FUNÇÃO QUE EXIBE O FORMULÁRIO PARA NOVA POSTAGEM
    function novaPostagem(){
        echo '
            <section>
                <div class="row d-flex p-2 justify-content-between">
                    <div id="form-nova-postagem" class="col-12 p-2 m-0">
                        <h1 class="ubuntu-bold mb-3">Nova postagem</h1>
                        <form method="POST">
                            <div class="form-group">
                                <textarea required spellcheck="true" class="ubuntu-regular w-100 form-control mb-2" rows="3" maxlength="150" minlength="1" id="nova-postagem" name="texto-postagem" placeholder="O que está acontecendo?"></textarea>
                                <div class="d-flex justify-content-between align-items-top">
                                    <small id="bem-pequeno" class="ubuntu-light">Limite de 150 caracteres</small>
                                    <button class="btn btn-primary w-20 ubuntu-bold" name="submit-nova-postagem">Postar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        ';
    }

    //FUNÇÃO QUE EXIBE O FORMULÁRIO PARA NOVO COMENTÁRIO
    function novoComentario(){
        echo '
            <section>
                <div class="row d-flex p-2 justify-content-between">
                    <div id="form-novo-comentario" class="col-12 p-2 m-0">
                        <h1 class="ubuntu-bold mb-3">Comentar</h1>
                        <form method="POST" id="comentar">
                            <div class="form-group">
                                <textarea required spellcheck="true" class="ubuntu-regular w-100 form-control mb-2" rows="3" maxlength="150" minlength="1" id="texto-comentario" name="texto-comentario" placeholder="O que você quer responder?"></textarea>
                                <div class="d-flex justify-content-between align-items-top">
                                    <small id="bem-pequeno" class="ubuntu-light">Limite de 150 caracteres</small>
                                    <button class="btn btn-primary w-20 ubuntu-bold" name="submit-novo-comentario">Comentar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        ';
    }

    //FUNÇÃO QUE RECEBE OS DADOS DO FORMULÁRIO DE NOVO COMENTARIO E ADICIONA O COMENTARIO AO DB
    function adicionaComentarioDB($mysqli){
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit-novo-comentario'])){
            
            $autorID = $mysqli->real_escape_string($_SESSION['id_usuario']);
            $idPostagem = $mysqli->real_escape_string($_GET['id-post']);
            $autorNome = $mysqli->real_escape_string($_SESSION['nome_usuario']);
            $avatar = $mysqli->real_escape_string($_SESSION['avatar_usuario']);
            $comentario = $mysqli->real_escape_string($_POST['texto-comentario']);

            $sql_code = "INSERT INTO comentarios (id_autor, id_post, nome_autor, avatar_autor, comentario) VALUES ('$autorID','$idPostagem','$autorNome','$avatar','$comentario')";
        
            //variavel que vai abrigar a mensagem caso a postagem seja enviada ao DB
            $feedbackSistema = "";

            if($query = $mysqli->query($sql_code)){
                $feedbackSistema = "Comentário enviado";

                //enviando o feedback para a session
                $_SESSION['feedback-sistema'] = $feedbackSistema;

                header("Location: post.php?id-post=" . $idPostagem);
                exit;
            } else {
                header("Location: paginas-erro/erro-conexao.php");
                exit;
            }
        }
    }

    //FUNÇÃO QUE RECEBE OS DADOS DO FORMULÁRIO DE NOVO POST E ADICIONA A POSTAGEM AO DB
    function adicionaPostagemDB($mysqli){
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit-nova-postagem'])){
            
            $autorID = $mysqli->real_escape_string($_SESSION['id_usuario']);
            $autorNome = $mysqli->real_escape_string($_SESSION['nome_usuario']);
            $avatar = $mysqli->real_escape_string($_SESSION['avatar_usuario']);
            $texto = $mysqli->real_escape_string($_POST['texto-postagem']);

            $sql_code = "INSERT INTO postagens (id_autor, nome, avatar, texto) VALUES ('$autorID','$autorNome','$avatar','$texto')";
        
            //variavel que vai abrigar a mensagem caso a postagem seja enviada ao DB
            $feedbackSistema = "";

            if($query = $mysqli->query($sql_code)){
                $feedbackSistema = "Postagem enviada";

                //enviando o feedback para a session
                $_SESSION['feedback-sistema'] = $feedbackSistema;
                //recarregando a página
                header("Location: home.php");
                exit;
            } else {
                header("Location: paginas-erro/erro-conexao.php");
                exit;
            }
        }
    }

    //FUNÇÃO QUE MOSTRAR OS TODOS COMENTÁRIOS DO POST NA SUA PRÓPRIA PÁGINA
    function comentariosPost($mysqli){
        $id = $mysqli->real_escape_string($_GET['id-post']);

        $sql_code = "SELECT id_autor, nome_autor, avatar_autor, data_comentario, comentario FROM comentarios WHERE id_post = '$id' ORDER BY data_comentario DESC";

        //exibindo título para sessão de comentários
        echo '
            <h1 class="ubuntu-bold mb-3">Comentários</h1>
        ';

        if($query = $mysqli->query($sql_code)){
            while($dados = $query->fetch_assoc()){
                $idAutor = $dados['id_autor'];
                $nomeAutor = $dados['nome_autor'];
                $avatarAutor = $dados['avatar_autor'];
                $comentario = $dados['comentario'];
                $dataComentario = $dados['data_comentario'];

               echo '
                    <blockquote class="post p-3">
                            <header>
                                <section class="d-flex align-items-center">
                                        <figure>
                                            <img class="border avatar-perfil-postagem "src="'. $avatarAutor .'" alt="Avatar do usuário">
                                        </figure>
                                        <h3 class="ubuntu-bold">'. $nomeAutor .'</h3>                                       
                                </section>
                            </header>
                            <section>
                                <p class="ubuntu-regular">
                                    '. $comentario .'
                                </p>
                            </section>
                            <footer class="d-flex justify-content-between">
                                <small class="ubuntu-light">'. $dataComentario .'</small>
                            </footer> 
                    </blockquote>
               ';
            }
        }
    }

    //FUNÇÃO QUE MOSTRA TODOS OS POSTS DO USUÁRIO NO PERFIL
    function postsUsuario($mysqli){
        $id = $mysqli->real_escape_string($_GET['id']);

        $sql_code = "SELECT id, id_autor, nome, avatar, texto, data_publicacao FROM postagens WHERE id_autor = '$id' ORDER BY data_publicacao DESC";
       
        if($query = $mysqli->query($sql_code)){
            while($dados = $query->fetch_assoc()){
                $idPostagem = $dados['id'];
                $textoPostagem = $dados['texto'];
                $dataPostagem = $dados['data_publicacao'];
                $visualizacoesPostagem = totalVisuPOST($mysqli, $idPostagem); 
                $comentariosPostagem = totalComPOST($mysqli,  $idPostagem);  
                $idAutor = $dados['id_autor'];
                $nomeAutor = $dados['nome'];
                $avatarAutor = $dados['avatar'];

                echo '
                    <article class="post p-3">
                        <a class="link-post" href="post.php?id-post='.$idPostagem .'">
                            <header>
                                <section class="d-flex align-items-center">
                                        <figure>
                                            <img class="border avatar-perfil-postagem "src="'. $avatarAutor .'" alt="Avatar do usuário">
                                        </figure>
                                        <h3 class="ubuntu-bold">'. $nomeAutor .'</h3>                                        
                                </section>
                            </header>
                            <section>
                                <p class="ubuntu-regular">
                                    '. $textoPostagem .'
                                </p>
                            </section>
                            <footer class="d-flex justify-content-between">
                                <small class="ubuntu-light">'. $dataPostagem .'</small>
                                <span class="ubuntu-light"><i class="fa-solid fa-comment fa-md me-2" style="color: #979797;"></i>'.$comentariosPostagem.'</span>
                                <span class="ubuntu-light"><i class="fa-solid fa-eye fa-md me-2" style="color: #979797;"></i>'.$visualizacoesPostagem.'</span>
                            </footer> 
                        </a>
                    </article>
                ';
            }
        } else {
            header("Location: paginas-erro/erro-conexao.php");
            exit;
        }
    }

    //FUNÇÃO QUE EXIBE O POST NA PÁGINA POST.PHP
    function mostrarPost($mysqli){
        $id = $mysqli->real_escape_string($_GET['id-post']);

        $sql_code = "SELECT * FROM postagens WHERE id = '$id'";

        if($query = $mysqli->query($sql_code)){
            $dados = $query->fetch_assoc();  
            $idPostagem = $dados['id'];
            $textoPostagem = $dados['texto'];
            $dataPostagem = $dados['data_publicacao'];
            $botaoRelacionamento = botaoRelacionamento($mysqli);
            $visualizacoesPostagem = totalVisuPOST($mysqli, $idPostagem); 
            $comentariosPostagem = totalComPOST($mysqli,  $idPostagem); 
            $idAutor = $dados['id_autor'];
            $avatarAutor = $dados['avatar'];
            $nomeAutor = $dados['nome'];    
            
            echo '
                <header class="mb-3 d-flex justify-content-between align-items-center">
                    <a class="d-inline me-1 voltar-perfil p-1" href="'.$_SERVER['HTTP_REFERER'].'"><i class="fa-solid px-1 fa-arrow-left fa-md" style="color: #FFFFFF;"></i></a>
                    '.$botaoRelacionamento.'  
                    <h2 class="ubuntu-bold">Post</h2>
                    <small class="ubuntu-light">'.$nomeAutor.'</small>
                    </header>
                <article class="post p-3">
                    <header>
                        <section class="d-flex align-items-center">
                            <figure>
                                <a href="perfil.php?id='. $idAutor .'">
                                    <img class="border avatar-perfil-postagem "src="'.$avatarAutor.'" alt="Avatar do usuário">
                                </a>
                            </figure>
                            <h3 class="ubuntu-bold">'. $nomeAutor .'</h3>                                       
                        </section>
                    </header>
                    <section>
                        <p class="ubuntu-regular">
                            '. $textoPostagem .'
                        </p>
                    </section>
                    <footer class="d-flex justify-content-between">
                        <small class="ubuntu-light">'. $dataPostagem .'</small>
                        <span class="ubuntu-light"><i class="fa-solid fa-comment fa-md me-2" style="color: #979797;"></i>'.$comentariosPostagem.'</span>
                        <span class="ubuntu-light d-none d-md-inline"><i class="fa-solid fa-eye fa-md me-2" style="color: #979797;"></i>'.$visualizacoesPostagem.'</span>
                    </footer> 
                </article>
            ';
        }
    }

    //FUNÇÃO PRINCIPAL QUE ADICIONA VISUALIZACAO AO POST
    function visualizacaoPOST($mysqli){
        $idPostagem = $mysqli->real_escape_string($_GET['id-post']);
        $idUsuario = $mysqli->real_escape_string($_SESSION['id_usuario']);

        //verificando se o usuário viu a postagem, se ele não viu adiciona uma visualizacao
        $sql_code = "SELECT COUNT(*) AS total FROM visualizacoes WHERE id_post = '$idPostagem' AND id_visualizou = '$idUsuario'";

        if($query = $mysqli->query($sql_code)){
            $dados = $query->fetch_assoc();

            //adicionando a visualizacao
            if($dados['total'] == 0){
                $sql_code = "INSERT INTO visualizacoes (id_post, id_visualizou) VALUES ('$idPostagem', '$idUsuario')";

                if($mysqli->query($sql_code)){
                    //visualizacao adicionada
                } else {
                    header("Location: paginas-erro/erro-conexao.php");
                }
            }
        } else {
            header("Location: paginas-erro/erro-conexao.php");
        }
    }
        //FUNÇÃO SECUNDÁRIA QUE RETORNA O TOTAL DE COMENTARIOS DE UM POST
        function totalComPOST($mysqli,  $idPostagem){
            //se está na página de post, pega o id da postagem pelo get
            if(!empty($_GET['id-post'])){
                $id = $mysqli->real_escape_string($_GET['id-post']);

                $sql_code = "SELECT COUNT(*) AS total FROM comentarios WHERE id_post = '$id'";

                if($query = $mysqli->query($sql_code)){
                    $dados = $query->fetch_assoc();
                    
                    return $dados['total'];
                }
            } else {
                //se for em outra página sem o GET ID-POST (perfil, feed, ...) pega a variavel com o id, que a função de imprimir postagem passa
                $id = $idPostagem;

                $sql_code = "SELECT COUNT(*) AS total FROM comentarios WHERE id_post = '$idPostagem'";

                if($query = $mysqli->query($sql_code)){
                    $dados = $query->fetch_assoc();
                    
                    return $dados['total'];
                }
            }
        }
        //FUNÇÃO SECUNDÁRIA QUE RETORNA O TOTAL DE VISUALIZACOES DE UM POST
        function totalVisuPOST($mysqli,  $idPostagem){
            //se está na página de post, pega o id da postagem pelo get
            if(!empty($_GET['id-post'])){
                $id = $mysqli->real_escape_string($_GET['id-post']);

                $sql_code = "SELECT COUNT(*) AS total FROM visualizacoes WHERE id_post = '$id'";

                if($query = $mysqli->query($sql_code)){
                    $dados = $query->fetch_assoc();
                    
                    return $dados['total'];
                }
            } else {
                //se for em outra página sem o GET ID-POST (perfil, feed, ...) pega a variavel com o id, que a função de imprimir postagem passa
                $id = $idPostagem;

                $sql_code = "SELECT COUNT(*) AS total FROM visualizacoes WHERE id_post = '$idPostagem'";

                if($query = $mysqli->query($sql_code)){
                    $dados = $query->fetch_assoc();
                    
                    return $dados['total'];
                }
            }
        }
?>