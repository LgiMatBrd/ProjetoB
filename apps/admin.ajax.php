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
$porSemana = array();
$atrasadasPorHora = array();
$noPrazoPorHora = array();
$atrasadasNaSemana = array();
$noPrazoNaSemana = array();
$atrasadasNoMes = array();
$noPrazoNoMes = array();
$PecasPorDia = array();
$datetime = new DateTime();
$sDatetime = clone $datetime;
$sDatetime->modify('-7 days');
$mDatetime = clone $datetime;
$mDatetime->modify('-1 month');
for (; $mDatetime < $sDatetime; $mDatetime->modify('+1 day'))
{
    if (Botoes::ChecaExistencia($sDatetime->format('d-m-Y'), FILE_PATH) === false)
	    continue;
    $botoes = new Botoes($sDatetime->format('d-m-Y'), '30 minutes', '10 minutes', FILE_PATH);
    $dia = $mDatetime->format('d');
    $botoes->GetTotalPorDia($atrasadasNoMes[$dia], $noPrazoNoMes[$dia]); // Para o gráfico 'comparação mensal'
}

for (; $sDatetime <= $datetime; $sDatetime->modify('+1 day'))
{
    if (Botoes::ChecaExistencia($sDatetime->format('d-m-Y'), FILE_PATH) === false)
	    continue;
    $botoes = new Botoes($sDatetime->format('d-m-Y'), '30 minutes', '10 minutes', FILE_PATH);
    $dia = $sDatetime->format('d');
    $botoes->GetTotalPorDia($atrasadasNoMes[$dia], $noPrazoNoMes[$dia]); // Para o gráfico 'comparação mensal'
    $PecasPorDia[$dia] = $botoes->GetTotalChamadas(); // Para o gráfico 'solicitações semanais'
    $atrasadasNaSemana[$dia] = $atrasadasNoMes[$dia]; // Para o gráfico 'comparação semanal'
    $noPrazoNaSemana[$dia] = $noPrazoNoMes[$dia]; // Para o gráfico 'comparação semanal'
}
if (Botoes::ChecaExistencia($datetime->format('d-m-Y'), FILE_PATH) !== false)
{
    $botoes = new Botoes($datetime->format('d-m-Y'), '30 minutes', '10 minutes', FILE_PATH);
    $botoes->GetTotalPorHora($atrasadasPorHora, $noPrazoPorHora);
}
else
{
    $atrasadasPorHora = 0;
    $noPrazoPorHora = 0;
}
uksort($atrasadasPorHora, function($a,$b){
    return $a - $b;
});
uksort($noPrazoPorHora, function($a,$b){
    return $a - $b;
});

$resposta['solDiarias'] = [
    'atrasadas' => $atrasadasPorHora,
    'noPrazo' => $noPrazoPorHora
];
$resposta['solSemanais'] = $PecasPorDia;
$resposta['compSemanal'] = [
    'atrasadas' => $atrasadasNaSemana,
    'noPrazo' => $noPrazoNaSemana
];
$resposta['compMensal'] = [
    'atrasadas' => $atrasadasNoMes,
    'noPrazo' => $noPrazoNoMes
];
$resposta['status'] = 'ok';
}
catch (Exception $e)
{
    $resposta['status'] = 'error';
    $resposta['message'] = $e->getMessage();
}

echo json_encode((object)$resposta);