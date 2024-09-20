<?php 
require("DAO\config.php");

function insere($cod,$valor){
    $sql="UPDATE tbproduto set valor={$valor} where codigo='{$cod}'";
    $con = ConexaoMysql::getConnectionMysql();
    try {
        $sql=$con->prepare($sql);
        $sql->execute();
    } catch (\Throwable $e) {
        # code...
    }
}
function valores(){
    $arquivo="valores.csv";

    $handle = fopen($arquivo,'r');
    
    if ($handle!== false) {
        $dados = array();
         while (($linha = fgetcsv($handle)) !== false) {
            $dados[]= $linha;
         }
         fclose($handle);
    
        foreach ($dados as $k) {
           print($k[0]."-".$k[1]."<br>");
           insere($k[0],$k[1]);
        }
    }
}

function botavalores(){
    $con = ConexaoMysql::getConnectionMysql();
    //ler produtos e pegar valor e id
    $sql="SELECT id, valor from tbproduto where valor is NOT null and valor>0 ";
    try {
        $sql=$con->prepare($sql);
        $sql->execute();
        $item = $sql->fetchAll(PDO::FETCH_ASSOC);
        foreach ($item as $k) {
            $id=$k['id'];
            $valor=$k['valor'];
            $up="UPDATE tbapontamento set valor={$valor} where idproduto='{$id}'";
            try {
                $up=$con->prepare($up);
                $up->execute();
            } catch (\Throwable $e) {
               echo "errou";
            }
        }
    } catch (\Throwable $e) {
        # code...
    }

}

botavalores();

?>