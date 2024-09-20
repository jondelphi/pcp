<?php
$conn = ConexaoMysql::getConnectionMysql();
require("../Model/classeAlmoco.php");

function monitor($conn)
{
    $classelmoco=new Almoco;
    $almoco=$classelmoco->saidaentrada();

    $sel = "SELECT COUNT(etiqueta) as 'total',intervalo ";
    $sel .= "FROM tbapontamento INNER JOIN tblinha ON tblinha.id= tbapontamento.idlinha WHERE diaapont=";
    $sel .= "'" . $_SESSION['pcpAPI']['diaapont'] . "' ";
    $sel .= "AND tblinha.linha=";
    $sel .= "'" . $_GET['linha'] . "' ";
    
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
                    <th colspan="2"><i class="bi bi-clock-history m-1 p-1"></i>Produção por Hora</th>
                </tr>
                <tr>
                    <th><i class="bi bi-clock m-1 p-1"></i>Intervalo</th>
                    <th><i class="bi bi-gem m-1 p-1"></i>Quantidade</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $horario=false;
                while ($l = $sel->fetch(PDO::FETCH_ASSOC)) {
                 
                ?>
                    <tr>
                        <?php
                       
                       
                        if ($almoco==false) {
                           
                        switch ($l['intervalo']) {
                            case 0:
                                $entre = "06:00-07:40";
                                break;
                            case 1:
                                $entre = "07:40-08:40";
                                break;
                            case 2:
                                $entre = "08:41-09:40";
                                break;
                            case 3:
                                $entre = "09:41-10:40";
                                break;
                            case 4:
                                $entre = "10:41-11:40";
                                break;
                            case 5:
                                $entre = "11:41-12:40";
                                break;
                            case 6:
                                $entre = "12:41-13:40";
                                break;
                            case 7:
                                $entre = "13:41-14:40";
                                break;
                            case 8:
                                $entre = "14:41-15:40";
                                break;
                            case 9:
                                $entre = "15:41-16:40";
                                break;
                            case 10:
                                $entre = "16:41-17:40";
                                break;
                            case 11:
                                $entre = "17:41-18:40";
                                break;
                            case 12:
                                $entre = "Depois das 18:40";
                                break;
                            default:
                                $entre = "Fora do expediente";
                                break;
                        }
                    }else{
                    
                        switch ($l['intervalo']) {
                            case 0:
                                $inicio="06:00";
                                $fim="07:40";
                              if ($almoco['intervalosaida']==0) {
                                $fim=$almoco['saida'];
                              }
                              if ($almoco['intervaloentrada']==0) {
                                $inicio=$almoco['entrada'];
                              }
                              $entre=$inicio."-".$fim;                                                
                             break;
                            case 1:
                            
                                $inicio="07:40";
                                $fim="08:40";
                              if ($almoco['intervalosaida']==1) {
                                $fim=$almoco['saida'];
                              }
                              if ($almoco['intervaloentrada']==1) {
                                $inicio=$almoco['entrada'];
                              }
                              if ($almoco['intervaloentrada']==1&&$almoco['intervalosaida']==1) {
                                
                                 $inicio=$almoco['entrada'];;
                                $fim="08:40";
                              }
                              $entre=$inicio."-".$fim;   
                                break;
                            case 2:
                                $inicio="08:41";
                                $fim="09:40";
                              if ($almoco['intervalosaida']==2) {
                                $fim=$almoco['saida'];
                              }
                              if ($almoco['intervaloentrada']==2) {
                                $inicio=$almoco['entrada'];
                              }
                              if ($almoco['intervaloentrada']==2&&$almoco['intervalosaida']==2) {
                                 $inicio=$almoco['entrada'];;
                                $fim="09:40";
                              }
                              
                              $entre=$inicio."-".$fim;   
                                break;
                            case 3:
                               
                                $entre = "09:41-10:40";
                                $inicio="09:41";
                                $fim="10:40";
                              if ($almoco['intervalosaida']==3) {
                                $fim=$almoco['saida'];
                              }
                              if ($almoco['intervaloentrada']==3) {
                                $inicio=$almoco['entrada'];
                              }
                              if ($almoco['intervaloentrada']==3&&$almoco['intervalosaida']==3) {
                                $inicio='10:41';
                                $fim=$almoco['saida'];
                                $entre=$inicio."-".$fim;   
                                ?>
                                   <td><?php echo $entre; ?></td>
                                 <td class="text-end"><?php echo quantosnesseintervalo($inicio,$fim); ?></td>
                              </tr><tr>
                                <?php
                                $inicio=$almoco['entrada'];;
                                $fim="11:40";
                                $entre=$inicio."-".$fim;   
                                ?>
                                   <td><?php echo $entre; ?></td>
                                 <td class="text-end"><?php echo quantosnesseintervalo($inicio,$fim); ?></td>
                              </tr>
                                <?php
                                $horario=true;
                              }
                              $entre=$inicio."-".$fim;   
                                break;
                            case 4:
                                $entre = "10:41-11:40";
                                $inicio="10:41";
                                $fim="11:40";
                              if ($almoco['intervalosaida']==4) {
                                $fim=$almoco['saida'];
                              }
                              if ($almoco['intervaloentrada']==4) {
                                $inicio=$almoco['entrada'];
                              }
                              if ($almoco['intervaloentrada']==4&&$almoco['intervalosaida']==4) {
                                $inicio='10:41';
                                $fim=$almoco['saida'];
                                $entre=$inicio."-".$fim;   
                                ?>
                                   <td><?php echo $entre; ?></td>
                                 <td class="text-end"><?php echo quantosnesseintervalo($inicio,$fim); ?></td>
                              </tr><tr>
                                <?php
                                $inicio=$almoco['entrada'];;
                                $fim="11:40";
                                $entre=$inicio."-".$fim;   
                                ?>
                                   <td><?php echo $entre; ?></td>
                                 <td class="text-end"><?php echo quantosnesseintervalo($inicio,$fim); ?></td>
                              </tr>
                                <?php
                                $horario=true;
                              }
                              $entre=$inicio."-".$fim;   
                                break;
                            case 5:
                                $entre = "11:41-12:40";
                                $inicio="11:41";
                                $fim="12:40";
                              if ($almoco['intervalosaida']==5) {
                                $fim=$almoco['saida'];
                              }
                              if ($almoco['intervaloentrada']==5) {
                                $inicio=$almoco['entrada'];
                              }
                              if ($almoco['intervaloentrada']==5&&$almoco['intervalosaida']==5) {
                                $inicio='10:41';
                                $fim=$almoco['saida'];
                                $entre=$inicio."-".$fim;   
                                ?>
                                   <td><?php echo $entre; ?></td>
                                 <td class="text-end"><?php echo quantosnesseintervalo($inicio,$fim); ?></td>
                              </tr><tr>
                                <?php
                                $inicio=$almoco['entrada'];;
                                $fim="11:40";
                                $entre=$inicio."-".$fim;   
                                ?>
                                   <td><?php echo $entre; ?></td>
                                 <td class="text-end"><?php echo quantosnesseintervalo($inicio,$fim); ?></td>
                              </tr>
                                <?php
                                $horario=true;
                              }
                              $entre=$inicio."-".$fim;   
                                break;
                            case 6:
                                $entre = "12:41-13:40";
                              
                                $inicio="12:41";
                                $fim="13:40";
                              if ($almoco['intervalosaida']==6) {
                                $fim=$almoco['saida'];
                              }
                              if ($almoco['intervaloentrada']==6) {
                                $inicio=$almoco['entrada'];
                              }
                              if ($almoco['intervaloentrada']==6&&$almoco['intervalosaida']==6) {
                                $inicio='10:41';
                                $fim=$almoco['saida'];
                                $entre=$inicio."-".$fim;   
                                ?>
                                   <td><?php echo $entre; ?></td>
                                 <td class="text-end"><?php echo quantosnesseintervalo($inicio,$fim); ?></td>
                              </tr><tr>
                                <?php
                                $inicio=$almoco['entrada'];;
                                $fim="11:40";
                                $entre=$inicio."-".$fim;   
                                ?>
                                   <td><?php echo $entre; ?></td>
                                 <td class="text-end"><?php echo quantosnesseintervalo($inicio,$fim); ?></td>
                              </tr>
                                <?php
                                $horario=true;
                              }
                              $entre=$inicio."-".$fim;   
                                break;
                            case 7:
                                $entre = "13:41-14:40";
                                $inicio="13:41";
                                $fim="14:40";
                              if ($almoco['intervalosaida']==7) {
                                $fim=$almoco['saida'];
                              }
                              if ($almoco['intervaloentrada']==7) {
                                $inicio=$almoco['entrada'];
                              }
                              if ($almoco['intervaloentrada']==7&&$almoco['intervalosaida']==7) {
                                $inicio='10:41';
                                $fim=$almoco['saida'];
                                $entre=$inicio."-".$fim;   
                                ?>
                                   <td><?php echo $entre; ?></td>
                                 <td class="text-end"><?php echo quantosnesseintervalo($inicio,$fim); ?></td>
                              </tr><tr>
                                <?php
                                $inicio=$almoco['entrada'];;
                                $fim="11:40";
                                $entre=$inicio."-".$fim;   
                                ?>
                                   <td><?php echo $entre; ?></td>
                                 <td class="text-end"><?php echo quantosnesseintervalo($inicio,$fim); ?></td>
                              </tr>
                                <?php
                                $horario=true;
                              }
                              $entre=$inicio."-".$fim;   
                                break;
                            case 8:
                                $entre = "14:41-15:40";
                                $inicio="14:41";
                                $fim="15:40";
                              if ($almoco['intervalosaida']==8) {
                                $fim=$almoco['saida'];
                              }
                              if ($almoco['intervaloentrada']==8) {
                                $inicio=$almoco['entrada'];
                              }
                              if ($almoco['intervaloentrada']==8&&$almoco['intervalosaida']==8) {
                                 $inicio=$almoco['entrada'];;
                                $fim="10:40";
                              }
                              $entre=$inicio."-".$fim;   
                                break;
                            case 9:
                                $entre = "15:41-16:40";
                                break;
                            case 10:
                                $entre = "16:41-17:40";
                                break;
                            case 11:
                                $entre = "17:41-18:40";
                                break;
                            case 12:
                                $entre = "Depois das 18:40";
                                break;
                            default:
                                $entre = "Fora do expediente";
                                break;
                        }

                    }

if ($horario==false) {


                        ?>
                        <td><?php echo $entre; ?></td>
                        <td class="text-end"><?php echo $l['total']; ?></td>
                    </tr>
                <?php
                }
                }
                ?>
            </tbody>
        </table>
<?php
    }
}
monitor($conn);
function quantosnesseintervalo($inicio,$fim){
    $conn=ConexaoMysql::getConnectionMysql();
    $sel = "SELECT COUNT(etiqueta) as 'total' ";
    $sel .= "FROM tbapontamento INNER JOIN tblinha ON tblinha.id = tbapontamento.idlinha WHERE diaapont=";
    $sel .= "'" . $_SESSION['pcpAPI']['diaapont'] . "' ";
    $sel .= "AND tblinha.linha=";
    $sel .= "'" . $_GET['linha'] . "' ";
    $sel .="AND horaapont BETWEEN '$inicio' AND '$fim'";
    $sel = $conn->prepare($sel);
    $sel->execute();
    while ($k=$sel->fetch(PDO::FETCH_ASSOC)) {
        return $k['total'];
    }
}
?>

