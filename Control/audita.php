<?php
session_start();
$_SESSION['pcpAPI']['pagina']='View/auditoria.php';
$_SESSION['pcpAPI']['titulo']='Braslar- PCP';


header('location:../index.php')
?>