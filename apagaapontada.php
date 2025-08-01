<?php
ob_start();
session_start();
set_time_limit(0);

require "DAO/config.php";
require "DAO/configIgor.php";
require "Model/ClasseRequest.php";


function cadastra(){
   
$Conexao    = ConexaoMysql::getConnectionMysql();
$Conexao2    = ConexaoIgor::getConnectionIgor();
if(isset($_SESSION['pcpAPI']['diaapont'])){
    $dia=$_SESSION['pcpAPI']['diaapont'];
}else{
    $dia=date('Y-m-d');
}

$select = 'SELECT id,etiqueta FROM tbcoleta where diaapont="'.$dia.'"';
//var_dump($select);
$consulta = $Conexao->prepare($select);
$consulta->execute();
$apagou=0;
$_SESSION['coletaapagada']=0;
$dados=$consulta->fetchALL(PDO::FETCH_ASSOC);
//var_dump($dados);

foreach ($dados as $k) {
    
    $query = 'SELECT bip
    FROM etiquetas WHERE serie ="'. $k['etiqueta'] .'"';
    $procura = $Conexao2->prepare($query);
    $procura->execute();
    if($procura->rowCount()> 0){       
        $item=$procura->fetch(PDO::FETCH_ASSOC);
         if (!is_null($item['bip'])){
             if ($item['bip']=='4') {                
                 $apaga = 'DELETE FROM tbcoleta WHERE id=' . $k['id'];
                 $delete = $Conexao->prepare($apaga);
                 $delete->execute();
                 $_SESSION['coletaapagada']++;
             }  
         }
    }else{
        
    }
   
 
}

while ($linha = $consulta->fetchALL(PDO::FETCH_ASSOC)) {
    $query = 'SELECT bip
    FROM etiquetas WHERE serie ="'. $linha['etiqueta'] .'"';
    $procura = $Conexao2->prepare($query);
    $procura->execute();
    $procura=$procura->fetch(PDO::FETCH_ASSOC);
   
    $bip=$procura['bip'];
    if (!is_null($bip)){
        if ($bip=='4') {
            $apaga = 'DELETE FROM tbcoleta WHERE id='. $linha['id'];
            $delete = $Conexao->prepare($apaga);
            $delete->execute();
            $_SESSION['coletaapagada']++;
        }  
    }
}
}

cadastra();
if ($_SESSION['coletaapagada']==0) {
    unset($_SESSION['coletaapagada']);
}
header('location:coleta.php');
?>