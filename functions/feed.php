<?php 
include 'conn.php';
//ARQUIVO EXCLUSIVO PARA GERENCIAR ENVIO POSTAGEM E FEED DO USUÁRIO

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

    //FUNÇÃO QUE RECEBE OS DADOS DO FORMULÁRIO E ADICIONA A POSTAGEM AO DB
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

    //FUNÇÃO QUE MOSTRA TODOS OS POSTS DO USUÁRIO NO PERFIL
    function postsUsuario($mysqli){
        $id = $mysqli->real_escape_string($_GET['id']);

        $sql_code = "SELECT id, id_autor, nome, avatar, texto, data_publicacao FROM postagens WHERE id_autor = '$id'";
       
        if($query = $mysqli->query($sql_code)){
            while($dados = $query->fetch_assoc()){
                $idPostagem = $dados['id'];
                $textoPostagem = $dados['texto'];
                $dataPostagem = $dados['data_publicacao'];
                $visualizacoesPostagem = totalVisuPOST($mysqli, $idPostagem);  
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
            $visualizacoesPostagem = totalVisuPOST($mysqli, $idPostagem);  
            $idAutor = $dados['id_autor'];
            $avatarAutor = $dados['avatar'];
            $nomeAutor = $dados['nome'];    
            
            echo '
                <header class="mb-3 d-flex justify-content-between align-items-center">
                    <a class="d-inline me-1 voltar-perfil p-1" href="home.php"><i class="fa-solid px-1 fa-arrow-left fa-md" style="color: #FFFFFF;"></i></a>
                    <h1 class="ubuntu-bold d-inline m-0 p-0">Post '. $nomeAutor .'</h1>
                    <small class="ubuntu-light d-none d-md-inline">'. $dataPostagem .'</small>
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
                        <span class="ubuntu-light"><i class="fa-solid fa-eye fa-md me-2" style="color: #979797;"></i>'.$visualizacoesPostagem.'</span>
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