<?php

// Esta view possui um controller, por este motivo, este arquivo não deve ser 
// acessado de forma direta ou através de outro arquivo não previsto.
// 
// Testa se este arquivo foi incluído pelo seu controller
if (!defined('OPERADOR_CONTROLLER'))
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
        <title>Operador</title>
        <link rel="shortcut icon" href="assets/images/favicon.png" type="image/x-icon">
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
		#logo_topo {
			width: 20% !important;
			margin-left: 36% !important;
		}
		@media (max-width: 820px) {
			#logo_topo {
				width: 30% !important;
				margin-left: 30% !important;
				margin-top: -25px;
			}
			.uk-table tr, .uk-table tr {
				font-size: 10px;
				text-align: center;
			}
		}
		td i {
			margin-top: 10px;
		}
		.uk-button {
			border: 1px solid #bcbcbc !important;
			background: none !important;
			color: #e01048;
			padding: 0 12px 0px 10px;
			margin: 0 !important;
		}
		.uk-table > tbody > tr > td:nth-child(5) {
			background-color: #3b3b3b !important;
		}
		.uk-table > tbody > tr > td:nth-child(5) > i {
			font-size: 16px !important;
		}
        </style>
    </head>
    <body>
        <div class="uk-width-1-1">
            <div class="uk-width-9-10 uk-container-center uk-margin-large uk-margin-large-top">
                <div class="uk-panel uk-panel-space uk-panel-box uk-panel-header">
                    <div class="uk-grid">
                        <div class="uk-width-1-10"></div>
                        <!--<div class="uk-width-8-10"><h2 class="uk-text-center">Operador</h2></div>-->
                        <div class="uk-width-8-10 uk-container-center"><img id="logo_topo" class="" src="assets/images/logo_skorp.png" /></div>
                    </div>
                    <div class="uk-panel-title"></div>
					<h4 class="tm-article-subtitle" style="padding-left: 6px;border-left: 3px solid #E01048;font-size: 16px;line-height: 16px;"><strong>Lista de solicitações</strong></h4>
					<div class="uk-overflow-container">
						<table class="uk-table uk-table-hover uk-table-striped uk-table-condensed">
							<thead>
								<tr>
									<th>ID</th>
									<!--<th>Peça Solicitada</th>-->
									<th>Hora da solicitação</th>
									<th>Hora limite</th>
									<th>Hora de entrega</th>
									<th>Status</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<!--<tr>
									<td>23435</td>
									<!--<td>Coletor de resíduos</td>--><!--
									<td>04/03/2017 ás <strong>15:33</strong></td>
									<td>05/03/2017 ás <strong>15:33</strong></td>
									<td>--/--/--</td>
									<td><i style="color: green !important;" class="uk-icon-check-square-o"></i></td>
									<td></td>
								</tr>-->
								<tr>
									<td>23435</td>
									<!--<td>Coletor de resíduos</td>-->
									<td>04/03/2017 ás <strong>15:33</strong></td>
									<td>05/03/2017 ás <strong>15:33</strong></td>
									<td>--/--/--</td>
									<td><i style="color: #c6c634 !important;" class="uk-icon-exclamation-triangle uk-text-center"></i></td> 
									<td><button class="uk-button"><i style="color: #333 !important;" class="uk-icon-paper-plane uk-text-center"></i></button></td>
								</tr>
								<tr>
									<td>23435</td>
									<!--<td>Coletor de resíduos</td>-->
									<td>04/03/2017 ás <strong>15:33</strong></td>
									<td>05/03/2017 ás <strong>15:33</strong></td>
									<td>--/--/--</td>
									<td><i style="color: red !important;" class="uk-icon-close uk-text-center"></i></td>
									<td><button class="uk-button"><i style="color: #333 !important;" class="uk-icon-paper-plane uk-text-center"></i></button></td>
								</tr>
								<tr>
									<td>23435</td>
									<!--<td>Coletor de resíduos</td>-->
									<td>04/03/2017 ás <strong>15:33</strong></td>
									<td>05/03/2017 ás <strong>15:33</strong></td>
									<td>--/--/--</td>
									<td><i style="color: #fff !important;" class="uk-icon-clock-o uk-text-center"></i></td>
									<td><button class="uk-button"><i style="color: #333 !important;" class="uk-icon-paper-plane uk-text-center"></i></button></td>
								</tr>
							</tbody>
						</table>
						<i style="color: red !important;" class="uk-icon-close"></i> = Em atraso<br/>
						<i style="color: #c6c634 !important;" class="uk-icon-exclamation-triangle uk-text-center "></i> = Quase atrasando<br/>
						<i style="color: #333 !important;" class="uk-icon-clock-o"></i> = Dentro do prazo
					</div>
                </div>
            </div>
        </div>
        
        <div class="backstretch" style="left: 0px; top: 0px; overflow: hidden; margin: 0px; padding: 0px; height: 100%; width: 100%; z-index: -999999; position: fixed;">
            <img style="position: absolute; margin: 0px; padding: 0px; border: medium none; width: 100%; height: 100%; max-height: none; max-width: none; z-index: -999999; left: 0px; top: 0px;" src="<? echo $concreteBackgroundWallPaper; ?>">
        </div>
    </body>
</html>