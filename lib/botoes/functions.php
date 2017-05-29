<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!defined('ROOT_DIR'))
    die;

class Botoes
{
    const defaultPath = '';     // Diretório padrão dos arquivos
    protected $path;            // Diretório dos arquivos
    protected $ChamadasFile;    // Arquivo das chamadas atualmente aberto
    protected $LiberadasFile;   // Arquivo das liberadas atualmente aberto
    protected $ChamadasfPath;   // Caminho completo do arquivo de chamadas
    protected $LiberadasfPath;  // Caminho completo do arquivo de liberadas
    protected $arChamadas;      // Array contendo as chamadas atuais
    protected $arLiberadas;     // Array contendo as chamadas já liberadas
    protected $IteratorChamadas;    // Variável para controle de iteração das chamadas
    protected $IteratorLiberadas;   // Variável para controle de iteração das liberadas
    protected $TimeZone;	    //Objeto DateTimeZone com a Timezone padrão
    protected $Timeout;             // Tempo para que a chamada entre em atraso
    protected $hTimeout;            // Tempo para que a chamada entre em 'quase atraso'
    protected $DateTime;            // Objeto DateTime da hora e data atual
    
    // "ENUM"
    const TEMPO_ATRASADO = 0;
    const TEMPO_EXPIRANDO = 1;
    const TEMPO_OK = 2;
            
    function __construct($date, $timeout = '30 minutes', $htimeout = '10 minutes', $path = defaultPath)
    {
	$this->TimeZone = new DateTimeZone(date_default_timezone_get());
        $this->Timeout = $timeout;
        $this->hTimeout = $htimeout;
        $this->DateTime = new DateTime();
        $this->ChamadasFile = $date.'_-_registro_chamada.txt';
        $this->LiberadasFile = $date.'_-_registro_entregas.txt';
        $this->ChamadasfPath = $path.$this->ChamadasFile;
        $this->LiberadasfPath = $path.$this->LiberadasFile;
        $this->arChamadas = array();
        $this->arLiberadas = array();
        $this->IteratorChamadas = 0;
        $this->IteratorLiberadas = 0;
        if (file_exists($this->ChamadasfPath))
        {
            $this->_populateChamadas();
        } else $this->Error('O arquivo não existe: '.$this->ChamadasFile);
        
        if (file_exists($this->LiberadasfPath))
        {
            $this->_populateLiberadas();
        }
        else
        {
            if (!touch($this->LiberadasfPath))
                $this->Error('Não foi possível criar o arquivo: '.$this->LiberadasFile);
        }
    }
    
    function GetChamada($cursor = null)
    {
	if ($cursor !== null)
	    $this->IteratorChamadas = $cursor;
	if (!isset($this->arChamadas[$this->IteratorChamadas]))
	    return false;
        $chamada = &$this->arChamadas[$this->IteratorChamadas];
        $datetime = DateTime::createFromFormat('d/m/Y H:i:s', $chamada[0], $this->TimeZone);
        $timeoutDateTime = clone $datetime;
        $timeoutDateTime->modify('+ '.$this->Timeout);
        $hTimeoutDateTime = clone $datetime;
        $hTimeoutDateTime->modify('+ '.$this->hTimeout);
	if (isset($this->arLiberadas[$this->IteratorChamadas]))
	{
	    $liberada = &$this->arLiberadas[$this->IteratorChamadas];
	    $entrega = DateTime::createFromFormat('d/m/Y H:i:s', $liberada[3], $this->TimeZone);
	}
	else
	    $entrega = null;
	
	if ($entrega !== null)
	    $dref = $entrega;
	else
	    $dref = $this->DateTime;
	if ($dref > $timeoutDateTime)
	    $status = self::TEMPO_ATRASADO;
	else if ($dref > $hTimeoutDateTime)
	    $status = self::TEMPO_EXPIRANDO;
	else
	    $status = self::TEMPO_OK;
	return [
            'datahora'		=> $datetime,
            'limite'		=> $timeoutDateTime,
	    'registro'		=> $this->IteratorChamadas++,
	    'status'		=> $status,
            'peca'		=> $chamada[1],
	    'entrega'		=> $entrega
        ];
    }
    
    function RegEntrega($registro)
    {
	$ar = array();
	$od = DateTime::createFromFormat('d/m/Y H:i:s', $this->arChamadas[$registro][0]);
	$ar[] = $registro;
	$ar[] = $this->arChamadas[$registro][0];
	$ar[] = $this->arChamadas[$registro][1];
	$ar[] = $this->DateTime->format('d/m/Y H:i:s');
	$ar[] = $this->DateTime->getTimestamp() - $od->getTimestamp();
	$str = implode("\t", $ar);
	$str = str_replace(array("\n","\r"), '', $str);
	unset($ar[0]);
	$this->arLiberadas[$registro] = $ar;
	$fo = fopen($this->LiberadasfPath, 'a');
	fwrite($fo, $str."\r\n");
	fclose($fo);
	$back = $this->IteratorChamadas;
	$ret = $this->GetChamada($registro);
	$this->IteratorChamadas = $back;
	return $ret;
    }
    
    function CountTotalPecas()
    {
	$ret = array();
	foreach ($this->arChamadas as $cham)
	{
	    $pc = str_replace(array("\n","\r"), '', $cham[1]);
	    if (!isset($ret[$pc]))
		$ret[$pc] = 1;
	    else
		$ret[$pc]++;
	}
	return $ret;
    }
    
    static function ChecaExistencia($data, $path = defaultPath)
    {
	$f = $data.'_-_registro_chamada.txt';
	$fp = $path.$f;
	if (file_exists($fp))
	    return true;
        else
	    return false;
    }
    
    function Error($msg)
    {
	// Fatal error
	throw new Exception('Botoes error: '.$msg);
    }
    
    protected function _populateChamadas()
    {
        $fo = fopen($this->ChamadasfPath, 'r');
        while(($line = fgets($fo)) !== false)
        {
	    if (empty($line))
		continue;
	    $this->arChamadas[] = explode("\t", $line);
        }
        if (!feof($fo))
            $this->Error('Ocorreu um erro e não foi possível concluir a leitura do arquivo: '.$this->ChamadasfPath);
        fclose($fo);
    }
    
    protected function _populateLiberadas()
    {
        $fo = fopen($this->LiberadasfPath, 'r');
        while(($line = fgets($fo)) !== false)
        {
	    if (empty($line))
		continue;
	    $ar = explode("\t", $line);
	    $registro = $ar[0];
	    unset($ar[0]);
            $this->arLiberadas[$registro] = $ar;
        }
        if (!feof($fo))
            $this->Error('Ocorreu um erro e não foi possível concluir a leitura do arquivo: '.$this->LiberadasfPath);
        fclose($fo);
    }
    
    
}