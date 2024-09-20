<?php
session_start();
require('../DAO/config.php');
$conn=ConexaoMysql::getConnectionMysql();
$id=$_GET['id'];
$del="DELETE FROM tbpcp WHERE id=$id";
try
{
    $del=$conn->prepare($del);
    $del->execute();
    $_SESSION['pcpAPI']['erro']=true;
    $_SESSION['pcpAPI']['mensagem']="ODF excluida com sucesso!";
}catch(PDOException $e){
    $_SESSION['pcpAPI']['erro']=true;
    $_SESSION['pcpAPI']['mensagem']="Não foi deletado nenhuma ODF";

}
$_SESSION['pcpAPI']['pagina']='View/odf.php';
header('location:../index.php')
?>