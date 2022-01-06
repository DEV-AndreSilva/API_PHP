<?php
require_once 'inc/config.php';

$variables = ['id'=>10, 'nome'=>'Andre', 'idade'=>18];

//echo password_hash('123456',PASSWORD_DEFAULT);
//die();

$result = api_request('get_datetime','GET',$variables,);
echo '<pre>';
print_r($result);
echo '</pre>';

function api_request($endpoint, $method ='GET', $variables = [], $user = API_USER, $password = API_PASSWORD)
{
    //iniciando a variavel do curl
    $curl =  curl_init();

    //Defininido os headers da requisição
    $headers =  array(
        'Content-Type: application/json', //tipo
        'Authorization: Basic '.base64_encode("$user:$password") //formato de autorização
    );

    //preenchendo headers
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    //indicando o retorno de informações como string
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

    //define a URL base
    $url = API_BASE_URL . $endpoint .'/';

    //Verificando se é uma requisição do tipo GET
    if($method == 'GET')
    {
        //Se existir variaveis
        if(!empty($variables))
        {
            //concatenando as variaveis da query string da requisição
            $url.='?'.http_build_query($variables);
        }

    }

    //Verificando se é uma requisição do tipo POST
    if($method == 'POST')
    {
        //definindo as variaveis da requisição por post
        $variables = array_merge(['endpoint'=>$endpoint], $variables);

        curl_setopt($curl, CURLOPT_POSTFIELDS,  $variables);
    }

    //Indicando a URL da busca
    curl_setopt($curl, CURLOPT_URL, $url);

    //executando o CURL
    $response = curl_exec($curl);

   
    //verifica se há error
    if(curl_errno($curl))
    {
        throw new Exception(curl_errno($curl));
    }

    //fechamento da variavel curl
    curl_close($curl);

   return json_decode($response, true);
}

