<?php 
//ARQUIVO PARA LIMITAR ACESSO Á PAGINAS QUE PRECISA ESTAR LOGADO
if(!isset($_SESSION['id_usuario'])){
    header("Location: paginas-erro/erro-acesso.php");
    exit();
}
?>