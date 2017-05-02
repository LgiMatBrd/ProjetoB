<?php

if (!defined('ROOT_DIR'))
    die('Acesso não autorizado!');

    const OK = 1;
    const WARNING = 2;
    const ERROR = 3;
    
    const DB = 1;
    const CFILE = 2;
    const REG = 3;
    const VER = 4;
    const GLOB = 5;
    const HT = 6;
    const CRON = 7;
    

$msgs = array();
function AddMsg($task, $s, $txt)
{
    global $msgs;
    if (!isset($msgs[$task]))
        $msgs[$task] = array();
    $msgs[$task][] = [
        $s,
        $txt
    ];
}

function GetMsg($task)
{
    global $msgs;
    if (isset($msgs[$task][0]))
    {
        $mymsg = array_shift($msgs[$task]);
        $mymsg[0] = ($mymsg[0] === ERROR)? 'uk-alert-danger' : ($mymsg[0] === WARNING)? 'uk-alert-warning' : 'uk-alert-success';
        return [
            'status' => $mymsg[0],
            'msg' => $mymsg[1]
        ];
    }
    return false;
}

function hasError($task = null)
{
    global $msgs;
    if ($task !== null)
        $r = $msgs[$task];
    else
    {
        $r = array();
        foreach ($msgs as $arr)
            $r[] = $arr;
    }
    foreach ($r as $msg)
    {
        if (isset($msg[0]) && $msg[0] === ERROR)
            return true;
    }
    return false;
}

include_once ROOT_DIR.'/install/db.php';

if (!hasError())
{
    include_once ROOT_DIR.'/install/register.php';

    include_once ROOT_DIR.'/install/global.php';

    include_once ROOT_DIR.'/install/htaccess.php';
    
    if (!hasError())
        include_once ROOT_DIR.'/install/version.php';
    
    if (!hasError())
        include_once ROOT_DIR.'/install/cronjobs.php';

}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Concluindo a instalação</title>
        <link rel="shortcut icon" href="install/install.ico" type="image/x-icon">
        <link rel="stylesheet" href="css/uikit.min.css" />
        <link rel="stylesheet" href="css/uikit.gradient.min.css" />
        <script src="js/jquery.min.js"></script>
        <script src="js/uikit.min.js"></script>
    </head>
    <body>
        <div class="uk-width-1-1">
            <div class="uk-width-9-10 uk-container-center uk-margin-large uk-margin-large-top">
                <div class="uk-panel uk-panel-space uk-panel-box uk-panel-header">
                    <h2 class="uk-text-center">Instalação e configuração</h2>
                    <div class="uk-panel-title"></div>
                    <div class="uk-grid">
                        <div class="uk-width-1-3 uk-text-right">
                            <span class="uk-text-large">Banco de dados</span>
                        </div>
                        <div class="uk-width-2-3">
                            <?php
                            while ($msg = GetMsg(DB))
                            { ?>
                            <div class="uk-alert <? echo $msg['status']; ?>"><p><? echo $msg['msg']; ?></p></div>
                            <?php
                            } ?>
                        </div>
                    </div>
                    <div class="uk-grid">
                        <div class="uk-width-1-3 uk-text-right">
                            <span class="uk-text-large">Arquivo de configuração do banco de dados</span>
                        </div>
                        <div class="uk-width-2-3">
                            <?php
                            while ($msg = GetMsg(CFILE))
                            { ?>
                            <div class="uk-alert <? echo $msg['status']; ?>"><p><? echo $msg['msg']; ?></p></div>
                            <?php
                            } ?>
                        </div>
                    </div>
                    <div class="uk-grid">
                        <div class="uk-width-1-3 uk-text-right">
                            <span class="uk-text-large">Registro do primeiro usuário</span>
                        </div>
                        <div class="uk-width-2-3">
                            <?php
                            while ($msg = GetMsg(REG))
                            { ?>
                            <div class="uk-alert <? echo $msg['status']; ?>"><p><? echo $msg['msg']; ?></p></div>
                            <?php
                            } ?>
                        </div>
                    </div>
                    <div class="uk-grid">
                        <div class="uk-width-1-3 uk-text-right">
                            <span class="uk-text-large">Arquivo de controle de versão</span>
                        </div>
                        <div class="uk-width-2-3">
                            <?php
                            while ($msg = GetMsg(VER))
                            { ?>
                            <div class="uk-alert <? echo $msg['status']; ?>"><p><? echo $msg['msg']; ?></p></div>
                            <?php
                            } ?>
                        </div>
                    </div>
                    <div class="uk-grid">
                        <div class="uk-width-1-3 uk-text-right">
                            <span class="uk-text-large">Arquivo de configurações globais</span>
                        </div>
                        <div class="uk-width-2-3">
                            <?php
                            while ($msg = GetMsg(GLOB))
                            { ?>
                            <div class="uk-alert <? echo $msg['status']; ?>"><p><? echo $msg['msg']; ?></p></div>
                            <?php
                            } ?>
                        </div>
                    </div>
                    <div class="uk-grid">
                        <div class="uk-width-1-3 uk-text-right">
                            <span class="uk-text-large">Arquivo de configuração .htaccess</span>
                        </div>
                        <div class="uk-width-2-3">
                            <?php
                            while ($msg = GetMsg(HT))
                            { ?>
                            <div class="uk-alert <? echo $msg['status']; ?>"><p><? echo $msg['msg']; ?></p></div>
                            <?php
                            } ?>
                        </div>
                    </div>
                    <div class="uk-grid">
                        <div class="uk-width-1-3 uk-text-right">
                            <span class="uk-text-large">Tarefa Cron de Manutenção</span>
                        </div>
                        <div class="uk-width-2-3">
                            <?php
                            while ($msg = GetMsg(CRON))
                            { ?>
                            <div class="uk-alert <? echo $msg['status']; ?>"><p><? echo $msg['msg']; ?></p></div>
                            <?php
                            } ?>
                        </div>
                    </div>
                    <div class="uk-grid">
                        <div class="uk-width-1-1">
                            <div class="uk-alert uk-alert-large">
                                <?php if (!hasError()) { ?>                            
                                <p class="uk-text-large uk-text-center">Por motivos de segurança, apague completamente a pasta 'install' do servidor!</p>
                                <?php } else { ?>
                                <p class="uk-text-large uk-text-center">A instalação foi interrompida devido a erros durante a instalação!</p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="backstretch" style="left: 0px; top: 0px; overflow: hidden; margin: 0px; padding: 0px; height: 100%; width: 100%; z-index: -999999; position: fixed;">
            <img style="position: absolute; margin: 0px; padding: 0px; border: medium none; width: 100%; height: 100%; max-height: none; max-width: none; z-index: -999999; left: 0px; top: 0px;" src="<? echo $concreteBackgroundWallPaper; ?>">
        </div>
    </body>
</html>