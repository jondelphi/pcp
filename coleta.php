
<?php
session_start();
ob_start();
set_time_limit(0);
require('DAO/config.php');

require('DAO/configIgor.php');
require('Model/ClasseAcesso.php');
require('Model/ClasseApontamento.php');
require('Model/ClasseODF.php');
require('Model/ClasseLinha.php');
require('Model/ClasseProduto.php');


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
    <div class="container p-2 m-2">
          <div class="container p-2 m-2" hx-trigger="load" hx-get="http://10.1.2.251/api2/getTotalColeta/<?php echo $_SESSION['pcpAPI']['diaapont']?>">
        
        </div>
         <a href="apagaapontada.php" class="btn btn-danger">APAGA APONTADA</a><br>
         <a href="todosrequests.php" class="btn btn-warning m-2 p-2">Todos os requests</a>
  <!--   <button id="apagueapontada" onclick="apagaqualquer()" class="btn btn-danger">APAGA APONTADA</button><br>
    <button id="bt_request" onclick="todosRequests()" class="btn btn-warning m-2 p-2">Todos os requests</button> -->
    </div>

    <div class="container p-2 m-2" hx-trigger="load" hx-get="http://10.1.2.251/api2/getColeta/<?php echo $_SESSION['pcpAPI']['diaapont']?>">
        
    </div>
    <div>
        <button class="btn btn-primary btn-sm" id="cdODF" onclick="coletas()"><i class="bi bi-wrench"></i>Atualiza ODF</button>
        <script>
     async function coletas() {
       const bt = document.getElementById('cdODF')
       bt.disabled  = true; 
    
        const dia = "<?php echo $_SESSION['pcpAPI']['diaapont']; ?>";
        const url = `http://10.1.2.251/api/cadodf/${dia}`;
        
        try {
            const response = await fetch(url);
            if (response.ok) {
                alert('Atualizado ODFs no Sistema');
                bt.disabled  = false
            } else {
                alert('Erro ao atualizar ODFs');
            }
        } catch (error) {
            console.error('Erro ao fazer a requisição:', error);
            alert('Erro ao conectar com o servidor');
        }
    }
  </script>
    </div>
    <script src="bootstrap/js/bootstrap.js"></script>
    <script src="bootstrap/js/bootstrap.bundle.js"></script>
    <script src="https://unpkg.com/htmx.org@2.0.0" integrity="sha384-wS5l5IKJBvK6sPTKa2WZ1js3d947pvWXbPJ1OmWfEuxLgeHcEbjUUA5i9V5ZkpCw" crossorigin="anonymous"></script>
   <script>
    async function todosRequests(){
        const bt = document.getElementById('bt_request')
        bt.disabled= true
        const url = `http://10.1.2.251/api/requestKorp`
        const fet = await fetch(url)
        bt.disabled=false
        await apagaqualquer()
        window.location.reload()

    }

    async function apagaqualquer(){
        const bt = document.getElementById('apagueapontada')
        bt.disabled= true
        const url = `http://10.1.2.251/api/apagaqualquer`
        const fet = await fetch(url)
        bt.disabled=false

        alert('Apagadas as apontadas')

    }
   </script>
    <footer>
        <?php
        include("template/footer.php");
        if(isset($_SESSION['coletaapagada'])){
            $script="<script>alert('Foi apagada :".$_SESSION['coletaapagada']." etiquetas')</script>";
            echo $script;
            unset($_SESSION['coletaapagada']);
        }
        
        ?>
    </footer>
 
</body>
</html>