<?php
session_start();
set_time_limit(0);
require('DAO/config.php');

require('DAO/configIgor.php');
require('Model/ClasseAcesso.php');
require('Model/ClasseApontamento.php');
require('Model/ClasseODF.php');
require('Model/ClasseLinha.php');
require('Model/ClasseProduto.php');


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
    <script>
        function conExclui(link) {
            var str = "Deseja excluir a etiqueta?";
            var con = confirm(str);
            if (con == true) {
                window.location.href = link;
            }
        }

        function conExcluiCOLETA(link) {
            var str = "Deseja excluir a etiqueta da Coleta?";
            var con = confirm(str);
            if (con == true) {
                window.location.href = link;
            }
        }
    </script>


</head>

<body>
    <?php

    function executeQuery($connection, $query, $params = [])
    {
        $stmt = $connection->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getResult($result)
    {
        if ($result === false || $result === null || is_bool($result) || is_string($result)) {
            return 0;
        }
        return $result;
    }

    function trazbip($etiqueta)
    {
        try {
            $connection = ConexaoIgor::getConnectionIgor();
            $query = "SELECT bip FROM etiquetas WHERE serie = ?";
            $result = executeQuery($connection, $query, [$etiqueta]);
            return isset($result[0]['bip']) ? $result[0]['bip'] : null;
        } catch (Exception $e) {
            return 0;
        }
    }

    function trazreq($etiqueta)
    {
        $connection = ConexaoMysql::getConnectionMysql();
        $query = "SELECT numrequest FROM tbcoleta WHERE etiqueta = ?";
        $result = executeQuery($connection, $query, [$etiqueta]);
        return getResult($result[0]['numrequest']);
    }

    function trazODF($etiqueta)
    {
        try {
            $connection = ConexaoIgor::getConnectionIgor();
            $query = "SELECT odf FROM etiquetas WHERE serie = ?";
            $result = executeQuery($connection, $query, [$etiqueta]);
           
            if (isset($result[0])) {
                return getResult($result[0]['odf']);
            }else{
                return "";  
            }
        } catch (Exception $e) {
            return "";
        }
    }

    function trazultimoreq($etiqueta)
    {
        $connection = ConexaoMysql::getConnectionMysql();
        $query = "SELECT ultimorequest FROM tbcoleta WHERE etiqueta = ?";
        $result = executeQuery($connection, $query, [$etiqueta]);
        return getResult($result[0]['ultimorequest']);
    }




    try {
        if (isset($_SESSION['pcpAPI']['diaapont'])) {
            $dia = $_SESSION['pcpAPI']['diaapont'];
        } else {
            $n = new DateTime('now');
            $dia = $n->format('Y-m-d');
        }
    } catch (Exception $e) {
        $n = new DateTime('now');
        $dia = $n->format('Y-m-d');
    }
    $sql = "SELECT tbcoleta.*, tbapontamento.id AS idapont, tblinha.linha AS linha 
    FROM tbcoleta 
    INNER JOIN tbapontamento ON tbapontamento.etiqueta = tbcoleta.etiqueta
    INNER JOIN tbproduto ON tbapontamento.idproduto = tbproduto.id
    INNER JOIN tblinha ON tbapontamento.idlinha = tblinha.id
    WHERE tbcoleta.diaapont = :dia
    ORDER BY tbproduto.codigo DESC";

    $conn = ConexaoMysql::getConnectionMysql();
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':dia', $dia, PDO::PARAM_STR);
    $stmt->execute();
    $linhas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $linha = count($linhas);

    if ($linha > 0) {
        $id = 1;

    ?>
        <h2 class="text-bg-info bg-info text-center m-2 p-2" style="max-width: 500px;"><?php echo $linha . " Registros"; ?></h2>
        <div class="container">
            <a href="apagaapontada.php" class="btn btn-danger">APAGA APONTADA</a><br>
            <a href="todosrequests.php" class="btn btn-warning m-2 p-2">Todos os requests</a>

        </div>
        <table class="table table-light table-bordered table-striped m-2 p-2" style="width: fit-content;">
            <thead>
                <tr>
                    <th>
                        id
                    </th>
                    <th>
                        etiqueta
                    </th>
                    <th>
                        linha
                    </th>
                    <th>
                        bip
                    </th>
                    <th>
                        ODF
                    </th>
                    <th>
                        numreq
                    </th>
                    <th>
                        ultimoreq
                    </th>
                    <th>
                        apaga
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($linhas as $k) : ?>
                    <tr>
                        <td><?php echo $id; ?></td>
                        <td><?php echo $k['etiqueta']; ?></td>
                        <td><?php echo $k['linha']; ?></td>
                        <td>bip-<?php
                                try {
                                    echo trazbip($k['etiqueta']);
                                } catch (Exception $e) {
                                    echo 0;
                                }
                                ?></td>
                        <td><?php
                            try {
                                echo  trazodf($k['etiqueta']);
                            } catch (Exception $e) {
                                echo "";
                            }
                            ?></td>
                        <td>req-<?php echo trazreq($k['etiqueta']); ?></td>
                        <td><?php echo trazultimoreq($k['etiqueta']); ?></td>
                        <td>
                            <?php
                            $link = "conExclui('control/apagacoleta.php?coleta={$k['id']}&amp;aponta={$k['idapont']}')";
                            $link2 = "conExcluiCOLETA('control/apagasocoleta.php?coleta={$k['id']}&amp;aponta={$k['idapont']}')";
                            ?>
                            <button onclick="<?php echo $link; ?>" class="btn btn-danger btn-sm m-2 p-2" title="diminui o saldo e apaga da coleta">
                                Apaga SALDO
                            </button>
                            <button onclick="<?php echo $link2; ?>" class="btn btn-warning btn-sm m-2 p-2" title="não diminui o saldo da linha">
                                Apaga COLETA
                            </button>
                            <a href="request.php?etiqueta=<?php echo $k['etiqueta']; ?>" target="_blank" class="btn btn-secondary">Request</a>
                        </td>
                    </tr>
                <?php
                    $id++;
                endforeach;
                ?>

            </tbody>
        </table>
    <?php
    } else {
        echo "Tabela coleta zerada em " . $fdia->format('d/m/Y');
    }

    ?>
    <div class="container p-2 m-2">

    </div>
    <script src="bootstrap/js/bootstrap.js"></script>
    <script src="bootstrap/js/bootstrap.bundle.js"></script>
    <footer>
        <?php
        include("template/footer.php");
        if (isset($_SESSION['coletaapagada'])) {
            $script = "<script>alert('Foi apagada :" . $_SESSION['coletaapagada'] . " etiquetas')</script>";
            echo $script;
            unset($_SESSION['coletaapagada']);
        }

        ?>
    </footer>

</body>

</html>