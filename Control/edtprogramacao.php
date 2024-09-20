<?php
session_start();
require('../DAO/config.php');

require('../Model/ClasseLinha.php');
require('../Model/ClasseODF.php');
require('../Model/ClasseProduto.php');
require('../Model/ClasseProgramacao.php');
$conn=ConexaoMysql::getConnectionMysql();

if($_GET){
    $id=$_GET['id'];
    $_SESSION['programacao']['idprogramacao']=$id;
    $sql = "SELECT tbprogramacao.*, tblinha.linha as 'desclinha' FROM tbprogramacao 
    INNER JOIN tblinha ON tblinha.id = tbprogramacao.idlinha
    WHERE tbprogramacao.id=$id";
   try{
    $sql=$conn->prepare($sql);
    $sql->execute();
    while ($k=$sql->fetch(PDO::FETCH_ASSOC)) {
        $_SESSION['programacao']['idlinha']=$k['idlinha'];
        $_SESSION['programacao']['idproduto']=$k['idproduto'];
        $_SESSION['programacao']['quantidade']=$k['quantidade'];
        $_SESSION['programacao']['dataprojeto']=$k['dataprojeto'];
        $_SESSION['programacao']['obs']=$k['obs'];
        $_SESSION['programacao']['horainicio']=$k['horainicio'];
        $_SESSION['programacao']['horafim']=$k['horafim'];

$_SESSION['pcpAPI']['pagina']='View/edtprogramacao.php';
$_SESSION['pcpAPI']['titulo']='Braslar- PCP - Programação';
$_SESSION['pcpAPI']['linha']= $_SESSION['programacao']['idlinha'];
//var_dump($_SESSION['programacao']);
    }
    }catch(Exception){
    
    }
   
}

header('location:../index.php');
?>