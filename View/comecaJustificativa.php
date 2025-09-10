<?php
$acao = $_SESSION['pcpAPI']['diaapont'];
?>
<div class="container my-4 d-flex justify-content-center">
    <div class="card shadow rounded-4" style="max-width: 500px; width: 100%;">
        <div class="card-header bg-primary text-white text-center rounded-top-4">
            <h5 class="mb-0">Justificativa de Apontamento</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="codigo" class="form-label">Código</label>
                <input type="text" id="codigo" class="form-control"
                    value="<?php echo $_SESSION['pcpAPI']['codigojus']; ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="modelo" class="form-label">Modelo</label>
                <input type="text" id="modelo" class="form-control"
                    value="<?php echo $_SESSION['pcpAPI']['modelojus']; ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="linha" class="form-label">Linha</label>
                <input type="text" id="linha" class="form-control"
                    value="<?php echo $_SESSION['pcpAPI']['linhajus']; ?>" readonly>
            </div>

            <div class="form-floating mb-3">
                <textarea class="form-control" placeholder="Descreva a justificativa aqui"
                    id="justificativa" style="height: 120px" autofocus></textarea>
                <label for="justificativa">Justificativa</label>
            </div>

            <div class="text-center">
                <?php $acao = $_SESSION['pcpAPI']['diaapont']; ?>
                <button type="button" onclick="gravaJustificativa('<?php echo $acao ?>')"
                    class="btn btn-success w-100">
                    <i class="bi bi-save me-2"></i>Gravar Justificativa
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Bootstrap Icons (opcional para o ícone do botão) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">




</div>

</div>

<script>
    async function gravaJustificativa(dia) {
        let justificativa = document.getElementById('justificativa').value;
        let codigo = document.getElementById('codigo').value;
        let linha = document.getElementById('linha').value;
        linha = linha.replace(' ', '0');
        await requisicao(dia, codigo, justificativa, linha);
        window.location.href = "Control/placar.php";
    }



    async function requisicao(data, codigo, motivo, linha) {
        const url = "http://10.1.2.251/api/insereJustificativa";
        let linhaF = linha.replace(' ', '0');

        const options = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'User-Agent': 'insomnia/11.2.0'
            },
            body: `{"data":"${data}","codigo":"${codigo}","linha":"${linhaF}","motivo":"${motivo}"}`
        };
        await fetch(url, options)

    }
</script>