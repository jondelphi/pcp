<?php
session_start();
require('../DAO/config.php');
require('../Model/ClasseLinha.php');
require('../Model/ClasseProduto.php');
$conn=ConexaoMysql::getConnectionMysql();
$linha=new Linha;
$produto=new Produto;
$dia=new DateTime($_SESSION['pcpAPI']['diaapont']);
$diaodf=$dia->format('Y-m-d');


if ($_GET) {
    $idodf=$_GET['id'];
    $sql="SELECT tbpcp.*, tbproduto.codigo as 'produto', tbproduto.descricao as 'descricao', tblinha.linha as 'linha' FROM tbpcp 
    INNER JOIN tbproduto ON tbproduto.id = tbpcp.idproduto
    INNER JOIN tblinha ON tblinha.id = tbpcp.idlinha
    WHERE tbpcp.id=$idodf";
   
    try {
       $sql=$conn->prepare($sql);
       $sql->execute();
       if ($sql->rowCount()>0) {
        while($l=$sql->fetch(PDO::FETCH_ASSOC)){
            $_SESSION['pcpAPI']['odf']=$l['odf'];
            $_SESSION['pcpAPI']['linha']=$l['linha'];
            $_SESSION['pcpAPI']['idlinha']=$l['idlinha'];
         
            $_SESSION['pcpAPI']['produto']=$l['produto'];
            $_SESSION['pcpAPI']['dataentregue']=$l['dataentregue'];
            $_SESSION['pcpAPI']['quantidade']=$l['quantidade'];
            $_SESSION['pcpAPI']['e1']=$l['etiquetaini'];
            $_SESSION['pcpAPI']['e2']=$l['etiquetafim'];
            $_SESSION['pcpAPI']['idatual']=$l['id'];
        }
        $_SESSION['pcpAPI']['pagina']='View/editaodf.php';
      header('location:../index.php');
       }
    } catch (PDOException $e) {
        $_SESSION['pcpAPI']['erro']=true;
        $_SESSION['pcpAPI']['mensagem']="Não foi possível editar ODF";
      header('location:../index.php');
    }
    
}

?>