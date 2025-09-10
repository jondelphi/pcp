<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
$dia = new DateTime($_SESSION['pcpAPI']['diaapont']);
$inicio = $_GET['inicio'];
$fim = $_GET['fim'];

$diaInicio = new DateTime($inicio);
$diaFinal = new DateTime($fim);
$hora = new DateTime('now');
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PROGRAMAÇÂO BRASLAR - do dia
        <?php echo $diaInicio->format('d/m/Y')." até o dia ".$diaFinal->format('d/m/Y')." as ".$hora->format('h:m'); ?>
    </title>
    <link rel="stylesheet" href="../css/dataTables.dataTables.min.css">
    <script src="../jquery/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap-icons.css">
    <style>
    i {
        padding: 2px;
        margin: 2px;
    }
    </style>
    <style>
@media print {
            .quebra_pagina {
                page-break-before: always; /* Garante que o elemento comece em uma nova página */
            }

            /* Opcional: ajustar margens ou outros estilos para impressão */
            body {
                margin: 0 !important; 
            }

            table {
                page-break-inside: auto; /* Permite quebrar tabelas se forem muito longas */
                margin-left: 0 !important;
            }
            tr {
                page-break-inside: avoid; /* Evita quebrar linhas no meio */
                page-break-after: auto;
            }
            thead {
                display: table-header-group; /* Garante que o cabeçalho da tabela se repita em novas páginas */
            }
            tfoot {
                display: table-footer-group; /* Garante que o rodapé da tabela se repita em novas páginas */
            }
        }
</style>

</head>

<body>
    <div class="container" style="font-size: 80%;">
        <div class="container">
            <div class="container m-2 p-2">
                <h6>Braslar - Programação Peças data:
                    <?php echo $diaInicio->format('d/m/Y')." até o dia ".$diaFinal->format('d/m/Y')." as ".$hora->format('h:m'); ?>
                    </h3>
                   
            </div>
        </div>
         <div id="tbpecas" >
                       

        </div>
        <script src="../jQuery/dataTables.min.js"></script>
        <script>
        async function pegadados(inicio, fim) {
            let url = `http://10.1.2.251/api/getCodigosProgramacaoIntervalo/${inicio}/${fim}`;
            let response = await fetch(url);
            let data = await response.json();
            return data;
        }

        function criarTabelaPorSetorEMultiDias2(idTabela, titulo, dadosResumidos, elementoPaiId, prefixoSetor) {
    const tabelaExistente = document.getElementById(idTabela);
    if (tabelaExistente) {
        if ($.fn.DataTable.isDataTable(`#${idTabela}`)) {
            $(`#${idTabela}`).DataTable().destroy();
        }
        tabelaExistente.remove();
        // Remover o título anterior se houver
        const tituloExistente = document.querySelector(`#${elementoPaiId} h3[data-table-id="${idTabela}"]`);
        if(tituloExistente) tituloExistente.remove();
    }

    const elementoPai = document.getElementById(elementoPaiId);

    // 1. Filtrar os dados pelo prefixo do setor
    const dadosFiltrados = dadosResumidos.filter(item =>
        item.descricao.startsWith(prefixoSetor)
    );

    // Se não houver dados para o setor, não cria a tabela
    if (dadosFiltrados.length === 0) {
        // console.log(`Nenhum dado encontrado para o setor: ${prefixoSetor}`);
        return; // Sai da função se não houver dados para exibir
    }

    // 2. Extrair todos os dias únicos presentes nos dados filtrados e ordená-los
    const datasUnicasSet = new Set();
    dadosFiltrados.forEach(item => {
        datasUnicasSet.add(item.data_programada);
    });
    const datasOrdenadas = Array.from(datasUnicasSet).sort();

    // 3. Agrupar os dados por Código e Descrição, e consolidar as quantidades por dia
    const dadosAgrupados = {};
    dadosFiltrados.forEach(item => {
        const chaveProduto = item.codigo + '_' + item.descricao;
        if (!dadosAgrupados[chaveProduto]) {
            dadosAgrupados[chaveProduto] = {
                codigo: item.codigo,
                descricao: item.descricao,
                quantidadesPorDia: {} // Objeto para armazenar { 'data': quantidade }
            };
            // Inicializa todas as datas com 0 para este produto
            datasOrdenadas.forEach(data => {
                dadosAgrupados[chaveProduto].quantidadesPorDia[data] = 0;
            });
        }
        // Soma a quantidade para a data específica
        dadosAgrupados[chaveProduto].quantidadesPorDia[item.data_programada] += item.total_programado;
    });

    // Converter o objeto agrupado de volta para um array de objetos para fácil iteração
    const dadosParaTabela = Object.values(dadosAgrupados);

    // Ordenar os produtos por código
    dadosParaTabela.sort((a, b) => a.codigo.localeCompare(b.codigo));


    // Criar o título da tabela
    const tituloH3 = document.createElement('h3');
    tituloH3.textContent = titulo;
    tituloH3.classList.add('quebra_pagina');
    tituloH3.setAttribute('data-table-id', idTabela); // Ajuda a remover o título corretamente
    elementoPai.appendChild(tituloH3);

    // Criar a estrutura da tabela
    let tabela = document.createElement('table');
    tabela.classList.add('table', 'table-striped', 'table-bordered', 'table-hover');
    tabela.style.width = '100%'; // Ajuste para ocupar a largura total
    tabela.id = idTabela;

    // Criar o cabeçalho da tabela (thead)
    let thead = document.createElement('thead');
    let trHeader = document.createElement('tr');

    // Colunas fixas
    trHeader.innerHTML += `<th>Código</th>`;
    trHeader.innerHTML += `<th>Descrição</th>`;

    // Colunas dinâmicas para cada dia
    datasOrdenadas.forEach(data => {
        const diaFormatado = `${data.substring(8,10)}/${data.substring(5,7)}/${data.substring(0,4)}`;
        trHeader.innerHTML += `<th>Programado<br>${diaFormatado}</th>`;
    });

    // Colunas fixas adicionais
    trHeader.innerHTML += `<th>Estoque</th>`;
    trHeader.innerHTML += `<th>Realizado</th>`;

    thead.appendChild(trHeader);
    tabela.appendChild(thead);

    // Criar o corpo da tabela (tbody)
    let tbody = document.createElement('tbody');
    dadosParaTabela.forEach(produto => {
        let tr = document.createElement('tr');
        let codigoTd = document.createElement('td');
        codigoTd.textContent = produto.codigo;
        tr.appendChild(codigoTd);

        let descricaoTd = document.createElement('td');
        descricaoTd.textContent = produto.descricao;
        tr.appendChild(descricaoTd);

        // Adicionar células para as quantidades programadas por dia
        datasOrdenadas.forEach(data => {
            let quantidadeTd = document.createElement('td');
            quantidadeTd.classList.add('text-end');
            // Garante que a quantidade seja um número antes de formatar e somar, se necessário.
            // Aqui já vem somada pelo agrupamento
            const quantidade = produto.quantidadesPorDia[data] || 0; // Use 0 se não houver quantidade para a data
            quantidadeTd.textContent = Math.ceil(quantidade).toLocaleString('pt-BR');
            tr.appendChild(quantidadeTd);
        });

        // Células de Estoque e Realizado (vazias ou preenchidas conforme sua lógica real)
        let estoqueTd = document.createElement('td');
        estoqueTd.textContent = ''; // Lógica para preencher o estoque
        tr.appendChild(estoqueTd);

        let realizadoTd = document.createElement('td');
        realizadoTd.textContent = ''; // Lógica para preencher o realizado
        tr.appendChild(realizadoTd);

        tbody.appendChild(tr);
    });

    tabela.appendChild(tbody);
    elementoPai.appendChild(tabela);

    // Inicializar DataTable
    new DataTable(`#${idTabela}`, {
        paging: false,
        search: true,
        info: true,
        language: {
            search: "Busca",
            zeroRecords: "Nenhum registro encontrado.",
            infoEmpty: "Mostrando 0 a 0 de 0 registros",
            infoFiltered: "(filtrados de _MAX_ registros no total)"
        }
    });
}

function criarTabelaPorSetorEMultiDias(idTabela, titulo, dadosResumidos, elementoPaiId, includePrefix = null, excludePrefixes = []) {
    const tabelaExistente = document.getElementById(idTabela);
    if (tabelaExistente) {
        if ($.fn.DataTable.isDataTable(`#${idTabela}`)) {
            $(`#${idTabela}`).DataTable().destroy();
        }
        tabelaExistente.remove();
        const tituloExistente = document.querySelector(`#${elementoPaiId} h3[data-table-id="${idTabela}"]`);
        if(tituloExistente) tituloExistente.remove();
    }

    const elementoPai = document.getElementById(elementoPaiId);

    // 1. Filtrar os dados com base nos prefixos de inclusão/exclusão
    const dadosFiltrados = dadosResumidos.filter(item => {
        let passesFilter = true;

        // Se houver um prefixo para incluir, o item deve começar com ele
        if (includePrefix) {
            passesFilter = item.descricao.startsWith(includePrefix);
        }

        // Se houver prefixos para excluir, o item NÃO deve começar com nenhum deles
        if (passesFilter && excludePrefixes.length > 0) {
            const isExcluded = excludePrefixes.some(excludeP =>
                item.descricao.startsWith(excludeP)
            );
            passesFilter = !isExcluded; // Passa no filtro se NÃO for excluído
        }

        return passesFilter;
    });

    // Se não houver dados para o setor após a filtragem, não cria a tabela
    if (dadosFiltrados.length === 0) {
        // console.log(`Nenhum dado encontrado para o setor: ${titulo}`);
        return; // Sai da função se não houver dados para exibir
    }

    // 2. Extrair todos os dias únicos presentes nos dados filtrados e ordená-los
    const datasUnicasSet = new Set();
    dadosFiltrados.forEach(item => {
        datasUnicasSet.add(item.data_programada);
    });
    const datasOrdenadas = Array.from(datasUnicasSet).sort();

    // 3. Agrupar os dados por Código e Descrição, e consolidar as quantidades por dia
    const dadosAgrupados = {};
    dadosFiltrados.forEach(item => {
        const chaveProduto = item.codigo + '_' + item.descricao;
        if (!dadosAgrupados[chaveProduto]) {
            dadosAgrupados[chaveProduto] = {
                codigo: item.codigo,
                descricao: item.descricao,
                quantidadesPorDia: {} // Objeto para armazenar { 'data': quantidade }
            };
            datasOrdenadas.forEach(data => {
                dadosAgrupados[chaveProduto].quantidadesPorDia[data] = 0;
            });
        }
        dadosAgrupados[chaveProduto].quantidadesPorDia[item.data_programada] += item.total_programado;
    });

    const dadosParaTabela = Object.values(dadosAgrupados);
    dadosParaTabela.sort((a, b) => a.codigo.localeCompare(b.codigo));

    const tituloH3 = document.createElement('h3');
    tituloH3.textContent = titulo;
    tituloH3.setAttribute('data-table-id', idTabela);
    tituloH3.classList.add('quebra_pagina');
    elementoPai.appendChild(tituloH3);

    let tabela = document.createElement('table');
    tabela.classList.add('table', 'table-striped', 'table-bordered', 'table-hover', 'table-sm','small');
    tabela.style.width = '100%';
    tabela.id = idTabela;

    let thead = document.createElement('thead');
    let trHeader = document.createElement('tr');

    trHeader.innerHTML += `<th>Código</th>`;
    trHeader.innerHTML += `<th>Descrição</th>`;

    datasOrdenadas.forEach(data => {
        const diaFormatado = `${data.substring(8,10)}/${data.substring(5,7)}/${data.substring(0,4)}`;
        trHeader.innerHTML += `<th>Programado<br>${diaFormatado}</th>`;
    });

    trHeader.innerHTML += `<th>Estoque</th>`;
    trHeader.innerHTML += `<th>Realizado</th>`;

    thead.appendChild(trHeader);
    tabela.appendChild(thead);

    let tbody = document.createElement('tbody');
    dadosParaTabela.forEach(produto => {
        let tr = document.createElement('tr');
        let codigoTd = document.createElement('td');
        codigoTd.textContent = produto.codigo;
        tr.appendChild(codigoTd);

        let descricaoTd = document.createElement('td');
        descricaoTd.textContent = produto.descricao;
        tr.appendChild(descricaoTd);

        datasOrdenadas.forEach(data => {
            let quantidadeTd = document.createElement('td');
            quantidadeTd.classList.add('text-end');
            const quantidade = produto.quantidadesPorDia[data] || 0;
            quantidadeTd.textContent = Math.ceil(quantidade).toLocaleString('pt-BR');
            tr.appendChild(quantidadeTd);
        });

        let estoqueTd = document.createElement('td');
        estoqueTd.textContent = '';
        tr.appendChild(estoqueTd);

        let realizadoTd = document.createElement('td');
        realizadoTd.textContent = '';
        tr.appendChild(realizadoTd);

        tbody.appendChild(tr);
    });

    tabela.appendChild(tbody);
    elementoPai.appendChild(tabela);

    new DataTable(`#${idTabela}`, {
        paging: false,
        search: true,
        info: true,
        language: {
            search: "Busca",
            zeroRecords: "Nenhum registro encontrado.",
            infoEmpty: "Mostrando 0 a 0 de 0 registros",
            infoFiltered: "(filtrados de _MAX_ registros no total)"
        }
    });
}

document.addEventListener('DOMContentLoaded', async () => {
    // Certifique-se de que o elemento pai 'tbpecas' exista no seu HTML
    // <div id="tbpecas"></div>
    data = await pegadados('<?php echo $diaInicio->format('Y-m-d'); ?>', '<?php echo $diaFinal->format('Y-m-d'); ?>');
    const todosOsSetoresPrefixos = ['CT ', 'EST ', 'ESM ', 'PINT ']; // Defina todos os prefixos conhecidos

    // Setores específicos
    criarTabelaPorSetorEMultiDias('tabela_corte', 'Corte - Programação', data, 'tbpecas', 'CT ');
    criarTabelaPorSetorEMultiDias('tabela_estamparia', 'Estamparia - Programação', data, 'tbpecas', 'EST ');
    criarTabelaPorSetorEMultiDias('tabela_pintura', 'Pintura - Programação', data, 'tbpecas', 'PINT ');
    criarTabelaPorSetorEMultiDias('tabela_esmaltacao', 'Esmaltação - Programação', data, 'tbpecas', 'ESM ');

    // Setor Geral: inclui o que não começa com nenhum dos prefixos específicos
    criarTabelaPorSetorEMultiDias('tabela_geral', 'Geral - Programação', data, 'tbpecas', null, todosOsSetoresPrefixos);

   });
        </script>
        

       


</body>

</html>