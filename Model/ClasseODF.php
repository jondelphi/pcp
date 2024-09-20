<?php
class ODF
{
    public function mostraODF($conn, $sqlODF, $filtro)
    {

        $dia = new DateTime($_SESSION['pcpAPI']['diaapont']);

        $compare = false;
        $diaatual = new DateTime('now');
        if ($diaatual->format('Y-m-d') > $dia->format('Y-m-d')) {
            $compare = true;
        }

        
        $sql = $conn->prepare($sqlODF);

        $sql->execute();
        $entregue = 0;
        $soma = 0;
        if ($sql->rowCount() > 0) {
            $i=0;
?>
            <table class="table table-dark table-striped table-bordered table-hover">
                <thead class="text-center">
                    <tr>
                        <th colspan="12">
                            DIA <?php echo $dia->format("d/m/Y") ?>
                        </th>
                    </tr>
                    <tr>
                    <th>
                            <small>id</small>
                        </th>
                        <th>
                            <small><i class="bi bi-calendar-check"></i>DIA</small>
                        </th>
                        <th>
                            <small><i class="bi bi-box-seam"></i>ODF</small>
                        </th>
                        <th>
                            <small><i class="bi bi-minecart-loaded"></i>LINHA</small>
                            <?php
                            if (isset($filtro)) {
                                if ($filtro === true) {
                            ?>
                                    <a href="Control/Limpabusca.php" class="link text-decoration-none text-white"><i class="bi bi-filter-circle-fill"></i>Limpa filtro</a>
                                <?php
                                } else {
                                ?>
                                    <form action="Control/busca.php" method="post" class="form">
                                        <div class="input-group input-group-sm">
                                            <input type="text" name="busca" class="form-control form-control-sm" required>
                                            <input type="hidden" name="tipo" value="linha">
                                            <input type="submit" value="Ok" class="btn btn-success input-group-text ">
                                        </div>
                                    </form>
                                <?php
                                }
                            } else {
                                ?>
                                <form action="Control/busca.php" method="post" class="form">
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="busca" class="form-control form-control-sm" required>
                                        <input type="hidden" name="tipo" value="linha">
                                        <input type="submit" value="Ok" class="btn btn-success input-group-text ">
                                    </div>
                                </form>
                            <?php
                            }
                            ?>


                        </th>
                        <th colspan="2">
                            <small><i class="bi bi-boxes"></i>PRODUTO</small>
                            <?php
                            if (isset($filtro)) {
                                if ($filtro === true) {
                            ?>
                                    <a href="Control/Limpabusca.php" class="link text-decoration-none text-white"><i class="bi bi-filter-circle-fill"></i>Limpa filtro</a>
                                <?php
                                } else {
                                ?>
                                    <form action="Control/busca.php" method="post" class="form">
                                        <div class="input-group input-group-sm">
                                            <input type="text" name="busca" class="form-control form-control-sm" required>
                                            <input type="hidden" name="tipo" value="produto">
                                            <input type="submit" value="Ok" class="btn btn-success input-group-text ">
                                        </div>
                                    </form>
                                <?php
                                }
                            } else {
                                ?>
                                <form action="Control/busca.php" method="post" class="form">
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="busca" class="form-control form-control-sm" required>
                                        <input type="hidden" name="tipo" value="produto">
                                        <input type="submit" value="Ok" class="btn btn-success input-group-text ">
                                    </div>
                                </form>
                            <?php
                            }
                            ?>
                        </th>
                        <th>
                            <small><i class="bi bi-gem"></i>QTD</small>
                        </th>
                        <th>
                            <small><i class="bi bi-gem"></i>BIPADA</small>
                        </th>
                        <th>
                            <small><i class="bi bi-gem"></i>PENDENTE</small>
                        </th>
                        <th colspan="3">
                            <small><i class="bi bi-gem"></i>OPERAÇÃO</small>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($l = $sql->fetch(PDO::FETCH_ASSOC)) {
                        $i++;
                    ?>
                        <tr>
                                 <td class="text-end">
                                <small><?php echo $i;?></small>
                            </td>
                            <td>
                                <small><?php 
                                $dtodf= new DateTime($l['dataentregue']);
                                echo $dtodf->format('d/m/y') ?></small>
                            </td>
                            <td>
                                <small><?php echo $l['odf']; ?> </small>
                            </td>
                            <td>
                                <small>
                                    <a href="View/resumo.php?linha=<?php echo $l['linhamontagem'] ?>" target="blank" class="link text-decoration-none text-white fw-bold">
                                        <?php echo $l['linhamontagem'] ?>
                                    </a>
                                </small>
                                <!-- <small>
                                    <a href="javascript:newPopup('View/resumo.php?linha=<?php echo $l['linhamontagem'] ?>')"  class="link text-decoration-none text-white fw-bold">
                                        <?php echo $l['linhamontagem'] ?>
                                    </a>
                                </small> -->
                            </td>
                            <td>

                                <small><?php echo $l['codigo'] ?> </small>
                            </td>
                            <td>
                                <small><?php echo $l['descri'] ?></small>
                            </td>
                            <td class="text-end">
                                <small>
                                    <a href="View/resumoetiquetas.php?codigo=<?php echo ($l['codigo']); ?>&&linha=<?php echo ($l['linhamontagem']); ?>&&dia=<?php  echo ($dtodf->format('Y-m-d'));?>" target="blank" class="link text-decoration-none text-white fw-bold">
                                        <small><?php echo number_format($l['quantidade'], 0, ',', '.') ?>
                                        </small>


                            </td>
                            <td class="text-end">
                                <?php
                                $bipado = $this->TotalBipado($l['odf']);
                                $pendente= $l['quantidade'] -$this->todosbipado($l['odf']);
                                $soma = $soma + $bipado;
                                $entregue = $l['quantidade'] + $entregue;
                                echo $this->todosbipado($l['odf']);
                                
                                ?>
                            </td>

                            <td class="text-end">
                                <?php

                                echo $pendente;
                                ?>

                            </td>
                            <td class="text-center">
                                <?php
                                $edita = "onclick=conEdita('Control/editaodf.php?id=" . $l['id'] . "','" . $l['odf'] . "')";
                                $exclui = "onclick=conExclui('Control/excluiodf.php?id=" . $l['id'] . "','" . $l['odf'] . "')";
                                $detalhes = "onclick=conDetalhes('Control/detalhesodf.php?id=" . $l['id'] . "','" . $l['odf'] . "')";
                                ?>
                                <button <?php echo $edita; ?> class="btn btn-sm btn-warning"><small>Edita</small></button>
                            </td>
                            <td>
                                <button <?php echo $exclui; ?> class="btn btn-sm btn-danger"><small>Exclui</small></button>
                            </td>
                            <td>
                                <?php
                                if ($compare === true) {
                                ?>

                                    <a href="Control/devolve.php?id=<?php echo $l['id']; ?>" class="btn btn-sm btn-info">Devolve</a>
                                <?php }
                                ?>

                            </td>


                        </tr>

                    <?php
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6" class="text-end">Soma</th>
                        <th class="text-end">
                            <?php
                            echo number_format($entregue, 0, ',', '.');
                            ?>
                        </th>
                        <th class="text-end">
                            <?php
                            echo number_format($soma, 0, ',', '.');
                            ?>
                        </th>
                        <th colspan="4"></th>
                    </tr>
                </tfoot>
            </table>
<?php
        }
    }

    //proxima funcao
    public function TotalBipado($odf)
    {
        $con = ConexaoIgor::getConnectionIgor();
        try {
            $sql = "SELECT count(*) as 'total' FROM etiquetas
        WHERE date(data_apontamento) = '" . $_SESSION['pcpAPI']['diaapont'] . "' AND odf='$odf'";
            $sql = $con->prepare($sql);
            $sql->execute();
            $bip = 0;
            while ($l = $sql->fetch(PDO::FETCH_ASSOC)) {
                $bip = $l['total'];
            }

            return $bip;
        } catch (PDOException $e) {
        }
    }

    public function TodosBipado($odf)
    {
        $con = ConexaoIgor::getConnectionIgor();
        try {
            $sql = "SELECT count(*) as 'total' FROM etiquetas
        WHERE odf='$odf'";
            $sql = $con->prepare($sql);
            $sql->execute();
            $bip = 0;
            while ($l = $sql->fetch(PDO::FETCH_ASSOC)) {
                $bip = $l['total'];
            }

            return $bip;
        } catch (PDOException $e) {
        }
    }

    public function TotalBipadoDia($conn, $e1, $e2, $dia)
    {
        try {
            $sql = "SELECT count(id) as 'total' FROM tbapontamento
        WHERE diaapont = '" . $dia . "' AND etiqueta >= '$e1' AND  etiqueta <='$e2'";
            $sql = $conn->prepare($sql);
            $sql->execute();
            $bip = 0;
            while ($l = $sql->fetch(PDO::FETCH_ASSOC)) {
                $bip = $l['total'];
            }

            return $bip;
        } catch (PDOException $e) {
        }
    }


    public function proxODF($conn)
    {
        $bip = 1;
        try {
            $sql = "SELECT MAX(odf) as 'max' FROM tbpcp WHERE odf<5000";
            $sql = $conn->prepare($sql);
            $sql->execute();

            while ($l = $sql->fetch(PDO::FETCH_ASSOC)) {
                $bip = $l['max'];
            }
            $bip = $bip + 1;

            return $bip;
        } catch (PDOException $e) {
            return $bip;
        }
    }
    public function MaiorEtiquetaCadastrada($odf)
    {
        try {
            $conn = ConexaoKorp::getConnectionKorp();

            $sql = "SELECT max(etiqueta) as 'maior' FROM ETIQUETA_SERIE_PRODUCAO WHERE odf=$odf";
            $sql1 = $sql;

            $sql1 = $conn->prepare($sql1);
            $sql1->execute();
            $nr = $sql1->rowCount();

            if ($nr <> 0) {

                while ($k = $sql1->fetch(PDO::FETCH_ASSOC)) {
                    $maior = $k['maior'];

                    return $maior;
                }
            }
        } catch (PDOException $e) {
            echo $e->getMessage() . "<br>";
            return 0;
        }
    }

    public function MenorEtiquetaCadastrada($odf)
    {
        try {
            $conn = ConexaoKorp::getConnectionKorp();

            $sql = "SELECT min(etiqueta) as 'menor' FROM ETIQUETA_SERIE_PRODUCAO WHERE odf=$odf";
            $sql1 = $sql;

            $sql1 = $conn->prepare($sql1);
            $sql1->execute();
            $nr = $sql1->rowCount();

            if ($nr <> 0) {

                while ($k = $sql1->fetch(PDO::FETCH_ASSOC)) {
                    $maior = $k['menor'];

                    return $maior;
                }
            }
        } catch (PDOException $e) {
            //  echo $e->getMessage()."<br>";
            return 0;
        }
    }

    public function OdfDaEtiqueta($etiqueta)
    {
        try {
            $conn=ConexaoIgor::getConnectionIgor();

            $etiqueta=trim($etiqueta);
            $sql = "SELECT ODF FROM etiqueta WHERE etiqueta='$etiqueta'";
            $sql1 = $sql;
          
            $sql1 = $conn->prepare($sql1);
            $sql1->execute();
            $nr = $sql1->rowCount();

            if ($nr <> 0) {
                while ($k = $sql1->fetch(PDO::FETCH_ASSOC)) {
                    $odf = $k['ODF'];
                 

                    return $odf;
                }
            }
        } catch (PDOException $e) {
            //  echo $e->getMessage()."<br>";
            return 0;
        }
    }

    public function mostraapontada($con,$sql)
    {
        $sql=$con->prepare($sql);
        $sql->execute();
        
        if ($sql->rowCount()>0) {
            ?>
             <div class="container m-1 p-1">
                <table class="table table-dark table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>
                                Código
                            </th>
                            <th>
                                Produto
                            </th>
                            <th>
                                Linha
                            </th>
                            <th>
                                Dia
                            </th>
                            <th>
                                Hora
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $itens=$sql->fetchAll(PDO::FETCH_ASSOC);
                  
                        foreach($itens as $k){
                            ?>
                            <tr>
                                <td><?php echo $k['codigo']?></td>
                                <td><?php echo $k['descri']?></td>
                                <td><?php echo $k['linhamontagem']?></td>
                                <td class="text-end"><?php
                                $dia=new DateTime($k['dia']);
                                echo $dia->format('d/m/Y')?></td> 
                                <td class="text-end"><?php echo $k['hora']?></td>
                            </tr>
                            
                            <?php

                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <?php
        }else{
            ?>
            <div class="container bg-dark text-bg-dark m-1 p-1">
                <h6>Não está no banco de dados</h6>
            </div>
            <?php
        }
        
    }
}


?>