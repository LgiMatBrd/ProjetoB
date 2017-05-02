<?php

/* 
 * Arquivo de definição das pastas de acesso irrestrito.
 * As pastas listadas aqui não precisam passar pelo arquivo index.php
 */


if (!defined('ROOT_DIR'))
    die;

// Pastas de acessos liberados e irrestritos (imporante terminar pastas com '/')
$freeaccess = array(
    '/css/',
    '/js/',
    '/fonts/',
    '/assets/images/'
    
);