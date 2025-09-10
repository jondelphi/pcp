async function atualizarPlanejamento() {
    const dataFinal = document.getElementById('data_final').value;
    const url = `http://10.1.2.251/api/gerarPlanejamento/${dataFinal}`;
    const container = document.getElementById('necessidades');

    // 1. Adiciona um feedback de "carregando"
    container.innerHTML = '<p class="text-center">Carregando dados, por favor aguarde...</p>';

    try {
        const request = await fetch(url);
        
        // 2. Verifica se a requisição foi bem sucedida
        if (!request.ok) {
            throw new Error(`Erro na requisição: ${request.statusText}`);
        }

        const planejamento = await request.json();
        tabela_planejamento(planejamento);

    } catch (error) {
        // 3. Mostra uma mensagem de erro clara
        console.error("Falha ao buscar planejamento:", error);
        container.innerHTML = '<p class="text-center text-danger">Não foi possível carregar os dados. Verifique a conexão ou tente novamente.</p>';
    }
}

    /**
 * Formata uma string de data (ex: '2025-09-09') para o formato 'dd/mm/aa'.
 * Retorna 'N/A' se a data for inválida ou nula.
 * @param {string | null} dataString A string da data a ser formatada.
 * @returns {string} A data formatada ou 'N/A'.
 */
function formatarData(dataString) {
    if (!dataString) {
        return 'N/A';
    }
    const data = new Date(dataString);
    // Verifica se a data é válida após a conversão
    if (isNaN(data.getTime())) {
        return 'N/A';
    }

    // Adiciona um dia para corrigir problemas de fuso horário que podem "voltar" a data
    data.setDate(data.getDate() + 1);

    const dia = String(data.getDate()).padStart(2, '0');
    const mes = String(data.getMonth() + 1).padStart(2, '0'); // Meses começam do 0
    const ano = String(data.getFullYear()).slice(-2); // Pega os dois últimos dígitos

    return `${dia}/${mes}/${ano}`;
}
    
function tabela_planejamento(dados) {
    const container = document.getElementById('necessidades');
    let totalPedido = 0;
    let totalEstoque = 0;
    let totalProgramado = 0;
    let totalquantidadeNecessaria = 0;
    
    // Trata o caso de não haver dados
    if (!dados || dados.length === 0) {
        container.innerHTML = '<p class="text-center text-muted">Nenhuma necessidade de produção encontrada para o período.</p>';
        return;
    }

    // Usando Template Literals para melhor legibilidade
    const linhasTabela = dados.map(item => {
        const quantidadeNecessaria = item.quantidade_pedida - item.quantidade_estoque;
  

        // Lógica para exibir o intervalo de datas de pedido
        const dataPedidoMenor = formatarData(item.menor_data_pedido);
        const dataPedidoMaior = formatarData(item.maior_data_pedido);
        const textoDataPedido = dataPedidoMenor === dataPedidoMaior ? dataPedidoMenor : `${dataPedidoMenor} - ${dataPedidoMaior}`;

        // Lógica para exibir o intervalo de datas de programação
        const dataProgMenor = formatarData(item.menor_data_programada);
        const dataProgMaior = formatarData(item.maior_data_programada);
        const textoDataProgramada = dataProgMenor === dataProgMaior ? dataProgMenor : `${dataProgMenor} - ${dataProgMaior}`;

        // Adiciona formatação de número para milhares
        const formatNumber = (num) => num.toLocaleString('pt-BR');

        return `
            <tr>
                <td>${item.codigo}</td>
            <td>${item.descricao}</td>
            <td class='text-end' data-export='${item.quantidade_pedida}'>${formatNumber(item.quantidade_pedida)}</td>
            <td class='text-end' data-export='${item.quantidade_estoque}'>${formatNumber(item.quantidade_estoque)}</td>
            <td class='text-end' data-export='${item.quantidade_programada}'>${formatNumber(item.quantidade_programada)}</td>
            <td class='text-end fw-bold text-danger' data-export='${quantidadeNecessaria}'>${formatNumber(quantidadeNecessaria)}</td>
            <td class='text-center'>${textoDataPedido}</td>
            <td class='text-center'>${textoDataProgramada}</td>
            </tr>
        `;
    }).join(''); // Junta todos os <tr> em uma única string

    const tabelaCompleta = `
    <table class="table table-striped table-bordered table-hover" id="tabela_planejamento">
        <thead class="table-dark">
            <tr>
                <th>Código</th>
                <th>Descrição</th>
                <th class="text-end">Total Pedido</th>
                <th class="text-end">Estoque Atual</th>
                <th class="text-end">Qtd. Programada</th>
                <th class="text-end">Qtd. Necessária</th>
                <th class="text-center">Período Pedidos</th>
                <th class="text-center">Período Programado</th>
            </tr>
        </thead>
        <tbody>
            ${linhasTabela}
        </tbody>
       
        <tfoot class="table-dark fw-bold">
            <tr>
                <th colspan="2" class="text-end">TOTAIS:</th>
                <th class="text-end"></th> 
                <th class="text-end"></th> 
                <th class="text-end"></th> 
                <th class="text-end text-danger"></th> 
                <th></th> 
                <th></th> 
            </tr>
        </tfoot>
    </table>
`;

    container.innerHTML = tabelaCompleta;

    // --- INÍCIO DA INICIALIZAÇÃO DO DATATABLES ---
    // Este código é executado DEPOIS que a tabela foi inserida na página
   $('#tabela_planejamento').DataTable({
    paging: false,
    searching: true,
    dom: 'Bfrtip', 
    buttons: [
        // Seus botões de exportação continuam aqui, sem alterações...
        {
            extend: 'excelHtml5',
            text: 'Exportar para Excel',
            className: 'btn btn-secondary',
            title: 'Planejamento_Producao',
            exportOptions: {
                columns: ':visible',
                format: {
                    body: function ( data, row, column, node ) {
                        var exportData = $(node).data('export');
                        return exportData !== undefined ? exportData : data;
                    }
                }
            }
        },
        {
            extend: 'pdfHtml5',
            text: 'Exportar para PDF',
            className: 'btn btn-secondary',
            title: 'Planejamento_Producao',
            orientation: 'landscape',
            pageSize: 'A4',
            exportOptions: {
                columns: ':visible'
            }
        }
    ],
    columnDefs: [
        { "orderable": false, "targets": [1, 3, 4, 6, 7] }
    ],

    // ADICIONADA A FUNÇÃO DE CALLBACK PARA O RODAPÉ
    footerCallback: function (row, data, start, end, display) {
        const api = this.api();

        // Função auxiliar para remover a formatação e converter para número
        const intVal = function (i) {
            return typeof i === 'string'
                ? i.replace(/[\.]/g, '') * 1 // Remove pontos e converte para número
                : typeof i === 'number'
                ? i
                : 0;
        };

        // Função para formatar o número final com separador de milhar
        const formatNumber = (num) => num.toLocaleString('pt-BR');

        // Itera sobre as colunas que queremos somar (2, 3, 4, 5)
        [2, 3, 4, 5].forEach(colIndex => {
            // Soma os dados da página ATUAL (visível) para a coluna atual
            const total = api
                .column(colIndex, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            
            // Atualiza o conteúdo da célula do rodapé correspondente
            api.column(colIndex).footer().innerHTML = formatNumber(total);
        });
    },

    language: {
        // Sua tradução continua aqui...
        "sEmptyTable": "Nenhum registro encontrado",
        "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
        "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
        "sInfoFiltered": "(Filtrados de _MAX_ registros no total)",
        "sInfoPostFix": "",
        "sInfoThousands": ".",
        "sLengthMenu": "_MENU_ resultados por página",
        "sLoadingRecords": "Carregando...",
        "sProcessing": "Processando...",
        "sZeroRecords": "Nenhum registro encontrado",
        "sSearch": "Pesquisar:",
        "oPaginate": {
            "sNext": "Próximo",
            "sPrevious": "Anterior",
            "sFirst": "Primeiro",
            "sLast": "Último"
        },
        "oAria": {
            "sSortAscending": ": Ordenar colunas de forma ascendente",
            "sSortDescending": ": Ordenar colunas de forma descendente"
        }
    }
});
    // --- FIM DA INICIALIZAÇÃO DO DATATABLES ---
}