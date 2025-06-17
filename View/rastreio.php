<div>
    <p id="lista"></p>
</div>
<div class="container">
    <form action="index.php" method="post" class="m-2 p-2" style="background-color: #333; max-width: fit-content;">
        <div class="input-group input-group-sm m-2 p-2">
           <!--  <label for="busca" class="input-group-text input-group-sm">ODF ou Etiqueta</label> -->
            <input type="text" name="busca" class="form-control form-control-sm" autofocus id="etiqueta" required minlength="18" maxlength="18">
            <input type="submit" value="Busca" class="btn btn-success btn-sm">
        </div>
    </form>
</div>
<audio id="beepSound" src="content/beep.mp3"></audio>
<div class="container my-2" style="width: fit-content;">
        <button class="btn btn-primary m-1" onclick="comeca()"><i class="bi bi-camera-fill"></i> Câmera ON </button>
        <button class="btn btn-secondary m-1" onclick="para()"><i class="bi bi-camera-fill"></i> Câmera OFF </button>
    <div id="camera">
        
    </div>
<div class="container p-2 m-2">
    <?php
    if ($_POST) {
        $classODF = new ODF;
        $buscaodf = "SELECT * FROM tbpcp WHERE odf='" . $_POST['busca'] . "'";
        $con = ConexaoMysql::getConnectionMysql();
        $con2 = ConexaoIgor::getConnectionIgor();
        $buscaodf = $con->prepare($buscaodf);
        $buscaodf->execute();
        if ($buscaodf->rowCount() > 0) {
            $sql = "SELECT tbpcp.*,tbproduto.codigo as 'codigo', tbproduto.descricao as 'descri',tblinha.linha as 'linhamontagem'
            FROM tbpcp 
            INNER JOIN tbproduto ON tbproduto.id = tbpcp.idproduto
            INNER JOIN tblinha ON tblinha.id = tbpcp.idlinha
            WHERE tbpcp.odf='" . $_POST['busca'] . "'
            ORDER BY tblinha.linha ASC, tbproduto.codigo ASC";
            $classODF->mostraODF($con, $sql, false);
        } else {
            $odf = $classODF->OdfDaEtiqueta($_POST['busca']);
            $buscaodf = "SELECT * FROM tbpcp WHERE odf='" . $odf . "'";
            $con = ConexaoMysql::getConnectionMysql();
            $con2 = ConexaoIgor::getConnectionIgor();
            $buscaodf = $con->prepare($buscaodf);
            $buscaodf->execute();
            if ($buscaodf->rowCount() > 0) {
                $classODF = new ODF;
                $sql = "SELECT tbpcp.*,tbproduto.codigo as 'codigo', tbproduto.descricao as 'descri',tblinha.linha as 'linhamontagem'
                FROM tbpcp 
                INNER JOIN tbproduto ON tbproduto.id = tbpcp.idproduto
                INNER JOIN tblinha ON tblinha.id = tbpcp.idlinha
                WHERE tbpcp.odf='" . $odf . "'  ORDER BY dataentregue ASC,tblinha.linha ASC, tbproduto.codigo ASC";
                $classODF->mostraODF($con, $sql, false);
            }else{
                $sql = "SELECT tbproduto.codigo as 'codigo', tbproduto.descricao as 'descri',tblinha.linha as 'linhamontagem', tbapontamento.diaapont as 'dia', tbapontamento.horaapont as 'hora'
                FROM tbapontamento 
                INNER JOIN tbproduto ON tbproduto.id = tbapontamento.idproduto
                INNER JOIN tblinha ON tblinha.id = tbapontamento.idlinha
                WHERE tbapontamento.etiqueta like'" . $_POST['busca'] . "'  ORDER BY tbapontamento.diaapont ASC, tbapontamento.horaapont ASC,tblinha.linha ASC, tbproduto.codigo ASC";
                $classODF->mostraapontada($con, $sql);

            }
        }
    }

    ?>
</div>