<?php 
include 'conn.php';
//ARQUIVO EXCLUSIVO PARA FUNÇÕES DE SESSÃO DE USUÁRIO / REGISTRO / LOGIN / EXIBIR PERFIL / SEGUIDORES

    //FUNÇÃO QUE ATUALIZA DADOS DO USUÁRIO A CADA RECARREGAMENTO
    function dadosUsuario($mysqli){
        $ID = $mysqli->real_escape_string($_SESSION['id_usuario']);

        $sql_code = "SELECT * FROM usuarios WHERE id = '$ID'";

        if($query = $mysqli->query($sql_code)){
            $dados = $query->fetch_assoc();
            
            $_SESSION['nome_usuario'] = $dados['nome'];
            $_SESSION['avatar_usuario'] = $dados['avatar'];
            $_SESSION['background_usuario'] = $dados['fundoPerfil'];
        }
    }
    //FUNÇÃO QUE ADICIONA/REMOVE SEGUIDOR
    function gerenciarSeguidores($mysqli){

        //ADICIONAR SEGUIDOR
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit-seguir'])){
           //verificando se o submit-seguir é de um perfil
           if(!empty($_GET['id'])){
                $IDseguido = $mysqli->real_escape_string($_GET['id']);
                $IDseguidor = $mysqli->real_escape_string($_SESSION['id_usuario']);


                $sql_code = "INSERT INTO relacionamentos (id_seguiu, id_seguido) VALUES ('$IDseguidor','$IDseguido') ";

                if($query = $mysqli->query($sql_code)){

                    $_SESSION['feedback-sistema'] = "Usuário seguido";
                    header("Location: " . $_SERVER['HTTP_REFERER']);
                    exit;
                }
            }            
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit-seguir'])){
            //verificando se o submit-seguir é de um post
            if(!empty($_GET['id-post'])){

                //pegando o id do autor do post que foi passado pelo get
                $IDpost = $mysqli->real_escape_string($_GET['id-post']);

                $sql_code = "SELECT id_autor FROM postagens WHERE id = '$IDpost'";

                if($query = $mysqli->query($sql_code)){
                    $dados = $query->fetch_assoc();
                    $IDseguido = $dados['id_autor'];
                }

                $IDseguidor = $mysqli->real_escape_string($_SESSION['id_usuario']);
    
    
                $sql_code = "INSERT INTO relacionamentos  (id_seguido, id_seguiu) VALUES ('$IDseguido', '$IDseguidor')";
    
                if($query = $mysqli->query($sql_code)){
    
                    $_SESSION['feedback-sistema'] = "Usuário seguido";
                    header("Location: " . $_SERVER['HTTP_REFERER']);
                    exit;
                }
            }     
        }

        //REMOVER SEGUIDOR
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit-unfollow'])){
            //verificando se o submit-unfollow é de um perfil
            if(!empty($_GET['id'])){
                $IDseguido = $mysqli->real_escape_string($_GET['id']);
                $IDseguidor = $mysqli->real_escape_string($_SESSION['id_usuario']);
    
    
                $sql_code = "DELETE FROM relacionamentos WHERE id_seguido = '$IDseguido' AND id_seguiu = '$IDseguidor'";
    
                if($query = $mysqli->query($sql_code)){
    
                    $_SESSION['feedback-sistema'] = "Deixou de seguir o usuário";
                    header("Location: " . $_SERVER['HTTP_REFERER']);
                    exit;
                }
            }     
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit-unfollow'])){
            //verificando se o submit-unfollow é de um post
            if(!empty($_GET['id-post'])){

                //pegando o id do autor do post que foi passado pelo get
                $IDpost = $mysqli->real_escape_string($_GET['id-post']);

                $sql_code = "SELECT id_autor FROM postagens WHERE id = '$IDpost'";

                if($query = $mysqli->query($sql_code)){
                    $dados = $query->fetch_assoc();
                    $IDseguido = $dados['id_autor'];
                }

                $IDseguidor = $mysqli->real_escape_string($_SESSION['id_usuario']);
    
                $sql_code = "DELETE FROM relacionamentos WHERE id_seguido = '$IDseguido' AND id_seguiu = '$IDseguidor'";
    
                if($query = $mysqli->query($sql_code)){
    
                    $_SESSION['feedback-sistema'] = "Deixou de seguir o usuário";
                    header("Location: " . $_SERVER['HTTP_REFERER']);
                    exit;
                }
            }     
        }
    }
    

    //FUNÇÃO QUE EXIBE O BOTÃO DE SEGUIR / SEGUINDO (POSTAGEM E PERFIL)
    function botaoRelacionamento($mysqli){
        //VERIFICANDO SE É POST
        if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id-post'])){
            $IDusuario = $_SESSION['id_usuario'];
            $IDpost = $_GET['id-post'];

            //descobrindo id do autor
            $sql_code = "SELECT id_autor FROM postagens WHERE id = '$IDpost'";

            if($query = $mysqli->query($sql_code)){
                $dados = $query->fetch_assoc();
                $IDautor = $dados['id_autor'];

                //se o post é do próprio usuário, não mostrar nada
                if($IDusuario != $IDautor){
                    //descobrindo se segue o usuário ou não
                    $sql_code = "SELECT COUNT(*) AS total FROM relacionamentos WHERE id_seguiu = '$IDusuario' AND id_seguido = '$IDautor'";
                    
                    if($query = $mysqli->query($sql_code)){
                        $dados = $query->fetch_assoc();

                        //se não segue retorna o botão de seguir, se segue retorna o botão de unfollow
                        if($dados['total'] == 0){
                            return botaoSeguir($mysqli);
                        } else {
                            return botaoSeguindo($mysqli);
                        }
                    }    
                } 
            }
        } 

        //VERIFICANDO SE É PERFIL
        if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])){
            $IDusuario = $_SESSION['id_usuario'];
            $IDperfil = $_GET['id'];

            //se o perfil é do próprio usuário, não mostrar nada
            if($IDusuario != $IDperfil){
                //descobrindo se segue o usuário ou não
                $sql_code = "SELECT COUNT(*) AS total FROM relacionamentos WHERE id_seguiu = '$IDusuario' AND id_seguido = '$IDperfil'";
                    
                if($query = $mysqli->query($sql_code)){
                    $dados = $query->fetch_assoc();

                    //se não segue retorna o botão de seguir, se segue retorna o botão de unfollow
                    if($dados['total'] == 0){
                        return botaoSeguir($mysqli);
                    } else {
                        return botaoSeguindo($mysqli);
                    }
                }    
            } 
        }
    } 

        //FUNÇÕES SECUNDÁRIAS CHAMADAS NA PRINCIPAL DE MOSTRAR BOTÃO DE SEGUIR/UNFOLLOW
        function botaoSeguir($mysqli){
            echo '
                <form method="POST">
                    <button type="submit" name="submit-seguir" class="w-100 mb-3 ubuntu-bold btn btn-secondary"><strong>Seguir</strong></button>
                </form>
            ';
        }

        function botaoSeguindo($mysqli){
            echo '
            <form method="POST">
                <button type="submit" name="submit-unfollow" class="w-100 mb-3 ubuntu-bold btn btn-secondary"><strong>Deixar de seguir</strong></button>
            </form>
            ';
        }

    //FUNÇÃO QUE VERIFICA SE O ID DE PERFIL PASSADO PELO GET EXISTE NO DB
    function verificarIDperfil($mysqli){
        $id = $mysqli->real_escape_string($_GET['id']);

        $sql_code = "SELECT COUNT(*) AS total FROM usuarios WHERE id = '$id'";

        if($query = $mysqli->query($sql_code)){
            $dados = $query->fetch_assoc();

            if($dados['total'] == 0){
                header("Location: paginas-erro/erro-perfil-inexistente.php");
                exit;
            }
        } else {
            header("Location: paginas-erro/erro-conexao.php");
            exit;
        }
    }

    //FUNÇÃO QUE VERIFICA SE O ID DE POST PASSADO PELO GET EXISTE NO DB
    function verificarIDpost($mysqli){
        $id = $mysqli->real_escape_string($_GET['id-post']);

        $sql_code = "SELECT COUNT(*) AS total FROM postagens WHERE id = '$id'";

        if($query = $mysqli->query($sql_code)){
            $dados = $query->fetch_assoc();

            if($dados['total'] == 0){
                header("Location: paginas-erro/erro-post-inexistente.php");
                exit;
            }
        } else {
            header("Location: paginas-erro/erro-conexao.php");
            exit;
        }
    }

    //FUNCAO QUE COLETA DADOS DO USUÁRIO E EXIBE O PERFIL
    function exibirPerfil($mysqli){
        //usando GET para pegar id do usuário e imprimir o perfil
        $id = $mysqli->real_escape_string($_GET['id']);

        //consultando o número de postagens do usuário
        $sql_code = "SELECT COUNT(*) AS total FROM postagens WHERE id_autor = '$id'";

        if($query = $mysqli->query($sql_code)){
            $dados = $query->fetch_assoc();
            
            $totalPostagens = $dados['total'];
        } else {
            header("Location: paginas-erros/erro-conexao.php");
            exit;
        }

        //consultando dados usuario
        $sql_code = "SELECT nome, fundoPerfil, avatar, biografia, registro FROM usuarios WHERE id = '$id'";
        
        $botaoRelacionamento = botaoRelacionamento($mysqli);
        $totalSeguindo = totalSeguindo($mysqli, $id);
        $totalSeguidores = totalSeguidores($mysqli, $id);

        if($query = $mysqli->query($sql_code)){
            $dados = $query->fetch_assoc();

            echo '
                <header class="mb-3 d-flex justify-content-between align-items-center">
                    <a class="d-inline me-1 voltar-perfil p-1" href="'.$_SERVER['HTTP_REFERER'].'"><i class="fa-solid px-1 fa-arrow-left fa-md" style="color: #FFFFFF;"></i></a>
                    <h1 class="ubuntu-bold d-inline m-0 p-0">Perfil de ' .$dados['nome'] . '</h1>
                    <small class="ubuntu-light d-none d-md-inline">'. $totalPostagens .' posts</small>
                </header>

                <article id="perfil-usuario">
                    <header>
                        <figure id="imagens-perfil">
                            <img class="capa-perfil" src="'. $dados['fundoPerfil'] .'" alt="Capa do perfil">
                            <img class="avatar-perfil" src="'. $dados['avatar'] .'" alt="Avatar do usuário">
                        </figure>
                    </header>
                    <section id="dados-perfil">
                        <div class="row">
                        <div class="col-12">
                            <h2 class="d-inline ubuntu-bold mb-1 p-0">'. $dados['nome'] .'</h2> 
                            <blockquote class="ubuntu-regular m-0">
                                '. $dados['biografia'] .'
                            </blockquote>
                    </section>
                    <footer>
                        <small class="ubuntu-light"><i class="fa-solid fa-calendar-days fa-sm me-1" style="color: #979797;"></i> Ingressou em '. $dados['registro'] .'</small>
                        <hgroup class="mt-1">
                            <h3 class="ubuntu-bold d-inline-block">'.$totalSeguindo.'</h3><small class="ubuntu-light me-2"> Seguindo</small>
                            <h3 class="ubuntu-bold d-inline-block">'.$totalSeguidores.'</h3><small class="ubuntu-light"> Seguidores</small>
                            
                        </hgroup>
                    </footer>
                </article>
            ';
        } else {
            header("Location: paginas-erro/erro-conexao.php");
            exit;
        }
    }

        //FUNÇÕES SECUNDÁRIAS CHAMADAS NA FUNÇÃO PRINCIPAL DE EXIBIR PERFIL
        function totalSeguindo($mysqli, $id){
            $sql_code = "SELECT COUNT(*) AS total FROM relacionamentos WHERE id_seguiu = '$id'";

            if($query = $mysqli->query($sql_code)){
                $dados = $query->fetch_assoc();
                
                return $dados['total'];
            }
        }

        function totalSeguidores($mysqli, $id){
            $sql_code = "SELECT COUNT(*) AS total FROM relacionamentos WHERE id_seguido = '$id'";

            if($query = $mysqli->query($sql_code)){
                $dados = $query->fetch_assoc();
                
                return $dados['total'];
            }
        }

    //FUNCAO DE REGISTRO - CHAMANDO OUTRAS FUNCOES PARA COMPACTAR O CÓDIGO
    function registro($mysqli){    
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])){
            //pegando dados do formulário
            $nome = $mysqli->real_escape_string($_POST['nome']);
            $email = $mysqli->real_escape_string($_POST['email']);
            $senha = $mysqli->real_escape_string($_POST['senha']);
            $senhaConfirmacao = $mysqli->real_escape_string($_POST['senhaConfirmacao']);
            $nascimento = $mysqli->real_escape_string($_POST['nascimento']);

            //variável que vai abrigar as mensagens dos possíveis erros
            $errosRegistro = [];

            $errosRegistro['nome'] = verificaNome($mysqli, $nome);

            //verificando se o e-mail já existe no banco de dados
            $errosRegistro['email'] = verificaEmail($mysqli, $email);

            //verificando se a senha e a confirmacao da senha são iguais
            $errosRegistro['senha'] = verificaSenha($mysqli, $senha, $senhaConfirmacao);

            //verificando se o usuário é maior de 18 anos
            $errosRegistro['nascimento'] = verificaMaioridade($mysqli, $nascimento);

            if($errosRegistro['nome'] === "" && $errosRegistro['email'] === "" && $errosRegistro['senha'] === "" && $errosRegistro['nascimento'] === ""){
                //criptografando a senha
                $senha = password_hash($senha, PASSWORD_DEFAULT);
                
                //adicionando todos os dados do usuario no db
                registroDB($mysqli, $nome, $email, $senha, $nascimento);
        
                //pegando id do usuário no DB e passando para a sessão
                $sql_code = "SELECT id, nome, avatar, fundoPerfil FROM usuarios WHERE email = '$email' AND nome = '$nome'";
                
                if($query = $mysqli->query($sql_code)){
                    $dados = $query->fetch_assoc();
                    $_SESSION['id_usuario'] = $dados['id'];
                    $_SESSION['nome_usuario'] = $dados['nome'];
                    $_SESSION['avatar_usuario'] = $dados['avatar'];
                    $_SESSION['background_usuario'] = $dados['fundoPerfil'];
                } else {
                    header("Location: paginas-erro/erro-conexao.php");
                    exit;
                }

                //encaminha para a home
                header("Location: home.php");
                exit;
            }else{
                //adicionando mensagens de erro na session
                $_SESSION['errosRegistro'] = [
                        'nome' => ($errosRegistro['nome'] ?? ''),
                        'email' => ($errosRegistro['email'] ?? ''),
                        'senha' => ($errosRegistro['senha'] ?? ''),
                        'nascimento' => ($errosRegistro['nascimento'] ?? '')
                ];

                //adicionando dados do formulario na session antes de recarregar
                $_SESSION['dadosRegistro'] = [
                    'nome' => $nome,
                    'email' => $email,
                    'nascimento' => $nascimento
                ];
                
                //recarregando a página
                header("Location: registro.php");
                exit;
            }
        }
    }

    //FUNÇÕES SECUNDÁRIAS CHAMADAS NA PRINCIPAL DE REGISTRO
        function verificaNome($mysqli, $nome){
            $nome = $mysqli->real_escape_string($nome);
            $tamanhoNome = strlen($nome);

            if($tamanhoNome < 3){
                return "Nome muito curto.";
            }
            if($tamanhoNome >= 3 && $tamanhoNome <= 20){
                return "";
            }
            if($tamanhoNome > 20){
                return "Nome muito grande.";
            }
        }
    
        function verificaEmail($mysqli, $email){
            $email = $mysqli->real_escape_string($email);

            //comando sql para consultar banco
            $sql_code = "SELECT COUNT(*) as total FROM USUARIOS WHERE email = '$email'";

            if($query = $mysqli->query($sql_code)){
                $dados = $query->fetch_assoc();
                
                if($dados['total'] > 0){
                    return "Este e-mail já está registrado em outra conta";
                } else {
                    return "";
                } 
            }
        }

        function verificaSenha($mysqli, $senha, $senhaConfirmacao){
            $senha = $mysqli->real_escape_string($senha);
            $senhaConfirmacao = $mysqli->real_escape_string($senhaConfirmacao);

            if($senha != $senhaConfirmacao){
                return "A confirmação da senha não bate com a original";
            } else {
                return "";
            }
        }

        function verificaMaioridade($mysqli, $nascimento){
            $nascimento = $mysqli->real_escape_string($nascimento);

            $nascimento = new DateTime($nascimento);
            $hoje = new DateTime();
            $idade = $hoje->diff($nascimento)->y;

            if($idade < 18){
                return "Apenas maiores de idade podem criar contas.";
            } else {
                return "";
            }
        }

        function registroDB($mysqli, $nome, $email, $senha, $nascimento){
            $sql_code = "INSERT INTO usuarios (nome, email, senha, nascimento) VALUES ('$nome', '$email' , '$senha', '$nascimento')"; 
        
            if($mysqli->query($sql_code)){

            } else {
                header("Location: paginas-erro/erro-conexao.php");
                exit;
            }
        }

    //FUNCAO DE LOGIN - CHAMANDO OUTRAS FUNCOES PARA COMPACTAR O CÓDIGO
    function login($mysqli){
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])){
            $email = $mysqli->real_escape_string($_POST['email']);
            $senha = $mysqli->real_escape_string($_POST['senha']);

            //variável que vai abrigar as mensagens dos possíveis erros
            $errosLogin = [];

            //verificando se existe este email no banco
            $errosLogin['email'] = procuraEmail($mysqli, $email);

            //se tiver o email no banco, vamos verificar a senha
            if($errosLogin['email'] === ""){
                $errosLogin['senha'] = procuraSenha($mysqli, $senha, $email);
            }

            //senão tiver nenhum erro
            if($errosLogin['email'] === "" && $errosLogin['senha'] === ""){
                //pegando id usuário do DB e passando para a sessão
                $sql_code = "SELECT id, avatar, nome, fundoPerfil FROM usuarios WHERE email = '$email'";
                
                if($query = $mysqli->query($sql_code)){
                    $dados = $query->fetch_assoc();
                    $_SESSION['id_usuario'] = $dados['id'];
                    $_SESSION['nome_usuario'] = $dados['nome'];
                    $_SESSION['avatar_usuario'] = $dados['avatar'];
                    $_SESSION['background_usuario'] = $dados['fundoPerfil'];
                } else {
                    header("Location: paginas-erro/erro-conexao.php");
                    exit;
                }
                
                //redirecionando para a home
                header("Location: home.php");
                exit;
            } else {
                //adicionando mensagens de erro na session
                $_SESSION['errosLogin'] = [
                    'email' => ($errosLogin['email'] ?? ''),
                    'senha' => ($errosLogin['senha'] ?? '')
                ];
            
                //recarregando a página
                header("Location: login.php");
                exit;
            }
        }
    }

            //FUNÇÕES SECUNDÁRIAS CHAMADAS NA PRINCIPAL DE LOGIN
            function procuraEmail($mysqli, $email){
                $email = $mysqli->real_escape_string($email);

                $sql_code = "SELECT COUNT(*) AS total FROM usuarios WHERE email = '$email'";

                if($query = $mysqli->query($sql_code)){
                    $dados = $query->fetch_assoc();

                    if($dados['total'] == 1){
                        return "";
                    } else {
                        return "Nenhuma conta com este e-mail vinculado";
                    }
                } else {
                    header("Location: paginas-errro/erro-conexao.php");
                    exit;
                }
            }

            function procuraSenha($mysqli, $senha, $email){
                $email = $mysqli->real_escape_string($email);

                $sql_code = "SELECT senha FROM usuarios WHERE email = '$email'";

                if($query = $mysqli->query($sql_code)){
                    $dados = $query->fetch_assoc();
                    $hash = $dados['senha'];

                    $resultadoHash = password_verify($senha, $hash);

                    if($resultadoHash){
                        return "";
                    } else {
                        return "Senha incorreta";
                    }
                } else {
                    header("Location: paginas-erro/erro-conexao.php");
                    exit;
                }
            }
?>