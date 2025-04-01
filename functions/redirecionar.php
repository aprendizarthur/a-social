<?php 
//ARQUIVO PARA REDIRECIONAR USUARIOS LOGADOS PARA FORA DE PAGINAS DE REGISTRO
if(isset($_SESSION['id_usuario'])){
    header("Location: home.php");
    exit();
}
?>