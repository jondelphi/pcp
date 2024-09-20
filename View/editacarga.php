<?php
$idpedido=$_SESSION['pcpAPI']['idpedido'];
$sel="SELECT * from tbpedido where id=$idpedido";
$con=ConexaoMysql::getConnectionMysql();
$sel=$con->prepare($sel);
$sel->execute();
while ($a=$sel->fetch(PDO::FETCH_ASSOC)) {
    $destino=$a['destino'];
    $prioridade=$a['prioridade'];
$dia= new DateTime($a['datapedido']);
$diabanco=$dia->format('Y-m-d');
$diauser=$dia->format('d/m/y');
}

?>
<div class="container">
    <div class="container p-2 m-2">
        <!-- formulario  CADASTRO-->
        <form action="Control/mudadadoscarga.php" method="post">
            <fieldset class="bg-dark text-white p-2">Muda dados
                <div class="input-group input-group-sm p-1 m-1">
                     <label for="carga" class="input-group-text">Destinado</label>
                    <input type="text" name="carga" value="<?php  echo $destino;?>" class="form form-control " required > 
                    <label for="bompara"  class="input-group-text">Data</label>
                    
                    <input type="date" name="bompara" value="<?php  echo $diabanco;?>" class="form form-control " required>
                    <label for="prioridade" class="input-group-text">Prioridade</label>
                    <input type="number" name="prioridade"value="<?php  echo $prioridade;?>" class="form form-control" required>
                    <input type="submit" value="CADASTRA" class="btn btn-success">
                </div>
                </fieldset>
        </form>
        <form action="Control/maiscarga2.php" method="post">
            <fieldset class="bg-dark text-white p-2">Cadastra CARGA
                <div class="input-group input-group-sm p-1 m-1">
                <input type="hidden" name="carga" value="<?php  echo $destino;?>">
                <input type="hidden" name="bompara" value="<?php  echo $diabanco;?>">
                    <label for="produto" class="input-group-text">Produto</label>
                    <input type="text" name="produto" class="form form-control" required>
                    <label for="quantidade" class="input-group-text">Quantidade</label>
                    <input type="text" name="quantidade" class="form form-control" required>
                    
                    <input type="submit" value="CADASTRA" class="btn btn-success">
                
                </div>
            </fieldset>
        </form>
        <!-- formulario -->
    </div>
   
</div>

<div class="bg-dark text-bg-dark m-2 p-2">
       <?php
       $sel="SELECT 
       tbproduto.codigo as 'cod',
       tbproduto.descricao as 'desc',
       tbitempedido.id as 'iditempedido',
       tbitempedido.quantidade as 'qtd',
       tbitempedido.idpedido as 'idpedido',
       tbitempedido.idproduto as 'idproduto'
       FROM tbitempedido 
       INNER JOIN tbproduto ON tbproduto.id=tbitempedido.idproduto
       WHERE idpedido=$idpedido 
       ORDER BY tbproduto.codigo ASC
       ";
       $sel=$con->prepare($sel);
       $sel->execute();
       if($sel->rowCount()>0){
        ?>
        <table class="table table-dark table-bordered table-striped">
            <thead>
                <tr>
                <th>Codigo</th>
                <th>Produto</th>
                <th>Quantidade</th>
                <th>Operação</th>
                </tr>
            </thead>
            <tbody>
            <?php
            while ($q=$sel->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <tr>
                    <td><?php echo $q['cod'] ?></td>
                     <td><?php echo $q['desc'] ?></td>
                     <td class="text-end"><?php echo number_format($q['qtd'],0,',','.'); ?></td>
                     <td> <a href="Control/apagaitempedido.php?iditem=<?php echo $q['iditempedido'];?>&&idpedido=<?php echo $q['idpedido'];?>" class="btn btn-danger">APAGA</a></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
        
        <?php


       }

       ?>

    </div>