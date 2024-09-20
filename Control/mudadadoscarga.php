<?php
session_start();

require('../DAO/config.php');

require('../Model/ClasseLinha.php');
require('../Model/ClasseODF.php');
require('../Model/ClasseProduto.php');
require('../Model/ClasseProgramacao.php');
require('../Model/ClasseCarga.php');
if ($_POST) {
    $idpedido=$_SESSION['pcpAPI']['idpedido'];
    $bompara=$_POST['bompara'];
    $carga=$_POST['carga'];
    $prioridade=$_POST['prioridade'];

$conn=ConexaoMysql::getConnectionMysql();

try {
    $up="UPDATE tbpedido SET destino='$carga',datapedido='$bompara',prioridade=$prioridade WHERE id=$idpedido";  
        echo $up;
        $up=$conn->prepare($up);
        $up->execute();
    
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