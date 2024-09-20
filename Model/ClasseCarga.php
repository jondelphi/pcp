<?php
class Carga
{
    public function cadastraCarga($carga, $bompara, $idproduto, $quantidade)
    {
        $conn = ConexaoMysql::getConnectionMysql();
        $ins = "INSERT INTO tbcarga
        (
        carga,
        bompara,
        idproduto,
        quantidade
        )VALUES(
        '$carga',
        '$bompara',
        $idproduto,
        $quantidade
        )";
        try {
            $ins = $conn->prepare($ins);
            $ins->execute();
        } catch (PDOException $e) {
            //
        }
    }
    public function updateCarga($id, $carga, $bompara, $idproduto, $quantidade)
    {
        $conn = ConexaoMysql::getConnectionMysql();
        $up = "INSERT INTO tbcarga
        SET carga='$carga',
        bompara='$bompara',
        idproduto=$idproduto,
        quantidade=$quantidade
        WHERE id=$id;
        ";
        try {
            $up = $conn->prepare($up);
            $up->execute();
        } catch (PDOException $e) {
            //
        }
    }

    public function deleteCarga($id)
    {
        $conn = ConexaoMysql::getConnectionMysql();
        $up = "DELETE FROM tbcarga
        WHERE id=$id;
        ";
        try {
            $up = $conn->prepare($up);
            $up->execute();
        } catch (PDOException $e) {
            //
        }
    }
    public function idProduto($codigo)
    {
        $conn = ConexaoMysql::getConnectionMysql();
        $sql = "SELECT * FROM tbproduto WHERE codigo='" . $codigo . "'";
        $sel = $conn->prepare($sql);
        $sel->execute();
        $nr = $sel->rowCount();
        if ($nr > 0) {
            while ($l = $sel->fetch(PDO::FETCH_ASSOC)) {
                $result = $l['id'];
            }
        } else {
            $result = 0;
        }
        return ($result);
    }

    public function qtdEstoque($produto)
    {
        $total = 0;
        try {
            $conn = ConexaoIgor::getConnectionIgor();
            $sql = "SELECT
   SUM(quantidade) AS 'total'   
FROM produtos
WHERE CODIGO='$produto'";
            //echo $sql;
            $sql = $conn->prepare($sql);
            $sql->execute();
            while ($k = $sql->fetch(PDO::FETCH_ASSOC)) {
                $total = $k['total'];
            }
        } catch (PDOException $e) {
            //

        }
        return $total;
    }
    public function TemCarga($produto, $carga)
    {
    }


    public function qtdPedido($id)
    {
        $total = 0;
        try {
            $conn = ConexaoMysql::getConnectionMysql();
            $sql = "SELECT 
            tbcarga.quantidade as 'quantidade'
            from tbcarga
            WHERE tbcarga.id=$id";

            $sql = $conn->prepare($sql);
            $sql->execute();
            while ($k = $sql->fetch(PDO::FETCH_ASSOC)) {
                $total = $k['quantidade'];
            }
        } catch (PDOException $e) {
            //

        }
        return $total;
    }

    public function qualproduto($id)
    {
        $produto = 0;
        try {
            $conn = ConexaoMysql::getConnectionMysql();
            $sql = "SELECT 
            codigo 
            from tbproduto
            WHERE id=$id";

            $sql = $conn->prepare($sql);
            $sql->execute();
            while ($k = $sql->fetch(PDO::FETCH_ASSOC)) {
                $produto = $k['codigo'];

                // echo $produto."<br>";
            }
        } catch (PDOException $e) {
            //

        }

        return $produto;
    }
    public function qualidproduto($cod)
    {
        $produto = 0;
        try {
            $conn = ConexaoMysql::getConnectionMysql();
            $sql = "SELECT 
            id 
            from tbproduto
            WHERE codigo=$cod";

            $sql = $conn->prepare($sql);
            $sql->execute();
            while ($k = $sql->fetch(PDO::FETCH_ASSOC)) {
                $produto = $k['codigo'];

                // echo $produto."<br>";
            }
        } catch (PDOException $e) {
            //

        }

        return $produto;
    }

    public function mostraCargas()
    {
        $dia = new DateTime($_SESSION['pcpAPI']['diaapont']);
        $fdia = $dia->format('Y-m-d');
        try {
            $conMysql = ConexaoMysql::getConnectionMysql();
            $ConKorp = ConexaoKorp::getConnectionKorp();
            $select = "SELECT 
            tbcarga.*,
            tbproduto.codigo as 'codigo',
            tbproduto.descricao as 'descricao'
            from tbcarga
            INNER JOIN tbproduto ON tbproduto.id= tbcarga.idproduto
            WHERE bompara>='$fdia' 
            ORDER BY tbcarga.bompara ASC";

            $q = $conMysql->prepare($select);
            $q->execute();
            if ($q->rowCount() <> 0) {

                $clcombina = array();
                $i = 0;
?>
                <table class="tabelaCarga">
                    <thead class="text-center">
                        <tr class="small">
                            <th rowspan="3" style="border: none;background-color:#fff   "></th>
                            <th rowspan="3" style="border: none;background-color:#fff   "></th>
                            <?php
                            $sel = "SELECT 
                         tbcarga.id,
                         tbcarga.bompara,
                         tbcarga.idproduto,
                         tbproduto.codigo,
                         tbcarga.quantidade                        
                         from tbcarga  
                         INNER JOIN tbproduto ON tbcarga.idproduto=tbproduto.id                      
                         WHERE bompara>='$fdia' ORDER BY  tbcarga.bompara ASC, tbcarga.carga ASC";
                            $j = $conMysql->prepare($sel);
                            $j->execute();
                            while ($m = $j->fetch(PDO::FETCH_ASSOC)) {
                                $bompara = new DateTime($m['bompara']);
                                $clcombina['dia'][] = $m['bompara'];
                                $clcombina['id'][] = $m['id'];
                                $clcombina['idproduto'][] = $m['idproduto'];
                                $clcombina['quantidade'][] = $m['quantidade'];
                            ?>
                                <th colspan="2">
                                    <div class="input-group input-group-sm">
                                        <?php
                                        $edita = "Control/editacarga.php?id=" . $m['id'];
                                        $apaga = "Control/apagacarga.php?id=" . $m['id'];
                                        ?>
                                        <a href="<?php echo $edita ?>" class=" btn btn-sm btn-warning input-group"><i class="bi bi-pencil"></i></a>
                                        <a href="<?php echo $apaga ?>" class=" btn btn-sm btn-danger input-group"><i class="bi bi-trash"></i></a>
                                    </div>
                                    <?php

                                    echo $bompara->format('d/m/y'); ?>



                                </th>
                            <?php
                            } //fim do while bom para
                            ?>
                            <th rowspan="2" style="border: none;background-color:#fff   "></th>
                        </tr>

                        <tr>
                            <?php
                            $sel = "SELECT 
                         tbcarga.carga                        
                         from tbcarga                        
                         WHERE bompara>='$fdia' ORDER BY  tbcarga.bompara ASC, tbcarga.carga ASC";
                            $j = $conMysql->prepare($sel);
                            $j->execute();
                            while ($m = $j->fetch(PDO::FETCH_ASSOC)) {
                                $clcombina['carga'][] = $m['carga'];
                            ?>
                                <th colspan="2" class="textovertical"><?php echo $m['carga']; ?></th>
                            <?php
                            } //fim do while clcombina

                            ?>
                        </tr>
                        <tr>

                            <?php
                            $count = count($clcombina['dia']);
                            for ($i = 0; $i < $count; $i++) {
                            ?>
                                <td class="text-center fw-bold">P</td>
                                <td class="text-center fw-bold">E</td>
                            <?php
                            }
                            ?>
                            <th>Fabricar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        //selecionando linha do produto
                        $selprodutos = "SELECT DISTINCT
                   (tbproduto.codigo) AS 'codigo',
                   tbproduto.descricao AS 'descricao'
               FROM
                   tbcarga
               INNER JOIN tbproduto ON tbproduto.id = tbcarga.idproduto
               WHERE
                   bompara >= '$fdia'
               GROUP BY
                   tbproduto.descricao
                   ORDER BY tbproduto.codigo ASC
                   ";
                        $selprodutos = $conMysql->prepare($selprodutos);
                        $selprodutos->execute();
                        while ($l2 = $selprodutos->fetch(PDO::FETCH_ASSOC)) {
                            $totalpedido = 0;
                            $totalestoque = 0;
                        ?>
                            <tr>
                                <th style="min-width: fit-content;"><?php echo $l2['codigo'] ?></th>
                                <th style="min-width:300px"><?php echo $l2['descricao'] ?></th>
                                <?php
                                $count = count($clcombina['dia']);
                                //buscar desse codigo//nessadata/porgcarga
                                for ($i = 0; $i < $count; $i++) {
                                    $idprod = $clcombina['idproduto'][$i];
                                    $cod = $this->qualproduto($idprod);
                                    if ($cod == $l2['codigo']) {
                                        $totalpedido += $clcombina['quantidade'][$i];
                                        $estoque = $this->qtdEstoque($cod);
                                        $ttestoque = $this->qtdEstoque($cod);
                                        $estoque -= $totalestoque;
                                        if ($clcombina['quantidade'][$i] > $estoque) {
                                            $style = "style='background-color:#EEC900'";
                                        } else {
                                            $style = "";
                                        }
                                ?>
                                        <td class="text-end"><?php echo $clcombina['quantidade'][$i] ?></td>
                                        <td class="text-end" <?php echo $style ?>>
                                            <?php
                                            if ($estoque > 0) {
                                                echo number_format($estoque, 0, ',', '.');
                                            } else {
                                                echo number_format(0, 0, ',', '.');
                                            }
                                            $totalestoque += $clcombina['quantidade'][$i];

                                            ?>
                                        </td>

                                    <?php
                                    } else {
                                    ?>
                                        <td class="text-center fw-bold"> -- </td>
                                        <td class="text-center fw-bold"> -- </td>

                                <?php
                                    }
                                }

                                ?>

                                <?php
                                if ($ttestoque < $totalpedido) {
                                    $s = $totalpedido - $ttestoque;
                                ?>
                                    <td class="text-end fw-bold"> <?php echo number_format($s, 0, ',', '.') ?> </td>


                                <?php
                                } else {
                                ?>
                                    <td class="text-end fw-bold"> 0 </td>


                                <?php

                                }

                                ?>

                            </tr>
                        <?php
                        } //fim do while selprodutos

                        ?>
                    </tbody>

                </table>
            <?php
            } //fim do if
        } catch (PDOException $e) {
            //
        }
    }
    public function quantosprodutospedido($idproduto, $idpedido)
    {
        $con = ConexaoMysql::getConnectionMysql();
        $sel = "SELECT sum(quantidade) as 'qtd' from tbitempedido where idpedido=$idpedido and idproduto=$idproduto";

        $sel = $con->prepare($sel);
        $sel->execute();
        while ($k = $sel->fetch(PDO::FETCH_ASSOC)) {
            return $k['qtd'];
        }
    }

    public function mostraCargas2()
    {
        $totalfabricar=0;
        $numcolunas = 0;
        $dia = new DateTime($_SESSION['pcpAPI']['diaapont']);
        $fdia = $dia->format('Y-m-d');
        try {
            $conMysql = ConexaoMysql::getConnectionMysql();
         
            $select = "SELECT  *  from tbpedido         
            WHERE datapedido>='$fdia' 
            ORDER BY datapedido ASC,prioridade ASC";
            $altera = false;
            $_SESSION['pcpAPI']['dataexporta'] = false;
            if (isset($_SESSION['pcpAPI']['data'])) {
                unset($_SESSION['pcpAPI']['data']);
                $dia1 = $_SESSION['pcpAPI']['dia1'];
                $dia2 = $_SESSION['pcpAPI']['dia2'];
                $select = "SELECT  *  from tbpedido         
                    WHERE datapedido BETWEEN '$dia1' and '$dia2' 
                    ORDER BY datapedido ASC,prioridade ASC";

                $altera = true;
                $_SESSION['pcpAPI']['dataexporta'] = true;
            }
            $q = $conMysql->prepare($select);
            $q->execute();
            if ($q->rowCount() <> 0) {

                $clcombina = array();
                $i = 0;
                $style2 = "style='background:#ccc'";
                $style1 = "style='background:#fff'";
                $styleatual = "";
                $diaatual = "";
            ?>

                <div id="exporta">
                    <table class="tabelaCarga m-3">
                        <thead class="text-center">
                            <tr class="small">
                                <th rowspan="4" style="border: none;background-color:#fff   "></th>
                                <th rowspan="4" style="border: none;background-color:#fff   "></th>
                                <?php
                                $sel = "SELECT  *  from tbpedido         
                            WHERE datapedido>='$fdia' 
                            ORDER BY datapedido ASC,prioridade ASC";
                                if ($altera == true) {
                                    $sel = "SELECT  *  from tbpedido         
                                WHERE datapedido between '$dia1' and '$dia2'
                                ORDER BY datapedido ASC,prioridade ASC";
                                }
                                $j = $conMysql->prepare($sel);
                                $j->execute();
                                $numpedidos = $j->rowCount();
                                
                                while ($m = $j->fetch(PDO::FETCH_ASSOC)) {
                                    $datapedido = new DateTime($m['datapedido']);
                                    $clcombina['dia'][] = $m['datapedido'];
                                    $clcombina['id'][] = $m['id'];
                                    if ($m['datapedido'] <> $diaatual) {
                                        if ($styleatual == $style2) {
                                            $styleatual = $style1;
                                        } else {
                                            $styleatual = $style2;
                                        }
                                        $diaatual = $m['datapedido'];
                                    }
                                ?>
                                    <th colspan="2" <?php echo $styleatual; ?>>
                                        <div class="input-group input-group-sm">
                                            <small>
                                                <?php
                                                $edita = "Control/editacarga.php?id=" . $m['id'];
                                                $apaga = "Control/apagacarga.php?id=" . $m['id'];
                                                ?>
                                                <a href="<?php echo $edita ?>" class=" btn btn-sm btn-warning input-group"><i class="bi bi-pencil"></i></a>
                                                <a href="<?php echo $apaga ?>" class=" btn btn-sm btn-danger input-group"><i class="bi bi-trash"></i></a>
                                            </small>
                                        </div>
                                        <small>
                                            <?php

                                            echo $datapedido->format('d/m/y'); ?>
                                        </small>



                                    </th>
                                <?php
                                } //fim do while bom para
                                ?>
                                <th rowspan="2" style="border: none;background-color:#fff   "></th>
                            </tr>

                            <tr>
                                <?php
                                $styleatual = $style1;
                                $sel = "SELECT  *  from tbpedido         
                            WHERE datapedido>='$fdia' 
                            ORDER BY datapedido ASC,prioridade ASC";
                                if ($altera == true) {
                                    $sel = "SELECT  *  from tbpedido         
                                WHERE datapedido between '$dia1' and '$dia2'
                                ORDER BY datapedido ASC,prioridade ASC";
                                }

                                $j = $conMysql->prepare($sel);
                                $j->execute();
                                $diaatual = '';
                                while ($m = $j->fetch(PDO::FETCH_ASSOC)) {
                                    $clcombina['carga'][] = $m['destino'];
                                    if ($m['datapedido'] <> $diaatual) {
                                        if ($styleatual == $style2) {
                                            $styleatual = $style1;
                                        } else {
                                            $styleatual = $style2;
                                        }
                                    }
                                    $diaatual = $m['datapedido'];
                                ?>
                                    <th colspan="2" class="textovertical" <?php echo $styleatual; ?>><?php echo $m['destino']; ?></th>
                                <?php
                                } //fim do while clcombina

                                ?>
                            </tr>
                            <tr>
                                <?php
                                $count = count($clcombina['dia']);
                                $diaatual = '';
                                $styleatual = $style1;
                                for ($i = 0; $i < $count; $i++) {
                                    if ($clcombina['dia'][$i] <> $diaatual) {
                                        if ($styleatual == $style2) {
                                            $styleatual = $style1;
                                        } else {
                                            $styleatual = $style2;
                                        }
                                    }
                                    $diaatual = $clcombina['dia'][$i];
                                ?>
                                    <td class="text-center fw-bold" colspan="2" <?php echo $styleatual ?>>
                                        <small>
                                            <?php

                                            $total = $this->somapedido($clcombina['id'][$i]);
                                            echo number_format($total, 0, ',', '.');
                                            ?>
                                        </small>

                                    </td>
                                    

                                <?php
                                }
                                ?>
                            </tr>

                            <!-- teste -->
                           <tr>
                                <?php
                                $count = count($clcombina['dia']);
                                $diaatual = '';
                                $styleatual = $style1;
                                $ttpedido=0;
                                for ($i = 0; $i < $count; $i++) {
                                    if ($clcombina['dia'][$i] <> $diaatual) {
                                        if ($styleatual == $style2) {
                                            $styleatual = $style1;
                                        } else {
                                            $styleatual = $style2;
                                        }
                                    }
                                    $diaatual = $clcombina['dia'][$i];
                                ?>
                                    <td class="text-center fw-bold" colspan="2" <?php echo $styleatual ?>>
                                        <small>
                                            <?php

                                            $somPedido = $this->calcularPedido($clcombina['id'][$i]);
                                            $ttpedido += $somPedido;
                                            echo "R$ ".number_format($somPedido, 2, ',', '.');
                                            ?>
                                        </small>

                                    </td>
                                    

                                <?php
                                }
                                ?>
                                <td class="text-center small fw-bold" colspan="2"><?php echo "R$ ".number_format($ttpedido,2,',','.');?></td>
                            </tr> 
                             <!-- teste -->
                            <>
<td style="border-top:none;border-left:none;border-right: none;"> </td>
<td style="border-top:none;border-left:none"> </td>

                                <?php
                                $count = count($clcombina['dia']);
                                $diaatual = '';
                                $styleatual = $style1;
                                for ($i = 0; $i < $count; $i++) {
                                    if ($clcombina['dia'][$i] <> $diaatual) {
                                        if ($styleatual == $style2) {
                                            $styleatual = $style1;
                                        } else {
                                            $styleatual = $style2;
                                        }
                                    }
                                    $diaatual = $clcombina['dia'][$i];
                                ?>
                                    <td class="text-center fw-bold" <?php echo $styleatual ?>><small>C</small></td>
                                    <td class="text-center fw-bold" <?php echo $styleatual ?>><small>E</small></td>
                                <?php
                                }
                                ?>
                                <th><small>Fabricar</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            //selecionando linha do produto
                            $selprodutos = "SELECT DISTINCT
                        (tbproduto.codigo) AS 'codigo',
                        tbproduto.descricao AS 'descricao',
                        tbproduto.id AS 'idproduto'
                    FROM
                        tbitempedido
                    INNER JOIN tbproduto ON tbproduto.id = tbitempedido.idproduto
                    WHERE
                        tbitempedido.idpedido IN(
                        SELECT
                            id
                        FROM
                            tbpedido
                        WHERE
                            datapedido >= '$fdia'
                    )ORDER BY
                    tbproduto.codigo ASC
    
                   ";

if ($altera == true) {
    $selprodutos = "SELECT DISTINCT
    (tbproduto.codigo) AS 'codigo',
    tbproduto.descricao AS 'descricao',
    tbproduto.id AS 'idproduto'
    FROM
    tbitempedido
    INNER JOIN tbproduto ON tbproduto.id = tbitempedido.idproduto
    WHERE
    tbitempedido.idpedido IN(
    SELECT
        id
    FROM
        tbpedido

    WHERE datapedido between '$dia1' and '$dia2'
    )ORDER BY
    tbproduto.codigo ASC";
}
                            $selprodutos = $conMysql->prepare($selprodutos);
                            $selprodutos->execute();
                            while ($l2 = $selprodutos->fetch(PDO::FETCH_ASSOC)) {
                                $totalpedido = 0;
                                $totalestoque = 0;
                            ?>
                                <tr>
                                    <th style="min-width: fit-content;"><small><?php echo $l2['codigo'] ?></small></th>
                                    <th style="min-width:300px"><small><?php echo $l2['descricao'] ?></small></th>

                                    <?php
                                    $estoque = $this->qtdEstoque($l2['codigo']);
                                    $totalestoque = $estoque;
                                    $count = count($clcombina['id']);
                                    // busca quantidade de produtos 
                                    $diaatual = '';
                                    $styleatual = $style1;
                                    for ($i = 0; $i < $count; $i++) {
                                        $qtd = $this->quantosprodutospedido($l2['idproduto'], $clcombina['id'][$i]);

                                        $totalpedido += $qtd;
                                        if ($clcombina['dia'][$i] <> $diaatual) {
                                            if ($styleatual == $style2) {
                                                $styleatual = $style1;
                                            } else {
                                                $styleatual = $style2;
                                            }
                                        }
                                        $diaatual = $clcombina['dia'][$i];

                                        if ($qtd > 0) {
                                            if ($qtd > $totalestoque) {
                                                $style = "background:#FA8072";
                                            } else {
                                                $style = "background:#ADFF2F";
                                            }
                                            $diaf = new DateTime($clcombina['dia'][$i]);
                                            $titulo=$diaf->format('d/m/Y') . " \n " . $clcombina['carga'][$i]." \n ".$l2['codigo']." \n ".$l2['descricao'];
    
                                    ?>
                                            <td class="text-end fw-bold" style="<?php echo $style; ?>"><small><span data-bs-toggle="tooltip" data-bs-placement="right" title="<?php echo $titulo; ?>"><?php echo number_format($qtd, 0, ',', '.'); ?></span></small></td>
                                            <td class="text-end fw-bold" <?php echo $styleatual ?>><small><?php
                                                                                                            if ($totalestoque < 0) {
                                                                                                                echo number_format(0, 0, ',', '.');
                                                                                                            } else {
                                                                                                                echo number_format($totalestoque, 0, ',', '.');
                                                                                                            } ?></small></td>
                                        <?php
                                        } else {
                                        ?>
                                            <td class="text-center fw-bold" colspan="2" <?php echo $styleatual ?>>--</td>

                                    <?php
                                        }
                                        $totalestoque -= $qtd;
                                    }

                                    if ($totalpedido > $estoque) {

                                        $fabricar = $totalpedido - $estoque;
                                        $totalfabricar+=$fabricar;
                                    } else {
                                        $fabricar = 0;
                                    }
                                    ?>

                                    <td class="text-end fw-bold"><span><small title="<?php echo $l2['codigo']."\n".$l2['descricao'] ?>"><?php echo number_format($fabricar, 0, ',', '.'); ?></small></span></td>

                                </tr>
                            <?php
                            } //fim do while selprodutos
                                $numcolunas=$numcolunas+$numpedidos;
                            ?>
                        </tbody>
<tfoot>
<tr>
    <th colspan="<?php echo ($numcolunas*2)+2; ?>" class="text-end">Total</th>
    <th><?php echo number_format($totalfabricar,0,',','.');?></th>
</tr>
</tfoot>
                    </table>
                </div>
            <?php
            } //fim do if
        } catch (PDOException $e) {
            //
        }
    }

    public function somapedido($idpedido)
    {
        $total = 0;
        $con = ConexaoMysql::getConnectionMysql();
        $sel = "SELECT SUM(quantidade) as 'total' from tbitempedido WHERE idpedido=$idpedido";
        $sel = $con->prepare($sel);
        $sel->execute();
        while ($d = $sel->fetch(PDO::FETCH_ASSOC)) {
            $total = $d['total'];
        }
        return $total;
    }

    public function somavalor($idpedido)
    {
        $total = 0;
        $con = ConexaoMysql::getConnectionMysql();
        $sel = "SELECT SUM(quantidade) as 'total' from tbitempedido WHERE idpedido=$idpedido";
        $sel = $con->prepare($sel);
        $sel->execute();
        while ($d = $sel->fetch(PDO::FETCH_ASSOC)) {
            $total = $d['total'];
        }
        return $total;
    }

    public function mostraCargas3()
    {
        $dia = new DateTime($_SESSION['pcpAPI']['diaapont']);
        $fdia = $dia->format('Y-m-d');
        $altera = false;
        try {
            $conMysql = ConexaoMysql::getConnectionMysql();
           
            $select = "SELECT  *  from tbpedido         
            WHERE datapedido>='$fdia' 
            ORDER BY datapedido ASC,prioridade ASC";
            if (isset($_SESSION['pcpAPI']['dataexporta'])) {
                unset($_SESSION['pcpAPI']['dataexporta']);
                $altera = true;
                $dia1 = $_SESSION['pcpAPI']['dia1'];
                $dia2 = $_SESSION['pcpAPI']['dia2'];
                $select = "SELECT  *  from tbpedido         
                 WHERE datapedido between '$dia1' and '$dia2' 
                 ORDER BY datapedido ASC,prioridade ASC";
            }

            $q = $conMysql->prepare($select);
            $q->execute();
            if ($q->rowCount() <> 0) {

                $clcombina = array();
                $i = 0;
                $style2 = "style='background:#ccc'";
                $style1 = "style='background:#fff'";
                $styleatual = "";
                $diaatual = "";
            ?>

                <div id="exporta" class="m-2 p-2 small">
                    <table class="tabelaCarga">
                        <thead class="text-center">
                            <tr class="small">
                                <th rowspan="4" style="border: none;background-color:#fff   "></th>
                                <th rowspan="4" style="border: none;background-color:#fff   "></th>
                                <?php
                                $sel = "SELECT  *  from tbpedido         
                            WHERE datapedido>='$fdia' 
                            ORDER BY datapedido ASC,prioridade ASC";
                                if ($altera == true) {
                                    $sel = "SELECT  *  from tbpedido         
                                    WHERE datapedido between '$dia1' and '$dia2' 
                                    ORDER BY datapedido ASC,prioridade ASC";
                                }
                                $j = $conMysql->prepare($sel);
                                $j->execute();
                                while ($m = $j->fetch(PDO::FETCH_ASSOC)) {
                                    $datapedido = new DateTime($m['datapedido']);
                                    $clcombina['dia'][] = $m['datapedido'];
                                    $clcombina['id'][] = $m['id'];
                                    if ($m['datapedido'] <> $diaatual) {
                                        if ($styleatual == $style2) {
                                            $styleatual = $style1;
                                        } else {
                                            $styleatual = $style2;
                                        }
                                        $diaatual = $m['datapedido'];
                                    }
                                ?>
                                    <th colspan="2" <?php echo $styleatual; ?>>
                                        <div class="input-group input-group-sm">
                                            <?php
                                            $edita = "Control/editacarga.php?id=" . $m['id'];
                                            $apaga = "Control/apagacarga.php?id=" . $m['id'];
                                            ?>

                                        </div>
                                        <?php

                                        echo $datapedido->format('d/m/y'); ?>



                                    </th>
                                <?php
                                } //fim do while bom para
                                ?>
                                <th rowspan="2" style="border: none;background-color:#fff   "></th>
                            </tr>

                            <tr>
                                <?php
                                $styleatual = $style1;
                                $sel = "SELECT  *  from tbpedido         
                            WHERE datapedido>='$fdia' 
                            ORDER BY datapedido ASC,prioridade ASC";
                                if ($altera == true) {
                                    $sel = "SELECT  *  from tbpedido         
                               WHERE datapedido between '$dia1' and '$dia2' 
                                ORDER BY datapedido ASC,prioridade ASC";
                                }
                                $j = $conMysql->prepare($sel);
                                $j->execute();
                                $diaatual = '';
                                while ($m = $j->fetch(PDO::FETCH_ASSOC)) {
                                    $clcombina['carga'][] = $m['destino'];
                                    if ($m['datapedido'] <> $diaatual) {
                                        if ($styleatual == $style2) {
                                            $styleatual = $style1;
                                        } else {
                                            $styleatual = $style2;
                                        }
                                    }
                                    $diaatual = $m['datapedido'];
                                ?>
                                    <th colspan="2" class="textovertical" <?php echo $styleatual; ?>><?php echo $m['destino']; ?></th>
                                <?php
                                } //fim do while clcombina

                                ?>
                            </tr>
                            <tr>
                                <?php
                                $count = count($clcombina['dia']);
                                $diaatual = '';
                                $styleatual = $style1;
                                for ($i = 0; $i < $count; $i++) {
                                    if ($clcombina['dia'][$i] <> $diaatual) {
                                        if ($styleatual == $style2) {
                                            $styleatual = $style1;
                                        } else {
                                            $styleatual = $style2;
                                        }
                                    }
                                    $diaatual = $clcombina['dia'][$i];
                                ?>
                                    <td class="text-center fw-bold" colspan="2" <?php echo $styleatual ?>>
                                        <?php

                                        $total = $this->somapedido($clcombina['id'][$i]);
                                        echo number_format($total, 0, ',', '.');
                                        ?>

                                    </td>

                                <?php
                                }
                                ?>
                            </tr>
                            
                            <tr>

                                <?php
                                $count = count($clcombina['dia']);
                                $diaatual = '';
                                $styleatual = $style1;
                                for ($i = 0; $i < $count; $i++) {
                                    if ($clcombina['dia'][$i] <> $diaatual) {
                                        if ($styleatual == $style2) {
                                            $styleatual = $style1;
                                        } else {
                                            $styleatual = $style2;
                                        }
                                    }
                                    $diaatual = $clcombina['dia'][$i];
                                ?>
                                    <td class="text-center fw-bold" <?php echo $styleatual ?>>C</td>
                                    <td class="text-center fw-bold" <?php echo $styleatual ?>>E</td>
                                <?php
                                }
                                ?>
                                <th>Fabricar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            //selecionando linha do produto
                            $selprodutos = "SELECT DISTINCT
                        (tbproduto.codigo) AS 'codigo',
                        tbproduto.descricao AS 'descricao',
                        tbproduto.id AS 'idproduto'
                    FROM
                        tbitempedido
                    INNER JOIN tbproduto ON tbproduto.id = tbitempedido.idproduto
                    WHERE
                        tbitempedido.idpedido IN(
                        SELECT
                            id
                        FROM
                            tbpedido
                        WHERE
                            datapedido >= '$fdia'
                    )ORDER BY
                    tbproduto.codigo ASC
    
                   ";

                            if ($altera == true) {
                                        $selprodutos = "SELECT DISTINCT
                                        (tbproduto.codigo) AS 'codigo',
                                        tbproduto.descricao AS 'descricao',
                                        tbproduto.id AS 'idproduto'
                                        FROM
                                        tbitempedido
                                        INNER JOIN tbproduto ON tbproduto.id = tbitempedido.idproduto
                                        WHERE
                                        tbitempedido.idpedido IN(
                                        SELECT
                                            id
                                        FROM
                                            tbpedido
                                 
                                        WHERE datapedido between '$dia1' and '$dia2'
                                        )ORDER BY
                                        tbproduto.codigo ASC";
                            }
                           
                            $selprodutos = $conMysql->prepare($selprodutos);
                            $selprodutos->execute();
                            while ($l2 = $selprodutos->fetch(PDO::FETCH_ASSOC)) {
                                $totalpedido = 0;
                                $totalestoque = 0;
                            ?>
                                <tr>
                                    <th style="min-width: fit-content;"><?php echo $l2['codigo'] ?></th>
                                    <th style="min-width:300px"><?php echo $l2['descricao'] ?></th>
                                    <?php
                                    $estoque = $this->qtdEstoque($l2['codigo']);
                                    $totalestoque = $estoque;
                                    $count = count($clcombina['id']);
                                    // busca quantidade de produtos 
                                    $diaatual = '';
                                    $styleatual = $style1;
                                    for ($i = 0; $i < $count; $i++) {
                                        $qtd = $this->quantosprodutospedido($l2['idproduto'], $clcombina['id'][$i]);

                                        $totalpedido += $qtd;
                                        if ($clcombina['dia'][$i] <> $diaatual) {
                                            if ($styleatual == $style2) {
                                                $styleatual = $style1;
                                            } else {
                                                $styleatual = $style2;
                                            }
                                        }
                                        $diaatual = $clcombina['dia'][$i];

                                        if ($qtd > 0) {
                                            if ($qtd > $totalestoque) {
                                                $style = "background:#FA8072";
                                            } else {
                                                $style = "background:#ADFF2F";
                                            }
                                            $diaf = new DateTime($clcombina['dia'][$i]);

                                    ?>
                                            <td class="text-end fw-bold" style="<?php echo $style; ?>"><span data-bs-toggle="tooltip" data-bs-placement="right" title="<?php echo $diaf->format('d/m/Y') . "&#013;" . $clcombina['carga'][$i]; ?>"><?php echo number_format($qtd, 0, ',', '.'); ?></span></td>
                                            <td class="text-end fw-bold" <?php echo $styleatual ?>><?php
                                                                                                    if ($totalestoque < 0) {
                                                                                                        echo number_format(0, 0, ',', '.');
                                                                                                    } else {
                                                                                                        echo number_format($totalestoque, 0, ',', '.');
                                                                                                    } ?></td>
                                        <?php
                                        } else {
                                        ?>
                                            <td class="text-center fw-bold" colspan="2" <?php echo $styleatual ?>>--</td>

                                    <?php
                                        }
                                        $totalestoque -= $qtd;
                                    }

                                    if ($totalpedido > $estoque) {

                                        $fabricar = $totalpedido - $estoque;
                                    } else {
                                        $fabricar = 0;
                                    }
                                    ?>
                                    <td class="text-end fw-bold"><?php echo number_format($fabricar, 0, ',', '.'); ?></td>

                                </tr>
                            <?php
                            } //fim do while selprodutos

                            ?>
                        </tbody>

                    </table>
                </div>
<?php
            } //fim do if
        } catch (PDOException $e) {
            //
        }
    }
     public function calcularPedido($idpedido) {
        $con = ConexaoMysql::getConnectionMysql();
        // Query para obter os itens do pedido junto com os valores dos produtos
        $query = "
            SELECT i.quantidade, p.valor
            FROM tbitempedido i
            JOIN tbproduto p ON i.idproduto = p.id
            WHERE i.idpedido = {$idpedido}
        ";
  
     $soma = 0;
       
      $stmt = $con->prepare($query);
       
            // Executar a consulta
            $stmt->execute();
    

           
             $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($row as  $k){
                $soma += $k['quantidade'] * $k['valor'];  
            } 
             

    return $soma;
     
    }    
    
}
?>