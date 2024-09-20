<?php
session_start();
require('../DAO/config.php');

if ($_POST) {
    $id=$_SESSION['pcpAPI']['idalmoco'];
    $horasaida=$_POST['horasaida'];
    $horaentrada=$_POST['horaentrada'];
    $horasaida=new DateTime($horasaida);
    $horaentrada=new DateTime($horaentrada);
$saida=$horasaida->format("H:i:s");
$entrada=$horaentrada->format("H:i:s");

$conn=ConexaoMysql::getConnectionMysql();

try {
    $update="UPDATE tbalmocolinha set horaentrada='$entrada',horasaida='$saida' WHERE id=$id";
   echo $update;
    $update=$conn->prepare($update);
    $update->execute();
} catch (PDOException $th) {
   // throw $th;
}


    
}
$_SESSION['pcpAPI']['pagina']='View/almoco.php';
unset($_SESSION['pcpAPI']['idalmoco']);
$_SESSION['pcpAPI']['titulo']='Braslar- PCP';
header('location:../index.php');
?>