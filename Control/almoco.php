<?php
session_start();
$_SESSION['pcpAPI']['pagina']='View/almoco.php';
$_SESSION['pcpAPI']['titulo']='Braslar- PCP';


header('location:../index.php')
?>