<?php 
date_default_timezone_set('America/Sao_Paulo');
class Relatorio
{
    public function retorna($sql)
    {
        $con= ConexaoMysql::getConnectionMysql();
        $consulta= $con->prepare($sql);
        $consulta->execute();
        return $consulta;
    }

    public function dump($var)
    {
        echo ("<pre>");
        var_dump($var);
        echo ("</pre>");
    }
    public function producaomes($mes)
    {
       $sql="SELECT COUNT(etiqueta) as total from tbapontamento where
       MONTH(tbapontamento.diaapont) = $mes and YEAR(tbapontamento.diaapont)='2023';";
       $tudo=$this->retorna($sql);
       $tudo=$tudo->fetch(PDO::FETCH_ASSOC);
       return $tudo['total'];
    }
    
    public function mediamesporlinha(){
        $linhas=$this->listalinhas();
        ?>
<table class="table table-light table-striped table-bordered" style="width:fit-content">
    <thead>
        <tr>
            <th>
                Mês
            </th>
            <th>Linha</th>
            <th>Total Produzido</th>
            <th>Dias Produzido</th>
            <th>Média/Dia</th>
        </tr>
    </thead>
    <tbody>
        <?php
        for ($i=1; $i <= 12; $i++) { 
           $extenso=$this->mesextenso($i);
           $producao=$this->producaomes($i);
    
           if ($producao>0) {
                    foreach ($linhas as $k) {
                        $produtosdalinha=$this->quantofez($i,$k['linha']);

                        $diastrabalhados=$this->contadiastrabalhadosdalinha($i,$k['linha']);
                        if ($produtosdalinha>0) {
                            ?>
                <tr>
                    <td>
                        <?php 
                            echo $extenso;
                            ?>
                    </td>
                    <td>
                        <?php echo $k['linha'];?>
                    </td>
                    <td class="text-end">
                        <?php echo(number_format($produtosdalinha,0,',','.'));?>
                    </td>
                    <td class="text-end">
                        <?php echo(number_format($diastrabalhados,0,',','.'));?>
                    </td>
                    <td class="text-end">
                        <?php echo(number_format($produtosdalinha/$diastrabalhados,0,',','.'));?>
                    </td>
                </tr>
                <?php
                        }
                    }                  
           }
        }
        ?>
        </tbody>
    </table>
    <?php
    }

    public function contadiastrabalhadosdalinha($mes,$linha){
        $sql="SELECT
        COUNT(DISTINCT(DAY(diaapont))) AS total
    FROM
        tbapontamento
    INNER JOIN tblinha ON tblinha.id = tbapontamento.idlinha
    WHERE
        MONTH(tbapontamento.diaapont) = $mes AND linha = '$linha' and YEAR(tbapontamento.diaapont)='2023'"; 
        $dias=$this->retorna($sql);
        $dias= $dias->fetch(PDO::FETCH_ASSOC);
        return $dias['total'];
    }

    public function listalinhas(){
        $sql="select linha from tblinha order by linha";
        $linhas=$this->retorna($sql);
        $linhas=$linhas->fetchAll(PDO::FETCH_ASSOC);
        return $linhas;
    }
    public function quantofez($mes, $linha){
        $sql="select count(etiqueta) as total 
        from tbapontamento
        inner join tblinha on tblinha.id = tbapontamento.idlinha
        where MONTH(diaapont)=$mes and tblinha.linha = '$linha' and YEAR(tbapontamento.diaapont)='2023';";
        $tudo=$this->retorna($sql);
        $tudo=$tudo->fetch(PDO::FETCH_ASSOC);
        return $tudo['total'];
    }
    public function mesextenso($mes){
        switch ($mes) {
            case 1:
                return 'Janeiro';
            case 2:
                return 'Fevereiro';
            case 3:
                return 'Março';
            case 4:
                return 'Abril';
            case 5:
                return 'Maio';
            case 6:
                return 'Junho';
             case 7:
                return 'Julho';
            case 8:
                return 'Agosto';
            case 9:
                return 'Setembro';
            case 10:
                return 'Outubro';
            case 11:
                return 'Novembro';
            case 12:
                return 'Dezembro';
            
        }
    }
    
    
}
?>