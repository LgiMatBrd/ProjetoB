<?php


/* 
 * Interface AJAX com a view admin.
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


try
{
$pecas7 = array();
$pecas = array();
$datetime = new DateTime();
$sDatetime = clone $datetime;
$sDatetime->modify('-6 days');
for (; $sDatetime <= $datetime; $sDatetime->modify('+1 day'))
{
    if (Botoes::ChecaExistencia($sDatetime->format('d-m-Y'), FILE_PATH) === false)
	    continue;
    $botoes = new Botoes($sDatetime->format('d-m-Y'), '30 minutes', '10 minutes', FILE_PATH);
    $pecas = $botoes->CountTotalPecas();
    foreach($pecas as $key => $val)
	$pecas7[$key] = $pecas7[$key] + $val;
}
uksort($pecas7, function($a,$b){
    return $a - $b;
});
uksort($pecas, function($a,$b){
    return $a - $b;
});
$resposta['semanal'] = $pecas7;
$resposta['diario'] = $pecas;
$resposta['status'] = 'ok';
}
catch (Exception $e)
{
    $resposta['status'] = 'error';
    $resposta['message'] = $e->getMessage();
}

echo json_encode((object)$resposta);