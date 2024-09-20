<div class="container">
<?php
$almoco=new Almoco;
$conn=ConexaoMysql::getConnectionMysql();


?>

<div class="container m-2 p-2">
    <div class="container m-2 p-2 bg-dark text-bg-dark">
   <?php
    $almoco->criaformulario();
   ?>
    </div>
    <div class="container m-2 p-2">
   <?php
    $almoco->etiquetasdalinha();
   ?>
    </div>
</div>
</div>