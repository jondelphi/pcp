<div class="container">
    <div class="container p-2 m-2">
        <!-- formulario  CADASTRO-->
        <form action="Control/cadodf.php" method="post">
            <fieldset class="bg-dark text-white p-2">Cadastra ODF
                <div class="input-group input-group-sm p-1 m-1">
                     <label for="odf" class="input-group-text">ODF</label>
                    <input type="text" name="odf" class="form form-control" required autofocus> 
                    <label for="odf" class="input-group-text">Linha</label>
                    <select name="linha" class="form-select" required>
                        <?php
                        $conn = ConexaoMysql::getConnectionMysql();
                        $conn2=ConexaoMysql::getConnectionMysql();
                        $linha = new Linha;
                        $linha->montaoptionlinha($conn)
                        ?>
                    </select>
                </div>
                <div class="input-group input-group-sm p-1 m-1">
                    <label for="produto" class="input-group-text">Produto</label>
                    <input type="text" name="produto" class="form form-control" required>
                    <label for="quantidade" class="input-group-text">Quantidade</label>
                    <input type="text" name="quantidade" class="form form-control" required>
                    </select>
                </div>
                <div class="input-group input-group-sm p-1 m-1">
                    <!-- <label for="etiqueta1" class="input-group-text">1ª Etiqueta</label>
                    <input type="text" name="etiqueta1" class="form form-control"> -->
                    <label for="etiqueta2" class="input-group-text">Menor Etiqueta</label>
                    <input type="text" name="etiqueta2" class="form form-control">
                    <input type="submit" value="CADASTRA" class="btn btn-success">
                    </select>
                </div>
            </fieldset>
        </form>
        <!-- formulario -->
    </div>
    <div class="container p-2 m-2">
        <?php
        $conn = ConexaoMysql::getConnectionMysql();
        $odf = new ODF;
        $odf->mostraODF($conn,$_SESSION['pcpAPI']['sqlODF'],$_SESSION['pcpAPI']['filtro']);
        ?>
    </div>
</div>