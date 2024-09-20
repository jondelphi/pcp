<?php
class ClasseRequest
{
    public function estoque($status, $categoria, $familia, $local)
    {
        $curl = curl_init();
        $url = "https://gateway.korp.com.br/flow/workflow/webhook/InformacoesEstoque?Status=" . $status . "&Categoria=" . $categoria . "&Familia=" . $familia . "&Local=" . $local;
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => [
                "Authorization: Basic bWFqR0JxclRlREZiRVVHeDpjS1ZUSWZRWDFINUpYd2UyMjM3UndRPT0=",
                "User-Agent: insomnia/8.2.0"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        sleep(6);
    }
    public function etiqueta($serie)
    {

        $curl = curl_init();
        $url = "https://gateway.korp.com.br/flow/workflow/webhook/InformacoesEtiqueta?Serie=" . $serie;
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => [
                "Authorization: Basic bWFqR0JxclRlREZiRVVHeDpjS1ZUSWZRWDFINUpYd2UyMjM3UndRPT0=",
                "User-Agent: insomnia/8.2.0"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        sleep(6);
    }
    public function odfs($arr)
    {
        $url = "https://gateway.korp.com.br/flow/workflow/webhook/InformacoesOrdemProducao?ODF=%20";
        foreach ($arr as $k) {
            $url .= $k . "%2C";
        }
        $url = substr($url, 0, -3);
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => [
                "Authorization: Basic bWFqR0JxclRlREZiRVVHeDpjS1ZUSWZRWDFINUpYd2UyMjM3UndRPT0=",
                "User-Agent: insomnia/8.2.0"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        sleep(6);
    }

    public function dadosodf($odf)
    {
        $url = "https://gateway.korp.com.br/flow/workflow/webhook/InformacoesOrdemProducao?ODF=%20";

        $url .= $odf;
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => [
                "Authorization: Basic bWFqR0JxclRlREZiRVVHeDpjS1ZUSWZRWDFINUpYd2UyMjM3UndRPT0=",
                "User-Agent: insomnia/8.2.0"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        sleep(6);
    }
}
?>
<?php

$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => "https://gateway.korp.com.br/flow/workflow/webhook/InformacoesOrdemProducao?ODF=%2099136%2C99076%2C99491%2C99139%2C99506%2C99137%2C99492%2C99080%2C98439%2C98205%2C99140%2C98632%2C99489%2C99495%2C99532",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "",
    CURLOPT_HTTPHEADER => [
        "Authorization: Basic bWFqR0JxclRlREZiRVVHeDpjS1ZUSWZRWDFINUpYd2UyMjM3UndRPT0=",
        "User-Agent: insomnia/8.2.0"
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    echo $response;
}
