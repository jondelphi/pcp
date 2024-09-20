<?php
session_start();
require('../DAO/config.php');
require('../Model/ClasseLinha.php');
require('../Model/ClasseProduto.php');
require('../Model/ClasseODF.php');
$classeodf=new ODF;
$conn=ConexaoMysql::getConnectionMysql();
$linha=new Linha;
$produto=new Produto;
$dia=new DateTime($_SESSION['pcpAPI']['diaapont']);
$id=$_SESSION['pcpAPI']['idatual'];
if ($_POST) {
    $idlinha=$_POST['linha'];
   /*  $odf=$_POST['odf']; */
    $_POST['linha'];
    $idproduto=$_POST['produto'];
    $idproduto=$_POST['produto'];
    if(strlen($idproduto)==5){
        $idproduto="100".$idproduto;
    }
    $quantidade=$_POST['quantidade'];
    $etiqueta1=$_POST['etiqueta1'];
    $etiqueta2=$_POST['etiqueta2'];
    $diaodf=$dia->format('Y-m-d');
    $idproduto=$produto->TrazCodigo($idproduto,$conn);
    if ($idproduto!==false) {
        $sql="UPDATE tbpcp SET idlinha=$idlinha,idproduto=$idproduto,quantidade=$quantidade,etiquetaini='$etiqueta1',etiquetafim='$etiqueta2' WHERE id=$id";

        try
        {
            $cad=$conn->prepare($sql);
            $cad->execute();
            $_SESSION['pcpAPI']['erro']=true;
            $_SESSION['pcpAPI']['mensagem']="Editado com sucesso";

        }catch(PDOException $e){
            $_SESSION['pcpAPI']['erro']=true;
            $_SESSION['pcpAPI']['mensagem']="Não foi possivel editar essa ODF, \n verifique todos os campos e tente novamente";
        }        
    }else{
        $_SESSION['pcpAPI']['erro']=true;
        $_SESSION['pcpAPI']['mensagem']="Não existe cadastro desse produto";
    }
}
$_SESSION['pcpAPI']['pagina']='View/odf.php';
header('location:../index.php');
?>