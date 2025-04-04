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
                                <textarea required class="ubuntu-regular w-100 form-control mb-2" rows="3" maxlength="150" minlength="1" id="nova-postagem" name="texto-postagem" placeholder="O que está acontecendo?"></textarea>
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
                $idAutor = $dados['id_autor'];
                $nomeAutor = $dados['nome'];
                $avatarAutor = $dados['avatar'];

                echo '
                    <article class="post p-3">
                        <a class="link-post" href="post.php?id='.$idPostagem .'">
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
?>