<?php
session_start();
require('DAO/config.php');


require('Model/ClasseAcesso.php');
require('Model/ClasseApontamento.php');
require('Model/ClasseODF.php');
require('Model/ClasseLinha.php');
require('Model/ClasseProduto.php');
$conn=ConexaoMysql::getConnectionMysql();
$row = 1;
if (($handle = fopen("etiquetas.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
     
       
        try {
            $insert="INSERT INTO tbapontamento(etiqueta,linha,produto,diaapont,horaapont,intervalo)";
            $insert.="VALUES(
                '".$data[0]."',
                'Linha 1',
                '10010075',
                '2023-03-06',
                '17:00',
                11
            )";
            $query=$conn->prepare($insert);
            $query->execute();
            $insert="INSERT INTO tbcoleta(etiqueta,diaapont)";
            $q=$insert;
            $insert.="VALUES(
                '".$data[0]."',
                '2023-03-06'
            )";
            $query=$conn->prepare($insert);
            $query->execute();
            echo "ok";
        } catch (PDOException $e) {
           
            //echo $e->getMessage();
          
        
         }
    }
    fclose($handle);
}
?>