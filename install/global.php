<?php

if (!defined('ROOT_DIR'))
    die('Acesso não autorizado!');


if (isset($_POST['from-email'], $_POST['from-name']))
{
    // Limpa e valida os dados passados em 
    $fromname = filter_input(INPUT_POST, 'from-name', FILTER_SANITIZE_STRING);
    $fromemail = filter_input(INPUT_POST, 'from-email', FILTER_SANITIZE_EMAIL);
    if (!filter_var($fromemail, FILTER_VALIDATE_EMAIL)) {
        // Email inválido
        AddMsg(GLOB, ERROR, 'O endereço de email digitado não é válido');
    }
    // Gera o arquivo global.php
    if (file_exists(ROOT_DIR.'/config/global.php'))
    {
        if (!unlink(ROOT_DIR.'/config/global.php'))
            AddMsg(GLOB, ERROR, 'Já existe um arquivo global.php e o instalador não conseguiu apagá-lo.');
    }
    if (!file_exists(ROOT_DIR.'/config/global.php'))
    {
        if (!$myfile = fopen(ROOT_DIR.'/config/global.php', 'w'))
        {
            AddMsg(GLOB, ERROR, 'Não foi possível criar o arquivo global.php.');
        }
        else
        {
            $urlBase = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
            $urlBase .= $_SERVER['HTTP_HOST'];
            $strconf = <<< EOF
<?php

/* 
 * Arquivo de configurações globais do servidor.
 */

date_default_timezone_set('America/Sao_Paulo');
setlocale(LC_ALL, 'pt_BR', 'ptb');

error_reporting(0);

define('UTC_TIMEZONE', -3);
define('FROM_EMAIL', '$fromemail');
define('FROM_NAME', '$fromname');

\$url_base = '$urlBase';
EOF;
            if (fwrite($myfile, $strconf))
                    AddMsg(GLOB, OK, 'Arquivo de configurações globais criado com sucesso!');
            fclose($myfile);
        }

    }
}
else
    AddMsg(GLOB, ERROR, 'Dados enviados insuficientes!');