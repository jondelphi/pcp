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
    <title>PROGRAMAÇÂO BRASLAR - <?php echo $dia->format('d/m/Y')." as ".$hora->format('h:m'); ?> </title>
    <link rel="stylesheet" href="../css/dataTables.dataTables.min.css">
    <script src="../jquery/jquery-3.7.1.min.js"></script>

    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap-icons.css">
    
</head>

<body>
    <div class="container" style="font-size: 80%;">
        <div class="container">
            <div class="container m-2 p-2" id="processo">
              
            </div>
        </div>
    </div>
    <script src="../jQuery/dataTables.min.js"></script>
    <script>
        async function pecas(produto){
            let url = "http://10.1.2.251/api/getEstrutura/"+produto;
            let response = await fetch(url);
            let data = await response.json();
            return data;
        }
        async function monta_tabela(produto){
            const dados = await pecas(produto);
            let tabela = document.createElement('table');
            tabela.classList.add('table', 'table-striped', 'table-bordered', 'table-hover');
            tabela.style.width = 'fit-content';
            tabela.id = 'tabela_estrutura';
            let thead = document.createElement('thead');
            thead.innerHTML = `<tr><th>Código</th><th>Descrição</th><th>Revisão</th><th>Quantidade</th></tr>`;
            tabela.appendChild(thead);
            let tbody = document.createElement('tbody');
            for(let i = 0; i < dados.length; i++){
                let tr = document.createElement('tr');
                let codigo = document.createElement('td');
                codigo.innerHTML = dados[i].codigo;
                let descricao = document.createElement('td');
                descricao.innerHTML = dados[i].descricao;
                let revisao = document.createElement('td');
                revisao.innerHTML = dados[i].revisao;
                let quantidade = document.createElement('td');
                quantidade.classList.add('text-end');
                quantidade.innerHTML =Math.ceil( dados[i].quantidade);
                tr.appendChild(codigo);
                tr.appendChild(descricao);
                tr.appendChild(revisao);
                tr.appendChild(quantidade);
                tbody.appendChild(tr);
            }
                tabela.appendChild(tbody);
                document.getElementById('processo').appendChild(tabela);
                jQuery.fn.dataTable.ext.order['formatted-num-pt-br-pre'] = function(data) {
            // Remove os pontos de milhar e substitui a vírgula por um ponto
            let parsed = data.replace(/\./g, '').replace(',', '.');
            return parseFloat(parsed) || 0;
        };

        Object.assign(DataTable.defaults, {
            search: true,
            info: false,
            paging: false,
            language: {
                search: "Busca"
            }
        });


        new DataTable('#tabela_estrutura');
       

        }
        monta_tabela('<?php echo $_GET['produto']; ?>');
    </script>

</body>

</html>