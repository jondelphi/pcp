<?php
session_start();
$_SESSION['pcpAPI']['pagina']='View/planejamento.php';
$_SESSION['pcpAPI']['titulo']='BRASLAR - PCP - PLANEJAMENTO';
header('location:../index.php');

?>