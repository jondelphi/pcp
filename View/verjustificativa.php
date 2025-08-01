<div class="container p-3" id="justificativa">
    <h4 class="mb-3"><i class="bi bi-card-list"></i> Justificativas Registradas</h4>
</div>

<script>
    async function pegaJustificativa(dia, codigo, linha) {
        let nlinha = linha.replace(' ', '0');
        let url = `http://10.1.2.251/api/verJustificativa/${dia}/${codigo}/${nlinha}`;
        console.log(url)
        const response = await fetch(url);
        const data = await response.json();
        return data;
    }

    async function montaJustificativa(dia, codigo, linha) {
        const dados = await pegaJustificativa(dia, codigo, linha);
        const container = document.getElementById('justificativa');

        if (dados.length === 0) {
            container.innerHTML += `<div class="alert alert-secondary">Nenhuma justificativa encontrada.</div>`;
            return;
        }

        dados.forEach(j => {
            container.innerHTML += `
                <div class="card mb-3 shadow-sm border-info">
                    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-upc"></i> Código: <strong>${codigo}</strong></span>
                        <a class="btn btn-sm btn-danger" href='Control/deletaJustificativa.php?id=${j.id}'>
                            <i class="bi bi-trash"></i> Apagar
                        </a>
                    </div>
                    <div class="card-body bg-light">
                        <h6 class="card-title"><i class="bi bi-chat-left-text"></i> Motivo:</h6>
                        <p class="card-text">${j.motivo}</p>
                    </div>
                </div>
            `;
        });
    }

    montaJustificativa(
        '<?php echo $_SESSION['pcpAPI']['diaapont']; ?>',
        '<?php echo $_SESSION['pcpAPI']['codigojus']; ?>',
        '<?php echo $_SESSION['pcpAPI']['linhajus']; ?>'
    );
</script>
