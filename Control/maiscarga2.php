<?php
session_start();
require('../DAO/config.php');

require('../Model/ClasseLinha.php');
require('../Model/ClasseODF.php');
require('../Model/ClasseProduto.php');
require('../Model/ClasseProgramacao.php');
require('../Model/ClasseCarga.php');
if ($_POST) {
   
    $produto=$_POST['produto'];
    if(strlen($produto)==5){
        $produto="100".$idproduto;
    }
    $classProduto=new Carga;
    $idproduto=$classProduto->idProduto($produto);
    $quantidade=$_POST['quantidade'];
$lastid=$_SESSION['pcpAPI']['idpedido'];

$conn=ConexaoMysql::getConnectionMysql();

try {
    $insert="INSERT INTO tbitempedido(idproduto,quantidade,idpedido) 
        VALUES($idproduto,$quantidade,$lastid)";  
        echo $insert;        
        $insert=$conn->prepare($insert);
        $insert->execute();
    
    $_SESSION['pcpAPI']['erro']=true;
    $_SESSION['pcpAPI']['mensagem']="Cadastrado com sucesso";
    
   
} catch (PDOException $th) {
    //throw $th;
    $_SESSION['pcpAPI']['erro']=true;
    $_SESSION['pcpAPI']['mensagem']="Não foi cadastrado";
}


}
    
    


header('location:../index.php');
?>