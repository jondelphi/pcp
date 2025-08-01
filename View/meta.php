<div class="container" id="meta" style="font-size:12px;">

</div>
<div class="container" style="clear:both"></div>
<script>
async function getDadosMeta(linha) {
    const dia = '<?php echo $_SESSION['pcpAPI']['diaapont'] ?>';
    const linhaFormatada = linha.replace(' ', '0');
    const [metaResponse, linhaResponse] = await Promise.all([
        fetch(`http://10.1.2.251/api/getQuantidadeProgramadaLinha/${linhaFormatada}/${dia}`),
        fetch(`http://10.1.2.251/api/getLInhaDiaHora/${linhaFormatada}/${dia}`)
    ]);
    const metaData = await metaResponse.json();
    const linhaData = await linhaResponse.json();
    const quantidade = Math.ceil((metaData[0].total / 528) * 60);
    return { dadosLinha: linhaData, meta: quantidade };
}

async function linhasProduzindoDia() {
    const dia = '<?php echo $_SESSION['pcpAPI']['diaapont'] ?>';
    const response = await fetch(`http://10.1.2.251/api/getLinhasProduzindoDia/${dia}`);
    return await response.json();
}

async function criarTabela2(dt, lin, obj) {
    const tabela = document.createElement('table');
    tabela.className = 'table table-light table-striped table-bordered';
    tabela.style.cssText = 'width: fit-content; float: left; clear: right; margin: 20px';

    const thead = document.createElement('thead');
    thead.innerHTML = `<tr class='text-center'><th colspan=6>${lin}</th></tr>
                       <tr class='text-center'><th>Horario</th><th>Meta</th><th>Realizado</th><th>Delta</th><th>%</th><th>Status</th></tr>`;
    tabela.appendChild(thead);

    const tbody = document.createElement('tbody');
    const acumulado = dt.reduce((soma, item) => soma + item.total, 0);
    dt.forEach(item => {
        const tr = document.createElement('tr');
        tr.className = 'text-end';

        const meta = obj;
        const realizado = item.total || 0;
        const delta = realizado - meta;
        const porcentagem = ((realizado / meta) * 100).toFixed(2) + " %";
        const horario = item.horario === "16:30 - 17:30" ? "16:30 - 17:15" : item.horario;
        let inicio = horario.substring(0,5)
        let fim = horario.substring(8,5)
        let linha_parametro = lin.substring(' ','0')
      //  const resultado = await resultados_hora(inicio,fim,dt,linha_parametro)
       // console.log(resultado)
        const ajustadoMeta = horario === "16:30 - 17:15" ? Math.floor(meta / 60 * 43) : meta;
        const ajustadoDelta = realizado - ajustadoMeta;
        const ajustadoPorcentagem = ((realizado / ajustadoMeta) * 100).toFixed(2) + " %";

        tr.innerHTML = `
            <td>${horario}</td>
            <td>${ajustadoMeta}</td>
            <td>${realizado}</td>
            <td>${ajustadoDelta}</td>
            <td>${ajustadoPorcentagem}</td>
            <td class='text-center'>
                <button class='btn btn-sm ${realizado >= ajustadoMeta ? 'btn-success' : 'btn-danger'}'>
                    <i class='bi ${realizado >= ajustadoMeta ? 'bi-hand-thumbs-up' : 'bi-hand-thumbs-down'}'></i>
                </button>
            </td>`;
        tbody.appendChild(tr);
    });
    tabela.appendChild(tbody);

    const mediaHora = (acumulado / dt.length).toFixed(0);
    const deltaAcumulado = acumulado - (dt.length * obj);
    const porcentagemAcumulado = ((acumulado / (dt.length * obj)) * 100).toFixed(2);
    const tfoot = document.createElement('tfoot');
    tfoot.innerHTML = `
        <tr><th colspan=5>Acumulado Realizado</th><th class='text-end'>${acumulado}</th></tr>
        <tr><th colspan=5>Média Hora</th><th class='text-end'>${mediaHora}</th></tr>
        <tr><th colspan=5>Delta Acumulado</th><th class='text-end'>${deltaAcumulado}</th></tr>
        <tr><th colspan=5>Porcentagem Acumulado</th><th class='text-end'>${porcentagemAcumulado} %</th></tr>`;
    tabela.appendChild(tfoot);

    document.getElementById('meta').appendChild(tabela);
}

async function monta() {
    const linhas = await linhasProduzindoDia();
    const promessasLinhas = linhas.map(async linha => {
        const { dadosLinha, meta } = await getDadosMeta(linha.linha);
        criarTabela(dadosLinha, linha.linha, meta);
    });
    await Promise.all(promessasLinhas);
}

async function resultados_hora(inicio,fim,linha){
    const dia = '<?php echo $_SESSION['pcpAPI']['diaapont'] ?>';
    url =`http://10.1.2.251/api/getMaxMinHora/${inicio}/${fim}/${dia}/${linha}`
    const response = await fetch(url);
    return await response.json();
}


async function criarTabela(dt, lin, obj) {
    const tabela = document.createElement('table');
    tabela.className = 'table table-light table-striped table-bordered';
    tabela.style.cssText = 'width: fit-content; float: left; clear: right; margin: 20px';

    const thead = document.createElement('thead');
    thead.innerHTML = `<tr class='text-center'><th colspan=6>${lin}</th></tr>
                       <tr class='text-center'><th>Horario</th><th>Meta</th><th>Realizado</th><th>Delta</th><th>%</th><th>Status</th></tr>`;
    tabela.appendChild(thead);

    const tbody = document.createElement('tbody');
    const acumulado = dt.reduce((soma, item) => soma + item.total, 0);

    for (const item of dt) {
        const tr = document.createElement('tr');
        tr.className = 'text-end';

        const meta = obj;
        const realizado = item.total || 0;
        const delta = realizado - meta;
        const porcentagem = ((realizado / meta) * 100).toFixed(2) + " %";
        const horario = item.horario === "16:30 - 17:30" ? "16:30 - 17:13" : item.horario;
        let inicio = horario.substring(0, 5);
        let fim = horario.substring(8, 13);
        let linha_parametro = lin.replace(' ', '0');

        const resultado = await resultados_hora(inicio, fim, linha_parametro);
        let informacao ="Primeiro aponamento: "+resultado[0].minimo
        informacao =informacao+ "\n Último apontamento: "+resultado[0].maximo
        let media_real = Math.floor( (resultado[0].total / resultado[0].diferenca_minutos)*60)
        informacao =informacao+ "\n Minutos trabalhados: "+ resultado[0].diferenca_minutos
        informacao =informacao+ "\n Média/Hora: "+ media_real
       // console.log(resultado[0].minimo)
    

        const ajustadoMeta = horario === "16:30 - 17:15" ? Math.floor(meta / 60 * 43) : meta;
        const ajustadoDelta = realizado - ajustadoMeta;
        const ajustadoPorcentagem = ((realizado / ajustadoMeta) * 100).toFixed(2) + " %";

        tr.innerHTML = `
            <td><span title="${informacao}">${horario}</span></td>
            <td>${ajustadoMeta}</td>
            <td>${realizado}</td>
            <td>${ajustadoDelta}</td>
            <td>${ajustadoPorcentagem}</td>
            <td class='text-center'>
                <button class='btn btn-sm ${realizado >= ajustadoMeta ? 'btn-success' : 'btn-danger'}'>
                    <i class='bi ${realizado >= ajustadoMeta ? 'bi-hand-thumbs-up' : 'bi-hand-thumbs-down'}'></i>
                </button>
            </td>`;
        tbody.appendChild(tr);
    }

    tabela.appendChild(tbody);

    const mediaHora = (acumulado / dt.length).toFixed(0);
    const deltaAcumulado = acumulado - (dt.length * obj);
    const porcentagemAcumulado = ((acumulado / (dt.length * obj)) * 100).toFixed(2);
    const tfoot = document.createElement('tfoot');
    tfoot.innerHTML = `
        <tr><th colspan=5>Acumulado Realizado</th><th class='text-end'>${acumulado}</th></tr>
        <tr><th colspan=5>Média Hora</th><th class='text-end'>${mediaHora}</th></tr>
        <tr><th colspan=5>Delta Acumulado</th><th class='text-end'>${deltaAcumulado}</th></tr>
        <tr><th colspan=5>Porcentagem Acumulado</th><th class='text-end'>${porcentagemAcumulado} %</th></tr>`;
    tabela.appendChild(tfoot);

    document.getElementById('meta').appendChild(tabela);
}


monta();
</script>

