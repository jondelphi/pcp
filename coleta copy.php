
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
    <?php

    function trazbip($etiqueta)
    {
        $Conexao2=ConexaoIgor::getConnectionIgor();
        $query = 'SELECT bip
    FROM etiquetas WHERE serie ="' . $etiqueta. '"';
    $procura = $Conexao2->prepare($query);
    $procura->execute();
    $q=$procura->fetch(PDO::FETCH_ASSOC);
    if(is_null($q)){
        return 0;
    }elseif(is_bool($q)){
        return 0;
    }
    elseif(is_string($q)){
        return 0;
    }
    else{
    return $q['bip'];
    }
    
    }


    function trazreq($etiqueta)
    {
        $Conexao2=ConexaoMysql::getConnectionMysql();
        $query = 'SELECT numrequest
    FROM tbcoleta WHERE etiqueta =" '. $etiqueta. '"';
    $procura = $Conexao2->prepare($query);
    $procura->execute();
    $q=$procura->fetch(PDO::FETCH_ASSOC);
    if(is_null($q)){
        return 0;
    }elseif(is_bool($q)){
        return 0;
    }
    elseif(is_string($q)){
        return 0;
    }
    else{
    return $q['numrequest'];
    }    
    }
    function trazODF($etiqueta)
    {
        $Conexao2=ConexaoIgor::getConnectionIgor();
        $query = 'SELECT odf
    FROM etiquetas WHERE serie ="' . $etiqueta. '"';
    $procura = $Conexao2->prepare($query);
    $procura->execute();
    if($procura->rowCount()>0){
    $q=$procura->fetch(PDO::FETCH_ASSOC);
    if(is_null($q)){
        return 0;
    }elseif(is_bool($q)){
        return 0;
    }
    elseif(is_string($q)){
        return 0;
    }
    else{
    return $q['odf'];
    }   
}else{
    return '';
} 
    }

    function trazultimoreq($etiqueta)
    {
        $Conexao2=ConexaoMysql::getConnectionMysql();
        $query ='SELECT ultimorequest
    FROM tbcoleta WHERE etiqueta ="' . $etiqueta. '"';
    $procura = $Conexao2->prepare($query);
    $procura->execute();
    $q=$procura->fetch(PDO::FETCH_ASSOC);
    if(is_null($q)){
        return 0;
    }elseif(is_bool($q)){
        return 0;
    }
    elseif(is_string($q)){
        return 0;
    }
    else{
    return $q['ultimorequest'];
    }    
    }



    try{
        if(isset($_SESSION['pcpAPI']['diaapont'])){
    $dia=$_SESSION['pcpAPI']['diaapont'];
        }else{
            $n=new DateTime('now');
            $dia=$n->format('Y-m-d') ;  
        }
    }catch(Exception $e){
       $n=new DateTime('now');
       $dia=$n->format('Y-m-d') ;
    }
    $fdia=new DateTime($dia);
     $sql='SELECT tbcoleta.*, tbapontamento.id as idapont, tblinha.linha as linha from tbcoleta 
     INNER JOIN tbapontamento on tbapontamento.etiqueta = tbcoleta.etiqueta
     INNER JOIN tbproduto on tbapontamento.idproduto = tbproduto.id
     INNER JOIN tblinha on tbapontamento.idlinha = tblinha.id
     WHERE tbcoleta.diaapont="'.$dia.'" ORDER BY tbapontamento.id ASC';

     $sql=$conn->prepare($sql);
     $sql->execute();
     $linha=$sql->rowCount();
     if ($sql->rowCount()>0) {
        $id=1;
        
      ?>
      <h2 class="text-bg-info bg-info text-center m-2 p-2" style="max-width: 500px;"><?php echo $linha." Registros" ;?></h2>
     <div class="container">
        <a href="apagaapontada.php" class="btn btn-danger">APAGA APONTADA</a><br>
        <a href="todosrequests.php" class="btn btn-warning m-2 p-2">Todos os requests</a>

     </div>
      <table class="table table-light table-bordered table-striped m-2 p-2" style="width: fit-content;">
        <thead>
            <tr>
                <th>
                    id
                </th>
                <th>
                    etiqueta
                </th>
                <th>
                    linha
                </th>
                <th>
                    bip
                </th>
                <th>
                    ODF
                </th>
                <th>
                    numreq
                </th>
                <th>
                    ultimoreq
                </th>
                <th>
                    apaga
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
             while ($k=$sql->fetch(PDO::FETCH_ASSOC)) {
               ?>
                <tr>
                    <td><?php echo $k['idapont']?></td>
                    <td><?php echo $k['etiqueta'];?></td>
                    <td><?php echo $k['linha'];?></td>
                 
                     <td>bip-<?php 
                    // var_dump(trazbip($k['etiqueta']));
                     echo trazbip($k['etiqueta']);?></td>  
                     <td>
                        <?php 
                         echo trazodf($k['etiqueta']);
                        ?>
                     </td>
                     <td>
                     req-<?php 
                    // var_dump(trazbip($k['etiqueta']));
                    echo trazreq($k['etiqueta']);?>
                     </td>
                     <td>
                     <?php 
                    // var_dump(trazbip($k['etiqueta']));
                    echo trazultimoreq($k['etiqueta']);?>
                     </td>
                     
                    <td>
                        <?php
                        $link="conExclui('control/apagacoleta.php?coleta=".$k['id']."&&aponta=".$k['idapont']."')";
                        $link2="conExcluiCOLETA('control/apagasocoleta.php?coleta=".$k['id']."&&aponta=".$k['idapont']."')";
                        ?>
                        <button onclick="<?php echo $link;?>" class="btn btn-danger btn-sm m-2 p-2" title="diminui o saldo e apaga da coleta">

                     Apaga SALDO
                        </button>
                        <button onclick="<?php echo $link2;?>" class="btn btn-warning btn-sm m-2 p-2" tilte="não diminui o saldo da linha">
                     Apaga COLETA
                        </button>
                        <a href="request.php?etiqueta=<?php echo $k['etiqueta'];?>" target="_blank" class="btn btn-secondary">Request</a>
                      

                    </td>
                </tr>
               <?php
               $id++;
             }
            ?>
        </tbody>
      </table>
      <?php
     } else {
       echo "Tabela coleta zerada em ".$fdia->format('d/m/Y');
     }
     
    ?>
    <div class="container p-2 m-2">
      
    </div>
    <script src="bootstrap/js/bootstrap.js"></script>
    <script src="bootstrap/js/bootstrap.bundle.js"></script>
    <script src="https://unpkg.com/htmx.org@2.0.0" integrity="sha384-wS5l5IKJBvK6sPTKa2WZ1js3d947pvWXbPJ1OmWfEuxLgeHcEbjUUA5i9V5ZkpCw" crossorigin="anonymous"></script>
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