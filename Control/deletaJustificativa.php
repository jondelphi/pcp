<?php
 session_start();
 $_SESSION['pcpAPI']['pagina']='View/placar.php';
 $_SESSION['pcpAPI']['idatual']=$_GET['id'];
 $sql='http://10.1.2.251/api/deletaJustificativa/'. $_SESSION['pcpAPI']['idatual'];
file_get_contents('http://10.1.2.251/api/deletaJustificativa/'. $_SESSION['pcpAPI']['idatual']);
 header('location:../index.php');
?>