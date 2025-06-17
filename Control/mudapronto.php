<?php 
session_start();
require "../DAO/config.php";
$conexao = ConexaoMysql::getConnectionMysql();
$pronto = $_GET['pronto'];
$id = $_GET['id'];
try{
$update = 'UPDATE tbpedido SET pronto="'.$pronto.'" WHERE id='.$id;
/* echo $update;
echo "<br>"; */
$consulta = $conexao->prepare($update);
$consulta->execute();
}catch (PDOException $e) {
    echo "Erro ao atualizar o status: " . $e->getMessage();
    exit;
}

header("Location: ../");
?>