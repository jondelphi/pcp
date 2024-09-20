<div class="container">
    <div class="container p-2 m-2">
        <!-- formulario  CADASTRO-->
        <form action="Control/cadcarga.php" method="post">
            <fieldset class="bg-dark text-white p-2">Cadastra CARGA
                <div class="input-group input-group-sm p-1 m-1">
                    <label for="carga" class="input-group-text">Destinado</label>
                    <input type="text" name="carga" class="form form-control" required autofocus>
                    <label for="bompara" class="input-group-text">Data</label>
                    <input type="date" name="bompara" class="form form-control" required>
                </div>
                <div class="input-group input-group-sm p-1 m-1">
                    <label for="produto" class="input-group-text">Produto</label>
                    <input type="text" name="produto" class="form form-control" required minlength="5" maxlength="8">
                    <label for="quantidade" class="input-group-text">Quantidade</label>
                    <input type="text" name="quantidade" class="form form-control" required>
                    <label for="quantidade" class="input-group-text">Prioridade</label>
                    <input type="number" name="prioridade" value="1" class="form form-control" required>
                    <input type="submit" value="CADASTRA" class="btn btn-success">

                </div>
            </fieldset>
        </form>
        <!-- formulario -->
    </div>

</div>
<div class="container m-2 p-2">
    <!-- formulario  CADASTRO-->
    <form action="Control/carga.php" method="post">
        <fieldset class="bg-dark text-white p-2">Mude as Datas de Visualização
            <div class="input-group input-group-sm p-1 m-1">
                <label for="diainicial" class="input-group-text">Data Inicial</label>
                <input type="date" name="diainicial" class="form form-control" required>
                <label for="diafinal" class="input-group-text">Data Final</label>
                <input type="date" name="diafinal" class="form form-control" required>
                <input type="submit" value="Altera" class="btn btn-success">
            </div>
        </fieldset>
    </form>
    <!-- formulario -->
</div>
<div class="container m-2 p-2">
    <a href="View\exportar.php" class="btn btn-primary" target="_blank">Exportar</a>
</div>
<div style="max-width:max-content">
    <?php
    $classeCarga = new Carga;
    $classeCarga->mostraCargas2();

    ?>
</div>