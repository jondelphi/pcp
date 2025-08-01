<?php
session_start();
$dia = new DateTime($_SESSION['pcpAPI']['diaapont']);

$hora = new DateTime('now');
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PROGRAMAÇÂO BRASLAR - <?php echo $dia->format('d/m/Y') . " as " . $hora->format('H:i'); ?> </title>
    <link rel="stylesheet" href="../css/dataTables.dataTables.min.css">
    <script src="../jquery/jquery-3.7.1.min.js"></script>

    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap-icons.css">
    <style>
        i {
            padding: 2px;
            margin: 2px;
        }

        h3 {
            background: #666;
            color: white;
            width: 50%;
            margin: 0 auto;
            padding: 15px 30px;
        }

        /* --- Estilos para Impressão --- */
        @media print {

            /* Oculta tudo por padrão para ter controle total */
            body * {
                visibility: hidden;
            }

            /* Torna o container das tabelas visível */
            #tbpecas,
            #tbpecas * {
                visibility: visible;
            }

            /* Ajustes gerais para o corpo e containers na impressão */
            body {
                margin: 0;
                padding: 0;
            }

            .container {
                margin: 0;
                padding: 0;
                width: 100%;
            }

            /* Força a tabela a ocupar 100% da largura na impressão */
            table {
                width: 100% !important;
                font-size: 8pt;
                table-layout: auto;
                word-wrap: break-word;
            }

            th,
            td {
                padding: 4px !important;
                white-space: nowrap;
                /* Mantém o texto em uma linha para não quebrar a célula */
            }

            /* --- Quebra de Página por Tabela --- */
            /* Garante que cada tabela comece em uma nova página */
            #tbpecas table {

                margin-top: 20px;
                /* Adiciona uma margem superior para espaçamento entre tabelas impressas */
            }

            /* Opcional: Se a primeira tabela não precisa começar em uma nova página (apenas as subsequentes) */
            #tbpecas table:first-of-type {
                page-break-before: auto;
            }

            /* Oculta os títulos H3 que são gerados antes de cada tabela, se você não quiser eles na impressão */
            h3 {
                page-break-before: always;
                display: block;
                background: #666 !important;
                color: white !important;
                width: 100%;
                margin: 0 auto;
                padding: 15px 30px;
            }

            /* Oculta o título H6 principal que está fora do container tbpecas, mas dentro do 'container m-2 p-2' */
            /* Como usamos 'body * { visibility: hidden; }' já esconde tudo, esta regra é mais para reforço. */
            h6 {
                display: block;
            }

            /* Oculta os controles do DataTables (busca, info) na impressão */
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter,
            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_paginate,
            .dt-search {
                display: none !important;
            }
        }
    </style>
</head>

<body>
    <div class="container" style="font-size: 80%;">
        <div class="container">
            <div class="container m-2 p-2">
                <h6>Braslar - Programação Peças data: <?php echo $dia->format('d/m/Y') . " as " . $hora->format('H:i'); ?> </h3>
                    <div class="container" id="tbpecas">
                    </div>
            </div>
        </div>
    </div>
    <script src="../jQuery/dataTables.min.js"></script>
    <script>
        // Função para buscar os dados da API
        async function pegadados(dia) {
            let url = `http://10.1.2.251/api/getCodigosProgramacao/${dia}/${dia}`;
            try {
                let response = await fetch(url);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                let data = await response.json();
                console.log(data);
                return data;
            } catch (error) {
                console.error("Erro ao buscar dados:", error);
                return [];
            }
        }

        // Função principal para montar as tabelas
        async function monta_tabelas_filtradas(dia) {
            const dados = await pegadados(dia);

            const dadosCT = dados.filter(item => item.descricao.startsWith('CT '));
            const dadosEST = dados.filter(item => item.descricao.startsWith('EST '));
            const dadosPINT = dados.filter(item => item.descricao.startsWith('PINT '));
            const dadosESM = dados.filter(item => item.descricao.startsWith('ESM '));
            const dadosOutros = dados.filter(item =>
                !item.descricao.startsWith('CT ') &&
                !item.descricao.startsWith('EST ') &&
                !item.descricao.startsWith('PINT ') &&
                !item.descricao.startsWith('ESM ')
            );

           /*  function formatarDataEDiminuirUmDia(dataString, dias) {
                const dataObj = new Date(dataString + 'T00:00:00');
                dataObj.setDate(dataObj.getDate() - dias);

                // 3. Obter dia, mês e ano formatados
                const dia = String(dataObj.getDate()).padStart(2, '0'); // Garante dois dígitos (ex: '01' em vez de '1')
                const mes = String(dataObj.getMonth() + 1).padStart(2, '0'); // getMonth() retorna 0-11, então adicione 1
                const ano = dataObj.getFullYear();

                // 4. Retornar a data formatada como 'dd/mm/yyyy'
                return `${dia}/${mes}/${ano}`;

            } */

            function formatarDataEDiminuirUmDia(dataString, numDiasASubtrair) {
                const dataObj = new Date(dataString + 'T00:00:00');
                let diasUteisSubtraidos = 0;
                while (diasUteisSubtraidos < numDiasASubtrair) {
                    dataObj.setDate(dataObj.getDate() - 1);
                    const diaDaSemana = dataObj.getDay();
                    if (diaDaSemana !== 0 && diaDaSemana !== 6) {
                        diasUteisSubtraidos++; // Incrementa a contagem de dias úteis subtraídos
                    }

                }
                const dia = String(dataObj.getDate()).padStart(2, '0');
                const mes = String(dataObj.getMonth() + 1).padStart(2, '0');
                const ano = dataObj.getFullYear();
                return `${dia}/${mes}/${ano}`;
            }



            function criarTabela(idTabela, titulo, dadosFiltrados, elementoPaiId, dia) {
                const tabelaExistente = document.getElementById(idTabela);
                if (tabelaExistente) {
                    if ($.fn.DataTable.isDataTable(`#${idTabela}`)) {
                        $(`#${idTabela}`).DataTable().destroy();
                    }
                    tabelaExistente.remove();
                }

                const tituloH3 = document.createElement('h3');
                tituloH3.textContent = titulo;
                document.getElementById(elementoPaiId).appendChild(tituloH3);

                let tabela = document.createElement('table');
                tabela.classList.add('table', 'table-striped', 'table-bordered', 'table-hover');
                tabela.style.width = 'fit-content';
                tabela.id = idTabela;

                let thead = document.createElement('thead');
                thead.innerHTML = `<tr><th>Código</th><th>Descrição</th><th>Programado<br> ${dia.substring(8,10)}/${dia.substring(5,7)}/${dia.substring(0,4)}</th><th>Estoque<th>Realizado</th></tr>`;
                tabela.appendChild(thead);

                let tbody = document.createElement('tbody');
                for (let i = 0; i < dadosFiltrados.length; i++) {
                    let tr = document.createElement('tr');
                    let codigo = document.createElement('td');
                    let estoque = document.createElement('td');
                    codigo.innerHTML = dadosFiltrados[i].codigo;
                    let descricao = document.createElement('td');
                    descricao.innerHTML = dadosFiltrados[i].descricao;
                    let quantidade = document.createElement('td');
                    let realizado = document.createElement('td');

                    quantidade.classList.add('text-end');
                    quantidade.innerHTML = Math.ceil(dadosFiltrados[i].total_programado).toLocaleString('pt-BR');

                    tr.appendChild(codigo);
                    tr.appendChild(descricao);
                    tr.appendChild(quantidade);
                    tr.appendChild(estoque);
                    tr.appendChild(realizado);
                    tbody.appendChild(tr);
                }
                tabela.appendChild(tbody);
                document.getElementById(elementoPaiId).appendChild(tabela);

                new DataTable(`#${idTabela}`, {
                    paging: false,
                    search: true,
                    info: true,
                    language: {
                        search: "Busca"
                    }
                });
            }
           // let titu_ct = `Corte -Programação para ${formatarDataEDiminuirUmDia(dia, 0)}`;
            let titu_ct = `Corte -Programação`;
            let titu_est = `Estamparia -Programação`;
            let titu_pint = `Pintura -Programação`; 
            let titu_esm = `Esmaltação -Programação`;
            let titu_outros = `Geral -Programação`;

            criarTabela('tabela_ct', titu_ct, dadosCT, 'tbpecas', dia);
            criarTabela('tabela_est', titu_est, dadosEST, 'tbpecas',dia);
            criarTabela('tabela_pint', titu_pint, dadosPINT, 'tbpecas',dia);
            criarTabela('tabela_esm', titu_esm, dadosESM, 'tbpecas',dia);
            criarTabela('tabela_outros', titu_outros, dadosOutros, 'tbpecas',dia);

            jQuery.fn.dataTable.ext.order['formatted-num-pt-br-pre'] = function(data) {
                let parsed = data.replace(/\./g, '').replace(',', '.');
                return parseFloat(parsed) || 0;
            };

            Object.assign(DataTable.defaults, {
                language: {
                    search: "Busca"
                }
            });
        }

        monta_tabelas_filtradas('<?php echo $dia->format('Y-m-d'); ?>');
    </script>

</body>

</html>