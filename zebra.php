<?php 

function lerCSVImprimirColuna($caminhoArquivo)
{
    // Abre o arquivo CSV para leitura
    $arquivo = fopen($caminhoArquivo, 'r');

    if ($arquivo) {
        // Loop para ler cada linha do arquivo
        while (($linha = fgetcsv($arquivo)) !== false) {
            // Acesse a primeira coluna (índice 0) e imprima o valor

            $valor="^XA<br>
            ^FO40,60^A0N,40,25^FD".$linha[0]."^FS<br>
            ^FO30,220^A0N,40,25^FD".$linha[0]."^FS<br>
            ^XZ<br>";
            echo $valor . "<br>";
        }

        // Fecha o arquivo
        fclose($arquivo);
    } else {
        echo "Erro ao abrir o arquivo CSV.";
    }
}
function quebrarString($texto) {
    // Encontrar a última posição do espaço na string
    $posicaoUltimoEspaco = strrpos($texto, ' ');

    if ($posicaoUltimoEspaco !== false) {
        // Dividir a string no último espaço encontrado
        $parte1 = substr($texto, 0, $posicaoUltimoEspaco);
        $parte2 = substr($texto, $posicaoUltimoEspaco + 1);

        // Retornar um array com as duas partes
        return array($parte1, $parte2);
    } else {
        // Se não houver espaços, retornar a string original como a primeira parte e uma string vazia como a segunda parte
        return array($texto, '');
    }
}

function lerCSVImprimirColuna2($caminhoArquivo)
{
    // Abre o arquivo CSV para leitura
    $arquivo = fopen($caminhoArquivo, 'r');
    $pula=1;

    if ($arquivo) {
        // Loop para ler cada linha do arquivo
        while (($linha = fgetcsv($arquivo)) !== false) {
            // Acesse a primeira coluna (índice 0) e imprima o valor
            $valor="";
            $texto=quebrarString($linha[0]);
          if($pula==1){
           
            $pula++;
            $valor="^XA<br>
            ^FO40,60^A0N,40,25^FD".$texto[0]."^FS<br>
            ^FO40,100^A0N,40,25^FD".$texto[1]."^FS<br>
            ";
            

          }else{
            $pula=1;
            $valor.="^FO410,60^A0N,40,25^FD".$texto[0]."^FS<br>
            ^FO410,100^A0N,40,25^FD".$texto[1]."^FS<br>
            ^XZ<br>";
          }

          echo $valor;
        }
        if($pula>1){
            echo "^XZ<br>";
        }

        // Fecha o arquivo
        fclose($arquivo);
    } else {
        echo "Erro ao abrir o arquivo CSV.";
    }
}
// Exemplo de uso
$caminhoDoArquivo = 'nomes.csv';
lerCSVImprimirColuna2($caminhoDoArquivo);


?>


