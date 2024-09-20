<?php
session_start();
require('../DAO/config.php');
require('../Model/ClasseProduto.php');
$_SESSION['pcpAPI']['pagina']='View/linha.php';
$_SESSION['pcpAPI']['titulo']='Braslar- PCP';
$con=ConexaoMysql::getConnectionMysql();
$dia=$_SESSION['pcpAPI']['diaapont'];
$idlinha=$_POST['idlinha'];
$quantidade=$_POST['colaboradores'];
$insert="INSERT INTO colaboradores(dia_presenca,idlinha,quantidade)VALUES('{$dia}',{$idlinha},{$quantidade})";
$update="UPDATE colaboradores set quantidade={$quantidade} where dia_presenca='{$dia}' and idlinha={$idlinha}";


try {
   $insert=$con->prepare($insert);
   $insert->execute(); 
} catch (PDOException $e) {
    $update=$con->prepare($update);
    $update->execute(); 
}

header('location:../index.php');
?>

