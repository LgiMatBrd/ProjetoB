<?php

/* 
 * Este arquivo é responsável pelo logout do usuário e destruição das variáveis
 * de sessão.
 */

if (!defined('ROOT_DIR'))
    die;

include_once ROOT_DIR.'/lib/login/functions.php';
// Se não houve sessão segura iniciada, deve-se iniciar
if (!defined('SESSION_START'))
    sec_session_start();
 
// Desfaz todos os valores da sessão  
$_SESSION = array();
 
// obtém os parâmetros da sessão 
$params = session_get_cookie_params();
 
// Deleta o cookie em uso. 
setcookie(session_name(),
        '', time() - 42000, 
        $params["path"], 
        $params["domain"], 
        $params["secure"], 
        $params["httponly"]);
 
// Destrói a sessão 
session_destroy();
$logged = 'out';
header('Location: /index.php');