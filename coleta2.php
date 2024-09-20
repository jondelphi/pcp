<?php
session_start();
require('DAO/config.php');
require('DAO/config2.php');


require('Model/ClasseAcesso.php');
require('Model/ClasseApontamento.php');
require('Model/ClasseODF.php');
require('Model/ClasseLinha.php');
require('Model/ClasseProduto.php');


$conn = ConexaoMysql::getConnectionMysql();
$conn2 = ConexaoMysql2::getConnectionMysql2();
function CodigoProduto($prefixo, $conn)
{
    $teste = substr($prefixo, 0, 8);
    $sql = "SELECT tbproduto.id as 'id' FROM tbproduto 
    INNER JOIN tbprefixo ON tbproduto.id=tbprefixo.idproduto
    WHERE tbprefixo.prefixo like '" . $teste . "%'";
    // echo $sql."<br>";

    $sel = $conn->prepare($sql);
    $sel->execute();
    $nr = $sel->rowCount();
    if ($nr > 0) {
        while ($l = $sel->fetch(PDO::FETCH_ASSOC)) {
            $result = $l['id'];
        }
    } else {
        $result = "Sem Cadastro";
    }
    return ($result);
}
function insere()
{
    $conn2 = ConexaoMysql2::getConnectionMysql2();
    $conn = ConexaoMysql::getConnectionMysql();
    $sql = "select etiqueta from tbcoleta order by id limit 60";
    $sql = $conn2->prepare($sql);
    $sql->execute();
    $item = $sql->fetchAll(PDO::FETCH_ASSOC);
    /*  echo "<pre>";
    var_dump($item);
    echo "</pre>"; */

    foreach ($item as $k) {
        $etiqueta = $k['etiqueta'];
        $cod = CodigoProduto($etiqueta, $conn);
        $query = "INSERT INTO tbcoleta (etiqueta,diaapont) values('$etiqueta','2023-09-17')";
        try {
            $query = $conn->prepare($query);
            $query->execute();
            $query = "INSERT INTO tbapontamento (etiqueta,idlinha,idproduto,diaapont,horaapont,intervalo) values('$etiqueta',4,$cod,'2023-09-17','17:00','9')";
            try {
                $query = $conn->prepare($query);
                $query->execute();
            } catch (PDOException $e) {
            }
        } catch (PDOException $e) {
        }
    }
}

function compara()
{
    $pcmmy = ConexaoMysql2::getConnectionMysql2();
    $conn = ConexaoMysql::getConnectionMysql();
    $sql = "select etiqueta from tbcoleta";
    $arr = array();
    $sql = $pcmmy->prepare($sql);
    $sql->execute();
    $item = $sql->fetchAll(PDO::FETCH_ASSOC);
    /*     echo "<pre>";
    var_dump($item);
    echo "</pre>"; */

    foreach ($item as $k) {
        $etiqueta = $k['etiqueta'];
        //  $cod = CodigoProduto($etiqueta, $conn);
        $query = "select etiqueta from tbcoleta where etiqueta like '$etiqueta' ";
        $query = $conn->prepare($query);
        $query->execute();
        if($query->rowCount()<>0){          
            echo "<pre>";
            echo "'$etiqueta' está nas duas tabelas";
            echo "</pre>"; 
        }else{
            echo "<pre>";
            echo "'$etiqueta' não está na tbcoleta do banco bdpcp";
            echo "</pre>";  
        }  
    
}
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
    <style>
        i {
            padding: 2px;
            margin: 2px;
        }
    </style>


</head>

<body>
    <?php
    compara();

    ?>

</body>

</html>