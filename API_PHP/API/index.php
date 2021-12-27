<?php
//dependencias
require_once(dirname(__FILE__).'/inc/api_response.php');
require_once(dirname(__FILE__).'/inc/api_logic.php');
require_once(dirname(__FILE__).'/inc/database.php');

//--------------------------------------------------------------

//Instancia da classe
$api_response = new api_response();

//--------------------------------------------------------------

//Checando se o método é invalido
if(!$api_response->check_method($_SERVER['REQUEST_METHOD']))
{
    //envio da resposta ao usuário
    $api_response->api_request_error('Invalid request Method');
}

//--------------------------------------------------------------

//O método é valido, então é salvo na classe da API
$api_response->set_method($_SERVER['REQUEST_METHOD']);
$params =  null;

//--------------------------------------------------------------

//Verifica o método da requisição
if($api_response->get_method() == "GET")
{
    if(isset($_GET['endpoint']))
    {
         //salva o endpoint na classe
        $api_response->set_endpoint($_GET['endpoint']);
        $params =  $_GET;    
    }
    else{
        $api_response->api_request_error('Não foi informado o endpoint da consulta');
    }
    
}
else if($api_response->get_method() == "POST")
{
    if(isset($_POST['endpoint']))
    {
        //salva o endpoint na classe
        $api_response->set_endpoint($_POST['endpoint']);
        $params =  $_POST;
    }
    else
    {
        $api_response->api_request_error('Não foi informado o endpoint da consulta');
    }
}

//--------------------------------------------------------------
$api_logic =  new api_logic($api_response->get_endpoint(), $params);

//verifica se o endpoint não existe
if(!$api_logic->endpoint_exists())
{
    $api_response->api_request_error("O Endpoint que você deseja acessar não existe (".$api_response->get_endpoint().")");
}

//chamando o método da api_logic solicitado pela api_response
//$result = $api_logic->status();
$result = $api_logic->{$api_response->get_endpoint()}();

$api_response->add_to_data('data',$result);

$api_response->send_response();