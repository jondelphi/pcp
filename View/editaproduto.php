<?php
if(!isset($_SESSION['pcpAPI']['erro'])){
    $_SESSION['pcpAPI']['erro']=false;
}
if ($_SESSION['pcpAPI']['erro']==false) {
            $produto=$_SESSION['pcpAPI']['produto'];
            $prefixo=$_SESSION['pcpAPI']['prefixo'];
            $idproduto=$_SESSION['pcpAPI']['idproduto'];
            $descricao=$_SESSION['pcpAPI']['descricao'];         
            $valor=$_SESSION['pcpAPI']['valor'];
            $valor = str_replace('.',',',$valor);
            $codigobase=$_SESSION['pcpAPI']['codigobase'];
            $idcategoria=$_SESSION['pcpAPI']['idcategoria'];
            $categoria=$_SESSION['pcpAPI']['categoria'];
   
} else {
    $_SESSION['pcpAPI']['pagina']='View/produtos.php';
    header("location:../index.php");
}


?>
<div class="container">
    <div class="container p-2 m-2">
        <!-- formulario  CADASTRO-->
        <form action="Control/coneditaproduto.php" method="post">
            <fieldset class="bg-dark text-white p-2">Cadastra Produto
                <div class="input-group input-group-sm p-1 m-1">
                     <label for="codigo" class="input-group-text">Codigo</label>
                    <input type="text" name="codigo" class="form form-control" value="<?php echo $produto?>" required autofocus> 
                    <label for="odf" class="input-group-text">Categorias</label>
                    <select name="categoria" class="form-select" required>
                        <?php
                        $conn = ConexaoMysql::getConnectionMysql();
                        $conn2=ConexaoMysql::getConnectionMysql();
                        $categoria = new Produto;
                        $categoria->montaoptioncategoriaEDT($idcategoria);
                        ?>
                    </select>
                </div>
                <div class="input-group input-group-sm p-1 m-1">
                    <label for="prefixo" class="input-group-text" >Prefixo</label>
                    <input type="text" name="prefixo" value="<?php echo $prefixo;?>"  class="form form-control" required>
                    <label for="ean" class="input-group-text">Descricão</label>
                    <input type="text" name="descricao"  value="<?php echo $descricao;?>" class="form form-control">
                </div>
                <div class="input-group input-group-sm p-1 m-1">
                    <label for="codbase" class="input-group-text">Codigo Base</label>
                    <input type="text" name="codbase"  value="<?php echo $codigobase;?>"class="form form-control">
                    <label for="valor" class="input-group-text">Valor</label>
                    <input type="text" name="valor"  value="<?php echo $valor;?>" class="form form-control">
                    <input type="hidden" name="idproduto"  value="<?php echo $idproduto;?>" >
                    
                    <input type="submit" value="CADASTRA" class="btn btn-success">
                </div>
                
            </fieldset>
        </form>
        <!-- formulario -->
    </div>
    <div class="container p-2 m-2">
        <?php
        $conn = ConexaoMysql::getConnectionMysql();
        $produtos = new Produto;
        $produtos->mostraprodutos();
        ?>
    </div>
</div>