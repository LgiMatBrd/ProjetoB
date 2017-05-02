<?php

/* 
 * Executa operações de manutenção programa no sistema web.
 */
define('MY_DIR', dirname(__FILE__));
define('ROOT_DIR', dirname(dirname(__FILE__)));

$logs = fopen(MY_DIR.'/logs.txt', 'a');
$lines = '';
set_error_handler(function ($severity, $message, $file, $line) {
    global $lines;
    $lines .= "[$severity - $file] Linha $line: $message\r\n";
    return true;
});
fwrite($logs, $line);
fclose($logs);

$dbDeleteAfter = new DateInterval('P7D');
include MY_DIR.'/crons/db.php';
$loginDeleteAfter = new DateInterval('P3D');
include MY_DIR.'/crons/logins.php';
