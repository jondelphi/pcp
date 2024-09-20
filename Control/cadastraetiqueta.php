<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
require('../DAO/config.php');

require('../Model/ClasseProduto.php');
require('../Model/ClasseLinha.php');
function apontareparo(PDO $con, $cod, $idlinha)
{
    $dia = $_SESSION['diadoget'];
    $hora = date("H:i:s");
    $idreparo=trazidreparo($con,$cod);
    $sql = "INSERT INTO tbapontareparo (idlinha, idreparo, diaapont, horaapont) VALUES (?, ?, ?, ?)";
    
    try {
        $stmt = $con->prepare($sql);
        $stmt->execute([$idlinha, $idreparo, $dia, $hora]);
    } catch(PDOException $e) {
        // Handle the exception (log or display an error message)
    }
}

function apontaPECAreparo(PDO $con, $cod, $idlinha)
{
    $idreparo=trazuidreparo($con,$idlinha);
    $idpeca=trazidpecareparada($con,$cod);
    $sql = "INSERT INTO tbpcreparada (idpeca, idapontareparo) VALUES (?, ?)";    
 
    try {
        $stmt = $con->prepare($sql);
        $stmt->execute([$idpeca, $idreparo]);
    } catch(PDOException $e) {
        // Handle the exception (log or display an error message)
      //  $_SESSION['mensagem']=$e->getMessage();
    }
}

function apontaponto(PDO $con, $idfuncionario)
{
    $dia = date("Y-m-d");
    $sql = "INSERT INTO ponto (diaponto, idfuncionario) VALUES (?, ?)";
    
    try {
        $stmt = $con->prepare($sql);
        $stmt->execute([$dia,$idfuncionario]);
    } catch(PDOException $e) {
        // Handle the exception (log or display an error message)
    }
}

function trazidreparo(PDO $con, $cod)
{
    $sql = "SELECT id FROM tbreparo WHERE codigo = :cod";
    try {
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':cod', $cod);
        $stmt->execute();
        $id = $stmt->fetchColumn();
        if ($id) {
            return $id;
        }
    } catch (PDOException $e) {
        error_log($e->getMessage()); // Log the error
    }
    return null;
}
function trazidpecareparada(PDO $con, $cod)
{
    $sql = "SELECT id FROM tbpcreparo WHERE codigo = :cod";
    try {
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':cod', $cod);
        $stmt->execute();
        $id = $stmt->fetchColumn();
        if ($id) {
            return $id;
        }
    } catch (PDOException $e) {
        error_log($e->getMessage()); // Log the error
    }
    return null;
}
function trazuidreparo(PDO $con,$linha)
{
    $sql = "SELECT id FROM tbapontareparo where idlinha=$linha order by id desc limit 1";
    try {
        $stmt = $con->prepare($sql);
        $stmt->execute();
        $id = $stmt->fetchColumn();
        if ($id) {
            return $id;
        }
    } catch (PDOException $e) {
        error_log($e->getMessage()); // Log the error
    }
    return null;
}




$idlinha =  $_SESSION['linhaapontada'];;

$dia = $_SESSION['diapont'];
if (isset($_GET['etiqueta'])) {    
    $etiqueta = $_GET['etiqueta'];
    $letras = strlen($etiqueta);
    $conn = ConexaoMysql::getConnectionMysql();
    $classeproduto = new Produto;
   
        $prefixo = substr($etiqueta, 0, 8);
        $codigo = $classeproduto->CodigoProduto($prefixo, $conn);
        echo $codigo." - Codigo<br>";
        $idproduto = $classeproduto->trazid($codigo, $conn);
        echo $idproduto." - Codigo<br>";
        $tempo = $_SESSION['diadoget'];
        $hora = date("H:i:s");

        $int = $classeproduto->devolveintervalo($hora);
        if (strlen($etiqueta) == 18) {
            try {
                $insert = "INSERT INTO tbapontamento(etiqueta,idlinha,idproduto,diaapont,horaapont,intervalo)";
                $insert .= "VALUES(
            '" . $etiqueta . "',
            " . $idlinha . ",           
            $idproduto,            
            '" . $tempo . "',
            '" . $hora . "',
            $int
        )";
                var_dump($insert);
                $query = $conn->prepare($insert);
                $query->execute();
                $insert = "INSERT INTO tbcoleta(etiqueta,diaapont)";
                $q = $insert;
                $insert .= "VALUES(
            '" . $etiqueta . "',
            '" . $tempo . "'
        )";
                $query = $conn->prepare($insert);
                $query->execute();
            } catch (PDOException $e) {
                //header('location:../index.php');
                //echo $e->getMessage();     

            }
        }
    }
    echo "<script>
    window.close();
</script>";




?>

