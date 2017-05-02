<?php

/* 
 * Executa o backup do banco de dados
 */

if (!defined('MY_DIR'))
    die('Acesso não autorizado!');

require_once ROOT_DIR.'/config/psl-config.php';
require_once ROOT_DIR.'/config/global.php';

$date = new DateTime();
$timezone = new DateTimeZone(date_default_timezone_get());
$date->setTimezone($timezone);
$file = MY_DIR.'/backups/'.$date->format('dmY').'.sql';

system('mysqldump -h '.HOST.' -u '.USER.' -p\''.PASSWORD.'\' '.DATABASE." > $file");


$files = glob(MY_DIR.'/backups/*.sql');
$date->sub($dbDeleteAfter);
foreach ($files as $fsql)
{
    $name = substr($fsql, -3, 3);
    $fdate = DateTime::createFromFormat('dmY', $name, $timezone);
    if ($date > $fdate)
        unlink(MY_DIR.'/backups/'.$fsql);
}