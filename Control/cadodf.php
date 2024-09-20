<?php
session_start();
require('../DAO/config.php');

require('../Model/ClasseLinha.php');
require('../Model/ClasseODF.php');
require('../Model/ClasseProduto.php');
$conn=ConexaoMysql::getConnectionMysql();

$linha=new Linha;
$produto=new Produto;
$classODF=new ODF;
$dia=new DateTime($_SESSION['pcpAPI']['diaapont']);


if ($_POST) {
    
    $idlinha=$_POST['linha'];
   // $odf=$classODF->proxODF($conn);
    
    $odf=$_POST['odf'];
    $odf=intval($odf);

    if($odf==1){
        $odf=$classODF->proxODF($conn); 
    }

    
    $semetiqueta=false;
 
    $idproduto=$_POST['produto'];
    if(strlen($idproduto)==5){
        $idproduto="100".$idproduto;
    }
    $quantidade=$_POST['quantidade'];
    if(isset($_POST['etiqueta2'])){
       
        if(strlen($_POST['etiqueta2'])>13){
           
            $etiqueta2=$produto->geraprimeiraetiqueta($_POST['etiqueta2'],$quantidade);
            $etiqueta1=$_POST['etiqueta2'];
        }else{
           
            $semetiqueta=true;
        }
    }else{
        $semetiqueta=true;
    }
    if($semetiqueta==true){
        $etiqueta1='';
        $etiqueta2='';
        
        try{
            
        $etiqueta1=$classODF->MenorEtiquetaCadastrada($odf);

        $etiqueta2=$classODF->MaiorEtiquetaCadastrada($odf);
        }catch(Exception){
            $etiqueta1='';
        $etiqueta2='';
        }

    }
  
    $diaodf=$dia->format('Y-m-d');
    $idproduto=$produto->TrazCodigo($idproduto,$conn);
    if ($idproduto!==false) {
        $sql="INSERT INTO tbpcp(odf,idlinha,idproduto,dataentregue,quantidade,etiquetaini,etiquetafim)";
        $sql.="VALUES($odf,$idlinha,$idproduto,'$diaodf',$quantidade,'$etiqueta1','$etiqueta2')";

        try
        {
            $cad=$conn->prepare($sql);
            $cad->execute();
            $_SESSION['pcpAPI']['erro']=true;
            $_SESSION['pcpAPI']['mensagem']="Cadastrado com sucesso";

        }catch(PDOException $e){
            $_SESSION['pcpAPI']['erro']=true;
            $_SESSION['pcpAPI']['mensagem']="Não foi possivel cadastrar essa ODF, \n verifique todos os campos e tente novamente";
        }        
    }else{
        $_SESSION['pcpAPI']['erro']=true;
        $_SESSION['pcpAPI']['mensagem']="Não existe cadastro desse produto";
    }
}

header('location:../index.php');
?>