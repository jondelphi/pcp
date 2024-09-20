<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
require('../DAO/config.php');
require('../Model/ClasseProduto.php');

$idcoleta=$_GET['coleta'];
$idaponta=$_GET['aponta'];




$conn= ConexaoMysql::getConnectionMysql();



try {
    
    $insert="DELETE FROM tbcoleta WHERE id='".$idcoleta."'";
    $query=$conn->prepare($insert);
    $query->execute();
} catch (PDOException $e) {
    header("location:../coleta.php");
 }
   
 header("location:../coleta.php");

?>