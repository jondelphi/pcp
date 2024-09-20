<?php
session_start();
$_SESSION['pcpAPI']['pagina']='View/produtos.php';
$_SESSION['pcpAPI']['titulo']='Braslar- PCP - Produtos';
header('location:../index.php')
?>