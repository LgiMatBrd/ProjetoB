<?php

// Esta view possui um controller, por este motivo, este arquivo não deve ser 
// acessado de forma direta ou através de outro arquivo não previsto.
// 
// Testa se este arquivo foi incluído pelo seu controller
if (!defined('OPERADOR_CONTROLLER'))
    die;

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
		#logo_topo {
			width: 50% !important;
			margin-left: 0 !important;
		}
		@media (max-width: 820px) {
			#logo_topo {
				width: 80% !important;
				margin-left: 0% !important;
				margin-top: -20px;
			}
		}
		.uk-icon-exclamation-triangle {
		    color: #c6c634 !important;
		}
		.uk-icon-close {
		    color: #ff0000 !important;
		}
		.uk-icon-clock-o {
		    color: #ffffff !important;
		}
        </style>
    </head>
    <body>
        <div class="uk-width-1-1">
            <div class="uk-width-9-10 uk-container-center uk-margin-large uk-margin-large-top">
                <div class="uk-panel uk-panel-space uk-panel-box uk-panel-header">
                    <div class="uk-grid">
                        <div class="uk-width-2-10 uk-container-center"><img id="logo_topo" class="" src="assets/images/geld.png" /></div>
                        <div class="uk-width-2-10 uk-container-center"><img id="logo_topo" class="" src="assets/images/bsa.png" /></div>
                    </div>
					<?php if (admin_check() == true) { 
						echo '<a href="/admin"><button>Tela do Administrador</button></a>'; 
					} ?>
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
			    <tbody id="table">
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
            <img style="position: absolute; margin: 0px; padding: 0px; border: medium none; width: 100%; height: 100%; max-height: none; max-width: none; z-index: -999999; left: 0px; top: 0px;" src="assets/images/background_cnh_1.jpeg">
        </div>
	
	<script>
	    $(document).ready(function (){
		populate();
	    });
	    var task;
	    function populate()
	    {
		$.ajax({
		    type: "POST",
		    url: "/apps/operador.ajax.php",
		    data: "",
		    success: function (response)
		    {
			if (response.status === "ok")
			{
			    for (var i = 0, row = response.data[0]; i < response.data.length; i++, row = response.data[i])
			    {
				_printRow(row);
			    }
			}
			task = setTimeout(populate, 5000);
		    },
		    dataType: "json"
		});
	    }
	    
	    function entregue(btn,reg,data)
	    {
		clearTimeout(task);
		$(btn).html('<i style="color: #333 !important;" class="uk-icon-refresh uk-icon-spin"></i>');
		$(btn).attr("disabled",true);
		$.ajax({
		    type: "POST",
		    url: "/apps/operador.ajax.php",
		    data: {
			update: 'true',
			reg: reg,
			d: data
		    },
		    success: function (response)
		    {
			if (response.status === "ok")
			{
			    _printRow(response.data[0]);
			}
			task = setTimeout(populate, 5000);
		    },
		    dataType: "json"
		});
	    }
	    
	    function _printRow(row)
	    {
		if (row['ed'] != null)
		{
		    var entr = row['ed']+' às '+row['eh'];
		    var btIco = '<i style="color: #333 !important;" class="uk-icon-check"></i>';
		    var btAttr = ' disabled';
		}
		else
		{
		    var entr = '--/--/--';
		    var btIco = '<i style="color: #333 !important;" class="uk-icon-paper-plane uk-text-center"></i>';
		    var btAttr = '';
		}
	    
		var html = '<td>'+row['p']+'</td>\
				    <!--<td>Coletor de resíduos</td>-->\
				    <td>'+row['d']+' às <strong>'+row['h']+'</strong></td>\
				    <td>'+row['td']+' às <strong>'+row['th']+'</strong></td>\
				    <td>'+entr+'</td>\
				    <td class="uk-text-center"><i class="'+row['status']+'"></i></td>\
				    <td><button class="uk-button" onclick="entregue(this,'+row['r']+',\''+row['d']+'\');"'+btAttr+'>'+btIco+'</button></td>';
		if ($('#'+row['r']).length != 0)
		{
		    if ($('#'+row['r']).html() != html)
		    {
			$('#'+row['r']).html(html);
		    }
		}
		else
		{
		    $('#table').prepend('<tr id="'+row['r']+'">'+html+'</tr>');
		}
	    }
	</script>
    </body>
</html>