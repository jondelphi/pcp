<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Folha de Processo</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap-icons.css">
</head>
<?php


function lerArquivoCSV($nomeArquivo)
{
    // Array para armazenar os dados do CSV
    $dados = array();

    // Abre o arquivo CSV para leitura
    if (($handle = fopen($nomeArquivo, "r")) !== FALSE) {
        // Lê cada linha do arquivo CSV
        while (($linha = fgetcsv($handle, 1000, ";")) !== FALSE) {
            // Cria um array associativo com os valores da linha
            $registro = array(
                'cod' => $linha[0],
                'desc' => $linha[1],
                'qtd' => $linha[2],
                'un' => $linha[3]
            );
            // Adiciona o registro ao array de dados
            $dados[] = $registro;
        }
        // Fecha o arquivo CSV
        fclose($handle);
    }

    // Retorna o array com os dados do CSV
    return $dados;
}





?>

<body>
    <div class="container">
        <form action="folha.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="" class="form-label">Choose file</label>
                <input type="file" class="form-control" name="arquivo" id="" placeholder="" aria-describedby="fileHelpId" />
                <div id="fileHelpId" class="form-text">Help text</div>
            </div>
            <div>
                <input type="submit" value="OK">
            </div>
        </form>
    </div>


    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Verifica se o arquivo foi enviado sem erros
        if (isset($_FILES["arquivo"]) && $_FILES["arquivo"]["error"] == UPLOAD_ERR_OK) {

            echo ("<pre>");
            var_dump($_FILES["arquivo"]);
            echo ("</pre>");

            /*   echo ("<pre>");
            $dados = lerArquivoCSV($_FILES["arquivo"]['full_path']);
            var_dump($dados);
            echo ("</pre>"); */

            $caminhoArquivo = 'folha.xlsx';
        } else {
            echo "Erro no envio do arquivo.";
        }
    } else {
        echo "Nenhum arquivo enviado.";
    }


    ?>

    <script src="bootstrap/js/bootstrap.js"></script>
    <script src="bootstrap/js/bootstrap.bundle.js"></script>
    <script src="bootstrap/js/popper.min.js"></script>
</body>

</html>