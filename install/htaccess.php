<?php

if (!defined('ROOT_DIR'))
    die('Acesso não autorizado!');

$jainserido = false;
// Gera o arquivo .htaccess
$myht = file_get_contents(ROOT_DIR.'/install/htaccess.txt');
$srvht = '';
if (file_exists(ROOT_DIR.'/.htaccess'))
{
    AddMsg(HT, WARNING, 'Já existe um arquivo .htaccess.');
    $srvht = file_get_contents(ROOT_DIR.'/.htaccess');
    if (strpos($srvht, '#HtAccess do sistema') === FALSE)
    {
        AddMsg(HT, WARNING, 'O arquivo .htaccess existente é diferente do arquivo esperado');
        $jainserido = false;
    }
    else
    {
        AddMsg(HT, OK, 'O arquivo .htaccess existente já possui o conteúdo esperado');
        $jainserido = true;
    }
    
}
if (!$jainserido)
{
    if (!$myfile = fopen(ROOT_DIR.'/.htaccess', 'w'))
    {
        AddMsg(HT, ERROR, 'Não foi possível criar o arquivo .htaccess.');
    }
    else
    {
        if (fwrite($myfile, $myht."\n".$srvht))
                if (!empty($srvht))
                    AddMsg(HT, OK, 'O arquivo .htaccess foi modificado com sucesso!');
                else
                    AddMsg(HT, OK, 'Arquivo .htaccess criado com sucesso!');
        fclose($myfile);
    }

}