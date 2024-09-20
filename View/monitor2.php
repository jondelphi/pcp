<?php
$conn = ConexaoMysql::getConnectionMysql();


function monitor($conn)
{
    $sel = "SELECT COUNT(etiqueta) as 'total',intervalo ";
    $sel .= "FROM tbapontamento WHERE diaapont=";
    $sel .= "'" . $_SESSION['pcpAPI']['diaapont'] . "' ";
    $sel .= " GROUP BY intervalo, linha ";
    $sel .= " ORDER BY intervalo ASC";

    $sel = $conn->prepare($sel);
    $sel->execute();
    $numreg = $sel->rowCount();
    

    if ($numreg > 0) {
?>
        <table class="table table-dark table-striped table-bordered" style="max-width: 500px;">
            <thead class="text-center">
            <tr>
                    <th colspan="2">Produção por Hora</th>
                </tr>
                <tr>
                    <th>Intervalo</th>
                    <th>Quantidade</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($l = $sel->fetch(PDO::FETCH_ASSOC)) {
                ?>
                    <tr>
                        <?php
                        switch ($l['intervalo']) {
                            case 0:
                                $entre = "06:00-07:00";
                                break;
                            case 1:
                                $entre = "07:00-08:00";
                                break;
                            case 2:
                                $entre = "08:00-09:00";
                                break;
                            case 3:
                                $entre = "09:00-10:00";
                                break;
                            case 4:
                                $entre = "10:00-11:00";
                                break;
                            case 5:
                                $entre = "11:00-12:00";
                                break;
                            case 6:
                                $entre = "12:00-13:00";
                                break;
                            case 7:
                                $entre = "13:00-14:00";
                                break;
                            case 8:
                                $entre = "14:00-15:00";
                                break;
                            case 9:
                                $entre = "15:00-16:00";
                                break;
                            case 10:
                                $entre = "16:00-17:00";
                                break;
                            case 11:
                                $entre = "17:00-18:00";
                                break;
                            case 12:
                                $entre = "Depois das 18:00";
                                break;
                            default:
                                $entre = "Fora do expediente";
                                break;
                        }


                        ?>
                        <td><?php echo $entre; ?></td>
                        <td class="text-end"><?php echo $l['total']; ?></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
<?php
    }
}
monitor($conn);
?>

