<?php

// verifica se existe o usuario e password
if(!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']))
{
    echo json_encode([
        'status'=>"Error",
        'message'=>"Invalid access"
    ]);
    die;
}

// usuários permitidos
$usuarios = [
    ['user'=>'Andre','password'=>'123'],
    ['user'=>'Luis','password'=>'321'],
    ['user'=>'Joao','password'=>'132'],
];

// verifica se o usuário e password são validos
$user = $_SERVER['PHP_AUTH_USER'];
$pass = $_SERVER['PHP_AUTH_PW'];

$valid_authentication =  false;

foreach($usuarios as $usuario)
{
    if($usuario['user']==$user && $usuario['password']==$pass)
    {
        $valid_authentication = true;
    }
}

if(!$valid_authentication)
{
    echo json_encode([
        'status'=>"Error",
        'message'=>'invalid authorization'
    ]);
    die;
}

echo json_encode([
    'status'=>"Success",
    'message'=>"Welcome to the API"
]);