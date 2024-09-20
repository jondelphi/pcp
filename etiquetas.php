
<?php
session_start();
require('DAO/config.php');

require('Model/ClasseAcesso.php');
require('Model/ClasseApontamento.php');
require('Model/ClasseAlmoco.php');
require('Model/ClasseODF.php');
require('Model/ClasseLinha.php');
require('Model/ClasseProduto.php');
require('Model/ClasseProgramacao.php');
require('Model/ClasseCarga.php');
require('Model/ClasseEstamparia.php');
$teste = new Pecas;
$Conexao = ConexaoMysql::getConnectionMysql();
$_SESSION['diadoget']=$_GET['dia'];

function peganumeros($arr){
    $result=array();
    foreach ($arr as $k) {
       $result[]=substr($k['etiqueta'],-10);
       $_SESSION['linhaapontada']=$k['idlinha'];
    }
    return $result;

}
function encontrarNumerosFaltando($array) {
    if (empty($array)) {
        return []; // Se o array estiver vazio, retorna um array vazio
    }

    sort($array); // Ordena o array em ordem crescente
    $numerosFaltando = [];

    $min = min($array);

    echo "<p class='bg-dark text-info p-2 m-2' style='width:fit-content'>".$_GET['prefixo'].$min."<br>Menor Etiqueta</p>";
    $max = max($array);
    echo "<p class='bg-dark text-info p-2 m-2'  style='width:fit-content'>".$_GET['prefixo'].$max."<br>Maior Etiqueta</p>";

    for ($i = $min + 1; $i < $max; $i++) {
        if (!in_array($i, $array)) {
            $numerosFaltando[] = $i;
        }
    }

    return $numerosFaltando;
}
function concatenarComPrefixo($prefixo, $valores) {
    $resultado = [];

    foreach ($valores as $valor) {     
       

        // Completa com zeros à esquerda até atingir 10 dígitos
        $valorFormatado = str_pad($valor, 10, '0', STR_PAD_LEFT);

        // Concatena o prefixo com o valor formatado
        $resultado[] = $prefixo . $valorFormatado;
    }

    return $resultado;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="content/icone.png">
    <title><?php echo ($_SESSION['pcpAPI']['titulo']) ?></title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap-icons.css">
    <style>
    i {
            padding: 2px;
            margin: 2px;
        }
    .textovertical{
    writing-mode: vertical-rl;
    text-align: center;
    padding: 2px 3px;
    margin: 2px 3px;
}
table.tabelaCarga{
  
    padding: 2px;
    width: fit-content;
}
table.tabelaCarga th {
    border-collapse: collapse;
    border: 1px solid #000;
    padding: 2px 3px;
}
table.tabelaCarga td{
    border: 1px solid #000;
    padding: 2px 3px;

}

.break { page-break-after:left; }




    @media print{
        body * {
            display: none;
        }
        #exporta{
            visibility: visible;
        }
  
    }

    </style>
   
   
</head>
<body>
    <?php 
     $codigo=$_GET['prefixo'];
     $dia=$_GET['dia'];
     $query="select id from tbproduto 
     where tbproduto.codigo = '".$codigo."'";     
     $idproduto=$Conexao->prepare($query);
     $idproduto->execute();
     $idproduto=$idproduto->fetch(PDO::FETCH_ASSOC);
     
     $idproduto=$idproduto['id'];
   
   

     /*  */
     $query="select max(etiqueta) as etiqueta from tbapontamento
     where idproduto=".$idproduto." AND diaapont='".$dia."'";     

    
      $prefixo=$Conexao->prepare($query);
     $prefixo->execute();
     if($prefixo->rowCount()>0){
     $prefixo=$prefixo->fetch(PDO::FETCH_ASSOC);
     $prefixo=$prefixo['etiqueta'];
     if(isset($prefixo)){
        $prefixo=substr($prefixo,0,8);
     
     }else{
        echo "não tem produto";
        die();

     }
     
     $prefixo=substr($prefixo,0,8);
     }else{
        echo "não tem produto";
        die();
     }

     
        $sql="select etiqueta,idlinha from tbapontamento where etiqueta like '".$prefixo."%' and diaapont='".$dia."' order by etiqueta";
        $con=ConexaoMysql::getConnectionMysql();
        $sql=$con->prepare($sql);
        $sql->execute();
        if($sql->rowCount()>0){
            /*  */
            $itens=$sql->fetchAll(PDO::FETCH_ASSOC);
            $numeros=peganumeros($itens);
            /* echo "<pre>";
            var_dump($itens);
            echo "</pre>"; */
            $falta=encontrarNumerosFaltando($numeros);
            /* echo "<pre>";
            var_dump($falta);
            echo "</pre>";
            echo "<pre>";
            var_dump($numeros);
            echo "</pre>";  */
            $monta=concatenarComPrefixo($prefixo,$falta);
    
           /*  echo "<pre>";
            var_dump($monta);
            echo "</pre>"; */
    
                $faltou=count($monta);
                ?>    
                <div class="container m-2 p-2">
                 <a href="teste.php" class="btn btn-secondary m-2">Outra</a>
                  
                 <table class="table table-dark table-striped table-bordered" style="width:fit-content;">
                     <thead>
                         <tr>
                             <th>Etiqueta</th>
                             <th>Aponta</th>
                         </tr>
                     </thead>
                     <tbody>
                         <?php 
                         $falta = 0;
                         foreach($monta as $k){
                            $encontre = "SELECT count(*) as tudo from tbapontamento where etiqueta like '{$k}'";
                            $encontre = $Conexao->prepare($encontre);
                            $encontre->execute();
                            $encontre = $encontre->fetch(PDO::FETCH_ASSOC);
                            $encontre = $encontre['tudo'];                          
                            if($encontre==0){
                                $falta++;
                             ?>
                             <tr>
                                 <td><?php echo $k;?></td>
                                 <td>
                                     <a href="Control/cadastraetiqueta.php?etiqueta=<?php echo $k;?>" target="_blank" class="btn btn-primary">
                                     <i class="bi bi-check"></i>
                                 </a>
                                 </td>
                             </tr>
                             <?php
             
                         }
                        }
                         ?>
                     </tbody>
                 </table>
                 <p class="bg-info text-bg-info m-1 p-2" style="width: fit-content;">
                     <?php 
                     echo "Faltou apontarem ".$falta." etiquetas";
                     ?>
                 </p>
             
                </div><?php

            /*  */
        }
       
        ?>
        
        
     -->

    
   

    <script src="bootstrap/js/bootstrap.js"></script>
    <script src="bootstrap/js/bootstrap.bundle.js"></script>
    <script src="bootstrap/js/popper.min.js"></script>


    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})
    </script>
   
</body>
</html> 