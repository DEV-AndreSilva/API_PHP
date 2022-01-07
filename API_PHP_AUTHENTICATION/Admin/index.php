<?php
define('ROOT',true);

require_once('inc/config.php');
require_once('inc/database.php');
require_once('inc/html_header.php');

//se não existir sessão ela sera iniciada
if(session_status()===PHP_SESSION_NONE) session_start();

$rota = '';

//Verifica se o administrador não esta logado e a requisição da pagina não é post
if(!isset($_SESSION['id_admin']) && $_SERVER['REQUEST_METHOD'] !='POST' )
{
    $rota = 'login';
}
//Verifica se o administrador não esta logado e a requisição da pagina é post
else if(!isset($_SESSION['id_admin']) && $_SERVER['REQUEST_METHOD'] =='POST')
{
    $rota = 'login_submit';
}
else
{

}

//define qual a rota o usuário acessara
switch ($rota) {
    case 'login':
        require_once('login.php');
        break;

    case 'login_submit':
        require_once('login_submit.php');
        break;

    default:
        echo 'rota não definida';
        break;
}

require_once('inc/html_footer.php');