<?php
session_start();
require('../DAO/config.php');

require('../Model/ClasseLinha.php');
require('../Model/ClasseODF.php');
require('../Model/ClasseProduto.php');
require('../Model/ClasseProgramacao.php');
if ($_POST) {
    $id=$_POST['idproduto'];
    $max=$_POST['maximo'];
    
$conn=ConexaoMysql::getConnectionMysql();

try {
    $update="UPDATE tbproduto set maxestoque=$max 
    WHERE id=$id";

    $update=$conn->prepare($update);
    $update->execute();
} catch (PDOException $th) {
  throw $th;
  echo "<pre>".$th->getMessage()."</pre>";
}



    
    
}

$_SESSION['pcpAPI']['titulo']='Braslar- PCP - Produtos';
header('location:../index.php');
?>