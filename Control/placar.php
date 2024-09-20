<?php
session_start();
$_SESSION['pcpAPI']['pagina']='View/placar.php';
$_SESSION['pcpAPI']['titulo']='BRASLAR - PCP - PLACAR';
header('location:../index.php');

?>