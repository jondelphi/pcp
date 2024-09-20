<?php
    class Acesso
    {
        public function carrega(){
            if(!isset($_SESSION['pcpAPI']['pagina'])){
                $_SESSION['pcpAPI']['pagina']='View/login.php';
                $_SESSION['pcpAPI']['titulo']='Braslar- PCP - Login';
            }
        }
        public function destroi(){
            session_destroy();
            header('index.php');
        }
        public function mostrapagina($pag){
           include_once($pag);
        }
                
        
    }
    
?>