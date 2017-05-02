<?php

// Esta view possui um controller, por este motivo, este arquivo não deve ser 
// acessado de forma direta ou através de outro arquivo não previsto.
// 
// Testa se este arquivo foi incluído pelo seu controller
if (!defined('HOME_CONTROLLER'))
    die;

$time = date('Ymd');
$concreteBackgroundWallPaper = 'http://backgroundimages.concrete5.org/wallpaper/'.$time.'.jpg';
$concreteBackgroundDesc = 'http://backgroundimages.concrete5.org/get_image_data.php?image='.$time.'.jpg';

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Cadastro de usuários</title>
        <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="css/uikit.min.css" />
        <link rel="stylesheet" href="css/uikit.gradient.min.css" />
        <link rel="stylesheet" href="css/components/notify.min.css" />
        <link rel="stylesheet" href="css/components/notify.gradient.min.css" />
        <script src="js/jquery.min.js"></script>
        <script src="js/uikit.min.js"></script>
        <script src="js/components/notify.min.js"></script>
        <script src="js/sha512.js"></script> 
        <script src="js/forms.js"></script> 
        <script src="js/jquery.validate.min.js"></script>
        <script src="js/localization/messages_pt_BR.min.js"></script>
        <style>
            .backstretch {
                background: #2cb5e8cc; /* For browsers that do not support gradients */
                background: -webkit-linear-gradient(#0fb8ad, #2cb5e8); /* For Safari 5.1 to 6.0 */
                background: -o-linear-gradient(#0fb8ad, #2cb5e8); /* For Opera 11.1 to 12.0 */
                background: -moz-linear-gradient(#0fb8ad, #2cb5e8); /* For Firefox 3.6 to 15 */
                background: linear-gradient(0deg, #0fb8ad 0%, #1fc8db 51%, #2cb5e8 75%);
            }
        </style>
    </head>
    <body>
        <div class="uk-width-1-1">
            <div class="uk-width-9-10 uk-container-center uk-margin-large uk-margin-large-top">
                <div class="uk-panel uk-panel-space uk-panel-box uk-panel-header">
                    <div class="uk-grid">
                        <div class="uk-width-1-10"></div>
                        <div class="uk-width-8-10"><h2 class="uk-text-center">Cadastrar novo usuário:</h2></div>
                        <div class="uk-width-1-10"><a class="uk-button-link uk-align-right" href="/logout">Sair</a></div>
                    </div>
                    <div class="uk-panel-title"></div>
                    <form id="form1" class="uk-form uk-form-horizontal" action="/home" method="post" name="form">                      
                        <fieldset>
                            <div class="uk-form-row">
                                <label class="uk-form-label">Usuário:</label>
                                <div class="uk-form-controls">
                                    <input class="uk-width-1-1" type="text" minlength="4" name="username" required />                                    
                                </div>
                            </div>
                            <div class="uk-form-row">
                                <label class="uk-form-label">Senha:</label>
                                <div class="uk-form-controls">
                                    <input class="uk-width-1-1" type="password" minlength="8" pwcheck="true" id="user-pass1" name="password1" required />
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
                                    <input class="uk-width-1-1" type="email" id="user-email1" name="email1" required />
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
                                Cadastrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="backstretch" style="left: 0px; top: 0px; overflow: hidden; margin: 0px; padding: 0px; height: 100%; width: 100%; z-index: -999999; position: fixed;">
            <img style="position: absolute; margin: 0px; padding: 0px; border: medium none; width: 100%; height: 100%; max-height: none; max-width: none; z-index: -999999; left: 0px; top: 0px;" src="<? echo $concreteBackgroundWallPaper; ?>">
        </div>
        <script>
            
            <?php
            while ($msg = GetMsg())
            { ?>
            UIkit.notify({
                message : '<? echo $msg['msg']; ?>',
                status  : '<? echo $msg['status']; ?>',
                timeout : 5000,
                pos     : 'top-center'
            });
            <?php }
            ?>
            $('form').validate({
                submitHandler: function (form) {
                    $(form).find('#user-email1').removeAttr('name');
                    $(form).find('#user-pass1').removeAttr('name');
                    formhash(form, form.password);
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
                    password1: {
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