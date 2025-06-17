/**
 * Cria e exibe uma tabela dinâmica com programação por dia e filtro por setor.
 * @param {string} idTabela O ID da tabela a ser criada (ex: 'tabela_corte').
 * @param {string} titulo O título a ser exibido acima da tabela.
 * @param {Array<Object>} dadosResumidos O array de dados já resumido por código e data (seu `resumo_final`).
 * @param {string} elementoPaiId O ID do elemento HTML onde a tabela será adicionada (ex: 'tbpecas').
 * @param {string} prefixoSetor O prefixo da descrição para filtrar os itens (ex: 'CT ' para Corte).
 */
function criarTabelaPorSetorEMultiDias(idTabela, titulo, dadosResumidos, elementoPaiId, prefixoSetor) {
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




// Chamar a função para cada setor
document.addEventListener('DOMContentLoaded', () => {
    // Certifique-se de que o elemento pai 'tbpecas' exista no seu HTML
    // <div id="tbpecas"></div>

    criarTabelaPorSetorEMultiDias('tabela_corte', 'Corte - Programação', data, 'tbpecas', 'CT ');
    criarTabelaPorSetorEMultiDias('tabela_estamparia', 'Estamparia - Programação', data, 'tbpecas', 'EST ');
    criarTabelaPorSetorEMultiDias('tabela_pintura', 'Pintura - Programação', data, 'tbpecas', 'PINT '); // Ajuste o prefixo real se for diferente
    criarTabelaPorSetorEMultiDias('tabela_esmaltacao', 'Esmaltação - Programação', data, 'tbpecas', 'ESM '); // Ajuste o prefixo real se for diferente
    criarTabelaPorSetorEMultiDias('tabela_geral', 'Geral - Programação', data, 'tbpecas', ''); // Use prefixo vazio para "outros" ou defina uma lógica mais específica
});