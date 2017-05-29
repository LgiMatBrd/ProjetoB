<?php

// Esta view possui um controller, por este motivo, este arquivo não deve ser 
// acessado de forma direta ou através de outro arquivo não previsto.
// 
// Testa se este arquivo foi incluído pelo seu controller
if (!defined('ADMIN_CONTROLLER'))
    die;

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Administrador</title>
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
			width: 50% !important;
			margin-left: 0 !important;
		}
		@media (max-width: 820px) {
			#logo_topo {
				width: 80% !important;
				margin-left: 0% !important;
				margin-top: -20px;
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
		.uk-overflow-container {
			float: left !important;
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
                        <div class="uk-width-2-10 uk-container-center"><img id="logo_topo" class="" src="assets/images/geld.png" /></div>
                        <div class="uk-width-2-10 uk-container-center"><img id="logo_topo" class="" src="assets/images/bsa.png" /></div>
                    </div>
                    <div class="uk-panel-title"></div>
					<div class="uk-width-1-2 uk-overflow-container">
						<h4 class="tm-article-subtitle" style="padding-left: 6px;border-left: 3px solid #E01048;font-size: 16px;line-height: 16px;"><strong>Solicitações Díarias</strong></h4>
						<canvas id="myChart" width="400" height="400"></canvas>
					</div>
					<div class="uk-width-1-2 uk-overflow-container">
						<h4 class="tm-article-subtitle" style="padding-left: 6px;border-left: 3px solid #E01048;font-size: 16px;line-height: 16px;"><strong>Solicitações Semanais</strong></h4>
						<canvas id="myChart2" width="400" height="400"></canvas>
					</div>
					<!--<div class="uk-width-1-1 uk-overflow-container">
						<h4 class="tm-article-subtitle" style="padding-left: 6px;border-left: 3px solid #E01048;font-size: 16px;line-height: 16px;"><strong>Mais solicitadas do mês</strong></h4>
						<canvas id="myChart3" width="400" height="400"></canvas>
					</div>-->
                </div>
            </div>
        </div>
        
        <div class="backstretch" style="left: 0px; top: 0px; overflow: hidden; margin: 0px; padding: 0px; height: 100%; width: 100%; z-index: -999999; position: fixed;">
            <img style="position: absolute; margin: 0px; padding: 0px; border: medium none; width: 100%; height: 100%; max-height: none; max-width: none; z-index: -999999; left: 0px; top: 0px;" src="assets/images/background_cnh_1.jpeg">
        </div>
	<!--
	<script src="../js/Chart.min.js" />
	<script src="../js/admin_charts.js" />
	-->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
	<script>
	    var ctx = document.getElementById("myChart");
	    var ctx2 = document.getElementById("myChart2");
	    //var ctx3 = document.getElementById("myChart3");
	    var task;
	    var baColor = new Array();
	    var boColor = new Array();

	    $(document).ready(function (){
		populate();
	    });
	    
	    function populate()
	    {
		$.ajax({
		    type: "POST",
		    url: "/apps/admin.ajax.php",
		    data: "",
		    success: function (response)
		    {
			if (response.status === "ok")
			{
			    var labels = new Array();
			    var data = new Array();
			    $.each(response.semanal, function (index, value)
			    {
				labels.push("Peça "+index);
				data.push(value);
			    });
			    printChart1(labels, data);
			    labels = [];
			    data = [];
			    $.each(response.diario, function (index, value)
			    {
				labels.push("Peça "+index);
				data.push(value);
			    });
			    printChart2(labels, data);
			}
			task = setTimeout(populate, 60000);
		    },
		    dataType: "json"
		});
	    }
	    
	    function randomNumber(min, max)
	    {
		return Math.floor(Math.random() * (max - min + 1)) + min;
	    }
	    
	    function printChart1(labels, data)
	    {
		for (var i = baColor.length; i < labels.length; i++)
		{
		    var r = randomNumber(0,255);
		    var g = randomNumber(0,255);
		    var b = randomNumber(0,255);
		    baColor.push('rgba('+r+','+g+','+b+',0.2)');
		    boColor.push('rgba('+r+','+g+','+b+',1)');
		}
		
		var myChart = new Chart(ctx, {
			type: 'bar',
			data: {
			    labels: labels,
			    datasets: [{
				label: 'Solicitações Diárias',
				data: data,
				backgroundColor: baColor,
				borderColor: boColor,
				borderWidth: 1
			    }]
			},
			options: {
			    scales: {
				yAxes: [{
				    ticks: {
					beginAtZero:true
				    }
				}]
			    }
			}
		});
	    }
	    function printChart2(labels, data)
	    {
		var myChart2 = new Chart(ctx2, {
		    type: 'line',
		    data: {
			labels: labels,
			datasets: [
			{
			    label: "Solicitações semanais",
			    fill: false,
			    lineTension: 0.1,
			    backgroundColor: "rgba(75,192,192,0.4)",
			    borderColor: "rgba(75,192,192,1)",
			    borderCapStyle: 'butt',
			    borderDash: [],
			    borderDashOffset: 0.0,
			    borderJoinStyle: 'miter',
			    pointBorderColor: "rgba(75,192,192,1)",
			    pointBackgroundColor: "#fff",
			    pointBorderWidth: 1,
			    pointHoverRadius: 5,
			    pointHoverBackgroundColor: "rgba(75,192,192,1)",
			    pointHoverBorderColor: "rgba(220,220,220,1)",
			    pointHoverBorderWidth: 2,
			    pointRadius: 1,
			    pointHitRadius: 10,
			    data: data,
			    spanGaps: false,
			}
		    ]
		    },
		    options: {
			scales: {
			    yAxes: [{
				ticks: {
				    beginAtZero:true
				}
			    }]
			}
		    }
		});
	    }
	</script>
		
    </body>
</html>