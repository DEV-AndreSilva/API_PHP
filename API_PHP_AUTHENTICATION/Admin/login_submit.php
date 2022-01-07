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

//ligar a base de dados

//confirmar as credenciais

//criar usuário na sessao

$usuario = $_POST['text_usuario'];
$senha = $_POST['text_senha'];

//Verifica se o usuário ou senha são vazios
if(empty($usuario) || empty($senha))
{
    $_SESSION['error'] = 'Dados Insuficientes';
    header('Location: index.php');
    exit;
}

echo "usuario: $usuario e senha: $senha";
