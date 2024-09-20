<?php
session_start();
require('../DAO/config.php');

require('../Model/ClasseLinha.php');
require('../Model/ClasseODF.php');
require('../Model/ClasseProduto.php');
require('../Model/ClasseProgramacao.php');
$conn=ConexaoMysql::getConnectionMysql();

$linha=new Linha;
$produto=new Produto;
$classODF=new ODF;
$classPrograma=new Programacao;
$dia=new DateTime($_SESSION['pcpAPI']['diaapont']);


if ($_POST) {
    $idlinha=$_POST['linha'];
    
    $idproduto=$_POST['produto'];
    if(strlen($idproduto)==5){
        $idproduto="100".$idproduto;
    }
    $quantidade=$_POST['quantidade'];
    $horainicio=$_POST['horainicio'];
    $horafim=$_POST['horafim'];
    if(!isset($_POST['obs'])){
        $obs='';
    }else{
        $obs=$_POST['obs'];
    }
  
    $diaprogramacao=$dia->format('Y-m-d');
    $idproduto=$classPrograma->TrazIdProduto($idproduto);
    $desclinha=$classPrograma->TrazLinha($idlinha);
    if ($idproduto!==false) {
        $sql="INSERT INTO tbprogramacao(idlinha,idproduto,quantidade,dataprojeto,obs,horainicio,horafim)";
        $sql.="VALUES($idlinha,$idproduto,$quantidade,'$diaprogramacao','$obs','$horainicio','$horafim')";
        echo $sql;
        try
        {
            $cad=$conn->prepare($sql);
            $cad->execute();
            $_SESSION['pcpAPI']['erro']=false;
            $_SESSION['pcpAPI']['mensagem']="Cadastrado com sucesso";
            $_SESSION['pcpAPI']['mensagem']="Cadastrado com sucesso";
            $_SESSION['pcpAPI']['linha']=$idlinha;

        }catch(PDOException $e){
            $_SESSION['pcpAPI']['erro']=true;
            $_SESSION['pcpAPI']['mensagem']="Não foi possivel cadastrar essa Programação, \n verifique todos os campos e tente novamente";
        }        
    }else{
        $_SESSION['pcpAPI']['erro']=true;
        $_SESSION['pcpAPI']['mensagem']="Não existe cadastro desse produto";
    }
}

header('location:../index.php');
?>