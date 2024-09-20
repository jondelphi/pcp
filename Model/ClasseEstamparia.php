<?php
class Pecas
{
    private $array;
    public function retornaKorp($sql)
    {
        try {
            $conn = ConexaoKorp::getConnectionKorp();
            $ret = $conn->prepare($sql);
            $ret->execute();
          
            return $ret;
        } catch (PDOException $e) {
        }
    }
    public function retornaMysql($sql)
    {
        $conn = ConexaoMysql::getConnectionMysql();
        $ret = $conn->prepare($sql);
        $ret->execute();
        return $ret;
    }

    public function maiorrevisao($cod)
    {
        $sql = "SELECT MAX(PROCESSO_KIT.REVISAO) as 'revisao'      
        FROM PROCESSO_KIT
        WHERE PROCESSO_KIT.NUMPEC = '$cod'  AND NUMPECPAI IS NOT NULL";
        $item = $this->retornaKorp($sql);
        $item = $item->fetch(PDO::FETCH_ASSOC);
        return $item['revisao'];
    }
    public function maiornivel($cod, $revisao)
    {
        $sql = "SELECT COUNT(distinct(PROCESSO_KIT.NIVEL)) as 'nivel'      
        FROM PROCESSO_KIT
        WHERE PROCESSO_KIT.NUMPEC = '$cod'  AND NUMPECPAI IS NOT NULL and PROCESSO_KIT.REVISAO=$revisao";
        $item = $this->retornaKorp($sql);
        $item = $item->fetch(PDO::FETCH_ASSOC);

        return $item['nivel'];
    }

    
    public function pecaskitsetor($cod, $qtd, $setor)
    {
        $revisao = $this->maiorrevisao($cod);
        $nivel = $this->maiornivel($cod, $revisao);

        $sql = "SELECT distinct(PROCESSO_KIT.NUMPECKIT) as 'codpeca'
        ,PROCESSO_KIT.NUMPEC as 'produto'
         ,PROCESSO_KIT.REVISAO  as 'revisao'  
         ,(PROCESSO_KIT.QTD * $qtd) as 'qtd'
         ,PROCESSO_KIT.NIVEL as 'nivel'
         ,ESTOQUE.DESCRI as 'descri'
     FROM [BRASLAR_26].[dbo].[PROCESSO_KIT]
     LEFT OUTER join ESTOQUE on ESTOQUE.CODIGO = PROCESSO_KIT.NUMPECKIT
     WHERE PROCESSO_KIT.NUMPEC = '$cod'  AND NUMPECPAI IS NOT NULL AND PROCESSO_KIT.REVISAO ='$revisao' 
     and ESTOQUE.DESCRI LIKE '$setor%' 
     order by  ESTOQUE.DESCRI ASC,PROCESSO_KIT.REVISAO DESC";
     //$this->mvar($sql);

        $item = $this->retornaKorp($sql);
        $item = $item->fetchAll(PDO::FETCH_ASSOC);

        return $item;
    }

    public function pegaprogramacao($dia)
    {
        $dia = new DateTime($dia);
        $sql = "SELECT
        SUM(tbprogramacao.quantidade) AS 'qtd',
        tbproduto.codigo as 'cod'
    FROM
        tbprogramacao
    INNER JOIN tbproduto ON tbproduto.id = tbprogramacao.idproduto
    WHERE
        tbprogramacao.dataprojeto = '" . $dia->format('Y-m-d') . "'
    GROUP BY
        tbproduto.codigo
    ORDER BY
        tbproduto.codigo ASC;";

        $item = $this->retornaMysql($sql);
        $item = $item->fetchAll(PDO::FETCH_ASSOC);
        return $item;
    }

    public function primeiraetapa($dia)
    {
        $dia = new DateTime($dia);
        $mdia = $dia->format('Y-m-d');
     
        $this->contapeca('CT', 'CORTE', $mdia);
        $this->contapeca('EST', 'ESTAMPARIA', $mdia);
        $this->contapeca('ESM', 'ESMALTAÇÃO', $mdia);
        $this->contapeca('PINT', 'PINTURA', $mdia);
    }

    public function mostraprogramados($setor, $arr)
    {
        usort($arr, function($a, $b) {
            return strcmp($a["descri"], $b["descri"]);
        });
        $dia= new DateTime($_SESSION['pcpAPI']['diaapont']);
?>

        <div class="container m-2 p-2">
            <table class="table table-light table-bordered m-2 p-2" style="width: fit-content;">
                <thead>
                    <tr>
                        <th colspan="5" class="text-center"><?php echo $setor ?> para <?php echo $dia->format('d/m/Y') ?></th>
                    </tr>
                    <tr>
                        <th>Codigo</th>
                        <th>Descrição</th>
                      
                        <th>Quantidade</th>
                        <th>Produtos</th>
                        <th style="padding:0px 40px;">Anotações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($arr as $k) {
                    ?>

                        <tr>
                            <td><?php echo $k['codpeca']; ?></td>
                            <td><?php echo $k['descri']; ?></td>
                            <td class="text-end"><?php echo number_format($k['qtd'], 0, ',', '.'); ?></td>
                            <td>
                                <?php
                                     $pro=$this->produtospeca($k['codpeca'],$dia->format('Y-m-d'));
                                     $td="";
                                     foreach ($pro as $x) {
                                       $td.=$x."<br>";
                                     }  
                                     echo $td;                 
                                ?>
                            </td>
                            <td></td>
                            
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <hr class="break">

<?php

    }
    //misturando as listas
    public function contapeca($abrv, $setor, $dia)
    {
        $pecassetor = array();
        //ITENS DO DIA NA PROGRAMAÇAO
        $programados = $this->pegaprogramacao($dia); //devolve cod,qtd
        //$this->mvar($programados);
        //percorre o programado e levanta a quantidade de peças do setor
        $i = 0;
        foreach ($programados as $k) {
            $arr = $this->pecaskitsetor($k['cod'], $k['qtd'], $abrv); //codpeca,produto,qtd(já multiplicada),descri
            foreach ($arr as $l) {
                $pecassetor[$i]['codpeca'] = $l['codpeca'];
                $pecassetor[$i]['produto'] = $l['produto'];
                $pecassetor[$i]['qtd'] = $l['qtd'];
                $pecassetor[$i]['descri'] = $l['descri'];
                $i++;
            }
        }

        $pecassetor = $this->removeDuplicates($pecassetor);
        $pecassetor = $this->somapecas($pecassetor);
        $this->mostraprogramados($setor,$pecassetor);
       
    }
      
    public function quantosprogramados($dia, $cod)
    {
        $sql = "SELECT
        SUM(tbprogramacao.quantidade) as 'qtd'
       FROM
           `tbprogramacao`
       INNER JOIN tbproduto ON tbprogramacao.idproduto = tbproduto.id    
       WHERE
           `dataprojeto` = '$dia' AND tbproduto.codigo = '$cod'
       GROUP BY tbproduto.codigo;";
        $item = $this->retornaMysql($sql);
        $q = $item->fetch(PDO::FETCH_ASSOC);

        return $q['qtd'];
    }
    public function quantospecas($pec, $cod)
    {
        $sql = "select top(1)
        PROCESSO_KIT.QTD as 'qtd'
        FROM PROCESSO_KIT
        WHERE PROCESSO_KIT.NUMPEC = '$cod'and PROCESSO_KIT.NUMPECKIT='$pec'
        ORDER BY PROCESSO_KIT.NIVEL DESC";
        $item = $this->retornaKorp($sql);
        $q = $item->fetch(PDO::FETCH_ASSOC);
        return $q['qtd'];
    }

    public function mvar($a)
    {
        echo ("<pre>");
        var_dump($a);
        echo ("</pre>");
    }
    public function removeDuplicates($arr)
    {
        $uniqueArray = array();
        $i = 0;
        $ultimopeca = "";
        $ultimoproduto = "";
        foreach ($arr as $r) {
            if ($r['produto'] <> $ultimoproduto || $r['codpeca'] <> $ultimopeca) {             
                $uniqueArray[$i]['codpeca'] = $r['codpeca'];
                $uniqueArray[$i]['produto'] = $r['produto'];
                $uniqueArray[$i]['qtd'] = $r['qtd'];
                $uniqueArray[$i]['descri'] = $r['descri'];
                $i++;                
            }
            $ultimopeca = $r['codpeca'];
            $ultimoproduto = $r['produto'];
        }

        return $uniqueArray;
    }
    public function removeDuplicatessoma($arr)
    {
        $uniqueArray = array();
        $i = 0;
        foreach ($arr as $r) {
            $dupla=false;
            if(count($uniqueArray)>0){
                foreach ($uniqueArray as $m) {
                    if($m['codpeca']==$r['codpeca']){
                        $dupla=true;
                    }
                }
            }
            if($dupla==false){
            $uniqueArray[$i]['codpeca'] = $r['codpeca'];
            $uniqueArray[$i]['qtd'] = $r['qtd'];
            $uniqueArray[$i]['descri'] = $r['descri'];
            $i++;
            }
        }

        return $uniqueArray;
    }
    public function produtospeca($peca,$dia)
    {
        $arr=array();
        $arr2=array();
        $items=$this->pegaprogramacao($dia);
        $i=0;
        foreach($items as $k){
            $arr[]=$k['cod'];
        }   
            $sql="select distinct(PROCESSO_KIT.NUMPEC) from PROCESSO_KIT where PROCESSO_KIT.NUMPECKIT='$peca'";
            $fogoes=$this->retornaKorp($sql);
            $fogoes=$fogoes->fetchAll(PDO::FETCH_ASSOC);
            foreach($fogoes as $k){
                $arr2[]=$k['NUMPEC'];
            }
            $inter=array_intersect($arr,$arr2);      
         return $inter;
    }

    public function somapecas($arr)
    {
        $uniqueArray = array();
        $i = 0;
        $ultimopeca = "";
        $qtd=0;
        foreach ($arr as $r) {
           $qtd=0;
            foreach ($arr as $m) {
                if ($m['codpeca']==$r['codpeca']) {
                    $qtd+=$m['qtd'];
                }
            }
            $uniqueArray[$i]['codpeca'] = $r['codpeca'];
            $uniqueArray[$i]['qtd'] = $qtd;
            $uniqueArray[$i]['descri'] = $r['descri'];
            $i++;                
        }
        $uniqueArray=$this->removeDuplicatessoma($uniqueArray);   
        return $uniqueArray;
    }

    public function processos($cod,$setor)
    {
        $sql="SELECT PROCESSO_KIT.NUMPECKIT as 'codpeca'
        ,PROCESSO_KIT.NUMPEC as 'produto'
         ,PROCESSO_KIT.REVISAO  as 'revisao'  
         ,(PROCESSO_KIT.QTD ) as 'qtd'
         ,PROCESSO_KIT.NIVEL as 'nivel'
         ,ESTOQUE.DESCRI as 'descri'
     FROM [BRASLAR_26].[dbo].[PROCESSO_KIT]
     LEFT OUTER join ESTOQUE on ESTOQUE.CODIGO = PROCESSO_KIT.NUMPECKIT
     WHERE PROCESSO_KIT.NUMPEC = '$cod'  AND NUMPECPAI IS NOT NULL 
	 AND PROCESSO_KIT.REVISAO in(select max(PROCESSO_KIT.REVISAO) from PROCESSO_KIT where PROCESSO_KIT.NUMPEC='$cod') 
     and ESTOQUE.DESCRI LIKE '$setor%' 
     order by  ESTOQUE.DESCRI ASC,PROCESSO_KIT.REVISAO DESC";
     $item=$this->retornaKorp($sql);
     $item=$item->fetchAll(PDO::FETCH_ASSOC);
     return $item;
    }

    public function mostraprocesso($cod,$setor)
    {
      
        $arr=$this->processos($cod,$setor);
        $arr=$this->removeDuplicatesProcesso($arr);
        usort($arr, function($a, $b) {
            return strcmp($a["descri"], $b["descri"]);
        });
        $dia= new DateTime($_SESSION['pcpAPI']['diaapont']);
?>

        <div class="container m-2 p-2">
            <table class="table table-light table-bordered m-2 p-2" style="width: fit-content;">
                <thead>
                    <tr>
                        <th colspan="4" class="text-center"><?php echo $setor ?> para <?php echo $cod ?></th>
                    </tr>
                    <tr>
                        <th>Codigo</th>
                        <th>Descrição</th>
                        <th>Quantidade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($arr as $k) {
                    ?>

                        <tr>
                            <td><?php echo $k['codpeca']; ?></td>
                           
                          
                            <td><?php echo $k['descri']; ?></td>
                            <td class="text-end"><?php echo number_format($k['qtd'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <hr class="break">

<?php

    }
    public function agrupaprocessos($cod)
    {
        $this->mostraprocesso($cod,'CT');
        $this->mostraprocesso($cod,'EST');
        $this->mostraprocesso($cod,'ESM');
        $this->mostraprocesso($cod,'PINT');
    }

    public function removeDuplicatesProcesso($arr)
    {
        $uniqueArray = array();
        $i = 0;
        $ultimopeca = "";
  
        foreach ($arr as $r) {
            if ( $r['codpeca'] <> $ultimopeca) {             
                $uniqueArray[$i]['codpeca'] = $r['codpeca'];
                $uniqueArray[$i]['qtd'] = $r['qtd'];
                $uniqueArray[$i]['descri'] = $r['descri'];
                $i++;                
            }
            $ultimopeca = $r['codpeca'];
  
        }

        return $uniqueArray;
    }
}
?>