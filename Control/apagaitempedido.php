<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
require('../DAO/config.php');


$iditem=$_GET['iditem'];



$conn= ConexaoMysql::getConnectionMysql();



try {
    $insert="DELETE FROM tbitempedido WHERE id='".$iditem."'";
    $query=$conn->prepare($insert);
    $query->execute();

  
} catch (PDOException $e) {
    
    header("location:../index.php");
 }
   
 header("location:../index.php");

?>