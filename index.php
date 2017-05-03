<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

define('ROOT_DIR', dirname(__FILE__));

// Verifica se o arquivo de instalação existe e faz sua inclusão.
if (file_exists(ROOT_DIR.'/install/index.php'))
{
    include ROOT_DIR.'/install/index.php';
    
}

require_once ROOT_DIR.'/config/global.php';

// Obtém o recurso solicitado ao servidor   
$recurso = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);

// Verifica se o arquivo solicitado é de acesso liberado e se existe e corrige a URL
if ($recurso[0] === '/') {
    include ROOT_DIR.'/config/freeaccess.php';
    foreach ($freeaccess as $pasta)
    {
        $pos = strpos($recurso, $pasta);
        if ($pos !== FALSE)
        {
            $arq = substr($recurso, $pos);
            if (file_exists(ROOT_DIR.$arq))
            {
                header("HTTP/1.1 301 Moved Permanently");
                header('Location: '.$url_base.$arq);
                die;
            }
            else
                die;
        }
    }
}


// Importa a conexão à DB e as funções da biblioteca de login
include_once ROOT_DIR.'/config/db_connect.php';
include_once ROOT_DIR.'/lib/login/functions.php';

// Inicia uma sessão segura
sec_session_start();


// Verifica se o usuário já está logado
if (login_check($mysqli) == true) {
    $logged = 'in';
    if ($recurso == '/logout')
            include ROOT_DIR.'/lib/login/logout.php';
    
    else if (file_exists(ROOT_DIR.'/controllers'.$recurso.'.php'))
            include ROOT_DIR.'/controllers'.$recurso.'.php';
    
    else if ($recurso == '/index.php' || $recurso == '/')
            header('Location: /home');
    
    else if (file_exists(ROOT_DIR.'/views'.$recurso.'.php'))
            include ROOT_DIR.'/views'.$recurso.'.php';
    
    else if (file_exists(ROOT_DIR.$recurso.'.php'))
            include ROOT_DIR.$recurso.'.php';
    
    else include 'error.php';
    exit;
} else {
    $logged = 'out';
    include ROOT_DIR.'/controllers/login.php';
}