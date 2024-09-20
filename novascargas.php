<?php
session_start();
require('DAO/config.php');

$conn=ConexaoMysql::getConnectionMysql();
// menor data
$sql="SELECT min(previsao) as menor FROM tbpedidos
WHERE previsao IS NOT NULL limit 1";
$dados = $conn->prepare($sql);
$dados->execute();
$menor = $dados->fetch(PDO::FETCH_ASSOC);
$menor = $menor['menor'];
//separa as regioes e estado / data
$sql="SELECT DISTINCT COALESCE(regiao, estado) AS destino, previsao
FROM tbpedidos
WHERE previsao IS NOT NULL
ORDER BY previsao, destino;";

$dados = $conn->prepare($sql);
$dados->execute();
$regiao_dia = $dados->fetchALL(PDO::FETCH_ASSOC);

//apagar os pedidos recentes
$sql="DELETE FROM tbpedido where datapedido >= '{$menor}'";
//$dados = $conn->prepare($sql);
//$dados->execute();
echo('<pre>');
//percorre o array região dia, vai gravando na tbpedido e na tbitempedido com o ultimo lastid
foreach($regiao_dia as $k){
    echo $k['destino']." ".$k['previsao']."<br>";
}



