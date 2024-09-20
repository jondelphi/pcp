<?php
session_start();
$_SESSION['pcpAPI']['pagina']='View/rastreio.php';
$_SESSION['pcpAPI']['titulo']='Braslar- Rastreio';


header('location:../index.php')
?>