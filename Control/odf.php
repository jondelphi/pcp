<?php
session_start();
$_SESSION['pcpAPI']['pagina']='View/ODF.php';
$_SESSION['pcpAPI']['titulo']='BRASLAR - PCP - ODF';
header('location:../index.php');

?>