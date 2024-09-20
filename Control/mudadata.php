<?php
session_start();
$_SESSION['pcpAPI']['diaapont']=$_GET['dia'];
header('location:../index.php');