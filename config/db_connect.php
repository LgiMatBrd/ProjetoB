<?php
if (!defined('ROOT_DIR'))
    die;

include_once ROOT_DIR.'/config/psl-config.php';   // Já que functions.php não está incluso
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
$mysqli->set_charset('utf8');