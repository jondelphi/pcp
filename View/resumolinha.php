<?php
session_start();
require("../Model/ClasseAcesso.php");

require("../DAO/configIgor.php");
require("../DAO/config.php");
require("../Model/ClasseApontamento.php");
require("../Model/ClasseProduto.php");
require("../Model/ClasseLinha.php");
require("../Model/classeAlmoco.php");
$classeapontamento = new Apontamento;

function qualodf($etiqueta)
{
    $res=array();
    $res['odf']='SEM ODF';
    $res['linha']='SEM LINHA';
    $res['idlinha']='0';

    $sel= "select odf from etiquetas where serie like '$etiqueta'";
    $korp=ConexaoIgor::getConnectionIgor();
    $sel=$korp->prepare($sel);
    $sel->execute();
    if ($sel->rowCount()<>0) {
        while ($k = $sel->fetch(PDO::FETCH_ASSOC)) {
          $odf=$k['odf'];
          $res['odf']=$odf;
        }
        $q="SELECT tblinha.linha, tblinha.id as 'idlinha' from tbpcp 
       inner join tblinha ON tblinha.id = tbpcp.idlinha where odf='$odf' and dataentregue='".$_SESSION['pcpAPI']['diaapont']."' ";
        $mysql=ConexaoMysql::getConnectionMysql();
        $q=$mysql->prepare($q);
        $q->execute();
        if($q->rowCount()<>0)
        {
            while ($j=$q->fetch(PDO::FETCH_ASSOC)) {
                $res['linha']=$j['linha'];
                $res['idlinha']=$j['idlinha'];
            }
        }
    }
    return $res;
}

$conn = ConexaoMysql::getConnectionMysql();
function odflinha($etiqueta){
    $classelinha=new Linha;
    $con=ConexaoIgor::getConnectionIgor();
    $con2=ConexaoMysql::getConnectionMysql();
    $etiqueta=trim($etiqueta);
    $sqlKorp="SELECT odf as 'odf' FROM etiquetas WHERE serie like'$etiqueta'";
   
    $sqlKorp=$con2->prepare($sqlKorp);
    $sqlKorp->execute();
    $result=array();
    $result['odf']='Sem odf';
    $result['idlinha']='Sem linha';
    if($sqlKorp->rowCount()<>0){
        while ($k=$sqlKorp->fetch(PDO::FETCH_ASSOC)) {
           $odf=$k['odf'];
           $sql3="SELECT * from tbpcp WHERE ODF='$odf'";
     
           $sql3=$con2->prepare($sql3);
           $sql3->execute();
           $result['odf']=$odf;
           if($sql3->rowCount()<>0){
            while ($j=$sql3->fetch(PDO::FETCH_ASSOC)) {
                $result['odf']=$odf;
                $result['linha']=$classelinha->trazlinha($j['linha']);  
                 
            }
           }
        }
    }
    return $result;

}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumo - PCP </title>
    <link rel="icon" type="image/x-icon" href="../content/icone.png">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap-icons.css">
    <style>
        i {
            padding: 2px;
            margin: 2px;
        }
    </style>

</head>

<body>
    <div style="margin-top:-8px; background-color: #333;color:#fff;">
        <div class="container">
            <div class="container m-2 p-2">
                <div class="row">
                    <div class="col">
                        <img src="../content/logobranco.png" width="200px;" style="float:left" class="m-2 p-1">
                    </div>
                    <div class="col m-2 p-2 text-center">
                        <h4 class="fw-bolder text" style="color:#fff;text-shadow:0px 0px 2px #fff;font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;">
                          
                            <?php
                            $dia = new DateTime($_SESSION['pcpAPI']['diaapont']);
                            ?>
                            <?php echo $dia->format("d/m/Y"); ?>
                        </h4>
                    </div>

                </div>



            </div>

        </div>
    </div>
    <script>
         function mensagem(link){
            var str="Deseja excluir a etiqueta?";
            var con=confirm(str);
            if (con==true) {
                window.location.href=link;
            }   
        }
    </script>    
    <div class="container">


            <div class="container m-2 p-2 bg-black text-white fw-bolder">
                <h2 class="text-center">Total:
                    <?php
                    echo ($classeapontamento->TodasApontadas($conn));
                    ?>
                </h2>
            </div>

          



            <div class="container m-2 p-2">
            
                       <?php
                    
                       $dia=$_SESSION['pcpAPI']['diaapont'];
                       $sql="SELECT tbapontamento.etiqueta as 'etiqueta',tbapontamento.id as 'idapont',tblinha.id as 'idlinha', tblinha.linha as 'linha',tbproduto.codigo as 'produto', tbapontamento.horaapont FROM tbapontamento 
                       INNER JOIN tblinha on tbapontamento.idlinha = tblinha.id
                       INNER JOIN tbproduto on tbapontamento.idproduto = tbproduto.id
            
                       WHERE diaapont='$dia'  ORDER BY tblinha.linha ASC, tbapontamento.id DESC";
                      
                        $sel=$conn->prepare($sql);
                        $sel->execute();
                        $numreg=$sel->rowCount();
                        if ($numreg>0) {
                            ?>
                            <table class="table table-dark table-bordered table-striped">
                                <thead>
                                <tr class="text-center">  
                                    <th colspan="6">Apontamentos</th>
                                    </tr>
                               
                                      <tr>  
                                      <th><i class="bi bi-minecart-loaded"></i>Linha</th>
                                        <th><i class="bi bi-box-fillt"></i>Produto</th>
                                        <th><i class="bi bi-ticket-detailed"></i>Etiqueta</th>
                                        <th><i class="bi bi-alarm"></i>HORA</th>
                                     
                                        <th><i class="bi bi-gear-fill"></i>ODF</th>
                                        <th>Linha</th>
                                
                                    </tr>
                                </thead>
                            <?php
                            while ($l = $sel->fetch(PDO::FETCH_ASSOC)) {
                              ?>
                                <tr>
                                <td><?php 
                               $res=qualodf($l['etiqueta']);
                               $linhaodf=$res['linha'];
                               $essaodf=$res['odf'];
                               $idlinha=$res['idlinha'];
                                $linhaapontou=$l['linha'];
                                echo $linhaapontou;?>
                                </td>
                                    <td><?php echo $l['produto'];?></td>
                                    <td><?php echo $l['etiqueta'];?></td>
                                    <td class="text-end"><?php echo $l['horaapont'];?></td>
                                    <td class="text-end">
                                        <span title="Linha que apontou <?php echo $linhaapontou?> <br> Linha original  <?php echo $linhaodf?>">
                                    <?php                             
                                    
                                  
                                   echo ($essaodf);
                                  
                                   
                
                                    ?>
                                    </span>
                
                                    </td>
                                  
                
                                    <td>
                                    <?php
                                 
                                    
                                  
                                    if ($linhaapontou<>$linhaodf&&$linhaodf!="SEM LINHA") {
                                       ?>
                                       <a href="../Control/mudalinha.php?id=<?php echo $l['idapont'];?>&idlinha=<?php echo $idlinha;?>" class="btn btn-primary m-1 p-1">MUDA</a>
                                       <?php
                                    }
                                                
                                    ?>
                                    
                                   </td>
                                </tr>
                              <?php
                            }
                            ?>  
                            </table>
                            <?php
                        } 
                       ?>
            
            </div>
        </div>

</body>

</html>