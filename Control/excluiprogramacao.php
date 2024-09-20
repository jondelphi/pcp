<?php
session_start();
require('../DAO/config.php');
$conn=ConexaoMysql::getConnectionMysql();
$id=$_GET['id'];
$del="DELETE FROM tbprogramacao WHERE id=$id";
try
{
    $del=$conn->prepare($del);
    $del->execute();
    $_SESSION['pcpAPI']['erro']=true;
    $_SESSION['pcpAPI']['mensagem']="Programação excluida com sucesso!";
}catch(PDOException $e){
    $_SESSION['pcpAPI']['erro']=true;
    $_SESSION['pcpAPI']['mensagem']="Não foi deletado nenhuma Programação";

}

header('location:../index.php')
?>