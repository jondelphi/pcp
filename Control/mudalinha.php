<?php
session_start();
require('../DAO/config.php');

$con=ConexaoMysql::getConnectionMysql();
$up="UPDATE tbapontamento set idlinha='".$_GET['idlinha']."' where id=".$_GET['id'];
try {
    echo $up;
    $up=$con->prepare($up);
    $up->execute();
} catch (PDOException $th) {
   echo $e->getmessage();
}
