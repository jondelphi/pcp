<?php
class Programacao
{
    
    public function TrazIdProduto($codigo)
{
    $conn = ConexaoMysql::getConnectionMysql();
    $sql = "SELECT id FROM tbproduto WHERE codigo = ?";
    $sel = $conn->prepare($sql);
    $sel->execute([$codigo]);
    $result = $sel->fetchColumn();
    return $result ? $result : false;
}

public function TrazCODProduto($id)
{
    $conn = ConexaoMysql::getConnectionMysql();
    $sql = "SELECT codigo FROM tbproduto WHERE id = ?";
    $sel = $conn->prepare($sql);
    $sel->execute([$id]);
    $result = $sel->fetchColumn();
    return $result ? $result : false;
}

public function TrazDescricaoProduto($id)
{
    $conn = ConexaoMysql::getConnectionMysql();
    $sql = "SELECT descricao FROM tbproduto WHERE id = ?";
    $sel = $conn->prepare($sql);
    $sel->execute([$id]);
    $result = $sel->fetchColumn();
    return $result ? $result : false;
}

public function TrazLinha($id)
{
    $conn = ConexaoMysql::getConnectionMysql();
    $sql = "SELECT linha FROM tblinha WHERE id = ?";
    $sel = $conn->prepare($sql);
    $sel->execute([$id]);
    $result = $sel->fetchColumn();
    return $result ? $result : false;
}

public function MostraProgramacao()
{
    $conn = ConexaoMysql::getConnectionMysql();
    
    $botalinha = 'Nenhuma';
    $dia = new DateTime($_SESSION['pcpAPI']['diaapont']);
    $sql = "SELECT tbprogramacao.*, tblinha.linha as desclinha, tbproduto.codigo as codigo 
            FROM tbprogramacao 
            INNER JOIN tblinha ON tblinha.id = tbprogramacao.idlinha
            INNER JOIN tbproduto ON tbproduto.id= tbprogramacao.idproduto
            WHERE dataprojeto= ?
            ORDER BY tbprogramacao.idlinha ASC, tbprogramacao.horainicio ASC, tbproduto.codigo ASC";
    
    $sel = $conn->prepare($sql);
    $sel->execute([$dia->format('Y-m-d')]);
    
    $nr = $sel->rowCount();
    
    if ($nr > 0) {
        $soma = 0;
?>
        <table class="table table-dark table-striped table-bordered" style="width: fit-content;">
            <thead>
                <tr>
                    <th colspan="8" class="text-center">
                        Data Programação: <?php echo $dia->format('d/m/Y'); ?> 
                        <a href="View/programapeca.php" target="_blank" class="btn btn-secondary"><i class="bi bi-printer"></i> Peças </a>
                    </th>
                </tr>
                <tr>
                    <th>
                        Intervalo
                    </th>
                    <th>
                        Código
                    </th>
                    <th>
                        Descrição
                    </th>
                    <th>
                        Quantidade
                    </th>
                    <th>
                        Observação
                    </th>
                    <th>
                        Produzido
                    </th>
                    <th>
                        <small>Eficiência PCP</small>
                    </th>
                    <th>
                        Operação
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                $somaprograma = 0;
                $quantasefi = 0;
                $somefi = 0;
                while ($l = $sel->fetch(PDO::FETCH_ASSOC)) {
                    $codproduto = $this->TrazCODProduto($l['idproduto']);
                    $descricao = $this->TrazDescricaoProduto($l['idproduto']);
                    $soma += $l['quantidade'];
                    $desclinha = $l['desclinha'];
                    $hi = new DateTime($l['horainicio']);
                    $hf = new DateTime($l['horafim']);
                    if ($desclinha !== $botalinha) {
                        $botalinha = $desclinha;
                ?>
                        <tr>
                            <th colspan="8">
                                <h4 class="text-center text-dark" style="background-color: #ccc;"><?php echo $botalinha; ?></h4>
                            </th>
                        </tr>
                    <?php
                    }

                    ?>
                    <tr>
                        <td>
                            <?php echo $hi->format('H:i') . "-" . $hf->format('H:i'); ?>
                        </td>
                        <td>
                            <a href="View/folhaprocesso.php?produto=<?php echo $codproduto;?>" target="_blank" style="text-decoration: none; color:white">
                                <?php echo $codproduto; ?></a>
                        </td>
                        <td>
                            <?php echo $descricao; ?>
                        </td>
                        <td class="text-end">
                            <?php echo $l['quantidade']; ?>
                        </td>
                        <td>
                            <?php echo $l['obs']; ?>
                        </td>
                        <td class="text-end">
                            <?php
                            $totalproduzido = $this->retornaProduzidos($botalinha, $codproduto, $dia->format('Y-m-d'));
                            echo number_format($totalproduzido, 0, ',', '.');
                            $somaprograma += $totalproduzido;
                            ?>
                        </td>
                        <td class="text-end">
                            <?php
                            $div = $l['quantidade'];
                            if ($div == 0) {
                                $div = 1;
                            }
                            $eficiencia = ($totalproduzido / $div) * 100;
                            if ($eficiencia > 100) {
                                $eficiencia = 100;
                            }
                            $quantasefi++;
                            $somefi = $somefi + $eficiencia;

                            echo number_format($eficiencia, 1, ',', '.') . "%";
                            ?>
                        </td>
                        <td class="text-center">
                            <?php
                            $exclui = "onclick=conExcluiProgramacao('Control/excluiprogramacao.php?id=" . $l['id'] . "')";
                            $edita = "onclick=conEditaProgramacao('Control/edtprogramacao.php?id=" . $l['id'] . "')";
                            ?>
                            <button <?php echo $exclui; ?> class="btn btn-sm btn-danger" title="Apaga programação"><small><i class="bi bi-trash"></i></small></button>
                            <button <?php echo $edita; ?> class="btn btn-sm btn-warning" title="Edita programação"><small><i class="bi bi-pencil"></i></small></button>
                        </td>
                    </tr>
                <?php
                }

                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">
                        Soma
                    </td>
                    <td class="text-end">
                        <?php echo  number_format($soma, 0, ',', '.'); ?>
                    </td>
                    <td></td>
                    <td class="text-end">
                        <?php echo  number_format($somaprograma, 0, ',', '.'); ?>
                    </td>
                    <td class="text-end">
                        <?php
                        $TTeficiencia = ($somefi / $quantasefi);
                        echo number_format($TTeficiencia, 1, ',', '.') . "%";
                        ?>
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    <?php
    } else {
    ?>
        <h3 class="bg-warning text-bg-warning">SEM REGISTROS</h3>
        <?php
    }
}



    public function MostraProgramacao2()
    {
        $conn = ConexaoMysql::getConnectionMysql();
    
        $botalinha = 'Nenhuma';
        $dia = new DateTime($_SESSION['pcpAPI']['diaapont']);
        $sql = 'SELECT tbprogramacao.*, tblinha.linha as desclinha, tbproduto.codigo as codigo 
                FROM tbprogramacao 
                INNER JOIN tblinha ON tblinha.id = tbprogramacao.idlinha
                INNER JOIN tbproduto ON tbproduto.id= tbprogramacao.idproduto
                WHERE dataprojeto= ?
                ORDER BY tbprogramacao.idlinha ASC, tbprogramacao.horainicio ASC, tbproduto.codigo ASC';
        
        $sel = $conn->prepare($sql);
        $sel->execute([$dia->format('Y-m-d')]);
        
        $nr = $sel->rowCount();
        
        if ($nr > 0) {
            $soma = 0;
    ?>
            <table class="table table-light table-striped table-bordered table-sm" style="width: fit-content;">
                <thead>
                    <tr>
                        <th colspan="5" class="text-center">
                            Data Programação: <?php echo $dia->format('d/m/Y'); ?>
                        </th>
                    </tr>
                    <tr>
                        <th>
                            Intervalo
                        </th>
    
                        <th>
                            Código
                        </th>
                        <th>
                            Descrição
                        </th>
                        <th>
                            Quantidade
                        </th>
                        <th>
                            Observação
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $somaprograma = 0;
                    $quantasefi = 0;
                    $somefi = 0;
                    while ($l = $sel->fetch(PDO::FETCH_ASSOC)) {
                        $codproduto = $this->TrazCODProduto($l['idproduto']);
                        $descricao = $this->TrazDescricaoProduto($l['idproduto']);
                        $soma += $l['quantidade'];
                        $hi = new DateTime($l['horainicio']);
                        $hf = new DateTime($l['horafim']);
                        $desclinha = $l['desclinha'];
                        
                        if ($desclinha !== $botalinha) {
                            $botalinha = $desclinha;
                    ?>
                            <tr>
                                <th colspan="5">
                                    <h4 class="text-center text-dark" style="background-color: #ccc;"><?php echo $botalinha; ?></h4>
                                </th>
                            </tr>
                        <?php
                        }
                        ?>
                        <tr>
                            <td>
                                <?php echo $hi->format('H:i') . "-" . $hf->format('H:i'); ?>
                            </td>
                            <td>
                                <?php echo $codproduto; ?>
                            </td>
                            <td>
                                <?php echo $descricao; ?>
                            </td>
                            <td class="text-end">
                                <?php echo $l['quantidade']; ?>
                            </td>
                            <td>
                                <?php echo $l['obs']; ?>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">
                            Soma
                        </td>
                        <td class="text-end">
                            <?php echo number_format($soma, 0, ',', '.'); ?>
                        </td>
                        <td class="text-end">
                            <?php echo number_format($somaprograma, 0, ',', '.'); ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
    <?php
        } else {
    ?>
            <h3 class="bg-warning text-bg-warning">SEM REGISTROS</h3>
    <?php
        }
    }
    

    public function retornaProduzidos($linha, $produto, $dia)
    {
        $sql = 'SELECT COUNT(etiqueta) as total 
                FROM tbapontamento 
                INNER JOIN tblinha ON tblinha.id = tbapontamento.idlinha
                INNER JOIN tbproduto ON tbproduto.id = tbapontamento.idproduto
                WHERE tblinha.linha = ? 
                AND tbproduto.codigo = ? 
                AND diaapont = ?';        
        $con = ConexaoMysql::getConnectionMysql();
        $stmt = $con->prepare($sql);
        $stmt->execute([$linha, $produto, $dia]);        
        return $stmt->fetchColumn();
    }
    

    public function quantosapontados($linha, $dia1, $dia2, $idlinha)
    {
        $sql = 'SELECT COUNT(tbapontamento.etiqueta) as total 
                FROM tbapontamento 
                INNER JOIN tblinha ON tblinha.id = tbapontamento.idlinha
                WHERE tblinha.linha = ? 
                AND tbapontamento.diaapont BETWEEN ? AND ?
                AND tbapontamento.idproduto IN (
                    SELECT idproduto 
                    FROM tbprogramacao 
                    WHERE idlinha = ? 
                    AND dataprojeto BETWEEN ? AND ?
                )';
        
        $con = ConexaoMysql::getConnectionMysql();
        $stmt = $con->prepare($sql);
        $stmt->execute([$linha, $dia1, $dia2, $idlinha, $dia1, $dia2]);
        
        $total = $stmt->fetchColumn();
        
        return $total;
    }
    

    public function quantosapontadosGERAL($linha, $dia1, $dia2)
    {
        $sql = "SELECT COUNT(etiqueta) as total 
                FROM tbapontamento
                INNER JOIN tblinha ON tblinha.id = tbapontamento.idlinha
                WHERE tblinha.linha = ? AND diaapont BETWEEN ? AND ?";
        
        $con = ConexaoMysql::getConnectionMysql();
        $stmt = $con->prepare($sql);
        $stmt->execute([$linha, $dia1, $dia2]);
        
        $total = $stmt->fetchColumn();
        
        return $total;
    }
    
    public function quantosapontadosGERALTOTAL($dia1, $dia2)
    {
        $sql = "SELECT COUNT(etiqueta) as total 
                FROM tbapontamento 
                WHERE diaapont BETWEEN ? AND ?";
        
        $con = ConexaoMysql::getConnectionMysql();
        $stmt = $con->prepare($sql);
        $stmt->execute([$dia1, $dia2]);
        
        return $stmt->fetchColumn();
    }
    
    public function todosapontadosEFTOTAL($dia1, $dia2)
    {
        $sql = "SELECT COUNT(ta.etiqueta) as total 
                FROM tbapontamento ta
                INNER JOIN tbprogramacao tp ON ta.idproduto = tp.idproduto
                WHERE ta.diaapont BETWEEN ? AND ?
                AND tp.dataprojeto BETWEEN ? AND ?";
        
        $con = ConexaoMysql::getConnectionMysql();
        $stmt = $con->prepare($sql);
        $stmt->execute([$dia1, $dia2, $dia1, $dia2]);
        
        return $stmt->fetchColumn();
    }
    

    public function todosprojetadosEFTOTAL($dia1, $dia2)
{
    $sql = "SELECT SUM(quantidade) as total 
            FROM tbprogramacao 
            WHERE dataprojeto BETWEEN ? AND ?";
    
    $con = ConexaoMysql::getConnectionMysql();
    $stmt = $con->prepare($sql);
    $stmt->execute([$dia1, $dia2]);
    
    return $stmt->fetchColumn();
}

    public function tabelaeficienciapcp($dia1, $dia2)
    {
        $fd1 = new DateTime($dia1);
        $fd2 = new DateTime($dia2);
        $f1 = $fd1->format('Y-m-d');
        $f2 = $fd2->format('Y-m-d');
        $con = ConexaoMysql::getConnectionMysql();
        $sql = "SELECT
                    tblinha.linha AS linha,
                    tblinha.id AS idlinha,
                    SUM(tbprogramacao.quantidade) AS projeto,
                    SUM(tbapontamento.produzido) AS produzido_projetados,
                    SUM(tbapontamento_geral.produzido) AS produzido_geral
                FROM
                    tbprogramacao
                INNER JOIN tblinha ON tblinha.id = tbprogramacao.idlinha
                LEFT JOIN (
                    SELECT
                        idlinha,
                        idproduto,
                        SUM(quantidade) AS produzido
                    FROM
                        tbapontamento
                    WHERE
                        diaapont BETWEEN :dia1 AND :dia2
                    GROUP BY
                        idlinha,
                        idproduto
                ) AS tbapontamento ON tbapontamento.idlinha = tbprogramacao.idlinha AND tbapontamento.idproduto = tbprogramacao.idproduto
                LEFT JOIN (
                    SELECT
                        idlinha,
                        SUM(quantidade) AS produzido
                    FROM
                        tbapontamento
                    WHERE
                        diaapont BETWEEN :dia1 AND :dia2
                    GROUP BY
                        idlinha
                ) AS tbapontamento_geral ON tbapontamento_geral.idlinha = tbprogramacao.idlinha
                WHERE
                    tbprogramacao.dataprojeto BETWEEN :dia1 AND :dia2
                GROUP BY
                    tblinha.linha,
                    tblinha.id
                ORDER BY
                    tblinha.id ASC";
    
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindParam(':dia1', $dia1);
            $stmt->bindParam(':dia2', $dia2);
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {

            ?>
                <div class="container m-2 p-2">
                    <table class="table table-dark table-sm table-bordered table-striped" style="width: fit-content;">
                        <thead class="text-center">
                            <tr>
                                <th colspan="8">
                                    TABELA Projetado x Produzido - Data: <?php echo $fd1->format("d/m/Y"); ?> até <?php echo $fd2->format("d/m/Y"); ?>
                                    <a href="View\exportatbeficiencia.php" target="blank" style="text-decoration:none;color:#ccc">
                                        <i class="bi bi-printer"></i></a>
                                </th>
                            </tr>
                            <tr>

                                <th>
                                    Linha
                                </th>
                                <th>
                                    Projetado
                                </th>
                                <th>
                                    Produzido(Projetados)
                                </th>
                                <th>
                                    Produzido(Geral)
                                </th>
                                <th>Eficiência(Produção)</th>
                                <th>Eficiência(PCP)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($j = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                                <tr>
                                    <td>
                                        <small>
                                            <?php
                                            echo $j['linha'];
                                            ?>
                                        </small>
                                    </td>
                                    <td class="text-end">
                                        
                                            <?php
                                            echo number_format($j['projeto'], 0, ',', '.');
                                            ?>
                                       
                                    </td>
                                    <td class="text-end">
                                       
                                            <?php
                                            $produzido = $this->quantosapontados($j['linha'], $dia1, $dia2,$j['idlinha']);
                                            echo number_format($produzido, 0, ',', '.');
                                            ?>
                                        
                                    </td>
                                    <td class="text-end">
                                       
                                            <?php
                                            $produzido2 = $this->quantosapontadosGERAL($j['linha'], $dia1, $dia2);
                                            echo number_format($produzido2, 0, ',', '.');
                                            ?>
                                        
                                    </td>
                                    <td class="text-end">
                                        
                                            <?php
                                            $eficiencia2 = ($produzido2 / $j['projeto']) * 100;
                                           
                                            echo number_format($eficiencia2, 0, ',', '.') . "%";
                                            ?>
                                        
                                    </td>
                                    <td class="text-end">
                                        
                                            <?php
                                            $eficiencia = ($produzido / $j['projeto']) * 100;
                                            if ($eficiencia > 100) {
                                                $eficiencia = 100;
                                            }
                                            echo number_format($eficiencia, 0, ',', '.') . "%";
                                            ?>
                                        
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>
                                    TOTAL
                                </th>
                                <?php
                                    $tprojetado=$this->todosprojetadosEFTOTAL($dia1,$dia2);
                                    $tproduzido=$this->todosapontadosEFTOTAL($dia1,$dia2);
                                    $TTeficiencia=($tproduzido/$tprojetado)*100;
                                    if($TTeficiencia>100){
                                        $TTeficiencia=100;
                                    }
                                ?>
                                <th class="text-end">
                                    <?php echo number_format($tprojetado,0,',','.')?>
                                </th>

                               
                                <th class="text-end">
                                    <?php echo number_format($tproduzido,0,',','.')?>
                                </th>
                                <th class="text-end">
                                <?php
                                            $produzido3 = $this->quantosapontadosGERALTOTAL( $dia1, $dia2);
                                            echo number_format($produzido3, 0, ',', '.');
                                            $ne=($produzido3/$tprojetado)*100;
                                            ?>
                                </th>  
                                <th class="text-end">
                                    <?php echo number_format($ne,1,',','.')."%"?>
                                </th>
                                <th class="text-end">
                                    <?php echo number_format($TTeficiencia,1,',','.')."%"?>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

<?php
            }
        } catch (PDOException) {
        }
    }


    public function tabelaeficienciapcp2($dia1, $dia2)
    {
        $fd1 = new DateTime($dia1);
        $fd2 = new DateTime($dia2);
        $f1 = $fd1->format('Y-m-d');
        $f2 = $fd2->format('Y-m-d');
        $con = ConexaoMysql::getConnectionMysql();
        $sql = "SELECT
        tblinha.linha AS 'linha',
        tblinha.id as 'idlinha',
        SUM(tbprogramacao.quantidade) AS 'projeto'     
    FROM
        tbprogramacao
    INNER JOIN tblinha ON tblinha.id = tbprogramacao.idlinha 
    WHERE
        tbprogramacao.dataprojeto BETWEEN '$f1'  and '$f2'
    GROUP BY
       tblinha.linha
        
    ORDER BY
         tblinha.id ASC";

        try {
            $sql = $con->prepare($sql);
            $sql->execute();
            if ($sql->rowCount() <> 0) {

            ?>
                <div class="container m-2 p-2">
                    <table class="table table-light table-sm table-bordered table-striped" style="width: fit-content;">
                        <thead class="text-center">
                            <tr>
                                <th colspan="8">
                                    TABELA Projetado x Produzido - Data: <?php echo $fd1->format("d/m/Y"); ?> até <?php echo $fd2->format("d/m/Y"); ?>
                                   
                                </th>
                            </tr>
                            <tr>

                                <th>
                                    Linha
                                </th>
                                <th>
                                    Projetado
                                </th>
                                <th>
                                    Produzido(Projetados)
                                </th>
                                <th>
                                    Produzido(Geral)
                                </th>
                                <th>Eficiência(Produção)</th>
                                <th>Eficiência(PCP)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($j = $sql->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                                <tr>
                                    <td>
                                        <small>
                                            <?php
                                            echo $j['linha'];
                                            ?>
                                        </small>
                                    </td>
                                    <td class="text-end">
                                        
                                            <?php
                                            echo number_format($j['projeto'], 0, ',', '.');
                                            ?>
                                       
                                    </td>
                                    <td class="text-end">
                                       
                                            <?php
                                            $produzido = $this->quantosapontados($j['linha'], $dia1, $dia2,$j['idlinha']);
                                            echo number_format($produzido, 0, ',', '.');
                                            ?>
                                        
                                    </td>
                                    <td class="text-end">
                                       
                                            <?php
                                            $produzido2 = $this->quantosapontadosGERAL($j['linha'], $dia1, $dia2);
                                            echo number_format($produzido2, 0, ',', '.');
                                            ?>
                                        
                                    </td>
                                    <td class="text-end">
                                        
                                            <?php
                                            $eficiencia2 = ($produzido2 / $j['projeto']) * 100;
                                           
                                            echo number_format($eficiencia2, 0, ',', '.') . "%";
                                            ?>
                                        
                                    </td>
                                    <td class="text-end">
                                        
                                            <?php
                                            $eficiencia = ($produzido / $j['projeto']) * 100;
                                            if ($eficiencia > 100) {
                                                $eficiencia = 100;
                                            }
                                            echo number_format($eficiencia, 0, ',', '.') . "%";
                                            ?>
                                        
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>
                                    TOTAL
                                </th>
                                <?php
                                    $tprojetado=$this->todosprojetadosEFTOTAL($dia1,$dia2);
                                    $tproduzido=$this->todosapontadosEFTOTAL($dia1,$dia2);
                                    $TTeficiencia=($tproduzido/$tprojetado)*100;
                                    if($TTeficiencia>100){
                                        $TTeficiencia=100;
                                    }
                                ?>
                                <th class="text-end">
                                    <?php echo number_format($tprojetado,0,',','.')?>
                                </th>

                               
                                <th class="text-end">
                                    <?php echo number_format($tproduzido,0,',','.')?>
                                </th>
                                <th class="text-end">
                                <?php
                                            $produzido3 = $this->quantosapontadosGERALTOTAL( $dia1, $dia2);
                                            echo number_format($produzido3, 0, ',', '.');
                                            $ne=($produzido3/$tprojetado)*100;
                                            ?>
                                </th>  
                                <th class="text-end">
                                    <?php echo number_format($ne,1,',','.')."%"?>
                                </th>
                                <th class="text-end">
                                    <?php echo number_format($TTeficiencia,1,',','.')."%"?>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

<?php
            }
        } catch (PDOException) {
        }
    }
   
}

?>