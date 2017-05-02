<?php

/* 
 * Remove as tentativas de login muito antigas do banco de dados.
 */

if (!defined('MY_DIR'))
    die('Acesso não autorizado!');

require_once ROOT_DIR.'/config/psl-config.php';
require_once ROOT_DIR.'/config/global.php';
require_once ROOT_DIR.'/config/db_connect.php';

$date = new DateTime(null, new DateTimeZone('UTC'));
$date->sub($loginDeleteAfter);
$date = $date->format('Y-m-d H:i:s');

$query = 'DELETE FROM `login_attempts` WHERE `datetime` <= \''.$mysqli->escape_string($date).'\'';
$mysqli->query($query);

