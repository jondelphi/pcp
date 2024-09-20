<?php
session_start();
require('../DAO/config.php');

require('../Model/ClasseAcesso.php');
require('../Model/ClasseApontamento.php');
require('../Model/ClasseODF.php');
require('../Model/ClasseLinha.php');
require('../Model/ClasseProduto.php');
require('../Model/ClasseProgramacao.php');
require('../Model/ClasseCarga.php');
$dia = new DateTime($_SESSION['pcpAPI']['diaapont']);
$hora = new DateTime('now');
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eficiência BRASLAR - <?php echo $dia->format('d/m/Y')." as ".$hora->format('h:m'); ?> </title>


    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap-icons.css">
    <style>
        i {
            padding: 2px;
            margin: 2px;
        }

       
    </style>
</head>

<body>
    <div class="container">
        <div class="container">
            <div class="container m-2 p-2">
                <?php
                $programacao = new Programacao;
                $programacao->tabelaeficienciapcp2($_SESSION['pcpAPI']['diaapont'],$_SESSION['pcpAPI']['diaapont']);
                ?>
            </div>
        </div>
    </div>

</body>

</html>