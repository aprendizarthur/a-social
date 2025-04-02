<?php 
//ARQUIVO PARA LIMITAR ACESSO Á PAGINAS QUE PRECISA ESTAR LOGADO E NÃO DEIXAR USUARIOS LOGADOS ENTRAREM EM PÁGINAS SÓ PARA QUEM ESTÁ DESLOGADO

//FUNÇÃO PARA LIMITAR ACESSO Á PAGINAS QUE PRECISA ESTAR LOGADO
function acessoLogado(){
    if(!isset($_SESSION['id_usuario'])){
        header("Location: paginas-erro/erro-acesso.php");
        exit();
    }    
}

//FUNÇÃO PARA NÃO DEIXAR USUÁRIOS LOGADOS ENTRAREM EM PÁGINAS SÓ PARA QUEM ESTÁ DESLOGADO
function redirecionarLogado(){
    if(isset($_SESSION['id_usuario'])){
        header("Location: home.php");
        exit();
    }
}
?>