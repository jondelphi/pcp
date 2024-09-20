<?php
  session_start();
  require('../DAO/config.php');
  
  require('../DAO/config.php');
  require('../Model/ClasseLinha.php');
  require('../Model/ClasseODF.php');
  require('../Model/ClasseProduto.php');
 $dia=new DateTime('now');
 $classODF=new ODF;
  $con=ConexaoMysql::getConnectionMysql();
$con2=ConexaoMysql::getConnectionMysql();


  $sel="SELECT * FROM tbpcp WHERE id=".$_GET['id'];
  $sel=$con->prepare($sel);
  $sel->execute();
  while ($k=$sel->fetch(PDO::FETCH_ASSOC)) {
    $odf=$k['odf'];
    $linha=$k['idlinha'];
    $produto=$k['idproduto'];
    $dataentregue=$dia->format('Y-m-d');
    $quantidade=$k['quantidade'];
    $e1=$k['etiquetaini'];
    $e2=$k['etiquetafim'];    
  }

  //quantos bipou;
  $nq=$classODF->TotalBipado($con2,$e1,$e2);
  $quantidade=$quantidade-$nq;
try{
  $insert="INSERT INTO tbpcp(odf,idlinha,idproduto,dataentregue,quantidade,etiquetaini,etiquetafim)VALUES
  ($odf,$linha,$produto,'$dataentregue',$quantidade,'$e1','$e2')";
  $insert=$con->prepare($insert);
  $insert->execute();
  $_SESSION['pcpAPI']['erro']=true;
  $_SESSION['pcpAPI']['mensagem']="Cadastrado com sucesso";
}catch(PDOException $e){
    $_SESSION['pcpAPI']['erro']=true;
    $_SESSION['pcpAPI']['mensagem']="Não foi atualizado";
}

header('location:../index.php')

?>