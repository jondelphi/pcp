<?php
session_start();
require('../DAO/config.php');

require('../Model/ClasseLinha.php');
require('../Model/ClasseODF.php');
require('../Model/ClasseProduto.php');
require('../Model/ClasseProgramacao.php');

function cadastraprefixo($idpro,$pre){
    $con=$conn=ConexaoMysql::getConnectionMysql();
    $ins="INSERT INTO tbprefixo(idproduto,prefixo)VALUES($idpro,'$pre')";
    try {
        $ins=$con->prepare($ins);
        $ins->execute();
    } catch (PDOException $e) {
        # code...
    }
}
if ($_POST) {
    $cod=$_POST['codigo'];
    $categoria=$_POST['categoria'];
    $prefixo=$_POST['prefixo'];
    $descri=$_POST['descricao'];
    $codbase=$_POST['codbase'];
    if(!isset($_POST['valor'])){
        $valor=0; 
    }else{
        $valor=$_POST['valor'];
    }
    $valor=$_POST['valor'];
    $valor=str_replace(',','.',$valor);


$conn=ConexaoMysql::getConnectionMysql();

/* try{
$conn2=ConexaoKorp::getConnectionKorp();
$busca="select descri,PREFIXO_N_SERIE from estoque where CODIGO='$cod'";
$busca=$conn2->prepare($busca);
$busca->execute();
if ($busca->rowCount()<>0) {
   while ($g=$busca->fetch(PDO::FETCH_ASSOC)) {
   $descri=$g['descri'];
   if(isset($g['PREFIXO_N_SERIE'])){
   $prefixo=$g['PREFIXO_N_SERIE'];
   }
   }
}

}catch(Exception){

} */
try {
    $insert="INSERT INTO tbproduto(codigo,descricao,idcategoria,codigobase,valor,minestoque) 
    VALUES('$cod','$descri',$categoria,'$codbase',$valor,0)";

    $insert=$conn->prepare($insert);
    $insert->execute();
    $idprod=$conn->lastInsertId();
    if(strlen($prefixo)==8){
    cadastraprefixo($idprod,$prefixo);
    }

} catch (PDOException $th) {
   // throw $th;
}



    
    
}

header('location:../index.php');
?>