<div class="container">
    <?php
    $idpedido=$_SESSION['pcpAPI']['idpedido'];
    $con=ConexaoMysql::getConnectionMysql();
    $sel="SELECT * FROM tbpedido where id=$idpedido";

    ?>
    <div class="bg-dark text-bg-dark m-2 p-2">
       <?php
       $sel=$con->prepare($sel);
       $sel->execute();
       while ($q=$sel->fetch(PDO::FETCH_ASSOC)) {
       ?>
       <h3>Destino: 
        <?php
            echo $q['destino'];
       ?>
        <h3>Data: 
        <?php
        $dia=new DateTime($q['datapedido']);
            echo $dia->format('d/m/Y')
       ?>
       </h3>
       <a href="Control/confirmadeletepedido.php?id=<?php echo $idpedido;?>" class="btn btn-danger">Apaga</a>
       <?php
       }

       ?>

    </div>

    <div class="bg-dark text-bg-dark m-2 p-2">
       <?php
       $sel="SELECT 
       tbproduto.codigo as 'cod',
       tbproduto.descricao as 'desc',
       tbitempedido.id as 'iditempedido',
       tbitempedido.quantidade as 'qtd',
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

</div>
   
