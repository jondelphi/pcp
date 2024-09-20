<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
require('../DAO/config.php');


$idpedido=$_GET['id'];



$conn= ConexaoMysql::getConnectionMysql();



try {
    $insert="DELETE FROM tbpedido WHERE id='".$idpedido."'";
    $query=$conn->prepare($insert);
    $query->execute();
    
    $query->execute();
    $_SESSION['pcpAPI']['pagina']='View/carga.php';
$_SESSION['pcpAPI']['titulo']='Braslar- PCP';
} catch (PDOException $e) {
    $_SESSION['pcpAPI']['pagina']='View/carga.php';
$_SESSION['pcpAPI']['titulo']='Braslar- PCP';
    header("location:../index.php");
 }
   
 header("location:../index.php");

?>