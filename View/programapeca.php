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
require('../Model/ClasseEstamparia.php');
$dia = new DateTime($_SESSION['pcpAPI']['diaapont']);

$hora = new DateTime('now');
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PROGRAMAÇÂO BRASLAR - <?php echo $dia->format('d/m/Y')." as ".$hora->format('h:m'); ?> </title>


    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap-icons.css">
    <style>
        i {
            padding: 2px;
            margin: 2px;
        }

        .textovertical {
            /* width:1px;
    word-wrap: break-word;
    font-family: monospace; 
    white-space: pre-wrap; */
            writing-mode: vertical-rl;
            text-align: center;
            padding: 2px 3px;
            margin: 2px 3px;
        }

        table.tabelaCarga {

            padding: 2px;
            width: fit-content;
        }

        table.tabelaCarga th {
            border-collapse: collapse;
            border: 1px solid #000;
            padding: 2px 3px;
        }

        table.tabelaCarga td {
            border: 1px solid #000;
            padding: 2px 3px;

        }

        table.tabelaCarga tbody tr:nth-child(even)

        /* CSS3 */
            {
            background: #ccc;
        }

        table.tabelaCarga thead {
            background-color: #ccd;
        }
        hr.break { page-break-after: always; }

        table.tabelaCarga thead th:nth-child(even)

        /* CSS3 */
            {
            background: #ccc;
        }
    </style>
</head>

<body>
    <div class="container" style="font-size: 80%;">
        <div class="container">
            <div class="container m-2 p-2">
                <h6>Braslar - Programação Peças data: <?php echo $dia->format('d/m/Y'); ?> </h3>
                <?php
                $programacao = new Pecas;
                $programacao->primeiraetapa($dia->format('Y-m-d'));
                ?>
            </div>
        </div>
    </div>

</body>

</html>