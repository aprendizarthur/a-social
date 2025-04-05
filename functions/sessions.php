<?php 
include 'conn.php';
//ARQUIVO EXCLUSIVO PARA FUNÇÕES DE SESSÃO DE USUÁRIO / REGISTRO / LOGIN / EXIBIR PERFIL / FEEDBACKS SISTEMA

    //FUNÇÃO QUE MOSTRA FEEDBACKS DO SISTEMA
    function feedbackSistema(){
        //se existem feedback apresenta ele pro usuário
        if(!empty($_SESSION['feedback-sistema'])){
            echo "<span class=\"ubuntu-regular\">" . $_SESSION['feedback-sistema'] . "</span>"; 
            //limpando feedback após printar pro usuário
            unset($_SESSION['feedback-sistema']);
        } else {
            echo "<span class=\"ubuntu-regular\"> Nenhuma mensagem do sistema </span>"; 
        }
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
        $id = $mysqli->real_escape_string($_GET['id']);

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
        
        if($query = $mysqli->query($sql_code)){
            $dados = $query->fetch_assoc();

            echo '
                <header class="mb-3 d-flex justify-content-between align-items-center">
                    <a class="d-inline me-1 voltar-perfil p-1" href="home.php"><i class="fa-solid px-1 fa-arrow-left fa-md" style="color: #FFFFFF;"></i></a>
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
                            <h2 class="ubuntu-bold mb-1 p-0">'. $dados['nome'] .'</h2>
                            <blockquote class="ubuntu-regular m-0">
                                '. $dados['biografia'] .'
                            </blockquote>
                    </section>
                    <footer>
                        <small class="ubuntu-light"><i class="fa-solid fa-calendar-days fa-sm me-1" style="color: #979797;"></i> Ingressou em '. $dados['registro'] .'</small>
                        <hgroup class="mt-1">
                            <h3 class="ubuntu-bold d-inline-block">0</h3><small class="ubuntu-light me-2"> Seguindo</small>
                            <h3 class="ubuntu-bold d-inline-block">0</h3><small class="ubuntu-light"> Seguidores</small>    
                        </hgroup>
                    </footer>
                </article>
            ';
        } else {
            header("Location: paginas-erro/erro-conexao.php");
            exit;
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