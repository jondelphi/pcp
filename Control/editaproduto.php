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
    $id=$_GET['id'];
    $sql="SELECT 
    tbproduto.id as 'idproduto', 
    tbprefixo.prefixo as 'prefixo', 
    tbproduto.idcategoria as 'idcategoria', 
    tbproduto.codigo as 'produto', 
    tbproduto.descricao as 'descricao', 
    tbproduto.valor as 'valor', 
    tbproduto.codigobase as 'codigobase', 
    tbcategoria.categoria as 'categoria'
    FROM tbproduto 
    INNER JOIN tbprefixo ON tbproduto.id = tbprefixo.idproduto
    INNER JOIN tbcategoria ON tbcategoria.id = tbproduto.idcategoria
    WHERE tbproduto.id=$id";
    echo $sql;
   
    try {
       $sql=$conn->prepare($sql);
       $sql->execute();
       if ($sql->rowCount()>0) {
        while($l=$sql->fetch(PDO::FETCH_ASSOC)){
            $_SESSION['pcpAPI']['produto']=$l['produto'];
            $_SESSION['pcpAPI']['idproduto']=$l['idproduto'];
            $_SESSION['pcpAPI']['descricao']=$l['descricao'];         
            $_SESSION['pcpAPI']['valor']=$l['valor'];
            $_SESSION['pcpAPI']['codigobase']=$l['codigobase'];
            $_SESSION['pcpAPI']['idcategoria']=$l['idcategoria'];
            $_SESSION['pcpAPI']['categoria']=$l['categoria'];
            $_SESSION['pcpAPI']['prefixo']=$l['prefixo'];
            
        }
        $_SESSION['pcpAPI']['pagina']='View/editaproduto.php';
      header('location:../index.php');
       }
    } catch (PDOException $e) {
        $_SESSION['pcpAPI']['erro']=true;
        $_SESSION['pcpAPI']['mensagem']="Não foi possível editar o produto";
      header('location:../index.php');
    }
    
}

?>