<?php
session_start();
require('../DAO/config.php');

require('../Model/ClasseLinha.php');
require('../Model/ClasseODF.php');
require('../Model/ClasseProduto.php');
require('../Model/ClasseProgramacao.php');
require('../Model/ClasseCarga.php');

if ($_POST) {
    $carga=$_POST['carga'];
    $produto=$_POST['produto'];
    if(strlen($produto)==5){
        $produto="100".$produto;
    }

    $classProduto=new Carga;
    $idproduto=$classProduto->idProduto($produto);
    $bompara=$_POST['bompara'];
    $quantidade=$_POST['quantidade'];
    $prioridade=$_POST['prioridade'];
    $user=$_SESSION['pcpAPI']['userlogado'];
  

$conn=ConexaoMysql::getConnectionMysql();

try {
    $insert="INSERT INTO tbpedido(destino,datapedido,prioridade,iduser) 
    VALUES('$carga','$bompara',$prioridade,$user)";  
   // echo $insert; 
   
    $insert=$conn->prepare($insert);
    $insert->execute();
    
    $_SESSION['pcpAPI']['pagina']='View/maisprodutos.php';
    $_SESSION['pcpAPI']['idpedido']=$conn->lastInsertId();
    $lastid=$conn->lastInsertId();;
    $_SESSION['pcpAPI']['carga']=$carga;
    $_SESSION['pcpAPI']['datapedido']=$bompara;
    $_SESSION['pcpAPI']['titulo']='Braslar- PCP';

    try{
        $insert="INSERT INTO tbitempedido(idproduto,quantidade,idpedido) 
        VALUES($idproduto,$quantidade,$lastid)";  
        echo $insert;        
        $insert=$conn->prepare($insert);
        $insert->execute();
    }catch(PDOException $e){
        $_SESSION['pcpAPI']['erro']=true;
        $_SESSION['pcpAPI']['mensagem']="Não foi cadastrado nenhum produto";
    }
} catch (PDOException $th) {
    //throw $th;
    $_SESSION['pcpAPI']['erro']=true;
    $_SESSION['pcpAPI']['mensagem']="Não foi cadastrado";
}


}
    
    


header('location:../index.php');
?>