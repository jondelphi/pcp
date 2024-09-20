<?php
 session_start();
 $_SESSION['pcpAPI']['pagina']='View/detalhesodf.php';
 $_SESSION['pcpAPI']['idatual']=$_GET['id'];
 header('location:../index.php')
?>