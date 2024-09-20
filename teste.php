
<?php
session_start();
require('DAO/config.php');

require('Model/ClasseAcesso.php');
require('Model/ClasseApontamento.php');
require('Model/ClasseAlmoco.php');
require('Model/ClasseODF.php');
require('Model/ClasseLinha.php');
require('Model/ClasseProduto.php');
require('Model/ClasseProgramacao.php');
require('Model/ClasseEstamparia.php');
$teste = new Pecas;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="content/icone.png">
    <title><?php echo ($_SESSION['pcpAPI']['titulo']) ?></title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap-icons.css">
    <style>
    i {
            padding: 2px;
            margin: 2px;
        }
    .textovertical{
    writing-mode: vertical-rl;
    text-align: center;
    padding: 2px 3px;
    margin: 2px 3px;
}
table.tabelaCarga{
  
    padding: 2px;
    width: fit-content;
}
table.tabelaCarga th {
    border-collapse: collapse;
    border: 1px solid #000;
    padding: 2px 3px;
}
table.tabelaCarga td{
    border: 1px solid #000;
    padding: 2px 3px;

}

.break { page-break-after:left; }




    @media print{
        body * {
            display: none;
        }
        #exporta{
            visibility: visible;
        }
  
    }

    </style>
   
   
</head>
<body>
    <?php 
       


$dia=date("Y-m-d");
    
    ?>    
   <div class="container">
   <div class="container bg-dark m-2 p-2" style="width:fit-content">
     <form action="etiquetas.php" method="get">
     <div class="input-group m-1 p-1">
            <span class="input-group-text">Data</span>
            <input type="date" name="dia" id="" 
            value="<?php echo $dia ;?>"
            class="form-control" required>
        </div>
        <div class="input-group m-1 p-1">
            <span class="input-group-text">Código do Produto</span>
            <input type="text" name="prefixo" id="" class="form-control" required>
            <input type="submit" value="Procura" class="btn btn-success">
        </div>
     </form>
</div>
   </div>

    <script src="bootstrap/js/bootstrap.js"></script>
    <script src="bootstrap/js/bootstrap.bundle.js"></script>
    <script src="bootstrap/js/popper.min.js"></script>


    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})
    </script>
   
</body>
</html>