<?php
session_start();
$_SESSION['pcpAPI']['pagina']='View/editacarga.php';
$_SESSION['pcpAPI']['titulo']='Braslar- PCP';
$_SESSION['pcpAPI']['idpedido']=$_GET['id'];
header('location:../index.php')
?>