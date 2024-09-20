<?php
session_start();
$tipo=$_POST['tipo'];
$busca=$_POST['busca'];
$busca=trim($busca);

if($tipo=='linha')
{
    $_SESSION['pcpAPI']['sqlODF']="SELECT tbpcp.*,tbproduto.codigo as 'codigo', tbproduto.descricao as 'descri',tblinha.linha as 'linhamontagem'
        FROM tbpcp 
        INNER JOIN tbproduto ON tbproduto.id = tbpcp.idproduto
        INNER JOIN tblinha ON tblinha.id = tbpcp.idlinha
        WHERE tbpcp.dataentregue='" . $_SESSION['pcpAPI']['diaapont'] . "' and tblinha.linha LiKE '%$busca%' 
        ORDER BY tblinha.linha ASC, tbproduto.codigo ASC ";


    // $_SESSION['pcpAPI']['sqlODF']="SELECT tbpcp.*,tbproduto.codigo as 'codigo', tbproduto.descricao as 'descri',tblinha.linha as 'linhamontagem'
    // FROM tbpcp 
    // INNER JOIN tbproduto ON tbproduto.id = tbpcp.idproduto
    // INNER JOIN tblinha ON tblinha.id = tbpcp.idlinha
    // WHERE tbpcp.dataentregue='". $_SESSION['pcpAPI']['diaapont'] ."' AND (tblinha.linha LIKE '%$busca%')
    // ORDER BY linha ASC, tbproduto.codigo ASC ";
     $_SESSION['pcpAPI']['busca']=true;

}

if($tipo=='produto')
{
    $_SESSION['pcpAPI']['sqlODF']="SELECT tbpcp.*,tbproduto.codigo as 'codigo', tbproduto.descricao as 'descri',tblinha.linha as 'linhamontagem'
    FROM tbpcp 
    INNER JOIN tbproduto ON tbproduto.id = tbpcp.idproduto
    INNER JOIN tblinha ON tblinha.id = tbpcp.idlinha
    WHERE tbpcp.dataentregue='" . $_SESSION['pcpAPI']['diaapont'] . "' AND (tbproduto.descricao LIKE '%$busca%' OR tbproduto.codigo='$busca')
    ORDER BY tblinha.linha ASC, tbproduto.codigo ASC ";
    $_SESSION['pcpAPI']['busca']=true;

}
header('location:../index.php')



?>