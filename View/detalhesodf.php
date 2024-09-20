<?php
$conn=ConexaoMysql::getConnectionMysql();
$produto=new Produto;
//trazer todos os dados da id
$sel="SELECT tbpcp.*,tbproduto.codigo AS 'codigo',tblinha.linha as 'descrilinha' FROM tbpcp 
INNER JOIN tbproduto ON tbproduto.id = tbpcp.idproduto 
INNER JOIN tblinha ON tblinha.id = tbpcp.idlinha 
WHERE tbpcp.id=".$_SESSION['pcpAPI']['idatual'];
$sel=$conn->prepare($sel);
$sel->execute();
if ($sel->rowCount()>0) {
    while($l=$sel->fetch(PDO::FETCH_ASSOC))
    {
        $id=$l['id'];
        $odf=$l['odf'];
        $idproduto=$l['produto'];
        $descriproduto=$l['codigo'];
        $idlinha=$l['linha'];
        $linha=$l['descrilinha'];
        $e1=$l['etiquetaini'];
        $e2=$l['etiquetafim'];
        $dia=$l['dataentregue'];
    }
}
if(isset($e1)&&isset($e2))
{
    if(strlen($e1)>1&&strlen($e2)>1)
    {
     $sql="SELECT * FROM tbapontamento WHERE diaapont='$dia' and etiqueta BETWEEN '$e1' AND '$e2'";
    }
}
?>