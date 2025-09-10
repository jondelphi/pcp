<?php
session_start();
require('../DAO/config.php');
require('../DAO/configIgor.php');
require('../Model/ClasseAcesso.php');
require('../Model/ClasseApontamento.php');
require('../Model/ClasseODF.php');
require('../Model/ClasseLinha.php');
require('../Model/ClasseProduto.php');
require('../Model/ClasseProgramacao.php');
require('../Model/ClasseCarga.php');
$dia=new DateTime('now');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CARGAS BRASLAR - <?php echo $dia->format('d/m/Y');?> </title>


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

        table.tabelaCarga thead th:nth-child(even)

        /* CSS3 */
            {
            background: #ccc;
        }
    </style>
</head>

<body>
    <div class="container" style="max-width:max-content">
        <?php
        $classeCarga = new Carga;
        $classeCarga->mostraCargas4();

        ?>
    </div>

</body>

</html>