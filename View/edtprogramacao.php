<?php
if(!isset($_SESSION['programacao'])){
    header('location:../Control/programacao.php');
}else{
    $idprogramacao= $_SESSION['programacao']['idprogramacao'];
    $idlinha=$_SESSION['programacao']['idlinha'];
    $idproduto=$_SESSION['programacao']['idproduto'];
   $quantidade= $_SESSION['programacao']['quantidade'];
    $dataprojeto=$_SESSION['programacao']['dataprojeto'];
    $horainicio=$_SESSION['programacao']['horainicio'];
    $horafim=$_SESSION['programacao']['horafim'];
    if(isset($_SESSION['programacao']['obs'])){
    $obs=$_SESSION['programacao']['obs'];
}else{
    $obs="";
}
   // var_dump($_SESSION['programacao']);
   $classProduto=new Produto;
   $con=ConexaoMysql::getConnectionMysql();
   $produto=$classProduto->TrazID($idproduto,$con);
   
}

?>

<div class="container">
    <div class="container p-2 m-2">
        <!-- formulario  CADASTRO-->
        <form action="Control/updateprogramacao.php" method="post">
            <fieldset class="bg-dark text-white p-2">Insere Programação
                <div class="input-group input-group-sm p-1 m-1">
                    <label for="linha" class="input-group-text">Linha</label>
                    <select name="linha" class="form-select" required>
                        <?php
                        $conn = ConexaoMysql::getConnectionMysql();
                        $conn2 = ConexaoMysql::getConnectionMysql();
                        $linha = new Linha;
                        $linha->montaoptionlinhaEDT($conn)
                        ?>
                    </select>
                </div>
                <div class="input-group input-group-sm p-1 m-1">
                    <label for="produto" class="input-group-text" >Produto</label>
                    <input type="text" name="produto" class="form form-control" value="<?php echo $produto;?>" required minlength="5" maxlength="8">
                    <label for="quantidade" class="input-group-text">Quantidade</label>
                    <input type="text" name="quantidade" value="<?php echo $quantidade;?>" class="form form-control" required>
                    
                </div>
                <div class="input-group input-group-sm p-1 m-1">
                <label for="horainicio" class="input-group-text">Hora Início</label>
                    <input type="time"  value="<?php echo $horainicio;?>" name="horainicio" class="form form-control" required >
                    <label for="horafim" class="input-group-text">Hora Fim</label>
                    <input type="time" value="<?php echo $horafim;?>" name="horafim" class="form form-control"  required>
                    <label for="obs" class="input-group-text">Observação</label>
                    <input type="text" name="obs" class="form form-control" value="<?php echo $obs;?>" >
                 <input type="hidden" name="idprogramacao" value="<?php echo $idprogramacao;?>">
                 <input type="hidden" name="dataprojeto" value="<?php echo $dataprojeto;?>">
                    
                </div>
                <div class="input-group input-group-sm p-1 m-1">
                    <input type="submit" value="CADASTRA" class="btn btn-success">
                    
                </div>
            </fieldset>
        </form>
        <!-- formulario -->
    </div>
    <div class="container">
        <div class="container m-2 p-2">
            <?php
            $programacao = new Programacao;
            $programacao->MostraProgramacao();
            ?>
        </div>
    </div>
</div>