<?php
$idpedido=$_SESSION['pcpAPI']['idpedido'];
$destino=$_SESSION['pcpAPI']['carga'];
$dia= new DateTime($_SESSION['pcpAPI']['datapedido']);
$diabanco=$dia->format('Y-m-d');
$diauser=$dia->format('d/m/y');
?>
<div class="container">
    <div class="container p-2 m-2">
        <!-- formulario  CADASTRO-->
        <form action="Control/maiscarga.php" method="post">
            <fieldset class="bg-dark text-white p-2">Cadastra CARGA
                <div class="input-group input-group-sm p-1 m-1">
                     <label for="carga" class="input-group-text">Destinado</label>
                    <input type="text" name="carga" value="<?php  echo $destino;?>"
                     class="form form-control input-group-text" disabled > 
                    <label for="bompara"  class="input-group-text">Data</label>
                    
                    <input type="text" name="bompara" value="<?php  echo $diauser;?>" class="form form-control input-group-text" disabled>
                </div>
                <div class="input-group input-group-sm p-1 m-1">
                    <label for="produto" class="input-group-text">Produto</label>
                    <input type="text" name="produto" class="form form-control" required autofocus>
                    <label for="quantidade" class="input-group-text">Quantidade</label>
                    <input type="text" name="quantidade" class="form form-control" required>
                    <input type="submit" value="CADASTRA" class="btn btn-success">
                
                </div>
            </fieldset>
        </form>
        <!-- formulario -->
    </div>
   
</div>

<div style="max-width:max-content">
        <?php
        $classeCarga=new Carga;
        $classeCarga->mostraCargas2();
        
        ?>
    </div>