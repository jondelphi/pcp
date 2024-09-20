<?php
date_default_timezone_set('America/Sao_Paulo');
class Apontamento
{
    public function TodasApontadas($conn)
    {
        $sql = "SELECT * FROM tbapontamento WHERE diaapont='" . $_SESSION['pcpAPI']['diaapont'] . "'";
        $sel = $conn->prepare($sql);
        $sel->execute();
        $numreg = $sel->rowCount();
        return $numreg;
    }
    public function trazidlinha($linha){
        $conn=ConexaoMysql::getConnectionMysql();
        $sql="SELECT * FROM tblinha WHERE linha='".$linha."'";
        $sel=$conn->prepare($sql);
        $sel->execute();
        $nr=$sel->rowCount();
        if ($nr>0) {
            while ($a = $sel->fetch(PDO::FETCH_ASSOC)) {
                return $a['id'];
            }
            
        }        
    }
    public function TodasApontadasdeUmaLinha($conn, $linha)
    {
        $linha=$this->trazidlinha($linha);
        $sql = "SELECT tbapontamento.*, tblinha.linha as 'linha' FROM tbapontamento 
        INNER JOIN tblinha ON tblinha.id=tbapontamento.idlinha
        WHERE diaapont='" . $_SESSION['pcpAPI']['diaapont'] . "' AND tblinha.id='" . $linha . "'";
       // echo $sql."<br>";
        $sel = $conn->prepare($sql);
        $sel->execute();
        $numreg = $sel->rowCount();
        return $numreg;
    }
    public function TodasApontadasdeUmaLinhaETQ($conn, $linha, $cod, $dia)
    {
        $linha=$this->trazidlinha($linha);
        $sql = "SELECT * FROM tbapontamento
        inner join tbproduto on tbproduto.id = tbapontamento.idproduto
         WHERE diaapont='" . $dia . "' AND idlinha=" . $linha . " AND tbproduto.codigo='$cod'
         ORDER BY horaapont DESC";
        $sel = $conn->prepare($sql);
        $sel->execute();
        $numreg = $sel->rowCount();
        return $numreg;
    }
    public function divisor()
    {
        $ha = new DateTime('now');
        $hi = new Datetime('07:40');
        if ($ha->format('H:i') > $hi->format('H:i')) {
            $int = $hi->diff($ha);
            $horas = ($int->format('%H')) * 60;
            $minutos = $int->format('%I');
            $th = ($horas + $minutos) / 60;
            $div = $th;
        } else {
            $div = 1;
        }
        return $div;
    }

    public function ApontadasPorLinhas($conn)
    {
        $q = "SELECT * FROM tblinha  order by id ASC";
        $q = $conn->prepare($q);
        $q->execute();
        $nr = $q->rowCount();
        $td = $this->TodasApontadas($conn);
        if ($nr > 0 && $td > 0) {

            $divisor = $this->divisor();

            $diaatual = new DateTime('now');
            $diasistema = new DateTime($_SESSION['pcpAPI']['diaapont']);

            if ($diaatual->format('d/m/y') !== $diasistema->format('d/m/y')) {
                $divisor = 8.75;
            }
            if ($divisor < 1) {
                $divisor = 1;
            }
            ?>
            <table class="table table-striped table-dark table-sm table-bordered table-hover">
                <thead>
                    <tr>
                        <th colspan="3" class="text-center">
                            TOTAL POR LINHA
                        </th>
                    </tr>
                    <tr class="text-center">
                        <th>Linha</th>
                        <th>Total</th>
                        <th>Média Hora</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($l = $q->fetch(PDO::FETCH_ASSOC)) {
                        $sql = "SELECT tblinha.linha as 'linha',count(etiqueta) as 'total' 
                 FROM tbapontamento 
                 INNER JOIN tblinha ON tblinha.id=tbapontamento.idlinha
                 WHERE tblinha.linha='" . $l['linha'] . "' AND diaapont='" . $_SESSION['pcpAPI']['diaapont'] . "'" .
                            " GROUP BY tblinha.linha ORDER BY tblinha.linha DESC";
                        $sel = $conn->prepare($sql);
                        $sel->execute();
                        $numreg = $sel->rowCount();
                        if ($numreg > 0) {
                            while ($a = $sel->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <tr>
                                    <td class="p-2"><a href="View/resumo.php?linha=<?php echo $a['linha'] ?>"
                                            class="link-light text-decoration-none" target="blank"><?php echo $a['linha'] ?></a></td>
                                    <td class="p-2 text-end">
                                        <?php echo number_format($a['total'], 0, ",", ".") ?>
                                    </td>
                                    <td class="p-2 m-2 text-end">
                                        <?php
                                        $media = $a['total'] / $divisor;
                                        echo number_format($media, 1, ",", ".");
                                        ?>


                                    </td>

                                </tr>
                                <?php
                            }
                        }
                    }
                    ?>
                </tbody>
            </table>
            <?php
        }
    }



    public function RetornaProdutosResumo($conn)
    {
        $linha = $_GET['linha'];
        $sql = "SELECT
            tbproduto.codigo as 'produto',
            tbproduto.descricao as 'descricao',
            COUNT(etiqueta) as 'total'
            from tbapontamento 
            INNER JOIN tbproduto ON tbproduto.id = tbapontamento.idproduto
            INNER JOIN tblinha ON tblinha.id = tbapontamento.idlinha
            WHERE tblinha.linha='" . $linha . "' AND 
            diaapont='" . $_SESSION['pcpAPI']['diaapont'] . "'
            
            GROUP BY tbproduto.codigo, tbproduto.descricao
            ORDER BY tbproduto.codigo ASC
            ;
         ";
        $sel = $conn->prepare($sql);
        $sel->execute();
        $numreg = $sel->rowCount();
        if ($numreg > 0) {
            ?>
            <table class="table table-dark table-bordered table-striped">
                <thead>
                    <tr>
                    <tr>

                        <th colspan="5" class="text-center">
                            TOTAL PRODUTOS POR MODELOS
                        </th>
                    </tr>
                    <tr class="text-center">
                        <th colspan="2">Produto</th>
                        <th>Quantidade</th>
                        <th>Projetado</th>
                        <th>Eficiência PCP</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    $se = 0;
                    $soma = 0;
                    $quantosprojetados = 0;
                    $quantosfeitos = 0;
                    while ($l = $sel->fetch(PDO::FETCH_ASSOC)) {
                        $i++;

                        ?>
                        <tr>
                            <td>
                                <?php echo $l['produto']; ?>
                            </td>
                            <td><small>
                                    <?php echo $l['descricao']; ?>
                                </small></td>
                            <td class="text-end">
                                <?php echo number_format($l['total'], 0, ',', '.'); ?>
                            </td>
                            <td class="text-end">
                                <?php
                                $t = $this->projetados($_SESSION['pcpAPI']['diaapont'], $l['produto'], $linha);
                                echo $t;
                                if ($t > 0) {
                                    $quantosfeitos += $l['total'];
                                }
                                ?>
                            </td>
                            <td class="text-end">
                                <?php
                                if ($t <> 0) {
                                    $soma = $soma + $l['total'];
                                    $tt = ($l['total']) / $t * 100;
                                    if ($tt > 100) {
                                        $tt = 100;
                                    }
                                } else {
                                    $tt = 0;
                                }
                                $se = $se + $tt;
                                echo number_format($tt, 1, ',', '.') . " %";

                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">Total de Eficiência</th>
                        <th class="text-end">




                        </th>
                    </tr>
                </tfoot>
            </table>
            <?php
        }
    }

    public function projetados($dia, $produto, $linha)
    {
        $sql = "SELECT tbprogramacao.quantidade as 'total' from tbprogramacao 
        INNER JOIN tblinha on tblinha.id=tbprogramacao.idlinha
        INNER JOIN tbproduto on tbproduto.id= tbprogramacao.idproduto
        WHERE tblinha.linha='$linha' and tbproduto.codigo='$produto' and tbprogramacao.dataprojeto='$dia'";
        $con = ConexaoMysql::getConnectionMysql();
        $sql = $con->prepare($sql);
        $sql->execute();
        while ($a = $sql->fetch(PDO::FETCH_ASSOC)) {
            $bb = $a['total'];
        }
        if (!isset($bb)) {
            $bb = 0;
        }
        return $bb;
    }

    public function RetornaTodosProdutos($conn)
    {

        $sql = "SELECT
            tbapontamento.idproduto as 'produto',
            tbproduto.descricao as 'descricao',
            tbproduto.codigo as 'codigo',
            COUNT(etiqueta) as 'total',
            tblinha.linha as 'linha'
            from tbapontamento 
            INNER JOIN tbproduto ON tbproduto.id=tbapontamento.idproduto
            INNER JOIN tblinha ON tblinha.id=tbapontamento.idlinha
            WHERE 
            diaapont='" . $_SESSION['pcpAPI']['diaapont'] . "'            
            GROUP BY tblinha.linha, tbapontamento.idproduto, tbproduto.descricao
            ORDER BY tblinha.linha asc, tbproduto.descricao ASC
            ;
         ";

        $sel = $conn->prepare($sql);
        $sel->execute();
        $numreg = $sel->rowCount();
        if ($numreg > 0) {
            $vldia = 0;
            ?>
            <table class="table table-dark table-bordered table-striped">
                <thead class="text-center">
                    <tr>
                        <th colspan="5">
                            <i class="bi bi-card-checklist"></i> DISCRIMINAÇÃO POR LINHA/PRODUTO
                        </th>
                    </tr>
                    <tr>
                    <tr>
                        <th></i>Linha</th>
                        <th colspan="2">Produto</th>
                        <th>Quantidade</th>
                        <th>Valor/Dia</th>
                    </tr>
                </thead>
                <tbody class="p-2">
                    <?php
                    while ($l = $sel->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <tr>
                            <td><small>
                                    <?php echo $l['linha']; ?>
                                </small></td>
                            <td><small>
                                    <?php echo $l['codigo']; ?>
                                </small></td>
                            <td><small class="text-nowrap" style="font-size: smaller;">
                                    <?php echo $l['descricao']; ?>
                                </small></td>
                            <td class="text-end"><small>
                                    <?php echo number_format($l['total'], 0, ',', '.'); ?>
                                </small></td>
                            <?php

                            $valor = $this->retornavalormedio($l['codigo']);

                            $vl = number_format($valor['valor'] * $l['total'], 2, ',', '.');
                            $vldia += ($valor['valor'] * $l['total']);
                            ?>
                            <td class="text-end"><small>
                                    <?php echo ("R$ " . $vl); ?>
                                </small></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4">Total</th>
                        <th class="text-end"><small>
                                <?php echo ("R$ " . number_format($vldia, 2, ',', '.')); ?>
                            </small></th>
                    </tr>
                </tfoot>
            </table>
            <?php
        }
    }

  
    public function EtiquetaCadastrada($etiqueta)
    {
        $conn = ConexaoMysql::getConnectionMysql();
        $conn2 = ConexaoIgor::getConnectionIgor();

        $sql1 = "SELECT * FROM etiquetas WHERE serie like '".$etiqueta."'";
        $sql1 = $conn2->prepare($sql1);
        $sql1->execute();
        $nr = $sql1->rowCount();
        if ($nr > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function RetornaTodasEtiquetas3($conn, $sql, $linha)
    {


        $sel = $conn->prepare($sql);
        $sel->execute();
        $numreg = $sel->rowCount();
        if ($numreg > 0) {
            ?>
            <table class="table table-dark table-bordered table-striped">
                <thead>
                    <tr class="text-center">
                        <th colspan="7">Apontamentos</th>
                    </tr>

                    <tr>
                        <th><i class="bi bi-minecart-loaded"></i>Linha</th>
                        <th><i class="bi bi-box-fillt"></i>Produto</th>
                        <th><i class="bi bi-box-fillt"></i>Descricao</th>
                        <th><i class="bi bi-ticket-detailed"></i>Etiqueta</th>
                        <th><i class="bi bi-alarm"></i>HORA</th>
                        <th>Cadastrada</th>
                        <th><i class="bi bi-gear-fill"></i>OPERAÇÃO</th>

                    </tr>
                </thead>
                <?php
                while ($l = $sel->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <tr>
                        <td>
                            <?php
                            $nlinha = $l['linha'];
                            echo $nlinha; ?>
                        </td>
                        <td>
                            <?php echo $l['produto']; ?>
                        </td>
                        <td>
                            <?php echo $l['descricao']; ?>
                        </td>
                        <td>
                            <?php echo $l['etiqueta']; ?>
                        </td>
                        <td class="text-end">
                            <?php echo $l['horaapont']; ?>
                        </td>
                        <td class="text-end">
                            <?php
                            $res = $this->EtiquetaCadastrada($l['etiqueta']);
                            if ($res === true) {
                                echo 'SIM';
                            } else {
                                echo 'NÃO';
                            }

                            ?>

                        </td>

                        <?php
                        $link = "../Control/apagaretiqueta.php?etiqueta=" . $l['etiqueta'];
                        $link = "onclick=mensagem('$link')";
                        $_SESSION['pcpAPI']['linha'] = $linha;



                        ?>

                        <td><button <?php echo $link; ?> class="btn btn-danger">Apaga Etiqueta</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <?php
        }
    }

    public function RetornaTodasEtiquetas5($conn, $sql)
    {


        $sel = $conn->prepare($sql);
        $sel->execute();
        $numreg = $sel->rowCount();
        if ($numreg > 0) {
            ?>
            <table class="table table-dark table-bordered table-striped">
                <thead>
                    <tr class="text-center">
                        <th colspan="6">Apontamentos</th>
                    </tr>

                    <tr>
                        <th><i class="bi bi-minecart-loaded"></i>Linha</th>
                        <th><i class="bi bi-box-fillt"></i>Produto</th>
                        <th><i class="bi bi-ticket-detailed"></i>Etiqueta</th>
                        <th><i class="bi bi-alarm"></i>HORA</th>
                        <th>Cadastrada</th>
                        <th><i class="bi bi-gear-fill"></i>OPERAÇÃO</th>

                    </tr>
                </thead>
                <?php
                while ($l = $sel->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <tr>
                        <td>
                            <?php
                            $nlinha = $l['linha'];
                            echo $nlinha; ?>
                        </td>
                        <td>
                            <?php echo $l['produto']; ?>
                        </td>
                        <td>
                            <?php echo $l['etiqueta']; ?>
                        </td>
                        <td class="text-end">
                            <?php echo $l['horaapont']; ?>
                        </td>
                        <td class="text-end">
                            <?php
                            $res = $this->verODF($l['etiqueta']);
                            if ($res === "") {
                                echo 'Sem cadastro.';
                            } else {
                                
                                echo 'ODF Cadastrada';
                            }

                            ?>

                        </td>

                        <?php
                        $link = "Control/apagaretiqueta3.php?etiqueta=" . $l['etiqueta'];
                        $link = "onclick=mensagem('$link')";



                        ?>

                        <td><button <?php echo $link; ?> class="btn btn-danger">Apaga Etiqueta</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <?php
        }
    }
    public function verODF($etiqueta){
    $conn2 = ConexaoIgor::getConnectionIgor();   
    $sql="SELECT odf as 'odf' from etiquetas where serie like '$etiqueta'";
    $sql=$conn2->prepare($sql);
    $sql->execute();
    if ($sql->rowCount()>0) {
        $odf= $sql->fetch(PDO::FETCH_ASSOC);
        $odf=$odf['odf'];
        return $odf;
        
    }else{
        return "";
    }
}
    public function RetornaTodasEtiquetas4($conn, $sql, $linha)
    {

        $q = $sql;

        $sel = $conn->prepare($sql);
        $sel->execute();
        $numreg = $sel->rowCount();
        if ($numreg > 0) {
            $id = 1;
            ?>
            <table class="table table-dark table-bordered table-striped">
                <thead>

                    <td>ID</td>
                    <th><i class="bi bi-minecart-loaded"></i>Linha</th>
                    <th><i class="bi bi-box-fillt"></i>Produto</th>
                    <th><i class="bi bi-ticket-detailed"></i>Etiqueta</th>
                    <th><i class="bi bi-ticket-detailed"></i>ODF</th>
                    <th><i class="bi bi-gear-fill"></i>OPERAÇÃO</th>
                    </tr>
                </thead>
                <?php
                while ($l = $sel->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <tr>
                        <td>
                            <?php
                            echo $id;
                            $id++;
                            ?>
                        </td>
                        <td>
                            <?php echo $l['linha']; ?>
                        </td>
                        <td>
                            <?php echo $l['produto']; ?>
                        </td>
                        <td>
                            <?php echo $l['etiqueta']; ?>
                        </td>
                        <td class="text-end">
                            <?php 
                            $odf=$this->verODF($l['etiqueta']);
                            echo $odf;
                            ?>
                        </td>
                        <!-- control/apagaretiqueta.php?etiqueta=<?php echo $l['etiqueta']; ?>  -->
                        <?php

                        $link = "../Control/apagaretiqueta.php?etiqueta=" . $l['etiqueta'];
                        $link = "onclick=mensagem('$link')";
                        $_SESSION['pcpAPI']['linha'] = $linha;
                        ?>
                        <td><button <?php echo $link; ?> class="btn btn-danger">Apaga Etiqueta</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <?php
            // echo $q;
        }
    }

    public function RetornaTOTALProdutos($conn, $dia)
    {
        $sel = "SELECT count(etiqueta) as 'total',tbapontamento.idproduto as 'produto', tbproduto.descricao as 'descricao' ,tbproduto.codigo as 'codigo' 
        FROM tbapontamento
        INNER JOIN tbproduto ON tbproduto.id = tbapontamento.idproduto
         WHERE diaapont='$dia' 
         GROUP BY tbapontamento.idproduto , tbproduto.codigo
         ORDER BY tbproduto.codigo ASC;";
        $sel = $conn->prepare($sel);
        $sel->execute();
        $nr = $sel->rowCount();
        if ($nr > 0) {
            $vldia = 0;
            ?>

            <table class="table table-dark table-striped table-bordered" style="width: max-content;">
                <thead class="text-center">
                    <tr>
                        <th colspan="4">
                            DISCRIMINAÇÃO POR PRODUTO
                        </th>
                    </tr>
                    <tr>

                        <th colspan="2">Produto</th>
                        <th>Quantidade</th>
                        <th>Valor/Dia</th>
                    </tr>
                </thead>
                <tbody class="p-2">
                    <?php
                    while ($l = $sel->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <tr>
                            <td><small>
                                    <?php echo ($l['codigo']); ?>
                                </small></td>
                            <?php
                            $valor = $this->retornavalormedio($l['codigo']);
                            $vl = number_format($valor['valor'] * $l['total'], 2, ',', '.');
                            $vldia += ($valor['valor'] * $l['total']);
                            ?>
                            <td><small>
                                    <?php echo ($l['descricao']); ?>
                                </small></td>
                            <td class="text-end"><small>
                                    <?php echo (number_format($l['total'], 0, ',', '.')); ?>
                                </small></td>
                            <td class="text-end"><small>
                                    <?php echo ("R$ " . $vl); ?>
                                </small></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">Total</th>
                        <th class="text-end"><small>
                                <?php echo ("R$ " . number_format($vldia, 2, ',', '.')); ?>
                            </small></th>
                    </tr>
                </tfoot>
            </table>

            <?php
        }
    }

    public function retornaProjetados($linha, $dia)
    {
        $sql = "SELECT tbprogramacao.quantidade as 'total',tbprogramacao.dataprojeto from tbprogramacao 
     INNER JOIN tblinha ON tblinha.id=tbprogramacao.idlinha
     WHERE tblinha.linha='$linha' and tbprogramacao.dataprojeto='$dia'";
        $con = ConexaoMysql::getConnectionMysql();
        $sql = $con->prepare($sql);
        $sql->execute();
        while ($l = $sql->fetch(PDO::FETCH_ASSOC)) {
            return $l['total'];
        }
    }


    public function retornavalormedio($codproduto)
    {
        $teste = array();
        $teste['valor'] = 0;
        $teste['custo'] = 0;
        $teste['lucro'] = 0;
        /* try {
            $conn = ConexaoKorp::getConnectionKorp();
            $q = "SELECT  
            TOTAL as 'valor'           
        FROM LISTA_PRECO
        where CODPCA = '$codproduto' and RECNO_LISTA_PRECO_CAB=85";

            $q = $conn->prepare($q);
            $q->execute();
            while ($k = $q->fetch(PDO::FETCH_ASSOC)) {
                $teste['valor'] = $k['valor'];

            }
        } catch (PDOException $th) {
            //
        } */
        if ($teste['valor'] == 0) {
            try {
                $conn = ConexaoMysql::getConnectionMysql();
                $q = "SELECT  
                valor as 'valor'           
            FROM tbproduto
            where codigo = '$codproduto' ";

                $q = $conn->prepare($q);
                $q->execute();
                while ($k = $q->fetch(PDO::FETCH_ASSOC)) {
                    $teste['valor'] = $k['valor'];

                }
            } catch (PDOException $th) {
                //
            }
        }
        return $teste;
    }

    public function atualizavalores()
    {
        try{
        $korp = ConexaoKorp::getConnectionKorp();
        $mysql = ConexaoMysql::getConnectionMysql();
        $sel = "SELECT codigo,codigobase from tbproduto";
        $sel = $mysql->prepare($sel);
        $sel->execute();
        while ($k = $sel->fetch(PDO::FETCH_ASSOC)) {
            $cod = $k['codigo'];
            $valor = $this->retornavalormedio($k['codigobase']);
            $vl = $valor['valor'];
            if ($vl <> 0) {
                $update = "update tbproduto set valor=$vl where codigo='$cod'";
                $update = $mysql->prepare($update);
                $update->execute();
            }

        }
    }catch(PDOException $e){
//
    }
}
}




?>