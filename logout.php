<?php 
//ARQUIVO PARA REALIZAR LOGOUT DESTRUINDO DADOS DA SESSÃO
session_start();
session_unset();
session_destroy();
header("Location: login.php");
?>