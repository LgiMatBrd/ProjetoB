<?php

/* 
 * Este arquivo é responsável por criar o primeiro usuário do sistema.
 */

if (!defined('ROOT_DIR'))
    die;

include_once ROOT_DIR.'/config/db_connect.php';
include_once ROOT_DIR.'/config/psl-config.php';

if (isset($_POST['username'], $_POST['email'], $_POST['p'], $_POST['pnome']))
{
    // Limpa e valida os dados passados em 
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Email inválido
        AddMsg(REG, ERROR, 'O endereço de email digitado não é válido');
    }
 
    $password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);
    if (strlen($password) != 128) {
        // A senha com hash deve ter 128 caracteres.
        // Caso contrário, algo muito estranho está acontecendo
        AddMsg(REG, ERROR, 'A senha enviada é inválida.');
    }
    
    $pNome = filter_input(INPUT_POST, 'pnome', FILTER_SANITIZE_STRING);
     
    $prep_stmt = 'SELECT id FROM users WHERE email = ? LIMIT 1';
    $stmt = $mysqli->prepare($prep_stmt);
 
    if ($stmt) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
 
        if ($stmt->num_rows == 1) {
            // Um usuário com esse email já esixte
            AddMsg(REG, ERROR, 'Já existe um usuário cadastrado com este email.');
        }
    } else {
        AddMsg(REG, ERROR, 'Erro no banco de dados');
    }
    
    $prep_stmt = 'SELECT id FROM users WHERE username = ? LIMIT 1';
    $stmt = $mysqli->prepare($prep_stmt);
 
    if ($stmt) {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
 
        if ($stmt->num_rows == 1) {
            // Um usuário com esse email já esixte
            AddMsg(REG, ERROR, 'Este nome de usuário já se encontra cadastrado.');
        }
    } else {
        AddMsg(REG, ERROR, 'Erro no banco de dados');
    }
 
    if (empty($msgs[REG])) {
        // Crie um salt aleatório
        $random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
        $atributos = 1;
        // Crie uma senha com salt
        $password = hash('sha512', $password . $random_salt);
        
        $create_time = gmdate('Y-m-d H:i:s');
         
        // Inserir o novo usuário no banco de dados 
        if ($insert_stmt = $mysqli->prepare("INSERT INTO users (username, pass, salt, email, create_time, atributos, pNome) VALUES (?, ?, ?, ?, ?, ?, ?)")) {
            $insert_stmt->bind_param('sssssis', $username, $password, $random_salt, $email, $create_time, $atributos, $pNome);
            // Executar a tarefa pré-estabelecida.
            if (! $insert_stmt->execute()) {
                AddMsg(REG, ERROR, 'Falha ao registrar usuário');
            }
            else
            {
                AddMsg(REG, OK, 'Usuário cadastrado!');
            }
        }
    }
    else
        AddMsg(REG, ERROR, 'Não foi possível criar o usuário devido a erros anteriores!');
}
else
    AddMsg(REG, ERROR, 'Dados enviados insuficientes!');