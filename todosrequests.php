<?php
session_start();
set_time_limit(0);
require('DAO/config.php');
require('Model/ClasseRequest.php');
$conn = ConexaoMysql::getConnectionMysql();
if(isset($_SESSION['pcpAPI']['diaapont'])){
    $dia=$_SESSION['pcpAPI']['diaapont'];
}else{
    $dia=date('Y-m-d');
}
$sql = "SELECT etiqueta FROM tbcoleta WHERE diaapont = :diaapont ORDER BY id ASC";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':diaapont', $dia);
$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $etiqueta = $row['etiqueta'];
    $req = new ClasseRequest;
    $req->etiqueta($etiqueta); 
    sleep(2);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/x-icon" href="content/icone.png">
    <title>Coleta</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap-icons.css">
</head>
<body>
    <h1>Request completo</h1>
    <a href="coleta.php" class="btn btn-secondary m-2 p-2">Coleta</a>
</body>
</html>
