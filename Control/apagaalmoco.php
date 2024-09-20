<?php
session_start();
require('../DAO/config.php');
if ($_GET) {
    $id = $_GET['id'];
    $conn = ConexaoMysql::getConnectionMysql();
    try {
        $update = "DELETE FROM tbalmocolinha WHERE id=$id";
        $update = $conn->prepare($update);
        $update->execute();
    } catch (PDOException $th) {
        throw $th;
    }
}
$_SESSION['pcpAPI']['pagina'] = 'View/almoco.php';
$_SESSION['pcpAPI']['titulo'] = 'Braslar- PCP';
header('location:../index.php');
