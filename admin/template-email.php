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
		$mail_template = $_REQUEST['file'];
		updateConfig("mail_template",$mail_template);
		header("Location: template-email.php");
		exit;
	}
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
	<style>
	.theme-preview-button
	{
		height: 10px;
		width: 15px;
		margin-top: 10px;
		margin-left: 20px;
	}
	
	.palette-block
	{
		float: left;
		margin-right: 5px;
		width: 40px;
		height: 28px;
	}
	
	.bselected
	{
		border:2px solid #ff0000;
	}
	</style>
	<div class="container">
		<H1>Templates des emails</H1>
		<div class="info">
		Vous pourrez selectionner ici parmis un panel de template pour l'expedition de vos emails à vos utilisateur.
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<H2>Vos template disponible</H2>
		<div style="overflow:auto;margin-bottom:20px;overflow-y: hidden;">
		<?php
		$dir = "../mail/";
		$x = 0;
		//  si le dossier pointe existe
		if(is_dir($dir)) 
		{
			// si il contient quelque chose
			if ($dh = opendir($dir)) 
			{
			   // boucler tant que quelque chose est trouve
			   while (($file = readdir($dh)) !== false) 
			   {
				   if($file == '.' || $file == '..')
				   {
					   
				   }
				   else
				   {
					   $f = explode(".",$file);
					   if($f[1] == 'tpl')
					   {
						   if($f[0] == getConfig("mail_template"))
						   {
							   ?>
							   <a href="javascript:void(0);" onclick="selectTemplate('<?php echo $f[0]; ?>');">
									<div id="item-<?php echo $f[0]; ?>" class="template-email-item template-email-selected">
										<div class="title-template"><?php echo $f[0]; ?></div>
										<img src="../mail/<?php echo $f[0].".jpg"; ?>">
									</div>
							   </a>
							   <?php
						   }
						   else
						   {
							   ?>
							   <a href="javascript:void(0);" onclick="selectTemplate('<?php echo $f[0]; ?>');">
									<div id="item-<?php echo $f[0]; ?>" class="template-email-item">
										<div class="title-template"><?php echo $f[0]; ?></div>
										<img src="../mail/<?php echo $f[0].".jpg"; ?>">
									</div>
							   </a>
							   <?php
						   }
					   }
				   }
				   
				   $x++;
			   }
			   // on ferme la connection
			   closedir($dh);
		   }
		}
		?>
		</div>
		<form method="POST">
		<input type="hidden" name="file" id="file" value="">
		<input type="hidden" name="action" value="1">
		<input type="submit" value="Modifier" class="btn">
		</form>
		<H2>Template email disponible dans la boutique</H2>
		<div class="info">
		Des template d'email supplémentaire sont disponible dans la boutique, le script est fourni avec quelque template, vous souhaitez changer votre template email en 1 clic choissisez parmis
		nos template en vente dans notre boutique au prix de 5.00 €.
		</div>
		<?php
		
		$data = file_get_contents("http://www.shua-creation.com/store/pas_template.php");
		echo $data;
		
		?>
		<script>
		var old = '<?php echo getConfig("mail_template"); ?>';
		
		function selectTemplate(file)
		{
			$('#file').val(file);
			$('#item-'+old).removeClass('template-email-selected');
			$('#item-'+file).addClass('template-email-selected');
			
			old = file;
		}
		</script>
	</div>
</body>
</html>