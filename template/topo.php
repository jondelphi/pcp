<header>
    <div class="container" style="border-bottom:1px solid #000;">
        <nav class="navbar navbar navbar-expand-lg bg-light">
            <figure class="p-2">
                <a href="teste.php" target="_blank" rel="noopener noreferrer">
                    <img src="content/colorido.png" alt="Braslar Eletro" style="max-width:100px;">
                </a>

            </figure>
            <div class="container-fluid">
                <a class="navbar-brand" href="Control/placar.php">Placar</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <!--<li class="nav-item">
                        <a class="nav-link" href="#">Linhas</a>
                        </li> -->
                        <li class="nav-item">
                            <a class="nav-link" href="Control/odf.php">ODF</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Control/audita.php">Auditoria</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Control/programacao.php">Programacão</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Control/produtos.php">Produtos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Control/rastreio.php">Rastreio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="http://10.1.2.251/logistica/">CARGA</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Control/justificativa.php">Justificativa</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Control/planejamento.php">Planejamento</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    </div>
    </div>
</header>
<div class="container p-2" style="background-color: #666; color:aliceblue">
    <div class="row">
        <div class="col">
            <h4><i class="bi bi-calendar3"></i>Dia Ativo
                <?php
                $dia = new DateTime($_SESSION['pcpAPI']['diaapont']);
                echo $dia->format('d/m/Y')
                ?>
            </h4>
        </div>
        <div class="col">
            <div class="container p-2 m-2">
                <!-- formulario  CADASTRO-->
                <form action="Control/mudadata.php" method="get">
                    <div class="input-group input-group-sm">
                        <label for="dia" class="input-group-text">Muda Data</label>
                        <?php
                        $hoje = new DateTime('now');

                        ?>
                        <input type="date" name="dia" value="<?php echo $hoje->format('Y-m-d'); ?>" class="form form-control" required>

                        <input type="submit" value="OK" class="input-group-text btn btn-success">
                    </div>
                </form>
                <!-- formulario -->
            </div>
        </div>
        <div class="col text-end">
            <a href="control/sair.php" class="btn btn-sm btn-warning shadow  m-2">SAIR</a>
        </div>
    </div>
</div>