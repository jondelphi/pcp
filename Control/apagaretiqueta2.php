<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
require('../DAO/config.php');
require('../Model/ClasseProduto.php');

$etiqueta=$_GET['etiqueta'];

$linha=$_SESSION['pcpAPI']['linha'];

$conn= ConexaoMysql::getConnectionMysql();



try {
    $insert="DELETE FROM tbapontamento WHERE etiqueta='".$etiqueta."'";
    $query=$conn->prepare($insert);
    $query->execute();
    $insert="DELETE FROM tbcoleta WHERE etiqueta='".$etiqueta."'";
    $query=$conn->prepare($insert);
    $query->execute();
} catch (PDOException $e) {
    header("location:../View/resumo.php?linha=$linha");
 }
   
 header("location:../View/resumoetiquetas.php?linha=$linha");

?>