<?php


/* 
 * Interface com o app para a realização do login do usuário no sistema Web.
 */

define('ROOT_DIR', dirname(dirname(__FILE__)));

// Importa a conexão à DB e as funções da biblioteca de login
require_once ROOT_DIR.'/config/db_connect.php';
require_once ROOT_DIR.'/lib/login/functions.php';

// Nossa segurança personalizada para iniciar uma sessão php.
sec_session_start();

header('Content-Type: application/json; charset=utf-8');
$resposta = array();

if (login_check($mysqli) != true)
{
    $resposta = [
        'status' => 'error',
        'logged' => 'out',
        'message' => 'Você está desconectado!'
    ];
    
    echo json_encode((object)$resposta);
    die;
}
require ROOT_DIR.'/config/global.php';
require ROOT_DIR.'/lib/botoes/functions.php';

if (isset($_POST['update']) && isset($_POST['reg']) && isset($_POST['d']) && $_POST['update'] === 'true')
{
    $r = filter_input(INPUT_POST, 'reg', FILTER_SANITIZE_NUMBER_INT);
    $d = filter_input(INPUT_POST, 'd', FILTER_SANITIZE_STRING);
    update($resposta, $r, $d);
}
else
    get($resposta);

function get(&$resposta)
{
    try
    {
    $datetime = new DateTime();
    $botoes = new Botoes($datetime->format('d-m-Y'), '30 minutes', '10 minutes', FILE_PATH);
    $resposta['data'] = array();
    for($i = 0; $cham = $botoes->GetChamada(); $i++)
    {
	_helperResposta($resposta, $cham, $i);
    }
    $resposta['status'] = 'ok';
    }
    catch (Exception $e)
    {
	$resposta['status'] = 'error';
	$resposta['message'] = $e->getMessage();
    }
}

function update(&$resposta, $r, $d)
{
    $data = DateTime::createFromFormat('d/m/Y', $d);
    try
    {
    $botoes = new Botoes($data->format('d-m-Y'), '30 minutes', '10 minutes', FILE_PATH);
    $ret = $botoes->RegEntrega($r);
    _helperResposta($resposta, $ret);
    $resposta['status'] = 'ok';
    }
    catch (Exception $e)
    {
	$resposta['status'] = 'error';
	$resposta['message'] = $e->getMessage();
    }
}

function _helperResposta(&$resposta, &$cham, $i = 0)
{
    $resposta['data'][$i]['d'] = $cham['datahora']->format('d/m/Y');
    $resposta['data'][$i]['h'] = $cham['datahora']->format('H:i');
    $resposta['data'][$i]['td'] = $cham['limite']->format('d/m/Y');
    $resposta['data'][$i]['th'] = $cham['limite']->format('H:i');
    $resposta['data'][$i]['r'] = $cham['registro'];
    $resposta['data'][$i]['p'] = $cham['peca'];
    if ($cham['entrega'] === null)
    {
	$resposta['data'][$i]['ed'] = $cham['entrega'];
	$resposta['data'][$i]['eh'] = $cham['entrega'];
    }
    else
    {
	$resposta['data'][$i]['ed'] = $cham['entrega']->format('d/m/Y');
	$resposta['data'][$i]['eh'] = $cham['entrega']->format('H:i');
    }
    switch($cham['status'])
    {
	case Botoes::TEMPO_OK:
	    $resposta['data'][$i]['status'] = 'uk-icon-clock-o';
	    break;
	case Botoes::TEMPO_EXPIRANDO:
	    $resposta['data'][$i]['status'] = 'uk-icon-exclamation-triangle';
	    break;
	default:
	    $resposta['data'][$i]['status'] = 'uk-icon-close';
    }
}

echo json_encode((object)$resposta);