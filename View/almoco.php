<div class="container">
<?php
$almoco=new Almoco;
$conn=ConexaoMysql::getConnectionMysql();
?>
<script>
    function conEdita(link,linha){
        var str ="Deseja editar a linha "+linha;
        var v=confirm(str);
        if (v==true) {
            window.location.href=link;
        }
    }
</script>
<div class="container m-2 p-2">
    <div class="container m-2 p-2">
  <?php
   $almoco->mostraAlmoco();
   ?>  
    </div>
</div>
</div>