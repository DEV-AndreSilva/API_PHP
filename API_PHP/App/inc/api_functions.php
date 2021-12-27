<?php

function api_request($endpoint, $method = 'GET', $variables = [])
{
    //Iniciando CURL
    $cliente = curl_init();

    //Retornando os valores da consulta como uma string
    curl_setopt($cliente, CURLOPT_RETURNTRANSFER, TRUE);

    //Definindo URL da consulta
    $url = API_BASE_URL;

    //Verificando se é uma requisição do tipo GET
    if($method == 'GET')
    {
        $url.="?endpoint=$endpoint";

        //Se existir variaveis
        if(!empty($variables))
        {
            $url.="&".http_build_query($variables);
        }
    }

    //Verificando se é uma requisição do tipo POST
    if($method == 'POST')
    {
        $variables = array_merge(['endpoint'=>$endpoint], $variables);
        curl_setopt($cliente, CURLOPT_POSTFIELDS,  $variables);
    }

    //Indicando a URL da busca
    curl_setopt($cliente, CURLOPT_URL, $url);

    //Executando consulta
    $response = curl_exec($cliente);


    //var_dump($response);
    //die();

    //Retorno
    return json_decode($response, true);
   
   
}