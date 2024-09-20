<?php
session_start();
$_SESSION['pcpAPI']['pagina']='View/programacao.php';
$_SESSION['pcpAPI']['titulo']='Braslar- PCP - Programação';
unset($_SESSION['pcpAPI']['linha']);

header('location:../index.php')
?>