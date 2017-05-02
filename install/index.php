<?php

/* 
 * Arquivo base do instalador do sistema web.
 */
header('Content-Type: text/html; charset=utf-8');
if (!defined('ROOT_DIR'))
    die('Diretório raiz não foi previamente definido!');

// Verifica se a instalação está atualizada (conforme arquivo version.php)
$installerVersion = '0.1';
$version = '-1';
if (file_exists(ROOT_DIR.'/config/version.php'))
    include_once ROOT_DIR.'/config/version.php';

if ($version !== $installerVersion)
{

$time = date('Ymd');
$concreteBackgroundWallPaper = 'http://backgroundimages.concrete5.org/wallpaper/'.$time.'.jpg';
$concreteBackgroundDesc = 'http://backgroundimages.concrete5.org/get_image_data.php?image='.$time.'.jpg';

// Checa se foram recebidos dados de formulário
if (isset($_POST['instalar']))
{
    include ROOT_DIR.'/install/install.php';
} 
else
{
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instalar e Configurar o Sistema</title>
        <link rel="shortcut icon" href="install/install.ico" type="image/x-icon">
        <link rel="stylesheet" href="css/uikit.min.css" />
        <link rel="stylesheet" href="css/uikit.gradient.min.css" />
        <link rel="stylesheet" href="css/components/form-password.min.css" />
        <script src="js/jquery.min.js"></script>
        <script src="js/uikit.min.js"></script>
        <script src="js/components/form-password.min.js"></script>
        <script src="js/sha512.js"></script> 
        <script src="js/forms.js"></script>
        <script src="js/jquery.validate.min.js"></script>
        <script src="js/localization/messages_pt_BR.min.js"></script>
    </head>
    <body>
        <div class="uk-width-1-1">
            <div class="uk-width-9-10 uk-container-center uk-margin-large uk-margin-large-top">
                <div class="uk-panel uk-panel-space uk-panel-box uk-panel-header">
                    <h2 class="uk-text-center">Instalação e configuração</h2>
                    <div class="uk-panel-title"></div>
                    <form id="form1" class="uk-form uk-form-horizontal" action="index.php" method="post" name="form">                      
                        <input type="hidden" name="instalar" value="true" />
                        <input type="hidden" name="atributos" value="1" />
                        <fieldset>
                            <legend>Informações do banco de dados</legend>
                            <div class="uk-form-row">
                                <label class="uk-form-label">Servidor:</label>
                                <div class="uk-form-controls">
                                    <input class="uk-width-1-1" value="localhost" type="text" name="db-server" required />
                                </div>
                            </div>
                            <div class="uk-form-row">
                                <label class="uk-form-label">Usuário MySQL:</label>
                                <div class="uk-form-controls">
                                    <input class="uk-width-1-1" type="text" name="db-user" required />
                                </div>
                            </div>
                            <div class="uk-form-row">
                                <label class="uk-form-label">Senha MySQL:</label>
                                <div class="uk-form-controls">
                                    <input class="uk-width-1-1" type="password" name="db-pass" required />
                                </div>
                            </div>
                            <div class="uk-form-row uk-margin-large-bottom">
                                <label class="uk-form-label">Nome do banco de dados:</label>
                                <div class="uk-form-controls">
                                    <input class="uk-width-1-1" type="text" name="db-name" required />
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend>Configurações dos emails enviados</legend>
                            <div class="uk-form-row">
                                <label class="uk-form-label">Enviar do email:</label>
                                <div class="uk-form-controls">
                                    <input class="uk-width-1-1" type="email" name="from-email" required />
                                </div>
                            </div>
                            <div class="uk-form-row uk-margin-large-bottom">
                                <label class="uk-form-label">Enviar com o nome:</label>
                                <div class="uk-form-controls">
                                    <input class="uk-width-1-1" type="text" name="from-name" required />
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend>Informações do usuário administrador</legend>
                            <div class="uk-form-row">
                                <label class="uk-form-label">Usuário:</label>
                                <div class="uk-form-controls">
                                    <input class="uk-width-1-1" type="text" minlength="4" name="username" required />
                                </div>
                            </div>
                            <div class="uk-form-row">
                                <label class="uk-form-label">Senha:</label>
                                <div class="uk-form-controls">
                                    <input class="uk-width-1-1" type="password" minlength="8" id="user-pass1" name="user-pass1" pwcheck="true" required />
                                </div>
                            </div>
                            <div class="uk-form-row">
                                <label class="uk-form-label">Confirmação da senha:</label>
                                <div class="uk-form-controls">
                                    <input class="uk-width-1-1" type="password" equalTo="#user-pass1" id="user-pass2" name="password" required />
                                </div>
                            </div>
                            <div class="uk-form-row">
                                <label class="uk-form-label">Email:</label>
                                <div class="uk-form-controls">
                                    <input class="uk-width-1-1" type="email" id="user-email1" required />
                                </div>
                            </div>
                            <div class="uk-form-row">
                                <label class="uk-form-label">Confirmação de email:</label>
                                <div class="uk-form-controls">
                                    <input class="uk-width-1-1" type="email" equalTo="#user-email1" id="user-email2" name="email" required />
                                </div>
                            </div>
                            <div class="uk-form-row uk-margin-large-bottom">
                                <label class="uk-form-label">Primeiro nome:</label>
                                <div class="uk-form-controls">
                                    <input class="uk-width-1-1" type="text" name="pnome" required />
                                </div>
                            </div>
                        </fieldset>
                        <div class="uk-form-row">
                            <button class="uk-width-1-1 uk-button uk-button-primary uk-button-large" type="submit">
                                Instalar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="backstretch" style="left: 0px; top: 0px; overflow: hidden; margin: 0px; padding: 0px; height: 100%; width: 100%; z-index: -999999; position: fixed;">
            <img style="position: absolute; margin: 0px; padding: 0px; border: medium none; width: 100%; height: 100%; max-height: none; max-width: none; z-index: -999999; left: 0px; top: 0px;" src="<? echo $concreteBackgroundWallPaper; ?>">
        </div>
        <script>
            
            $('form').validate({
                submitHandler: function (form) {
                    $(form).find('#user-pass1').removeAttr('name');
                    formhash(form, form.password);
                    $('button').attr("disabled", true);
                },
                onfocusout: function (element) {
                    $(element).valid();
                },
                onkeyup: false,
                validClass: "uk-form-success",
                errorClass: "uk-form-danger",
                errorElement: "span",
                focusInvalid: false,
                messages: {
                    username: {
                        minlength: 'Usuário muito curto. Mínimo de 4 caracteres.'
                    },
                    "user-pass1": {
                        minlength: 'Senha muito curta. Mínimo de 8 caracteres.'
                    },
                    password: {
                        equalTo: 'As senhas se diferem!'
                    },
                    email: {
                        equalTo: 'Parece que os emails não são iguais!'
                    }
                }
            });
            $.extend($.validator.messages, {
                required: 'Campo de preenchimento obrigatório.',
                email: 'Email inválido!',
                minlength: $.validator.format("São necessários pelo menos {0} caracteres.")
            });
            $.validator.addMethod("pwcheck", function(value) {
                return /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/.test(value);
            }, "A senha precisa ser composta de números, letras maiúsculas e letras minúsculas.");
            
        </script>
    </body>
</html>
<?php
}
exit;
}
?>