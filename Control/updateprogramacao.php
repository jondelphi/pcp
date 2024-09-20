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
$dia=new DateTime($_SESSION['programacao']['dataprojeto']);


if ($_POST) {
    $idlinha=$_POST['linha'];
    $idproduto=$_POST['produto'];
    $quantidade=$_POST['quantidade'];
    $hi=$_POST['horainicio'];
    $hf=$_POST['horafim'];
    if(!isset($_POST['obs'])){
        $obs='';
    }else{
        $obs=$_POST['obs'];
    }
    $idprogramacao=$_POST['idprogramacao'];
    $diaprogramacao=$dia->format('Y-m-d');
    $idproduto=$classPrograma->TrazIdProduto($idproduto);
    $desclinha=$classPrograma->TrazLinha($idlinha);
    if ($idproduto!==false) {
        $sql="UPDATE tbprogramacao SET  idlinha=$idlinha,idproduto=$idproduto,quantidade=$quantidade,dataprojeto='$diaprogramacao',obs='$obs',horainicio='$hi',horafim='$hf' WHERE id=$idprogramacao";
      
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
unset($_SESSION['programacao']);
$_SESSION['pcpAPI']['pagina']='View/programacao.php';
$_SESSION['pcpAPI']['titulo']='Braslar- PCP - Programação';
unset($_SESSION['pcpAPI']['linha']);

header('location:../index.php');
?>