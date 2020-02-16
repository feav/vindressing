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
	$action = $_REQUEST['action'];
	if($action == 1)
	{
		$isMaintenance = $_REQUEST['isMaintenance'];
		updateConfig("isMaintenance",$isMaintenance);
		
		$message_maintenance = $_REQUEST['message_maintenance'];
		$message_maintenance = htmlentities($message_maintenance);
		updateConfig("message_maintenance",$message_maintenance);
		
		$maintenance_ip = $_REQUEST['maintenance_ip'];
		updateConfig("maintenance_ip",$maintenance_ip);
	}
}

$langue = getConfig("langue_administration");
if($langue == 'fr')
{
	$titleh1 = "Maintenance";
	$description = "Gérer la maintenance de votre site internet depuis cette interface.";
	$activer_la_maintenance = "Activer la maintenance du site";
	$message_maintenance = "Message de maintenance";
	$label_ip = "Adresse IP autorisée <i>(1 seul ip est autorisé)</i>";
	$label_votre_ip = "Votre adresse ip est";
	$btn_edit = "Modifier";
}
if($langue == 'en')
{
	$titleh1 = "Maintenance";
	$description = "Manage the maintenance of your website from this interface.";
	$activer_la_maintenance = "Enable site maintenance";
	$message_maintenance = "Maintenance message";
	$label_ip = "IP address allowed <i> (only 1 ip is allowed) </ i>";
	$label_votre_ip = "Your ip address is";
	$btn_edit = "Edit";
}
if($langue == 'it')
{
	$titleh1 = "Manutenzione";
	$description = "Gestisci la manutenzione del tuo sito web da questa interfaccia.";
	$activer_la_maintenance = "Abilita la manutenzione del sito";
	$message_maintenance = "Messaggio di manutenzione";
	$label_ip = "Indirizzo IP consentito <i> (è ammesso solo 1 ip) </ i>";
	$label_votre_ip = "Il tuo indirizzo IP è";
	$btn_edit = "Cambiamento";
}
if($langue == 'bg')
{
	$titleh1 = "поддръжка";
	$description = "Управлявайте поддръжката на уебсайта си от този интерфейс.";
	$activer_la_maintenance = "Активиране на поддръжката на сайта";
	$message_maintenance = "Съобщение за поддръжка";
	$label_ip = "IP адресът е позволен <i> (разрешено е само 1 ip) </ i>";
	$label_votre_ip = "Вашият IP адрес е";
	$btn_edit = "промяна";
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
<body>
	<?php
	
	include "header.php";
	include "sidebar.php";
	
	?>
	<div class="container">
		<H1><?php echo $titleh1; ?></H1>
		<div class="info">
		<?php echo $description; ?>
		</div>
		<form method="POST">
			<?php
			if(getConfig("isMaintenance") == 'yes')
			{
				?>
				<input type="checkbox" name="isMaintenance" value="yes" checked> <?php echo $activer_la_maintenance; ?><br><br>
				<?php
			}
			else
			{
				?>
				<input type="checkbox" name="isMaintenance" value="yes"> <?php echo $activer_la_maintenance; ?><br><br>
				<?php
			}
			?>
			<label><?php echo $message_maintenance; ?></label>
			<textarea name="message_maintenance" class="textareabox"><?php echo getConfig("message_maintenance"); ?></textarea>
			<!--<input type="text" name="message_maintenance" class="inputbox" value="<?php echo getConfig("message_maintenance"); ?>">-->
			<label><?php echo $label_ip; ?></label>
			<label><br><b><?php echo $label_votre_ip; ?> <?php echo $_SERVER['REMOTE_ADDR']; ?></b></label>
			<input type="text" name="maintenance_ip" class="inputbox" value="<?php echo getConfig("maintenance_ip"); ?>">
			<input type="hidden" name="action" value="1">
			<input type="submit" value="Modifier" class="btn blue">
		</form>
	</div>
</body>
</html>