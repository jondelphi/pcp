<?php
session_start();
require('../DAO/config.php');

require('../DAO/configapontamento.php');
require('../Model/ClasseLinha.php');
require('../Model/ClasseODF.php');
require('../Model/ClasseProduto.php');

function devolveODF($etiqueta){
    $consql=ConexaoKorp::getConnectionKorp();
    $sql="SELECT DISTINCT(ODF) as 'odf' FROM ETIQUETA_SERIE_PRODUCAO WHERE ETIQUETA='$etiqueta'";
    $sql=$consql->prepare($sql);  
    $sql->execute();
   
        while ($k=$sql->fetch(PDO::FETCH_ASSOC)) {

           return $k['odf'];
        
    }
}

function quantidadeODF($odf){
    $consql=ConexaoKorp::getConnectionKorp();
    $sql="SELECT COUNT(etiqueta) as 'qtd' FROM ETIQUETA_SERIE_PRODUCAO WHERE ODF='$odf'";
    $sql=$consql->prepare($sql);  
    $sql->execute();
   
        while ($k=$sql->fetch(PDO::FETCH_ASSOC)) {

           return $k['qtd'];
        
    }
}

function devolveproduto($etiqueta){
    $prefixo=substr($etiqueta,0,8);
    $conaponta=ConexaoMysql::getConnectionMysql();
    $sql="SELECT codigo FROM tbproduto
    INNER JOIN tbprefixo ON tbprefixo.idproduto=tbproduto.id
     where tbprefixo.prefixo LIKE '%$prefixo%'";
    $sql=$conaponta->prepare($sql);  
    $sql->execute();
    if($sql->rowCount()>0){
        while ($k=$sql->fetch(PDO::FETCH_ASSOC)) {
           return $k['codigo'];
        }
    }
}

function temcadastro($odf){

    $conmysql=ConexaoMysql::getConnectionMysql();
    $dia=$_SESSION['pcpAPI']['diaapont'];
    $teste=false;
    $sql="SELECT * FROM tbpcp where odf='$odf' AND dataentregue ='$dia' ";
    try{
    $sql=$conmysql->prepare($sql);  
    $sql->execute();
    if($sql->rowCount()>0){       
           $teste= true;        
    }
}catch(PDOException $e){

}
return $teste;
}



$linha = new Linha;
$Classproduto = new Produto;
$classODF = new ODF;
$dia = new DateTime($_SESSION['pcpAPI']['diaapont']);
$diaodf = $dia->format('Y-m-d');
$conapont=ConexaoMysql::getConnectionMysql();
$conmysql=ConexaoMysql::getConnectionMysql();

 $sel="SELECT * from tbapontamento WHERE diaapont='$diaodf'";
 $sel=$conapont->prepare($sel);
 $sel->execute();
 if($sel->rowCount()>0){
    while($k=$sel->fetch(PDO::FETCH_ASSOC)){
        $numodf=devolveodf($k['etiqueta']);
        $produto=devolveproduto($k['etiqueta']);
        $idproduto = $Classproduto->TrazCodigo($produto, $conmysql);
        $linhaatual=$k['linha'];
        $etiqueta1 = $classODF->MenorEtiquetaCadastrada($numodf);
        $etiqueta2 = $classODF->MaiorEtiquetaCadastrada($numodf);
      
        $idlinha=$linha->trazidlinha($linhaatual);
        $qtd=quantidadeODF($numodf);
        $etiqueta1=$classODF->MenorEtiquetaCadastrada($numodf);
        $etiqueta2=$classODF->MaiorEtiquetaCadastrada($numodf);
        $cadastra=temcadastro($numodf);
       // $cadastra=false;
        if ($cadastra==false) {
 try{
        $sql="INSERT INTO tbpcp(odf,linha,produto,dataentregue,quantidade,etiquetaini,etiquetafim)";
        $sql.="VALUES($numodf,$idlinha,$idproduto,'$diaodf',$qtd,'$etiqueta1','$etiqueta2')";
       $sql=$conmysql->prepare($sql);
       $sql->execute();
       //echo $sql."<br>";
 }catch(PDOException $e){
    //
 }
        }
    }//fim das etiquetas
 }//fim do if apont etiquetas


       
    
