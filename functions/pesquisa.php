<?php 
include 'conn.php';
include 'sessions.php';
//ARQUIVO EXCLUSIVO PARA FUNÇÕES RELACIONADAS AO EXPLORAR E PESQUISAS
    //FUNÇÃO QUE IMPRIME O FORM PARA PESQUISAR NA PAGINA EXPLORAR
    function novaPesquisa($mysqli){
        echo '
            <section>
                <div class="row d-flex p-2 justify-content-between">
                    <div id="form-pesquisa" class="col-12 p-2 m-0">
                        <h1 class="ubuntu-bold mb-3">Explorar</h1>
                        <form method="GET">
                            <div class="d-inline form-group">
                                <input class="p-2 d-inline ubuntu-light" type="text" name="pesquisa" id="pesquisa" placeholder="Digite algo para pesquisar">
                            </div>
                            <button id="confirma-pesquisa" class="d-inline btn btn-primary" style="width:16%;" type="submit"><i class="fas fa-search fa-lg"></i></button>
                        </form>   
                    </div>
                </div>
            </section>
        ';
    }

    //FUNÇÃO QUE IMPRIME O FORM PARA PESQUISA NA PAGINA HOME
    

    //FUNÇÃO QUE PEGA OS DADOS DA PESQUISA E IMPRIME OS RESULTADOS
    function resultadoPesquisa($mysqli){
        //se não tiver pesquisado nada exibe dados das tendências
        if(empty($_GET['pesquisa'])){
            echo "Digite algo para pesquisar";
        } else {
            //pegando dados do get e passando feedback para o usuário
            $pesquisa = $mysqli->real_escape_string($_GET['pesquisa']);
            $_SESSION['feedback-sistema'] = "Pesquisa concluída";

            //primeiro pegando 5 nomes de usuario relacionado com a pesquisa
                
                //verificando se existe algum usuário relacionado a pesquisa, se nao tiver mostra mensagem de erro
                $sql_code = "SELECT COUNT(*) AS total FROM usuarios WHERE nome LIKE '%$pesquisa%'";

                echo "<h3 class=\"ubuntu-bold my-2\">Usuários</h3>";

                if($query = $mysqli->query($sql_code)){
                    $dados = $query->fetch_assoc();

                    if($dados['total'] == 0){
                        echo "<small class=\"ubuntu-light\">Nenhum usuário encontrado</small>";
                    } else {
                        $sql_code = "SELECT id, avatar, nome, biografia FROM usuarios WHERE nome LIKE '%$pesquisa%' LIMIT 3";

                        if($query = $mysqli->query($sql_code)){
                            while($dados = $query->fetch_assoc()){
                            $idUsuario = $dados['id'];
                            $biografiaUsuario = $dados['biografia'];
                            $nomeUsuario = $dados['nome'];
                            $avatarUsuario = $dados['avatar'];

                            echo '
                                <article class="post p-3">
                                    <a class="link-post" href="perfil.php?id='.$idUsuario.'">
                                        <header>
                                            <section class="d-flex align-items-center">
                                                <figure>
                                                    <img class="border avatar-perfil-postagem" src="'.$avatarUsuario.'" alt="Avatar do usuário">
                                                </figure>
                                                <h3 class="ubuntu-bold">'.$nomeUsuario.'</h3>                                        
                                            </section>
                                        </header>
                                        <section>
                                            <p class="ubuntu-regular">
                                                '.$biografiaUsuario.'
                                            </p>
                                        </section>
                                    </a>
                                </article>
                            ';
                            }
                        }else{
                            header("Location: paginas-erro/erro-conexao.php");
                            exit;
                        }
                    }
                } else {
                    header("Location: paginas-erro/erro-conexao.php");
                    exit;
                }

            //depois pegando todos posts com o texto relacionado a pesquisa

                //verificando se existe algum post relacionado a pesquisa, se nao tiver mostra mensagem de erro
                $sql_code = "SELECT COUNT(*) AS total FROM postagens WHERE texto LIKE '%$pesquisa%'";
                
                echo "<h3 class=\"ubuntu-bold my-2\">Posts</h3>";

                if($query = $mysqli->query($sql_code)){
                    $dados = $query->fetch_assoc();
                    
                    if($dados['total'] == 0){
                        echo "<small class=\"ubuntu-light\">Nenhuma postagem encontrada</small>";
                    }else{
                        $sql_code = "SELECT * FROM postagens WHERE texto LIKE '%$pesquisa%'";

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
                                            
                                        </footer> 
                                    </a>
                                </article>
                            ';
                            }
                        }
                    }
                } else {
                    header("Location: paginas-erro/erro-conexao.php");
                    exit;
                }

            
        }
    }

?>