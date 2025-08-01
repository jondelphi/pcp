<?php
session_start();
$_SESSION['pcpAPI']['pagina']='View/comecaJustificativa.php';
$_SESSION['pcpAPI']['titulo']='Braslar- PCP';
$_SESSION['pcpAPI']['codigojus']=$_GET['codigo'];
$_SESSION['pcpAPI']['modelojus']=$_GET['modelo'];
$_SESSION['pcpAPI']['linhajus']=str_replace('0', ' ', $_GET['linha']);


header('location:../index.php')
?>