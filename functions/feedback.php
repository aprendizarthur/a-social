<?php 
//ARQUIVO EXCLUSIVO PARA FUNCAO DE FEEDBACK DO SISTEMA
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

?>