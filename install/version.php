<?php

if (!defined('ROOT_DIR'))
    die('Acesso não autorizado!');


// Gera o arquivo version.php
if (file_exists(ROOT_DIR.'/config/version.php'))
{
    if (!unlink(ROOT_DIR.'/config/version.php'))
        AddMsg(VER, ERROR, 'Já existe um arquivo version.php e o instalador não conseguiu apagá-lo.');
}
if (!file_exists(ROOT_DIR.'/config/version.php'))
{
    if (!$myfile = fopen(ROOT_DIR.'/config/version.php', 'w'))
    {
        AddMsg(VER, ERROR, 'Não foi possível criar o arquivo version.php.');
    }
    else
    {
        $strconf = <<< EOF
<?php

/* 
 * Arquivo usado para controle de versões.
 * Através deste arquivo é possível verificar se a instalação atual é condizente
 * aos arquivos de instalação da pasta 'install'.
 * 
 * Este arquivo deve ser gerado automaticamente pelo processo de instalação,
 * de modo a refletir ao estado atual da instalação.
 */

\$version = '$installerVersion';
EOF;
        if (fwrite($myfile, $strconf))
                AddMsg(VER, OK, 'Arquivo version.php criado com sucesso!');
        fclose($myfile);
    }

}