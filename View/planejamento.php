<div class="d-flex justify-content-center align-items-center">
    <div class="bg-secondary rounded p-2">
        <div class="input-group">
            <span class="input-group-text">Data Final</span>
            <input type="date" class="form-control" id="data_final" name="data_final" value="<?php echo date('Y-m-d'); ?>">
            <button class="btn btn-primary" onclick="atualizarPlanejamento(),fabricantes()">Atualizar</button>
        </div>
    </div>
</div>
<div class="container" id="necessidades"></div>
<div class="container p-2 m-2" id="fabricantes"></div>

<script src="planejamento.js"></script>
<script>
 async function fabricantes(){
    const dataFinal = document.getElementById('data_final').value;
    const url = `http://10.1.2.251/api/buscaFabricantes/${dataFinal}`;
    const request = await fetch(url)
    const dados = await request.json()
    tabela_fabricantes(dados)
 }

function tabela_fabricantes(dados){
    const container = document.getElementById('fabricantes');
    container.innerHTML = ''
    const tabela = document.createElement('table')
    tabela.className = 'table table-striped table-bordered';
    const thead = document.createElement('thead');
    const tbody = document.createElement('tbody');
    thead.innerHTML = `
    <tr class='text-center bg-dark text-white'>
    <th>Cliente</th>
    <th>Data Pedido</th>
    </tr>
    `
    tabela.appendChild(thead)
    for(let i = 0; i<dados.length;i++){
        const linha = document.createElement('tr')
        linha.innerHTML = `
        <td>${dados[i].destino}</td>
        <td class='text-end'>${dados[i].datapedido}</td>
        `
        tbody.appendChild(linha)

    }
    tabela.appendChild(tbody)
    container.appendChild(tabela)

 }

</script>

<!-- 

  {
        "codigo": "10010017",
        "descricao": "FOGAO 4BC ASIATICO JR BRANCO",
        "menor_data_pedido": "2025-09-09",
        "maior_data_pedido": "2025-09-25",
        "quantidade_pedida": 858,
        "quantidade_estoque": 816,
        "quantidade_programada": 0,
        "menor_data_programada": null,
        "maior_data_programada": null
    },
-->