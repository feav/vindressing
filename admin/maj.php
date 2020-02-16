<?php

include "../config.php";
include "version.php";

@session_start();

$SQL = "SELECT COUNT(*) FROM pas_admin_user WHERE username = '".$_SESSION['admin_username']."' AND password = '".$_SESSION['admin_password']."'";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

if($req[0] == 0)
{
	header("Location: index.php");
}

if(isset($_REQUEST['action']))
{

}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Administration PAS Script v<?php echo $version; ?></title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> 
</head>
<style>
.update
{
	padding: 10px;
	background-color: #ffd02b;
	box-sizing: border-box;
	border-radius: 5px;
	overflow: auto;
}

.update-button
{
	float: right;
	margin-top: 0px;
}

.btn-update-button
{
	text-decoration: none;
	padding-left: 20px;
	background-color: #216aff;
	padding-right: 20px;
	padding-top: 5px;
	padding-bottom: 5px;
	border-radius: 5px;
	color: #ffffff;
	font-size: 12px;
	font-weight: bold;
}

.btn-update-button:hover
{
	background-color:#6698fc;
}

.progressbar
{
	width: 100%;
	height: 23px;
	background-color: #e4e4e4;
	border-radius: 5px;
	border: 1px solid #d1d1d1;
	overflow: hidden;
}

.prog
{
	width: 0%;
	height: 35px;
	background: #1e5799;
	background: -moz-linear-gradient(top, #1e5799 0%, #2989d8 50%, #207cca 100%, #7db9e8 100%); /* FF3.6-15 */
	background: -webkit-linear-gradient(top, #1e5799 0%,#2989d8 50%,#207cca 100%,#7db9e8 100%); /* Chrome10-25,Safari5.1-6 */
	background: linear-gradient(to bottom, #1e5799 0%,#2989d8 50%,#207cca 100%,#7db9e8 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#1e5799', endColorstr='#7db9e8',GradientType=0 ); /* IE6-9 */
}

.pourcent-info
{
	margin-top: -32px;
	text-align: center;
	font-size: 12px;
	font-weight: bold;
}
</style>
<body>
	<?php
	
	include "header.php";
	include "sidebar.php";
	
	?>
	<div class="container">
		<H1>Mise à jour</H1>
		<div class="info">Vous retrouverez ici les mises à jour du script, pour mettre à jour votre script, il vous suffit de cliquer sur le bouton "Metre à jour" et la mise à jour s'effectuera automatiquement.</div>
		<?php
		
		if($update->getUpdateAvaible($version))
		{
			$data = $update->getUpdate($version);
			//print_r($data);
			?>
			<div class="update">
				<div class="update-information">
				<b>Mise à jour du : </b>
				<?php 
				$date = $data->date; 
				$date = explode("-",$date);
				echo $date[2]."/".$date[1]."/".$date[0];
				?>
				<br>
				<b>Version : </b><?php echo $data->version; ?><br>
				<b>Information :</b> <br><?php echo $data->information; ?><br>
				</div>
				<div class="update-button">
					<div style="padding-top:30px;">
					<a href="javascript:void(0);" id="updateButton" onclick="startUpdate();" class="btn-update-button">Mettre à jour</a>
					</div>
				</div>
				<div class="progression" id="progression" style="display:none;">
					<center><b>Progression de l'installation :</b></center>
					<div class="progressbar">
						<div class="prog" id="prog"></div>
						<div class="pourcent-info" id="pourcent-info">0 %</div>
					</div>
				</div>
			</div>
			<script>
			function startUpdate()
			{
				$('#progression').css('display','block');
				$('#updateButton').html('&nbsp;<div style="position: absolute;width: 20px;height: 20px;margin-top: -20px;"><img src="images/Rolling-1s-25px.gif" style="width:100%;height:auto;"></div>&nbsp; Veuillez patientez');
				$('#updateButton').css('background-color','#25ae02');
				
				<?php
				if($demo)
				{
					?>
					alert("Cette fonctionnalité n'est pas disponible en Mode demonstration");
					<?php
				}
				else
				{
				?>
				
				$.post("upt1.php", function(data) 
				{
					$('#prog').css('width','25%');
					$('#pourcent-info').html('25 %');
					$.post("upt2.php", function(data) 
					{
						$('#prog').css('width','50%');
						$('#pourcent-info').html('50 %');
						$.post("upt3.php", function(data) 
						{
							$('#prog').css('width','75%');
							$('#pourcent-info').html('75 %');
							document.location.href="<?php echo $url_script; ?>/update.php";
						});
					});
				});
				
				<?php
				}
				?>
			}
			</script>
			<?php
		}
		else
		{
			?>
			<div style="background-color: #dddddd;padding: 50px;text-align: center;padding-top: 150px;padding-bottom: 150px;border-radius: 5px;">
			<H1>Votre script est actuellement à jour !</H1>
			</div>
			<?php
		}
		
		?>
	</div>
</body>
</html>