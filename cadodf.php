<?php
session_start();
set_time_limit(0);
require('DAO/config.php');
require('DAO/configIgor.php');
require('Model/ClasseRequest.php');
require('Model/ClasseLinha.php');
require('Model/ClasseODF.php');
require('Model/ClasseProduto.php');

if (!isset($_SESSION['pcpAPI']['diaapont'])) {
    $n = new DateTime('now');
    $_SESSION['pcpAPI']['diaapont'] = $n->format('Y-m-d');
}

try {
    $conmysql = ConexaoMysql::getConnectionMysql();
    $conIgor = ConexaoIgor::getConnectionIgor();
} catch (PDOException $e) {
    //
    exit;
}


function buscaODFIGOR($etiqueta)
{
    global $conIgor;
    global $conmysql;
    $req = new ClasseRequest;
    $array = array();
    $dia = $_SESSION['pcpAPI']['diaapont'];
    try {
        $sql = "select odf from etiquetas where serie like '$etiqueta'";
        $sql = $conIgor->prepare($sql);
        $sql->execute();
        if ($sql->rowCount() == 0) {
            $req->etiqueta($etiqueta);
            $sql = "select odf from etiquetas where serie like '$etiqueta'";
            $sql = $conIgor->prepare($sql);
            $sql->execute();
        }
        if ($sql->rowCount() <> 0) {
            $odf = $sql->fetch(PDO::FETCH_ASSOC);
            return $odf['odf'];
        } else {
            return $odf['odf'] = 0;
        }
    } catch (PDOException $e) {

        $req->etiqueta($etiqueta);
        return $odf['odf'] = 0;
    }
}

function buscaDADOSODFIGOR($odf)
{
    global $conIgor;
    global $conmysql;
    $array = array();
    $dia = $_SESSION['pcpAPI']['diaapont'];
    try {
        $sql = "select * from odfs where odf = '$odf'";
        $sql = $conIgor->prepare($sql);
        $sql->execute();
        if ($sql->rowCount() == 0) {
            $req = new ClasseRequest;
            $req->dadosodf($odf);
            $sql = "select * from odfs where odf = '$odf'";
            $sql = $conIgor->prepare($sql);
            $sql->execute();
        } else {
        }
        $odf = $sql->fetch(PDO::FETCH_ASSOC);
        return $odf;
    } catch (PDOException $e) {
        $req = new ClasseRequest;
        $req->dadosodf($odf);
        return $arr[] = 0;
    }
}
function dump($var)
{
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
}

function buscaapontadasantes($odf)
{
    global $conIgor;
    $dia = $_SESSION['pcpAPI']['diaapont'];
    try {
        $sql = "select count(serie) as total from etiquetas where odf = '$odf' and date(data_apontamento<'$dia' ";
        $sql = $conIgor->prepare($sql);
        $sql->execute();
        $item=$sql->fetch(PDO::FETCH_ASSOC);
        $item=$item['total'];
        
        return $item;

    }catch(PDOException $e){
      return 0;
    }
}
function buscaODFPCP($odf, $etiqueta)
{
    global $conIgor;
    global $conmysql;
    $array = array();
    $dia = $_SESSION['pcpAPI']['diaapont'];
    try {
        $sql = "select distinct(odf) from tbpcp where odf = '$odf' and dataentregue='$dia' ";
        $sql = $conmysql->prepare($sql);
        $sql->execute();
        $conta = $sql->rowCount();
        if ($conta <> 0) {
            //odf nõ está cadastrada, vamos cadastrar
            //pego dados odf primeiro   para gravar no banco

            $query = "SELECT * FROM odfs WHERE odf='$odf'";
            // echo $query."<br>";
            $query = $conIgor->prepare($query);
            $query->execute();
            $dados = $query->fetchAll(PDO::FETCH_ASSOC);
            //var_dump($dados);
            //agora tenho saber em qual linha ela foi bipada
            $sql2 = "SELECT idlinha,idproduto from tbapontamento where etiqueta='$etiqueta'";
            $sql2 = $conmysql->prepare($sql2);
            $sql2->execute();
            $dados2 = $sql2->fetch(PDO::FETCH_ASSOC);
            //var_dump($dados);
            $arr = array_merge_recursive($dados, $dados2);
            // print_r($arr);
            //Array ( [0] => Array ( [id] => 18 [odf] => 99138 [quantidade_pedido] => 100 [quantidade_entrada] => 71 ) [idlinha] => 1 [idproduto] => 112 )
            $linha = $arr[0]['idlinha'];
            $produto = $arr[0]['idproduto'];
            $quantidade = $arr[0]['quantidade_pedido'] - $arr[0]['quantidade_entrada'];
            $insere = "INSERT INTO tbpcp(odf,idlinha,idproduto,dataentregue,quantidade)VALUES($odf,$linha,$produto,$quantidade)";
            $insere = $conmysql->prepare($insere);
            $insere->execute();
        }
    } catch (PDOException $e) {
        return $arr[0]['id'] = 0;
    }
}
function insereodfpcp($odf,$idlinha,$idproduto,$quantidade){
    global $conmysql;
    $dia = $_SESSION['pcpAPI']['diaapont'];
    try{
        $sql="INSERT INTO tbpcp(odf,idlinha,idproduto,dataentregue,quantidade)VALUES($odf,$idlinha,$idproduto,'$dia',$quantidade)";
    $sql = $conmysql->prepare($sql);
    $sql->execute();
    }catch(PDOException $e){
//
    }
    
}
function cadastro()
{
    //tenho que montar os dados que preciso pra cadastrar
    //odf,idlinha,idproduto,dataentregue,quantidade)
    global $conIgor;
    global $conmysql;
    $dia = $_SESSION['pcpAPI']['diaapont'];
    $sql = "SELECT etiqueta,idlinha,idproduto FROM tbapontamento where diaapont='$dia'  order by id asc";
    $sql = $conmysql->prepare($sql);
    $sql->execute();
    $arr = array();
    $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach ($dados as $k) {
        $odfigor = buscaODFIGOR($k['etiqueta']);
      
        if ($odfigor <> 0) {
            $odf = $odfigor;        
            if (!in_array($odf, $arr)) {
                $arr[] = $odf;     
                 $item=buscaDADOSODFIGOR($odf);
                 $bapon=buscaapontadasantes($odf);
             
                     
                   if(is_array($item)){
                    $qtdentregue= $item['quantidade_pedido']-$bapon;
                    insereodfpcp($odf,$k['idlinha'],$k['idproduto'],$qtdentregue);
                   }                
                
                //preciso buscar ainda quanidade apontada dias antes desse odf
               
            }
        }
    }
    
}
cadastro();

