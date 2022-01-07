<?php
define('ROOT',true);
//se não existir sessão ela sera iniciada
if(session_status()===PHP_SESSION_NONE) session_start();

require_once('inc/config.php');
require_once('inc/database.php');
require_once('inc/html_header.php');

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
    //interior do BackOffice;
    $rota = 'home';
}

//define qual a rota o usuário acessara
switch ($rota) {
    case 'login':
        require_once('login.php');
        break;

    case 'login_submit':
        require_once('login_submit.php');
        break;

    case 'home':
        require_once('BO/home.php');
        break;

    default:
        echo 'rota não definida';
        break;
}

require_once('inc/html_footer.php');