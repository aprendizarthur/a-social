<?php 
include 'conn.php';
//ARQUIVO EXCLUSIVO PARA FUNÇÕES DE SESSÃO DE USUÁRIO / REGISTRO / LOGIN

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
            $erros = [];

            $erros['nome'] = verificaNome($mysqli, $nome);

            //verificando se o e-mail já existe no banco de dados
            $erros['email'] = verificaEmail($mysqli, $email);

            //verificando se a senha e a confirmacao da senha são iguais
            $erros['senha'] = verificaSenha($mysqli, $senha, $senhaConfirmacao);

            //verificando se o usuário é maior de 18 anos
            $erros['nascimento'] = verificaMaioridade($mysqli, $nascimento);

            if($erros['nome'] === "" && $erros['email'] === "" && $erros['senha'] === "" && $erros['nascimento'] === ""){
                //criptografando a senha
                $senha = password_hash($senha, PASSWORD_DEFAULT);
                
                //adicionando todos os dados do usuario no db
                registroDB($mysqli, $nome, $email, $senha, $nascimento);
        
                //pegando id do usuário no DB e passando para a sessão
                $sql_code = "SELECT id, nome, avatar FROM usuarios WHERE email = '$email' AND nome = '$nome'";
                
                if($query = $mysqli->query($sql_code)){
                    $dados = $query->fetch_assoc();
                    $_SESSION['id_usuario'] = $dados['id'];
                    $_SESSION['nome_usuario'] = $dados['nome'];
                    $_SESSION['avatar_usuario'] = $dados['avatar'];
                } else {
                    header("Location: paginas-erro/erro-conexao.php");
                }
                
                //encaminha para a home
                header("Location: home.php");
            }else{
                //adicionando mensagens de erro na session
                $_SESSION['erros'] = [
                        'nome' => ($erros['nome'] ?? ''),
                        'email' => ($erros['email'] ?? ''),
                        'senha' => ($erros['senha'] ?? ''),
                        'nascimento' => ($erros['nascimento'] ?? '')
                ];

                //adicionando dados do formulario na session antes de recarregar
                $_SESSION['dados'] = [
                    'nome' => $nome,
                    'email' => $email,
                    'nascimento' => $nascimento
                ];
                
                //recarregando a página
                header("Location: registro.php");
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
            }
        }

    //FUNCAO DE LOGIN - CHAMANDO OUTRAS FUNCOES PARA COMPACTAR O CÓDIGO
    function login($mysqli){
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])){
            $email = $mysqli->real_escape_string($_POST['email']);
            $senha = $mysqli->real_escape_string($_POST['senha']);

            //variável que vai abrigar as mensagens dos possíveis erros
            $erros2 = [];

            //verificando se existe este email no banco
            $erros2['email'] = procuraEmail($mysqli, $email);

            //se tiver o email no banco, vamos verificar a senha
            if($erros2['email'] === ""){
                $erros2['senha'] = procuraSenha($mysqli, $senha, $email);
            }

            //senão tiver nenhum erro
            if($erros2['email'] === "" && $erros2['senha'] === ""){
                //pegando id usuário do DB e passando para a sessão
                $sql_code = "SELECT id, avatar, nome FROM usuarios WHERE email = '$email'";
                
                if($query = $mysqli->query($sql_code)){
                    $dados = $query->fetch_assoc();
                    $_SESSION['id_usuario'] = $dados['id'];
                    $_SESSION['nome_usuario'] = $dados['nome'];
                    $_SESSION['avatar_usuario'] = $dados['avatar'];
                } else {
                    header("Location: paginas-erro/erro-conexao.php");
                }
                
                //redirecionando para a home
                header("Location: home.php");
            } else {
                //adicionando mensagens de erro na session
                $_SESSION['erros'] = [
                    'email' => ($erros2['email'] ?? ''),
                    'senha' => ($erros2['senha'] ?? '')
                ];
            
                //recarregando a página
                header("Location: login.php");
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
                }
            }
?>