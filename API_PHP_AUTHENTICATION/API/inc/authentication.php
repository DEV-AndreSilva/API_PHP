<?php
require_once 'config.php';
require_once 'database.php';

// verifica se existe o usuario e password
if(!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']))
{
    echo json_encode([
        'status'=>"Error",
        'message'=>"Invalid access"
    ]);
    die;
}
// verifica se o usuário e password são validos
$user = $_SERVER['PHP_AUTH_USER'];
$pass = $_SERVER['PHP_AUTH_PW'];

$params = [':user'=>$user, ':password'=>$pass];

//Verificar  se a autenticação é valida
$db = new database();

//Query de busca de usuários validos
$result = $db->EXE_QUERY("SELECT * FROM AUTHENTICATION WHERE user_name=:user AND passwrd=:password", $params);

$valid_authentication =  false;
if(count($result)==1)
{
    $valid_authentication =  true;
}

if(!$valid_authentication)
{
    echo json_encode([
        'status'=>"Error",
        'message'=>'invalid authorization'
    ]);
    die;
}

