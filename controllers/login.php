<?php
/*
 *  Arquivo controller da view 'login'
 */

if (!defined('ROOT_DIR')) // Impossibilita o acesso direto ao arquivo
    die;

// Se não houve sessão segura iniciada, deve-se iniciar
if (!defined('SESSION_START'))
    // Nossa segurança personalizada para iniciar uma sessão php.
    sec_session_start();

define('LOGIN_CONTROLLER', true);

// Checa se há dados enviados da view pelo método POST
if (isset($_POST['makelogin']) && filter_input(INPUT_POST, 'makelogin', FILTER_SANITIZE_STRING) === 'yes')
{
    // Hora de processar os dados de login
    include_once ROOT_DIR.'/config/db_connect.php';
    include_once ROOT_DIR.'/lib/login/functions.php';
    
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);
    $gotopage = filter_input(INPUT_POST, 'gotopage', FILTER_SANITIZE_URL);
    
    if (login($username, $password, $mysqli) == true)
    {
        // Login com sucesso!
        if ($gotopage !== '/login' && $gotopage !== '/' && $gotopage !== '/index.php')
            header('Location: .'.$gotopage);
        else
            header('Location: /home');
    }
    else
    {
        // Falha ao logar
        $error = '<p class="error">Erro ao fazer o login!</p>';
        // Importa o código da view para o usuário tentar logar novamente!
        include ROOT_DIR.'/views/login.php';
    }
}
else // Checa se já está logado. Se não estiver, importa o código da view do controller
{
    if ($logged === 'in')
        header('Location: /home');
    else
    {
        $gotopage = $recurso;
        include ROOT_DIR.'/views/login.php';
    }
}