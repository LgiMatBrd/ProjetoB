<?php

/* 
 * Este arquivo possui as funções principais do sistema de login e controle de
 * sessão.
 */

if (!defined('ROOT_DIR'))
    die;

include_once ROOT_DIR.'/config/psl-config.php';
 
function sec_session_start() {
    $session_name = 'sec_session_id';   // Estabeleça um nome personalizado para a sessão
        
    $secure = SECURE;
    // Isso impede que o JavaScript possa acessar a identificação da sessão.
    $httponly = true;
    // Assim você força a sessão a usar apenas cookies. 
   if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header('Location: ../error.php?err=Could not initiate a safe session (ini_set)');
        exit();
    }
    // Obtém params de cookies atualizados.
    $cookieParams = session_get_cookie_params();
    
    session_name($session_name);
    session_start();            // Inicia a sessão PHP 
    session_regenerate_id(true);    // Recupera a sessão e deleta a anterior.
    
    session_set_cookie_params($cookieParams['lifetime'],
        $cookieParams['path'], 
        $cookieParams['domain'], 
        $secure,
        $httponly);
    // Estabelece o nome fornecido acima como o nome da sessão.    
    define('SESSION_START', true);
}

function login($loginTxt, $password, $mysqli) {
    // Usando definições pré-estabelecidas significa que a injeção de SQL (um tipo de ataque) não é possível. 
    if ($stmt = $mysqli->prepare('SELECT id, email, pNome, username, pass, salt 
        FROM users
       WHERE username = ?
        LIMIT 1')) {
        $stmt->bind_param('s', $loginTxt);  // Relaciona  "$loginTxt" ao parâmetro.
        $stmt->execute();    // Executa a tarefa estabelecida.
        $stmt->store_result();
 
        // obtém variáveis a partir dos resultados. 
        $stmt->bind_result($user_id, $email, $pNome, $username, $db_password, $salt);
        $stmt->fetch();
 
        // faz o hash da senha com um salt excusivo.
        $password = hash('sha512', $password . $salt);
        if ($stmt->num_rows == 1) {
            // Caso o usuário exista, conferimos se a conta está bloqueada
            // devido ao limite de tentativas de login ter sido ultrapassado 
 
            if (checkbrute($user_id, $mysqli) == true) {
                // A conta está bloqueada 
                // Envia um email ao usuário informando que a conta está bloqueada 
                return false;
            } else {
                // Verifica se a senha confere com o que consta no banco de dados
                // a senha do usuário é enviada.
                if ($db_password === $password) {
                    // A senha está correta!
                    // Obtém o string usuário-agente do usuário. 
                    $user_browser = $_SERVER['HTTP_USER_AGENT'];
                    // proteção XSS conforme imprimimos este valor
                    $user_id = preg_replace("/[^0-9]+/", "", $user_id);
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['email'] = $email;
                    $_SESSION['pNome'] = $pNome;
                    // proteção XSS conforme imprimimos este valor 
                    $username = preg_replace("/[^a-zA-Z0-9_\-]+/", 
                                                                "", 
                                                                $username);
                    $_SESSION['username'] = $username;
                    $_SESSION['login_string'] = hash('sha512', 
                              $password . $user_browser);
                    // Login concluído com sucesso.
                    return true;
                } else {
                    // A senha não está correta
                    // Registramos essa tentativa no banco de dados
                    $now = date('Y-m-d H:i:s');
                    $mysqli->query("INSERT INTO login_attempts(userid, datetime)
                                    VALUES ('$user_id', '$now')");
                    return false;
                }
            }
        } else {
            // Tal usuário não existe.
            return false;
        }
    }
}

function checkbrute($user_id, $mysqli) {
    // Registra a hora atual 
    $now = time();
 
    // Todas as tentativas de login são contadas dentro do intervalo das últimas 2 horas. 
    $valid_attempts = date('Y-m-d H:i:s', $now - (2 * 60 * 60));
     
    if ($stmt = $mysqli->prepare("SELECT datetime 
                             FROM login_attempts
                             WHERE userid = ? 
                            AND datetime > '$valid_attempts'")) {
        $stmt->bind_param('i', $user_id);
 
        // Executa a tarefa pré-estabelecida. 
        $stmt->execute();
        $stmt->store_result();
 
        // Se houve mais do que 5 tentativas fracassadas de login 
        if ($stmt->num_rows > 5) {
            return true;
        } else {
            return false;
        }
    }
}

function login_check($mysqli) {
    // Verifica se todas as variáveis das sessões foram definidas 
    if (isset($_SESSION['user_id'], 
                        $_SESSION['username'], 
                        $_SESSION['login_string'])) {
 
        $user_id = $_SESSION['user_id'];
        $login_string = $_SESSION['login_string'];
        $username = $_SESSION['username'];
 
        // Pega a string do usuário.
        $user_browser = $_SERVER['HTTP_USER_AGENT'];
 
        if ($stmt = $mysqli->prepare("SELECT pass 
                                      FROM users 
                                      WHERE id = ? LIMIT 1")) {
 
            // Atribui "$user_id" ao parâmetro. 
            $stmt->bind_param('i', $user_id);
            $stmt->execute();   // Execute the prepared query.
            $stmt->store_result();
 
            if ($stmt->num_rows == 1) {

                // Caso o usuário exista, pega variáveis a partir do resultado.                 
                $stmt->bind_result($password);
                $stmt->fetch();
                $login_check = hash('sha512', $password . $user_browser);
 
                if ($login_check === $login_string) {
                    // Logado!!!
                    return true;
                } else {
                    // Não foi logado 
                    return false;
                }
            } else {
                // Não foi logado 
                return false;
            }
        } else {
            // Não foi logado 
            return false;
        }
    } else {
        // Não foi logado 
        return false;
    }
}



function esc_url($url) {
 
    if ('' == $url) {
        return $url;
    }
 
    $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);
 
    $strip = array('%0d', '%0a', '%0D', '%0A');
    $url = (string) $url;
 
    $count = 1;
    while ($count) {
        $url = str_replace($strip, '', $url, $count);
    }
 
    $url = str_replace(';//', '://', $url);
 
    $url = htmlentities($url);
 
    $url = str_replace('&amp;', '&#038;', $url);
    $url = str_replace("'", '&#039;', $url);
 
    if ($url[0] !== '/') {
        // Estamos interessados somente em links relacionados provenientes de $_SERVER['PHP_SELF']
        return '';
    } else {
        return $url;
    }
}

