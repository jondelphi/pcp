<div class="container mx-2 p-2 bg-dark text-white">
    <div class="input-group">
        <label for="inicial" class="input-group-text">Data Inicial</label>
        <input type="date" id="inicial" class="form form-control" value="<?php echo $_SESSION['pcpAPI']['diaapont']; ?>">
        <label for="final" class="input-group-text">Data Final</label>
        <input type="date" id="final" class="form form-control" value="<?php echo $_SESSION['pcpAPI']['diaapont']; ?>">
        <button class="btn btn-success" onclick="mostraJustificativa()">Mostrar</button>
    </div>
</div>
<div id="justificativas">

</div>
<script>
    async function mostraJustificativa() {
        const d = document.getElementById('justificativas');
        d.innerHTML = '';

        const dataInicial = document.getElementById('inicial').value;
        const dataFinal = document.getElementById('final').value;
        const url = `http://10.1.2.251/api/getJustificativa/${dataInicial}/${dataFinal}`;

        const response = await fetch(url);
        const json = await response.json();

        for (let i = 0; i < json.length; i++) {
            let tb = montatabela(json[i].data_justificativa, json[i].linha, json[i].motivo, json[i].codigo, json[i].descricao);
            d.appendChild(tb);
        }
    }

    function montatabela(data, linha, justificativa, codigo, descricao) {
    // Criar nova tabela com classes Bootstrap
    const tabela = document.createElement('table');
    tabela.className = 'table table-bordered table-striped table-hover mt-3';

    // Criar cabeçalho da tabela
    const thead = document.createElement('thead');
    thead.className = 'table-primary';
    thead.innerHTML = `
        <tr>
            <th scope="col">Data</th>
            <th scope="col">Linha</th>
            <th scope="col">Justificativa</th>
            <th scope="col">Solução</th>
        </tr>
    `;

    // Criar corpo da tabela
    const tbody = document.createElement('tbody');

    // Criar uma linha (<tr>) e suas células (<td>)
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td>${data.substring(8,10)}/${data.substring(5,7)}/${data.substring(0,4)}</td>
        <td>${linha}</td>
        <td>${justificativa}</td>
        <td>Entrou com ${codigo} - ${descricao}</td>
    `;

    // Adicionar a linha ao corpo da tabela
    tbody.appendChild(tr);

    // Montar a tabela final
    tabela.appendChild(thead);
    tabela.appendChild(tbody);

    return tabela;
}

</script>