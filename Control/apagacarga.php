<?php
session_start();
$_SESSION['pcpAPI']['pagina']='View/confirmaapagacarga.php';
$_SESSION['pcpAPI']['titulo']='Braslar- PCP';
$_SESSION['pcpAPI']['idpedido']=$_GET['id'];
header('location:../index.php')
?>