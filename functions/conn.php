<?php 
//ARQUIVO EXCLUSIVO PARA CONEXÃO COM O DB E INICIAR SESSÃO
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'asocial';

if($mysqli = new mysqli($host, $user, $pass, $db)){

} else {
    header("Location: paginas-erro/erro-conexao.php");
}
?>