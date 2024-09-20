<?php
session_start();
$_SESSION['pcpAPI']['pagina']='View/editaalmoco.php';
$_SESSION['pcpAPI']['titulo']='Braslar- PCP';
$_SESSION['pcpAPI']['idalmoco']=$_GET['id'];
$_SESSION['pcpAPI']['idlinha']=$_GET['idlinha'];


header('location:../index.php')
?>