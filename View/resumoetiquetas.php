<?php
session_start();
require("../Model/ClasseAcesso.php");
require("../DAO/config.php");
require("../DAO/configIgor.php");
require("../Model/ClasseApontamento.php");
require("../Model/ClasseProduto.php");
require("../Model/ClasseLinha.php");
$classeapontamento = new Apontamento;
$conn = ConexaoMysql::getConnectionMysql();
$codigo=$_GET['codigo'];

$dia2=$_GET['dia'];

function verODF($etiqueta){
    $conn2 = ConexaoIgor::getConnectionIgor();   
    $sql="SELECT ODF from etiquetas where serie like '$etiqueta'";
    $sql=$conn2->prepare($sql);
    $sql->execute();
    if ($sql->rowCount()>0) {
        $odf= $sql->fetch(PDO::FETCH_ASSOC);
        $odf=$odf['odf'];
        return $odf;
        
    }else{
        return "";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumo  -PCP - <?php echo $_GET['linha'] ?></title>
    <link rel="icon" type="image/x-icon" href="../content/icone.png">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap-icons.css">
    <script>
        function mensagem(link){
            v=confirm("Deseja excluir essa etiqueta?")
           if (v==true) {
            window.location.href=link;
           }
        }
    </script>

</head>

<body>
    <div style="margin-top:-8px; background-color: #333;color:#fff;">
        <div class="container">
            <div class="container m-2 p-2">
                <div class="row">
                    <div class="col">
                        <img src="../content/logobranco.png" width="200px;" style="float:left" class="m-2 p-1">
                    </div>
                    <div class="col m-2 p-2 text-center">
                        <h4 class="fw-bolder text" style="color:#fff;text-shadow:0px 0px 2px #fff;font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;">
                            <?php echo "<i class='fw-bolder' style='font-size:40px;'>".$_GET['linha']."</i>" ?> - em:
                            <?php
                            $dia = new DateTime($_SESSION['pcpAPI']['diaapont']);
                            ?>
                            <?php echo $dia->format("d/m/Y"); ?>
                        </h4>
                    </div>

                </div>



            </div>

        </div>
    </div>
        <div class="container">


            <div class="container m-2 p-2 bg-black text-white fw-bolder">
                <h2 class="text-center">Total:
                    <?php
                    echo ($classeapontamento->TodasApontadasdeUmaLinhaETQ($conn, $_GET['linha'],$codigo,$dia2));
                    ?>
                </h2>
            </div>

            <div class="container m-2 p-2">
            
                        <?php
                       
                        $linha=$_GET['linha'];
                        $sql="SELECT tbapontamento.*, tblinha.linha as 'linha', tbproduto.codigo as 'produto' FROM tbapontamento 
                        INNER JOIN tblinha ON tblinha.id= tbapontamento.idlinha
                        INNER JOIN tbproduto ON tbproduto.id= tbapontamento.idproduto
                        WHERE diaapont='".$dia2."' AND tbproduto.codigo='$codigo' ORDER BY tbapontamento.horaapont desc, tblinha.linha ASC, tbapontamento.id DESC";
                        echo $classeapontamento->RetornaTodasEtiquetas4($conn,$sql,$linha);
                        ?>
            
            </div>
        </div>

</body>

</html>