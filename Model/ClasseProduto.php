<?php
class Produto
{
    public function quantoestoque($cod)
    {
        /* $conn = ConexaoKorp::getConnectionKorp();
        $sql = "SELECT
        SUM(ESTOQUE_LOCAL.QTDE) AS 'TOTAL'   
     FROM ESTOQUE_LOCAL
     WHERE RECNO_LOCAIS=1 and ESTOQUE_LOCAL.CODIGO = '$cod' ";
        $sel = $conn->prepare($sql);
        $sel->execute();
        while ($l = $sel->fetch(PDO::FETCH_ASSOC)) {
            return $l['TOTAL'];
        } */
        return 0;

    }
    public function CodigoProduto($prefixo,$conn){
        $sql="SELECT tbproduto.*,tbprefixo.prefixo as 'prefixo' FROM tbproduto 
        INNER JOIN tbprefixo ON tbproduto.id=tbprefixo.idproduto
        WHERE tbprefixo.prefixo='".$prefixo."'";
        $sel=$conn->prepare($sql);
        $sel->execute();
        $nr=$sel->rowCount();
        if ($nr>0) {
            while ($l =$sel->fetch(PDO::FETCH_ASSOC)) {
               $result=$l['codigo'];
            }
        } else {
            $result="Sem Cadastro";
        }
        return($result);
        
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
   

    public function montaoptioncategoria()
    {
        $conn = ConexaoMysql::getConnectionMysql();
        $sql = "SELECT * FROM tbcategoria ORDER BY id ASC";
        $sel = $conn->prepare($sql);
        $sel->execute();
        $nr = $sel->rowCount();
        $option = "";
        if ($nr > 0) {
            while ($l = $sel->fetch(PDO::FETCH_ASSOC)) {
                $option = $option . "<option value='" . $l['id'] . "'>" . $l['categoria'] . "</option>";
            }
            echo ($option);
        } else {
            $option = "<option value='Inexistente'>Inexistente</option>";
            echo ($option);
        }
    }
    public function montaoptioncategoriaEDT($id)
    {
        $conn = ConexaoMysql::getConnectionMysql();
        $sql = "SELECT * FROM tbcategoria ORDER BY id ASC";
        $sel = $conn->prepare($sql);
        $sel->execute();
        $nr = $sel->rowCount();
        $option = "";
        if ($nr > 0) {
            while ($l = $sel->fetch(PDO::FETCH_ASSOC)) {
                if ($l['id'] == $id) {
                    $option = $option . "<option value='" . $l['id'] . "' selected>" . $l['categoria'] . "</option>";
                } else {
                    $option = $option . "<option value='" . $l['id'] . "'>" . $l['categoria'] . "</option>";
                }
            }
            echo ($option);
        } else {
            $option = "<option value='Inexistente'>Inexistente</option>";
            echo ($option);
        }
    }
    public function TrazCodigo($codigo, $conn)
    {
        $sql = "SELECT * FROM tbproduto WHERE codigo='" . $codigo . "'";
        $sel = $conn->prepare($sql);
        $sel->execute();
        $nr = $sel->rowCount();
        if ($nr > 0) {
            while ($l = $sel->fetch(PDO::FETCH_ASSOC)) {
                $result = $l['id'];
            }
        } else {
            $result = false;
        }
        return ($result);
    }
    public function TrazID($codigo, $conn)
    {
        $sql = "SELECT * FROM tbproduto WHERE codigo='" . $codigo . "'";
        $sel = $conn->prepare($sql);
        $sel->execute();
        $nr = $sel->rowCount();
        if ($nr > 0) {
            while ($l = $sel->fetch(PDO::FETCH_ASSOC)) {
                $result = $l['id'];
            }
        } else {
            $result = false;
        }
        return ($result);
    }

    public function geraprimeiraetiqueta($e1, $qtd)
    {
        $qtd = $qtd - 1;
        $et = substr($e1, -5);
        $r = str_split($et);
        if ($r[4] > 0) {
            $fim = $et + $qtd;
        } elseif ($r[3] > 0) {
            $et = substr($e1, -4);
            $fim = $et + $qtd;
        } elseif ($r[2] > 0) {
            $et = substr($e1, -3);
            $fim = $et + $qtd;
        } elseif ($r[1] > 0) {
            $et = substr($e1, -2);
            $fim = $et + $qtd;
        } else {
            $et = substr($e1, -1);
            $fim = $et + $qtd;
        }
        $letras = strlen($fim);
        $tt = strlen($e1);


        $tt = $tt - $letras;
        echo $tt . "<br>";

        $etiqueta = substr($e1, 0, $tt);
        echo $etiqueta . "<br>";
        $etiqueta = "$etiqueta" . "$fim";

        return $etiqueta;
    }
    public function mostraprodutos()
    {
        $conn = ConexaoMysql::getConnectionMysql();
        $select = "SELECT * from tbproduto ORDER BY codigo";
        $select = $conn->prepare($select);
        $select->execute();
        ?>
        <table class="table table-sm table-bordered table-striped table-dark" style="width:fit-content">
            <thead>
                <tr>
                    <th>
                        Código
                    </th>
                    <th>
                        Descrição
                    </th>
                    <th>
                        Valor
                    </th>
                    <th>
                        Mínimo em estoque
                    </th>
                    <th>
                        Máximo em estoque
                    </th>
                    <th>
                        Estoque
                    </th>
                    <th>
                        Operação
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($k = $select->fetch(PDO::FETCH_ASSOC)) {
                    $estoque = $this->quantoestoque($k['codigo'])
                        ?>
                    <tr>
                        <td>
                            <small>
                                <?php echo $k['codigo']; ?>
                            </small>
                        </td>
                        <td class="text-wrap" style="max-width: 380px;">
                            <small>
                                <?php echo $k['descricao']; ?>
                            </small>
                        </td>
                        <td class="text-end">
                            <small>
                                <?php echo "R$ " . number_format($k['valor'], 2, ',', '.'); ?>
                            </small>
                        </td>
                        <td>
                            <form action="control/mudaminimo.php" method="post">
                                <div class="input-group input-group-sm">
                                    <input type="number" name="minimo" style="max-width:150px" value="<?php echo $k['minestoque']; ?>"
                                        class="input-group-text" required>
                                    <input type="hidden" name="idproduto" value="<?php echo $k['id']; ?>">
                                    <input type="submit" value="OK" class="input-group-text btn btn-success">
                                </div>
                            </form>
                        </td>
                        <td>
                            <form action="control/mudamaximo.php" method="post">
                                <div class="input-group input-group-sm">
                                    <input type="number" style="max-width:150px"  name="maximo" value="<?php echo $k['maxestoque']; ?>"
                                        class="input-group-text" required>
                                    <input type="hidden" name="idproduto" value="<?php echo $k['id']; ?>">
                                    <input type="submit" value="OK" class="input-group-text btn btn-success">
                                </div>
                            </form>
                        </td>
                        <td class="text-end">
                            <small>
                                <?php echo number_format($estoque, 0, ',', '.'); ?>
                            </small>
                        </td>
                        <td>
                            <a href="Control/editaproduto.php?id=<?php echo $k['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
        <?php
    }

    public function devolveintervalo($hora)
        {
        
            switch ($hora) {
                case $hora < '07:30:00':
                    return 0;
                    break;
                case $hora < '08:30:00':
                    return 1;
                    break;
                case $hora < '09:30:00':
                    return 2;
                    break;
                case $hora < '10:30':
                    return 3;
                    break;
                case $hora < '11:30':
                    return 4;
                    break;
                case $hora < '12:30':
                    return 5;
                    break;
                case $hora < '13:30':
                    return 6;
                    break;
                case $hora < '14:30':
                    return 7;
                    break;
                case $hora < '15:30':
                    return 8;
                    break;
                case $hora < '16:30':
                    return 9;
                    break;
                case $hora < '17:30':
                    return 10;
                    break;
                case $hora < '18:30':
                    return 11;
                    break;
                case $hora >= '18:30':
                    return 12;
                    break;
            }
        }
}