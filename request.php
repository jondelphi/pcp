
<?php
session_start();
require('DAO/config.php');

require('DAO/configIgor.php');
require('Model/ClasseAcesso.php');
require('Model/ClasseApontamento.php');
require('Model/ClasseODF.php');
require('Model/ClasseLinha.php');
require('Model/ClasseRequest.php');


$conn =ConexaoMysql::getConnectionMysql();

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/x-icon" href="content/icone.png">
    <title>Coleta</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap-icons.css">
    <style>
        i {
            padding: 2px;
            margin: 2px;
        }
    </style>
     <script>
     function conExclui(link)
        {
            var str="Deseja excluir a etiqueta?";
            var con=confirm(str);
            if (con==true) {
                window.location.href=link;
            }
        }
        function conExcluiCOLETA(link)
        {
            var str="Deseja excluir a etiqueta da Coleta?";
            var con=confirm(str);
            if (con==true) {
                window.location.href=link;
            }
        }
       
    
    </script>
  
   
</head>
<body>
    <?php 
    
    if($_GET){
        $etiqueta=$_GET['etiqueta'];
        $req= new ClasseRequest;
     $req->etiqueta($etiqueta);       
    }
    ?>
    <script>
        window.close();
    </script>
  
</body>
</html>