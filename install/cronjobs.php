<?php

/* 
 * Este arquivo configura uma cron para que o arquivo cron.php execute
 * periodicamente.
 */

include_once ROOT_DIR.'/install/crontabmanager/CrontabAdapter.php';
include_once ROOT_DIR.'/install/crontabmanager/CrontabJob.php';
include_once ROOT_DIR.'/install/crontabmanager/CrontabRepository.php';

use TiBeN\CrontabManager\CrontabRepository;
use TiBeN\CrontabManager\CrontabAdapter;
use TiBeN\CrontabManager\CrontabJob;

$crontabRepository = new CrontabRepository(new CrontabAdapter());
$results = $crontabRepository->findJobByRegex('/WebMaintenance/');

if (empty($results))
{
    $crontabJob = new CrontabJob();
    $crontabJob->minutes = '30';
    $crontabJob->hours = '3';
    $crontabJob->dayOfMonth = '*';
    $crontabJob->months = '*';
    $crontabJob->dayOfWeek = '*';
    $crontabJob->taskCommandLine = 'php -f '.ROOT_DIR.'/maintenance/cron.php';
    $crontabJob->comments = 'WebMaintenance'; // Comments are persisted in the crontab

    $crontabRepository->addJob($crontabJob);
    $crontabRepository->persist();
    
    $results = $crontabRepository->findJobByRegex('/WebMaintenance/');
    if (!empty($results))
        AddMsg(CRON, OK, 'Cronjob criado com sucesso!');
    else
        AddMsg(CRON, ERROR, 'Não foi possível criar o cronjob de manutenção!');
}
else
    AddMsg(CRON, WARNING, 'Já existe uma cronjob para o serviço de manutenção automática!');

