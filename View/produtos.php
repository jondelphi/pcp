<div class="container">
    <div class="container p-2 m-2">
        <!-- formulario  CADASTRO-->
        <form action="Control/cadprodutos.php" method="post">
            <fieldset class="bg-dark text-white p-2">Cadastra Produto
                <div class="input-group input-group-sm p-1 m-1">
                     <label for="codigo" class="input-group-text">Codigo</label>
                    <input type="text" name="codigo" class="form form-control" required autofocus> 
                    <label for="odf" class="input-group-text">Categorias</label>
                    <select name="categoria" class="form-select" required>
                        <?php
                        $conn = ConexaoMysql::getConnectionMysql();
                        $conn2=ConexaoMysql::getConnectionMysql();
                        $categoria = new Produto;
                        $categoria->montaoptioncategoria()
                        ?>
                    </select>
                </div>
                <div class="input-group input-group-sm p-1 m-1">
                    <label for="prefixo" class="input-group-text">Prefixo</label>
                    <input type="text" name="prefixo" class="form form-control">
                    <label for="ean" class="input-group-text">Descricão</label>
                    <input type="text" name="descricao" class="form form-control">
                </div>
                <div class="input-group input-group-sm p-1 m-1">
                    <label for="codbase" class="input-group-text">Codigo Base</label>
                    <input type="text" name="codbase" class="form form-control">
                    <label for="valor" class="input-group-text">Valor</label>
                    <input type="text" name="valor" class="form form-control">
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