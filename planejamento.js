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
                <td><span title='Código do Produto'>${item.codigo}</span></td>
            <td><span title='Descrição do Produto'>${item.descricao}</span></td>
            <td class='text-end' data-export='${item.quantidade_pedida}'><span title='Quantidade Pedida'>${formatNumber(item.quantidade_pedida)}</span></td>
            <td class='text-end' data-export='${item.quantidade_estoque}'><span title='Estoque Atual'>${formatNumber(item.quantidade_estoque)}</span></td>
            <td class='text-end' data-export='${item.quantidade_programada}'><span title='Quantidade Programada'>${formatNumber(item.quantidade_programada)}</span></td>
            <td class='text-end fw-bold text-danger' data-export='${quantidadeNecessaria}'><span title='Quantidade Necessária'>${formatNumber(quantidadeNecessaria)}</span></td>
            <td class='text-center'><span title='Períodos de Pedidos'>${textoDataPedido}</span></td>
            <td class='text-center'><span title='Períodos Programados'>${textoDataProgramada}</span></td>
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
                <th class="text-center">Períodos Pedidos</th>
                <th class="text-center">Períodos Programados</th>
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
        <td><span onclick='dadosPedido(${dados[i].id})'>${dados[i].destino}</span></td>
        <td class='text-end'>${dados[i].datapedido}</td>
        `
        tbody.appendChild(linha)

    }
    tabela.appendChild(tbody)
    container.appendChild(tabela)

 }
    async function dadosPedido(id){
        const container = document.getElementById('dadosPedido');       
        container.innerHTML = '<p class="text-center">Carregando dados do pedido, por favor aguarde...</p>';
        const url = `http://10.1.2.251/api/buscaPedido/${id}`;
        const request = await fetch(url)
        const dados = await request.json()
        const url2 = `http://10.1.2.251/api/getHistorioLogistica/${id}`
        const request2 = await fetch(url2)
        const historico = await request2.json()
        modalDadosPedido(dados, historico)
     }

 function modalDadosPedido(dados, historico) {
    const container = document.getElementById('dadosPedido');

    // Se não houver dados, limpa o container e encerra a função
    if (!dados || dados.length === 0) {
        container.innerHTML = '';
        return;
    }

    // 1. Construir as linhas (<tr>) da tabela de itens em uma string separada
    let itemsHtml = '';
    for (let i = 0; i < dados.length; i++) {
        itemsHtml += `
            <tr>
                <td>${dados[i].codigo}</td>
                <td>${dados[i].descricao}</td>
                <td>${dados[i].quantidade}</td>
            </tr>
        `;
    }

    // 2. Construir as linhas (<tr>) da tabela de histórico em outra string
    let historicoHtml = '';
    for (let j = 0; j < historico.length; j++) {
        historicoHtml += `
            <tr>
                <td>${historico[j].acao}</td>
                <td class='text-center'>${historico[j].data_formatada}</td>
                <td>${historico[j].user}</td>
            </tr>
        `;
    }

    // 3. Montar o HTML completo do card de uma só vez, inserindo as strings das tabelas
    const cardHtml = `
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white">
                <h2 class="h4 mb-0" id="fimmodal">Detalhes do Pedido </h2>
                <button type="button" class="btn-close btn-close-white float-end" aria-label="Close" onclick="limparDadosPedido()"></button>
            </div>
            <div class="card-body">
                <h3 class='card-title h5 border-bottom pb-2 mb-3'>Dados do Pedido</h3>
                <div class='row'>
                    <div class='col-md-6'>
                        <p><strong>Destino:</strong> ${dados[0].destino}</p>
                        <p><strong>Data do Pedido:</strong> ${dados[0].datapedido}</p>
                        <p><strong>Data de Aprovação:</strong> ${dados[0].dataaprovado}</p>
                    </div>
                    <div class='col-md-6'>
                        <p><strong>Usuário que cadastrou:</strong> ${dados[0].usuario}</p>
                        <p><strong>Observação:</strong> ${dados[0].observacao}</p>
                    </div>
                </div>
                <hr class="my-4">
                <h3 class="card-title h5 border-bottom pb-2 mb-3">Itens do Pedido</h3>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Codigo</th>
                                <th>Descrição</th>
                                <th>Quantidade</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${itemsHtml}
                        </tbody>
                    </table>
                </div>
                <hr class="my-4">
                <h3 class="card-title h5 border-bottom pb-2 mb-3">Histórico de Movimentos</h3>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Ação</th>
                                <th>Data</th>
                                <th>Usuário</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${historicoHtml}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    `;

    // 4. Atribuir o HTML completo ao container uma única vez
    container.innerHTML = cardHtml;
    navegarAteAncora();

}

function navegarAteAncora() {
    const elemento = document.getElementById('fimmodal');
    if (elemento) {
        elemento.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

function limparDadosPedido() {
    const container = document.getElementById('dadosPedido');
    container.innerHTML = '';
}

    /* 
    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">
            <h2 class="h4 mb-0">Detalhes do Pedido</h2>
        </div>
        <div class="card-body">
            <h3 class="card-title h5 border-bottom pb-2 mb-3">Dados do Pedido</h3>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Destino:</strong> PRATTICA</p>
                    <p><strong>Data do Pedido:</strong> 10/09/2025</p>
                    <p><strong>Data de Aprovação:</strong> </p>
                </div>
                <div class="col-md-6">
                    <p><strong>Usuário que cadastrou:</strong> vitor</p>
                    <p><strong>Observação:</strong> </p>
                    <p><strong>Pronto para carregar:</strong>
                        <span class="badge bg-secondary">Não</span>                    </p>
                </div>
            </div>

            <hr class="my-4">

            <h3 class="card-title h5 border-bottom pb-2 mb-3">Itens do Pedido</h3>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Codigo</th>
                            <th>Descrição</th>
                            <th>Quantidade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                                <td>10010017</td>
                                <td>FOGAO 4BC ASIATICO JR BRANCO</td>
                                <td>14</td>
                            </tr><tr>
                                <td>10010030</td>
                                <td>FOGAO ASIÁTICO 4BC BLACK GLASS</td>
                                <td>6</td>
                            </tr><tr>
                                <td>10010103</td>
                                <td>FOGAO 4 BC NEW SIRIUS BCO </td>
                                <td>16</td>
                            </tr><tr>
                                <td>10010104</td>
                                <td>FOGAO 4 BC NEW SIRIUS BLACK </td>
                                <td>10</td>
                            </tr><tr>
                                <td>10010106</td>
                                <td>FOGAO 4 BC NEW SIRIUS PLUS BLACK</td>
                                <td>3</td>
                            </tr><tr>
                                <td>10010111</td>
                                <td>FOGAO 4 BC NEW SIRIUS BLACK S/ TAMPA</td>
                                <td>20</td>
                            </tr><tr>
                                <td>10010112</td>
                                <td>FOGAO 4 BC NEW SIRIUS BCO S/ TAMPA </td>
                                <td>17</td>
                            </tr><tr>
                                <td>10010114</td>
                                <td>FOGAO 4 BC CARINA BLACK</td>
                                <td>12</td>
                            </tr><tr>
                                <td>10010115</td>
                                <td>FOGAO 4 BC CARINA TITANIUM</td>
                                <td>12</td>
                            </tr><tr>
                                <td>10020053</td>
                                <td>FOGAO 5 BC HORUS TOP CONTROL BLACK</td>
                                <td>1</td>
                            </tr><tr>
                                <td>10020079</td>
                                <td>FOGAO 5 BC NEW SIRIUS PLUS BCO</td>
                                <td>8</td>
                            </tr><tr>
                                <td>10020080</td>
                                <td>FOGAO 5 BC CARINA BLACK</td>
                                <td>12</td>
                            </tr><tr>
                                <td>10020081</td>
                                <td>FOGAO 5 BC CARINA TITANIUM</td>
                                <td>13</td>
                            </tr><tr>
                                <td>10020085</td>
                                <td>FOGAO 5 BC NEW SIRIUS PLUS BLACK</td>
                                <td>4</td>
                            </tr><tr>
                                <td>10050016</td>
                                <td>FOGAO COOKTOP 4 BC POP PRETO</td>
                                <td>14</td>
                            </tr><tr>
                                <td>10050017</td>
                                <td>FOGAO COOKTOP 5 BC POP PRETO</td>
                                <td>59</td>
                            </tr>                    </tbody>
                </table>
            </div>

            <hr class="my-4">

            <h3 class="card-title h5 border-bottom pb-2 mb-3">Histórico de Movimentos</h3>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                     <thead class="table-light">
                        <tr>
                            <th>Ação</th>
                            <th>Data</th>
                            <th>Usuário</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                                <td>Cadastrou o pedido</td>
                                <td>05/09/2025 16:33</td>
                                <td>vitor</td>
                            </tr>                    </tbody>
                </table>
            </div>
        </div>
       
    </div>
    
    
    */
