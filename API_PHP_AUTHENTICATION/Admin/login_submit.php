<?PHP
//verificando se a constante root existe
defined('ROOT') or die('Acesso invalido');

//se não existir sessão ela sera iniciada
if(session_status()===PHP_SESSION_NONE) session_start();

//verificar os dados existem para autenticação
if($_SERVER['REQUEST_METHOD'] != 'POST')
{
    die('Acesso invalido');
}

$usuario = $_POST['text_usuario'];
$senha = $_POST['text_senha'];

//Verifica se o usuário ou senha são vazios
if(empty($usuario) || empty($senha))
{
    $_SESSION['error'] = 'Dados Insuficientes';
    header('Location: index.php');
    exit;
}

//echo "usuario: $usuario e senha: $senha";

//ligar a base de dados
$bd = new database();

//parametros
$params  = [
    ":username"=>$usuario
];

//retorno dos usuarios do banco
$usuarios = $bd->EXE_QUERY('SELECT * FROM authentication WHERE username=:username',$params);

//variavel de controle
$validUser = false;

//verifica se há usuário no banco
if(count($usuarios)==1)
{
    $userBD = $usuarios[0];
  
    //confirmar as credenciais
    if(password_verify($senha,$userBD['passwrd']))
    {
        $validUser = true;
    }
    else
    {
        $_SESSION['error'] = 'Credenciais invalidas';
    }
}
else
{
    $_SESSION['error'] = 'Usuário inexistente no sistema';
}

//verifica se o usuário é valido
if($validUser)
{
    //criar usuário na sessao
    $_SESSION['id_admin'] = $userBD['id_client'];
    $_SESSION['usuario'] = $userBD['username'];

   
}

header('Location: index.php');
exit;








