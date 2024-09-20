<?php
session_start();

require('../DAO/config.php');
$conn= ConexaoMysql::getConnectionMysql();     

$user=$_POST['user'];
$senha=$_POST['senha'];

$sql="SELECT * FROM tbuser WHERE user ='$user' AND senha = '$senha'";
$execute=$conn->prepare($sql);
$execute->execute();

if($execute->rowCount()==1){
  $item=$execute->fetch(PDO::FETCH_ASSOC);

  $_SESSION['pcpAPI']['pagina']='View/placar.php';
  $_SESSION['pcpAPI']['diaapont']=date('Y-m-d');
  $_SESSION['pcpAPI']['titulo']="BRASLAR - PCP";
  $_SESSION['pcpAPI']['logado']=true;
  $_SESSION['pcpAPI']['userlogado']=$item['id'];
}
header('location:../index.php');

?>