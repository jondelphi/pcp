<?php
session_start();
require('DAO/config.php');


require('Model/ClasseAcesso.php');
require('Model/ClasseApontamento.php');
require('Model/ClasseODF.php');
require('Model/ClasseLinha.php');
require('Model/ClasseProduto.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estornos</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap-icons.css">
   

      
  
</head>
<body>
<?php
$i=0;
$conmysql=ConexaoMysql::getConnectionMysql();
$conKorp=ConexaoKorp::getConnectionKorp();
$dia=$_SESSION['pcpAPI']['diaapont'];
$sel="SELECT id,etiqueta from tbapontamento WHERE diaapont='$dia'";
$sel=$conmysql->prepare($sel);
$sel->execute();
while ($k=$sel->fetch(PDO::FETCH_ASSOC)) {
    $etq=$k['etiqueta'];
    $busca="SELECT * FROM HISAPONTA WHERE etiqueta_serie_prod='$etq' and CONVERT(date,datahora)<>'$dia'";
    $busca=$conKorp->prepare($busca);
    $busca->execute();
    if($busca->rowCount()<>0){
        $id=$k['id'];
        $delete="DELETE FROM tbapontamento WHERE id=$id";
        $delete=$conmysql->prepare($delete);
        try{
        $delete->execute();
        $i++;
        }catch(PDOException $e){

        }
    }

}
echo $i." etiquetas apagadas";

?>

</body>
</div>
</html>
