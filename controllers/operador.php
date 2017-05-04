<?php
/*
 * Arquivo controller da view 'home'
 */

if (!defined('ROOT_DIR')) // Impossibilita o acesso direto ao arquivo
    die;

/*
 * Verifica se o usuário está logado.
 * O acesso a este controller é restrito a usuários logados!
 */
if ($logged !== 'in')
{
    header('Location: /operador');
    die;
}
    
// Se não houve sessão segura iniciada, deve-se iniciar
if (!defined('SESSION_START'))
    // Nossa segurança personalizada para iniciar uma sessão php.
    sec_session_start();

define('OPERADOR_CONTROLLER', true);

// Provê a view do controller
include ROOT_DIR.'/views/operador.php';
