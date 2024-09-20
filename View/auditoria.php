<div class="container">
<?php
$classeapontamento=new Apontamento;
$conn=ConexaoMysql::getConnectionMysql();
?>

<div class="container m-2 p-2">
    <div class="container m-2 p-2">
    <?php
                        $dia=$_SESSION['pcpAPI']['diaapont'];
               
                        $sql="SELECT tbapontamento.*, tblinha.linha as 'linha', tbproduto.codigo as 'produto' 
                         FROM tbapontamento 
                         INNER JOIN tblinha ON tblinha.id = tbapontamento.idlinha
                         INNER JOIN tbproduto ON tbproduto.id = tbapontamento.idproduto
                         WHERE diaapont='$dia' ORDER BY tblinha.linha ASC, tbproduto.codigo ASC , tbapontamento.horaapont ASC";
                        echo $classeapontamento->RetornaTodasEtiquetas5($conn,$sql);
                        ?>
    </div>
</div>
</div>