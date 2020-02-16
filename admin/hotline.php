<?php
include "../config.php";
include "version.php";
include "../engine/class.horaire-ouverture.php";

$horaire = new Horaire_Ouverture();

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
	$action = $_REQUEST['action'];
	if($action == 1)
	{
		$phone_hotline = $_REQUEST['phone_hotline'];
		updateConfig("phone_hotline",$phone_hotline);
		
		$message_hotline = $_REQUEST['message_hotline'];
		updateConfig("message_hotline",$message_hotline);
		
		$hotline_activate = $_REQUEST['hotline_activate'];
		updateConfig("hotline_activate",$hotline_activate);
		
		$hotline_open_message = $_REQUEST['hotline_open_message'];
		updateConfig("hotline_open_message",$hotline_open_message);
		
		$lundi_heure_ouverture1 = $_REQUEST['lundi_heure_ouverture1'];
		$lundi_heure_fermeture1 = $_REQUEST['lundi_heure_fermeture1'];
		$lundi_heure_ouverture2 = $_REQUEST['lundi_heure_ouverture2'];
		$lundi_heure_fermeture2 = $_REQUEST['lundi_heure_fermeture2'];
		
		$mardi_heure_ouverture1 = $_REQUEST['mardi_heure_ouverture1'];
		$mardi_heure_fermeture1 = $_REQUEST['mardi_heure_fermeture1'];
		$mardi_heure_ouverture2 = $_REQUEST['mardi_heure_ouverture2'];
		$mardi_heure_fermeture2 = $_REQUEST['mardi_heure_fermeture2'];
		
		$mercredi_heure_ouverture1 = $_REQUEST['mercredi_heure_ouverture1'];
		$mercredi_heure_fermeture1 = $_REQUEST['mercredi_heure_fermeture1'];
		$mercredi_heure_ouverture2 = $_REQUEST['mercredi_heure_ouverture2'];
		$mercredi_heure_fermeture2 = $_REQUEST['mercredi_heure_fermeture2'];
		
		$jeudi_heure_ouverture1 = $_REQUEST['jeudi_heure_ouverture1'];
		$jeudi_heure_fermeture1 = $_REQUEST['jeudi_heure_fermeture1'];
		$jeudi_heure_ouverture2 = $_REQUEST['jeudi_heure_ouverture2'];
		$jeudi_heure_fermeture2 = $_REQUEST['jeudi_heure_fermeture2'];
		
		$vendredi_heure_ouverture1 = $_REQUEST['vendredi_heure_ouverture1'];
		$vendredi_heure_fermeture1 = $_REQUEST['vendredi_heure_fermeture1'];
		$vendredi_heure_ouverture2 = $_REQUEST['vendredi_heure_ouverture2'];
		$vendredi_heure_fermeture2 = $_REQUEST['vendredi_heure_fermeture2'];
		
		$samedi_heure_ouverture1 = $_REQUEST['samedi_heure_ouverture1'];
		$samedi_heure_fermeture1 = $_REQUEST['samedi_heure_fermeture1'];
		$samedi_heure_ouverture2 = $_REQUEST['samedi_heure_ouverture2'];
		$samedi_heure_fermeture2 = $_REQUEST['samedi_heure_fermeture2'];
				
		$dimanche_heure_ouverture1 = $_REQUEST['dimanche_heure_ouverture1'];
		$dimanche_heure_fermeture1 = $_REQUEST['dimanche_heure_fermeture1'];
		$dimanche_heure_ouverture2 = $_REQUEST['dimanche_heure_ouverture2'];
		$dimanche_heure_fermeture2 = $_REQUEST['dimanche_heure_fermeture2'];
		
		$lundi = $_REQUEST['lundi'];
		$mardi = $_REQUEST['mardi'];
		$mercredi = $_REQUEST['mercredi'];
		$jeudi = $_REQUEST['jeudi'];
		$vendredi = $_REQUEST['vendredi'];
		$samedi = $_REQUEST['samedi'];
		$dimanche = $_REQUEST['dimanche'];
		
		if($lundi == '')
		{
			$lundi = 'non';
		}
		if($mardi == '')
		{
			$mardi = 'non';
		}
		if($mercredi == '')
		{
			$mercredi = 'non';
		}
		if($jeudi == '')
		{
			$jeudi = 'non';
		}
		if($vendredi == '')
		{
			$vendredi = 'non';
		}
		if($samedi == '')
		{
			$samedi = 'non';
		}
		if($dimanche == '')
		{
			$dimanche = 'non';
		}
		
		$SQL = "UPDATE horaire_ouverture SET isOpen = '$lundi' WHERE jour = 'Mon'";
		$pdo->query($SQL);
		$SQL = "UPDATE horaire_ouverture SET isOpen = '$mardi' WHERE jour = 'Tue'";
		$pdo->query($SQL);
		$SQL = "UPDATE horaire_ouverture SET isOpen = '$mercredi' WHERE jour = 'Wed'";
		$pdo->query($SQL);
		$SQL = "UPDATE horaire_ouverture SET isOpen = '$jeudi' WHERE jour = 'Thu'";
		$pdo->query($SQL);
		$SQL = "UPDATE horaire_ouverture SET isOpen = '$vendredi' WHERE jour = 'Fri'";
		$pdo->query($SQL);
		$SQL = "UPDATE horaire_ouverture SET isOpen = '$samedi' WHERE jour = 'Sat'";
		$pdo->query($SQL);
		$SQL = "UPDATE horaire_ouverture SET isOpen = '$samedi' WHERE jour = 'Sun'";
		$pdo->query($SQL);
		
		$SQL = "UPDATE horaire_ouverture SET heure_ouverture1 = '$lundi_heure_ouverture1' WHERE jour = 'Mon'";
		$pdo->query($SQL);
		$SQL = "UPDATE horaire_ouverture SET heure_ouverture2 = '$lundi_heure_ouverture2' WHERE jour = 'Mon'";
		$pdo->query($SQL);
		$SQL = "UPDATE horaire_ouverture SET heure_fermeture1 = '$lundi_heure_fermeture1' WHERE jour = 'Mon'";
		$pdo->query($SQL);
		$SQL = "UPDATE horaire_ouverture SET heure_fermeture2 = '$lundi_heure_fermeture2' WHERE jour = 'Mon'";
		$pdo->query($SQL);
		
		$SQL = "UPDATE horaire_ouverture SET heure_ouverture1 = '$mardi_heure_ouverture1' WHERE jour = 'Tue'";
		$pdo->query($SQL);
		$SQL = "UPDATE horaire_ouverture SET heure_ouverture2 = '$mardi_heure_ouverture2' WHERE jour = 'Tue'";
		$pdo->query($SQL);
		$SQL = "UPDATE horaire_ouverture SET heure_fermeture1 = '$mardi_heure_fermeture1' WHERE jour = 'Tue'";
		$pdo->query($SQL);
		$SQL = "UPDATE horaire_ouverture SET heure_fermeture2 = '$mardi_heure_fermeture2' WHERE jour = 'Tue'";
		$pdo->query($SQL);
		
		$SQL = "UPDATE horaire_ouverture SET heure_ouverture1 = '$mercredi_heure_ouverture1' WHERE jour = 'Wed'";
		$pdo->query($SQL);
		$SQL = "UPDATE horaire_ouverture SET heure_ouverture2 = '$mercredi_heure_ouverture2' WHERE jour = 'Wed'";
		$pdo->query($SQL);
		$SQL = "UPDATE horaire_ouverture SET heure_fermeture1 = '$mercredi_heure_fermeture1' WHERE jour = 'Wed'";
		$pdo->query($SQL);
		$SQL = "UPDATE horaire_ouverture SET heure_fermeture2 = '$mercredi_heure_fermeture2' WHERE jour = 'Wed'";
		$pdo->query($SQL);
		
		$SQL = "UPDATE horaire_ouverture SET heure_ouverture1 = '$jeudi_heure_ouverture1' WHERE jour = 'Thu'";
		$pdo->query($SQL);
		$SQL = "UPDATE horaire_ouverture SET heure_ouverture2 = '$jeudi_heure_ouverture2' WHERE jour = 'Thu'";
		$pdo->query($SQL);
		$SQL = "UPDATE horaire_ouverture SET heure_fermeture1 = '$jeudi_heure_fermeture1' WHERE jour = 'Thu'";
		$pdo->query($SQL);
		$SQL = "UPDATE horaire_ouverture SET heure_fermeture2 = '$jeudi_heure_fermeture2' WHERE jour = 'Thu'";
		$pdo->query($SQL);
		
		$SQL = "UPDATE horaire_ouverture SET heure_ouverture1 = '$vendredi_heure_ouverture1' WHERE jour = 'Fri'";
		$pdo->query($SQL);
		$SQL = "UPDATE horaire_ouverture SET heure_ouverture2 = '$vendredi_heure_ouverture2' WHERE jour = 'Fri'";
		$pdo->query($SQL);
		$SQL = "UPDATE horaire_ouverture SET heure_fermeture1 = '$vendredi_heure_fermeture1' WHERE jour = 'Fri'";
		$pdo->query($SQL);
		$SQL = "UPDATE horaire_ouverture SET heure_fermeture2 = '$vendredi_heure_fermeture2' WHERE jour = 'Fri'";
		$pdo->query($SQL);
		
		$SQL = "UPDATE horaire_ouverture SET heure_ouverture1 = '$samedi_heure_ouverture1' WHERE jour = 'Sat'";
		$pdo->query($SQL);
		$SQL = "UPDATE horaire_ouverture SET heure_ouverture2 = '$samedi_heure_ouverture2' WHERE jour = 'Sat'";
		$pdo->query($SQL);
		$SQL = "UPDATE horaire_ouverture SET heure_fermeture1 = '$samedi_heure_fermeture1' WHERE jour = 'Sat'";
		$pdo->query($SQL);
		$SQL = "UPDATE horaire_ouverture SET heure_fermeture2 = '$samedi_heure_fermeture2' WHERE jour = 'Sat'";
		$pdo->query($SQL);
		
		$SQL = "UPDATE horaire_ouverture SET heure_ouverture1 = '$dimanche_heure_ouverture1' WHERE jour = 'Sun'";
		$pdo->query($SQL);
		$SQL = "UPDATE horaire_ouverture SET heure_ouverture2 = '$dimanche_heure_ouverture2' WHERE jour = 'Sun'";
		$pdo->query($SQL);
		$SQL = "UPDATE horaire_ouverture SET heure_fermeture1 = '$dimanche_heure_fermeture1' WHERE jour = 'Sun'";
		$pdo->query($SQL);
		$SQL = "UPDATE horaire_ouverture SET heure_fermeture2 = '$dimanche_heure_fermeture2' WHERE jour = 'Sun'";
		$pdo->query($SQL);
	}
}

function addPickerTime($id,$name)
{
	$value = $_REQUEST[$name];
	if($value == '')
	{
		$value = '00:00:00';
	}
	?>
	<div style="display: inline-block;">
		<input type="text" name="<?php echo $name; ?>" value="<?php echo $value; ?>" id="timepicker-<?php echo $id; ?>" class="timepickervalue" onclick="showPicker(<?php echo $id; ?>)">
		<div class="timepickerbox" id="timepickerbox-<?php echo $id; ?>">
		<div class="timebox">
				<a href="javascript:void(0);" onclick="upHour(<?php echo $id; ?>);">
					<div class="upbtn">
						<i class="fas fa-chevron-up"></i>
					</div>
				</a>
				<div id="timehour-<?php echo $id; ?>">
				00
				</div>
				<a href="javascript:void(0);" onclick="downHour(<?php echo $id; ?>);">
					<div class="upbtn">
						<i class="fas fa-chevron-down"></i>
					</div>
				</a>
			</div>
			<div class="timebox">
				<a href="javascript:void(0);" onclick="upMinute(<?php echo $id; ?>);">
					<div class="upbtn">
						<i class="fas fa-chevron-up"></i>
					</div>
				</a>
				<div id="timeminute-<?php echo $id; ?>">
				00
				</div>
				<a href="javascript:void(0);" onclick="downMinute(<?php echo $id; ?>);">
					<div class="upbtn">
						<i class="fas fa-chevron-down"></i>
					</div>
				</a>
			</div>
			<div class="timebox">
				<a href="javascript:void(0);" onclick="upSeconde(<?php echo $id; ?>);">
					<div class="upbtn">
						<i class="fas fa-chevron-up"></i>
					</div>
				</a>
				<div id="timeseconde-<?php echo $id; ?>">
				00
				</div>
				<a href="javascript:void(0);" onclick="downSeconde(<?php echo $id; ?>);">
					<div class="upbtn">
						<i class="fas fa-chevron-down"></i>
					</div>
				</a>
			</div>
			<div class="valid-picker-btn">
				<a href="javascript:void(0);" class="btn blue" onclick="closePicker(<?php echo $id; ?>);">Valider</a>
			</div>
		</div>
	</div>
	<?php
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Administration PAS Script v<?php echo $version; ?></title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
</head>
<body>
	<?php
	
	include "header.php";
	include "sidebar.php";
	
	?>
	<style>
	.tabs
	{
		padding: 10px;
		background-color: #ffffff;
		box-sizing: border-box;
		border-bottom-right-radius: 5px;
		border-bottom-left-radius: 5px;
	}
	
	.tabs-element
	{
		-webkit-border-top-left-radius: 5px;
		-webkit-border-top-right-radius: 5px;
		-moz-border-radius-topleft: 5px;
		-moz-border-radius-topright: 5px;
		border-top-left-radius: 5px;
		border-top-right-radius: 5px;
		float: left;
		padding: 10px;
		background-color: #dddddd;
		margin-right: 1px;
	}
	
	.tabs-element:hover
	{
		background-color: #ffdddd;
	}
	
	.tabs-cover
	{
		overflow:auto;
	}
	
	.help
	{
		position: absolute;
		background-color: #333333;
		color: #ffffff;
		font-size: 12px;
		padding: 20px;
		border-radius: 5px;
		display: none;
		margin-top: 25px;
		margin-left: -20px;
	}
	
	a
	{
		color:#96d2ff;
	}
	
	.englobehelp
	{
		display:initial;
	}
	
	.help-video
	{
		float: right;
		margin-top: -65px;
		font-size: 25px;
		text-align: center;
	}
	
	.littletext
	{
		font-size: 12px;
		font-weight: bold;
	}
	
	.help-video-container
	{
		padding: 10px;
		background-color: #323131;
		width: 400px;
		border-radius: 5px;
		margin-bottom: 10px;
		float: right;
	}
	</style>
	<div class="container">
		<H1>Hotline</H1>
		<div class="help-video" onclick="showHelpVideo();">
			<i class="fas fa-video"></i>
			<div class="littletext">Aide video</div>
		</div>
		<div style="overflow:auto;">
			<div class="help-video-container" id="help-video" style="display:none;">
				<video width="400" height="222" controls="controls">
				  <source src="https://www.shua-creation.com/video/pas_script_hotline.mp4" type="video/mp4" />
				</video>
			</div>
		</div>
		<script>
		function showHelpVideo()
		{
			var help = $('#help-video').css('display');
			if(help == 'none')
			{
				$('#help-video').css('display','block');
			}
			else
			{
				$('#help-video').css('display','none');
			}
		}
		</script>
		<div class="info">
		Depuis cette page vous aurez la possibilité d'afficher un numéro de Hotline / Standard pour votre site internet.
		</div>
		<script>
		var heure = 0;
		var minute = 0;
		var seconde = 0;
		
		function showPicker(id)
		{
			var hh = $('#timepicker-'+id).val();
			hh = hh.split(':');
			heure = hh[0];
			minute = hh[1];
			seconde = hh[2];
			$('#timehour-'+id).html(heure);
			$('#timeminute-'+id).html(minute);
			$('#timeseconde-'+id).html(seconde);
			$('#timepickerbox-'+id).css('display','block');
		}
		
		function closePicker(id)
		{
			$('#timepickerbox-'+id).css('display','none');
		}
		
		function updateTime(id)
		{
			var h = '';
			if(heure < 10)
			{
				if(heure == '00')
				{
					if(heure == '0')
					{
						h = '0'+heure;
					}
					else
					{
						h = heure;
					}
				}
				else
				{
					h = '0'+heure;
				}
			}
			else
			{
				h = heure;
			}
			
			if(minute < 10)
			{
				if(minute == '00')
				{
					if(minute == '0')
					{
						m = '0'+minute;
					}
					else
					{
						m = minute;
					}
				}
				else
				{
					m = '0'+minute;
				}
			}
			else
			{
				m = minute;
			}
			
			if(seconde < 10)
			{
				if(seconde == '00')
				{
					if(seconde == '0')
					{
						s = '0'+seconde;
					}
					else
					{
						s = seconde;
					}
				}
				else
				{
					s = '0'+seconde;
				}
			}
			else
			{
				s = seconde;
			}
			$('#timepicker-'+id).val(h+':'+m+':'+s);
		}
		
		function upHour(id)
		{
			heure = $('#timehour-'+id).html();
			heure = Number(heure);
			heure = heure + 1;
			if(heure > 23)
			{
				heure = 0;
			}
			if(heure < 10)
			{
				$('#timehour-'+id).html('0'+heure);
			}
			else
			{
				$('#timehour-'+id).html(heure);
			}
			
			updateTime(id);
		}
		
		function upMinute(id)
		{
			minute = $('#timeminute-'+id).html();
			minute = Number(minute);
			minute = minute + 1;
			if(minute > 59)
			{
				minute = 0;
			}
			if(minute < 10)
			{
				$('#timeminute-'+id).html('0'+minute);
			}
			else
			{
				$('#timeminute-'+id).html(minute);
			}
			
			updateTime(id);
		}
		
		function upSeconde(id)
		{
			seconde = $('#timeseconde-'+id).html();
			seconde = Number(seconde);
			seconde = seconde + 1;
			if(seconde > 59)
			{
				seconde = 0;
			}
			if(seconde < 10)
			{
				$('#timeseconde-'+id).html('0'+seconde);
			}
			else
			{
				$('#timeseconde-'+id).html(seconde);
			}
			
			updateTime(id);
		}
		
		function downSeconde(id)
		{
			seconde = $('#timeseconde-'+id).html();
			seconde = Number(seconde);
			seconde = seconde - 1;
			if(seconde == -1)
			{
				seconde = 59;
			}
			if(seconde < 10)
			{
				$('#timeseconde-'+id).html('0'+seconde);
			}
			else
			{
				$('#timeseconde-'+id).html(seconde);
			}
			
			updateTime(id);
		}
		
		function downMinute(id)
		{
			minute = $('#timeminute-'+id).html();
			minute = Number(minute);
			minute = minute - 1;
			if(minute == -1)
			{
				minute = 59;
			}
			if(minute < 10)
			{
				$('#timeminute-'+id).html('0'+minute);
			}
			else
			{
				$('#timeminute-'+id).html(minute);
			}
			
			updateTime(id);
		}
		
		function downHour(id)
		{
			heure = $('#timehour-'+id).html();
			heure = Number(heure);
			heure = heure - 1;
			if(heure == -1)
			{
				heure = 23;
			}
			if(heure < 10)
			{
				$('#timehour-'+id).html('0'+heure);
			}
			else
			{
				$('#timehour-'+id).html(heure);
			}
			
			updateTime(id);
		}
		</script>
		<form method="POST">
			<?php
			if(getConfig("hotline_activate") == "yes")
			{
				?>
				<input type="checkbox" name="hotline_activate" value="yes" checked> Activer l'affichage de la Hotline<br><br>
				<?php
			}
			else
			{
				?>
				<input type="checkbox" name="hotline_activate" value="yes"> Activer l'affichage de la Hotline<br><br>
				<?php
			}
			?>
			<?php
			if(getConfig("hotline_open_message") == "yes")
			{
				?>
				<input type="checkbox" name="hotline_open_message" value="yes" checked> Activer l'affichage d'ouverture de la Hotline<br><br>
				<?php
			}
			else
			{
				?>
				<input type="checkbox" name="hotline_open_message" value="yes"> Activer l'affichage d'ouverture de la Hotline<br><br>
				<?php
			}
			?>
			<label><b>Numéro de téléphone de la Hotline :</b></label>
			<input type="text" name="phone_hotline" value="<?php echo getConfig("phone_hotline"); ?>" class="inputbox">
			<label><b>Message d'heure et jour d'ouverture :</b></label>
			<input type="text" name="message_hotline" value="<?php echo getConfig("message_hotline"); ?>" class="inputbox">
			<label><b>Ouvert les jours suivant :</b></label><br><br>
			<div style="margin-bottom:20px;">
			<?php
			if($horaire->isDayisOpen("Mon") == 'oui')
			{
				?>
				<input type="checkbox" name="lundi" value="oui" checked> Lundi
				<?php
			}
			else
			{
				?>
				<input type="checkbox" name="lundi" value="oui"> Lundi
				<?php
			}
			?> de <?php addPickerTime(1,'lundi_heure_ouverture1'); ?> à <?php addPickerTime(2,'lundi_heure_fermeture1'); ?> et de <?php addPickerTime(3,'lundi_heure_ouverture2'); ?> à <?php addPickerTime(4,'lundi_heure_fermeture2'); 
			?>
			</div>
			<div style="margin-bottom:20px;">
			<?php
			if($horaire->isDayisOpen("Tue") == 'oui')
			{
				?>
				<input type="checkbox" name="mardi" value="oui" checked> Mardi
				<?php
			}
			else
			{
				?>
				<input type="checkbox" name="mardi" value="oui"> Mardi
				<?php
			}
			?> de <?php addPickerTime(5,'mardi_heure_ouverture1'); ?> à <?php addPickerTime(6,'mardi_heure_fermeture1'); ?> et de <?php addPickerTime(7,'mardi_heure_ouverture2'); ?> à <?php addPickerTime(8,'mardi_heure_fermeture2'); 
			?>
			</div>
			<div style="margin-bottom:20px;">
			<?php
			if($horaire->isDayisOpen("Wed") == 'oui')
			{
				?>
				<input type="checkbox" name="mercredi" value="oui" checked> Mercredi
				<?php
			}
			else
			{
				?>
				<input type="checkbox" name="mercredi" value="oui"> Mercredi
				<?php
			}
			?> de <?php addPickerTime(9,'mercredi_heure_ouverture1'); ?> à <?php addPickerTime(10,'mercredi_heure_fermeture1'); ?> et de <?php addPickerTime(11,'mercredi_heure_ouverture2'); ?> à <?php addPickerTime(12,'mercredi_heure_fermeture2'); 
			?>
			</div>
			<div style="margin-bottom:20px;">
			<?php
			if($horaire->isDayisOpen("Thu") == 'oui')
			{
				?>
				<input type="checkbox" name="jeudi" value="oui" checked> Jeudi
				<?php
			}
			else
			{
				?>
				<input type="checkbox" name="jeudi" value="oui"> Jeudi
				<?php
			}
			?> de <?php addPickerTime(13,'jeudi_heure_ouverture1'); ?> à <?php addPickerTime(14,'jeudi_heure_fermeture1'); ?> et de <?php addPickerTime(15,'jeudi_heure_ouverture2'); ?> à <?php addPickerTime(16,'jeudi_heure_fermeture2'); 
			?>
			</div>
			<div style="margin-bottom:20px;">
			<?php
			if($horaire->isDayisOpen("Fri") == 'oui')
			{
				?>
				<input type="checkbox" name="vendredi" value="oui" checked> Vendredi
				<?php
			}
			else
			{
				?>
				<input type="checkbox" name="vendredi" value="oui"> Vendredi
				<?php
			}
			?> de <?php addPickerTime(17,'vendredi_heure_ouverture1'); ?> à <?php addPickerTime(18,'vendredi_heure_fermeture1'); ?> et de <?php addPickerTime(19,'vendredi_heure_ouverture2'); ?> à <?php addPickerTime(20,'vendredi_heure_fermeture2'); 
			?>
			</div>
			<div style="margin-bottom:20px;">
			<?php
			if($horaire->isDayisOpen("Sat") == 'oui')
			{
				?>
				<input type="checkbox" name="samedi" value="oui" checked> Samedi
				<?php
			}
			else
			{
				?>
				<input type="checkbox" name="samedi" value="oui"> Samedi
				<?php
			}
			?> de <?php addPickerTime(21,'samedi_heure_ouverture1'); ?> à <?php addPickerTime(22,'samedi_heure_fermeture1'); ?> et de <?php addPickerTime(23,'samedi_heure_ouverture2'); ?> à <?php addPickerTime(24,'samedi_heure_fermeture2'); 
			?>
			</div>
			<div style="margin-bottom:20px;">
			<?php
			if($horaire->isDayisOpen("Sun") == 'oui')
			{
				?>
				<input type="checkbox" name="dimanche" value="oui" checked> Dimanche
				<?php
			}
			else
			{
				?>
				<input type="checkbox" name="dimanche" value="oui"> Dimanche
				<?php
			}
			?> de <?php addPickerTime(25,'dimanche_heure_ouverture1'); ?> à <?php addPickerTime(26,'dimanche_heure_fermeture1'); ?> et de <?php addPickerTime(27,'dimanche_heure_ouverture2'); ?> à <?php addPickerTime(28,'dimanche_heure_fermeture2'); 
			?>
			</div>
			<input type="hidden" name="action" value="1">
			<input type="submit" value="Modifier" class="btn blue">
		</form>
	</div>
	<script>
	function showHelp(id)
	{
		var h = $('#help-'+id).css('display');
		if(h == 'none')
		{
			$('#help-'+id).css('display','initial');
		}
		else
		{
			$('#help-'+id).css('display','none');
		}
	}
	</script>
</body>
</html>