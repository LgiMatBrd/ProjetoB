<?php

/* 
 * Este arquivo cria as tabelas do banco de dados a partir do arquivo
 * mysql-create.sql e salva as configurações da conexão no arquivo
 * psl-config.php.
 */

if (!defined('ROOT_DIR'))
    die;


$host = filter_input(INPUT_POST, 'db-server', FILTER_SANITIZE_URL);
$user = filter_input(INPUT_POST, 'db-user', FILTER_SANITIZE_STRING);
$pass = filter_input(INPUT_POST, 'db-pass', FILTER_SANITIZE_STRING);
$dbname = filter_input(INPUT_POST, 'db-name', FILTER_SANITIZE_STRING);

$mysqli_install = new mysqli($host, $user, $pass, $dbname);

if ($mysqli_install->connect_error)
{
    AddMsg(DB, ERROR, 'Problema de conexão ('.$mysqli_install->connect_errno.'): '.$mysqli_install->connect_error);
}
else
{
    AddMsg(DB, OK, 'A conexão com o banco de dados foi bem sucedida!');
    $mysqli_install->set_charset('utf8');
    // Checa se a db está limpa
    $prep_stmt = 'SHOW TABLES';
    $stmt = $mysqli_install->prepare($prep_stmt);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows >= 1)
    {
        AddMsg(DB, WARNING, 'Já existem tabelas no banco de dados. O instalador saltou a criação de tabelas.');
    }
    else
    {
        $tabelas = 0;
        $ercrdb = false;
        
        $templine = 'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"';
        $mysqli_install->query($templine) or $ercrdb = true;
        $templine = 'SET time_zone = "+00:00"';
        $mysqli_install->query($templine) or $ercrdb = true;
        $templine = "CREATE DATABASE IF NOT EXISTS `{$dbname}` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
        $mysqli_install->query($templine) or $ercrdb = true;
        $templine = "USE `{$dbname}`";
        $mysqli_install->query($templine) or $ercrdb = true;
        if (!$ercrdb)
        {
            // Temporary variable, used to store current query
            $templine = '';
            // Read in entire file
            $lines = file(ROOT_DIR.'/install/mysql-create.sql');
            // Loop through each line
            $delimitador = ';';
            foreach ($lines as $line)
            {
                // Skip it if it's a comment
                if (substr($line, 0, 2) == '--' || $line == '')
                    continue;
                if (strpos($line, 'CREATE TABLE') !== FALSE)
                    $tabelas++;
                if (strpos($line, 'DELIMITER $$') !== FALSE)
                {
                    $delimitador = '$$';
                    continue;
                }
                if (strpos($line, 'DELIMITER ;') !== FALSE)
                {
                    $delimitador = ';';
                    continue;
                }
                
                // Add this line to the current segment
                if (substr(trim($line), -1, 1) == $delimitador)
                    $templine .= substr(trim($line), 0, -1);
                else if (substr(trim($line), -2, 2) == $delimitador)
                    $templine .= substr(trim($line), 0, -2);
                else
                    $templine .= $line;
                // If it has a semicolon at the end, it's the end of the query
                if (substr(trim($line), -1, 1) == $delimitador || substr(trim($line), -2, 2) == $delimitador)
                {
                    // Perform the query
                    $mysqli_install->query($templine) or AddMsg(DB, ERROR, 'Erro ao executar uma query \'<strong>' . $templine . '\':</strong> ' . $mysqli_install->error);
                    // Reset temp variable to empty
                    $templine = '';
                }
            }
        }
            else AddMsg(DB, ERROR, 'Erro ao configurar a conexão com o banco de dados');
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows == $tabelas && $tabelas !== 0)
            AddMsg(DB, OK, 'O banco de dados foi importado com sucesso!');
        else 
            AddMsg(DB, WARNING, 'Nem todas as tabelas foram criadas! Criado '.$stmt->num_rows.' de '.$tabelas);
    }
    
    if (file_exists(ROOT_DIR.'/config/psl-config.php'))
    {
        if (!unlink(ROOT_DIR.'/config/psl-config.php'))
                AddMsg(CFILE, ERROR, 'Já existe um arquivo de configuração do banco de dados e o instalador não conseguiu apagá-lo.');
    }
    if (!file_exists(ROOT_DIR.'/config/psl-config.php'))
    {
        if (!$myfile = fopen(ROOT_DIR.'/config/psl-config.php', 'w'))
        {
            AddMsg(CFILE, ERROR, 'Não foi possível criar o arquivo de configuração do banco de dados.');
        }
        else
        {
            $strconf = <<< EOF
<?php

if (!defined('ROOT_DIR'))
    die;
/**
 * Seguem os detalhes para login no banco de dados
 */  

define('HOST', '$host');     // Para o host com o qual você quer se conectar.
define('USER', '$user');    // O nome de usuário para o banco de dados. 
define('PASSWORD', '$pass');    // A senha do banco de dados. 
define('DATABASE', '$dbname');    // O nome do banco de dados. 
                    
define('SECURE', FALSE);    // ESTRITAMENTE PARA DESENVOLVIMENTO!!!!
EOF;
            if (fwrite($myfile, $strconf))
                AddMsg(CFILE, OK, 'O arquivo de configurações do banco de dados foi criado.');
            fclose($myfile);
        }
        
    }
    
            
    $mysqli_install->close();
}

