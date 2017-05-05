<?php

// Esta view possui um controller, por este motivo, este arquivo não deve ser 
// acessado de forma direta ou através de outro arquivo não previsto.
// 
// Testa se este arquivo foi incluído pelo seu controller
if (!defined('LOGIN_CONTROLLER'))
    die;

$time = date('Ymd');
$concreteBackgroundWallPaper = 'http://backgroundimages.concrete5.org/wallpaper/'.$time.'.jpg';
$concreteBackgroundDesc = 'http://backgroundimages.concrete5.org/get_image_data.php?image='.$time.'.jpg';
?>
<!DOCTYPE html>
<html class="uk-height-1-1">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login na área de cadastro</title>
        <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="css/uikit.min.css" />
        <link rel="stylesheet" href="css/uikit.gradient.min.css" />
        <link rel="stylesheet" href="css/components/form-password.min.css" />
        <script src="js/jquery.min.js"></script>
        <script src="js/uikit.min.js"></script>
        <script src="js/components/form-password.min.js"></script>
        <script type="text/JavaScript" src="js/sha512.js"></script>
        <script type="text/JavaScript" src="js/forms.js"></script>
        <style>
            .uk-panel-box {
                background: #fafafaee;
                border-radius: 8px;
            }
            .backstretch {
                background: #2cb5e8cc; /* For browsers that do not support gradients */
                background: -webkit-linear-gradient(#0fb8ad, #2cb5e8); /* For Safari 5.1 to 6.0 */
                background: -o-linear-gradient(#0fb8ad, #2cb5e8); /* For Opera 11.1 to 12.0 */
                background: -moz-linear-gradient(#0fb8ad, #2cb5e8); /* For Firefox 3.6 to 15 */
                background: linear-gradient(0deg, #0fb8ad 0%, #1fc8db 51%, #2cb5e8 75%);
            }
        </style>
    </head>
    <body class="uk-height-1-1">
        
        <?php
        if (isset($_GET['error'])) {
            echo '<p class="error">Erro ao fazer o login!</p>';
        }
        ?> 
        <div class="uk-vertical-align uk-text-center uk-height-1-1">
            <div class="uk-vertical-align-middle uk-width-9-10 uk-width-medium-1-3 uk-margin-small uk-margin-small-top">
                <div class="uk-panel uk-panel-box uk-panel-header">
                    <h3 class="uk-panel-title">Faça login para continuar</h3>
                    <form id="form1" class="uk-form uk-form-stacked uk-text-left" action="/login" method="post" name="login_form">                      
                        <input type="hidden" name="makelogin" value="yes" />
                        <input type="hidden" name="gotopage" value="<? echo $gotopage; ?>" />
                        <div class="uk-form-row">
                            <label class="uk-form-label" for="user">Usuário</label>
                            <div class="uk-form-controls">
                                <input class="uk-width-1-1 uk-form-large" type="text" id="user" name="username" />
                            </div>
                        </div>
                        <div class="uk-form-row">
                            <label class="uk-form-label" for="password">Senha</label>
                            <div class="uk-form-controls">
                                <div class="uk-form-password uk-width-1-1">
                                    <input class="uk-width-1-1 uk-form-large"
                                                     type="password" 
                                                     name="password" 
                                                     id="password"/>
                                    <a class="uk-form-password-toggle" data-uk-form-password="{lblShow:'Mostrar', lblHide:'Esconder'}">Mostrar</a>
                                </div>
                            </div>
                        </div>
                        <div class="uk-form-row">
                            <button class="uk-width-1-1 uk-button uk-button-primary uk-button-large" type="submit" 
                               onclick="formhash(form1, form1.password);">Entrar</button>
                        </div>
                    </form>
                    <p>No momento você está <?php echo ($logged == 'in')? 'conectado' : 'deconectado'; ?>.</p>
                </div>
            </div>
        </div>
        
        <div class="backstretch" style="left: 0px; top: 0px; overflow: hidden; margin: 0px; padding: 0px; height: 100%; width: 100%; z-index: -999999; position: fixed;">
            <img style="position: absolute; margin: 0px; padding: 0px; border: medium none; width: 100%; height: 100%; max-height: none; max-width: none; z-index: -999999; left: 0px; top: 0px;" src="<? echo $concreteBackgroundWallPaper; ?>">
        </div>
    </body>
</html>