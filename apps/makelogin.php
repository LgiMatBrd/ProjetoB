<?php

/* 
 * Interface com o app para a realização do login do usuário no sistema Web.
 */

define('ROOT_DIR', dirname(dirname(__FILE__)));

ob_start();
require '../config/global.php';

// Importa a conexão à DB e as funções da biblioteca de login
require_once ROOT_DIR.'/config/db_connect.php';
require_once ROOT_DIR.'/lib/login/functions.php';

// Nossa segurança personalizada para iniciar uma sessão php.
sec_session_start();

$resposta = array();

function checaLogin()
{
    global $mysqli, $resposta;
    // Verifica se o usuário já está logado
    if (login_check($mysqli) == false)
    {

        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);

        if (!isset($request['makelogin']) || $request['makelogin'] != 'true')
        {
            $resposta = [
                'status' => 'error',
                'msg' => 'Sua requisição é inválida!'
            ];
            return;
        }
        
        $username = filter_var($request['username'], FILTER_SANITIZE_STRING);
        $password = filter_var($request['p'], FILTER_SANITIZE_STRING);
        
        if (login($username, $password, $mysqli) == true)
        {
            // Login com sucesso!
            $resposta = [
                'status' => 'ok',
                'logged' => 'in',
                'msg' => ''
            ];
        }
        else
        {
            // Falha ao logar
            $resposta = [
                'status' => 'ok',
                'logged' => 'out',
                'msg' => 'Senha ou usuário incorretos!'
            ];
        }
    }
    else
    {
        $resposta = [
                'status' => 'ok',
                'logged' => 'in',
                'msg' => ''
            ];
    }
    
}

checaLogin();

$out1 = ob_get_contents();
ob_end_clean();

if (!empty($out1))
{
    $resposta = [
        'status' => 'error',
        'h2' => 'Erro no servidor!',
        'msg' => $out1
    ];
}

header('Content-Type: application/json; charset=utf-8');

echo json_encode((object)$resposta);