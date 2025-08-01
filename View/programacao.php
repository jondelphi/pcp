<div class="container">
    <div class="container p-2 m-2">
        <!-- formulario  CADASTRO-->
        <form action="Control/cadprogramacao.php" method="post">
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
                    <label for="produto" class="input-group-text">Produto</label>
                    <input type="text" name="produto" class="form form-control"  autofocus required minlength="5" maxlength="8">
                    <label for="quantidade" class="input-group-text">Quantidade</label>
                    <input type="text" name="quantidade" class="form form-control" required>
                    
                </div>
                <div class="input-group input-group-sm p-1 m-1">
                    <label for="horainicio" class="input-group-text">Hora Início</label>
                    <input type="time" value="07:30" name="horainicio" class="form form-control" required >
                    <label for="horafim" class="input-group-text">Hora Fim</label>
                    <input type="time" value="17:15" name="horafim" class="form form-control"  required>
                    <label for="obs" class="input-group-text">Observação</label>
                    <input type="text" name="obs" class="form form-control" >
                 
                    
                </div>
                <div class="input-group input-group-sm p-1 m-1">
                    <input type="submit" value="CADASTRA" class="btn btn-success">
                    
                </div>
            </fieldset>
        </form>
        <!-- formulario -->
        <a href="View/exportaprogramacao.php" target="_blank" class="btn btn-primary">Exportar</a>
        <a href="control/relatorioProgramacao.php" class="btn btn-primary">Relatorio</a>
    </div>
    <div class="container" id="ultimodia">

    </div>
    <div class="container">
        <div class="container m-2 p-2">
            <?php
            $programacao = new Programacao;
            $programacao->MostraProgramacao();
            ?>
        </div>
    </div>
    <div class="container">
        <div class="container m-2 p-2">
            <?php
            $programacao = new Programacao;
           $programacao->tabelaeficienciapcp($_SESSION['pcpAPI']['diaapont'],$_SESSION['pcpAPI']['diaapont']);
            ?>
        </div>
    </div>
</div>
<script>
    async function ultimo(){
        const url=`http://10.1.2.251/api/ultimodiaProgramado`
        const d = document.getElementById('ultimodia')
        const send = await fetch(url)
        const response = await send.json()
        console.log(response)
        const al = document.createElement('p')
        al.className = 'alert alert-warning'
        al.innerText = ` Último dia programado: ${response.ultimo.substring(8,11)}/${response.ultimo.substring(5,7)}/${response.ultimo.substring(0,4)}`
        d.appendChild(al)
    }
    ultimo()
</script>