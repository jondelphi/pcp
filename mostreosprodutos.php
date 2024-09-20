
<?php
session_start();
require('DAO/config.php');
$con = ConexaoMysql::getConnectionMysql();

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


    </style>
   
   
</head>
<body>
    <?php 
       


$dia=date("Y-m-d");
 $consulta = "SELECT
 COUNT(etiqueta) AS total,
 tbproduto.codigo,
 tbproduto.descricao,
 tbapontamento.diaapont
FROM
 tbapontamento
INNER JOIN tbproduto ON tbproduto.id = tbapontamento.idproduto
WHERE
 tbproduto.codigo = '{$_POST['codigo']}' AND tbapontamento.diaapont BETWEEN '{$_POST['dia1']}' AND '{$_POST['dia2']}'
GROUP BY
 tbapontamento.diaapont
ORDER BY tbapontamento.diaapont ASC
 ";
$sql=$con->prepare($consulta);
$sql->execute();
$dados = $sql->fetchAll(PDO::FETCH_ASSOC);

    
    ?>    
   <div class="container">
        <table class="table table-dark table-striped table-bordered">
            <thead>
                <tr>
                    <th>Dia</th>
                    <th>Código</th>
                    <th>Descrição</th>
                    <th>Quantidade</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total = 0;
                foreach ($dados as $k) {
                    $diaf=new DateTime($k['diaapont']);
                    $total+=$k['total']
                    ?>
                    <tr>
                        <td><?php echo $diaf->format('d/m/Y')?></td>
                        <td><?php echo $k['codigo']?></td>
                        <td><?php echo $k['descricao']?></td>
                        <td class="text-end"><?php echo $k['total']?></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td>Total</td>
                    <td class="text-end"  colspan="3"><?php echo number_format($total, 0,',','.') ?></td>
                </tr>
            </tfoot>
        </table>

   

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