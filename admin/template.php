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
		$template = $_REQUEST['file'];
		updateConfig("template",$template);
		header("Location: ".$url_script."/template/$template/update.php");
		exit;
	}
}

$langue = getConfig("langue_administration");
if($langue == 'fr')
{
	$titleh1 = "Templates";
	$description = "Le template de votre site internet constitue l'ensemble du design de celui-ci. Vous pourrez selectionner ici parmis un panel de template pour votre site internet, vous avez la possibilité d'en obtenir de nouveau depuis la boutique.";
	$titleh2 = "Vos template disponible";
	$btn_modifier = "Modifier";
}
if($langue == 'en')
{
	$titleh1 = "Templates";
	$description = "The template of your website constitutes the entire design of it. You can select here from a template panel for your website, you have the opportunity to get new from the shop.";
	$titleh2 = "Your available template";
	$btn_modifier = "Edit";
}
if($langue == 'it')
{
	$titleh1 = "Modelli";
	$description = "Il modello del tuo sito web costituisce l'intero design di esso. Puoi selezionare qui da un pannello di modelli per il tuo sito web, hai l'opportunità di ottenere nuovi dal negozio.";
	$titleh2 = "Il tuo modello disponibile";
	$btn_modifier = "Cambiamento";
}
if($langue == 'bg')
{
	$titleh1 = "шаблони";
	$description = "Шаблонът на уебсайта ви представлява целия дизайн на него. Можете да изберете тук от панел с шаблони за вашия сайт, имате възможност да получите нови от магазина.";
	$titleh2 = "Вашият наличен шаблон";
	$btn_modifier = "промяна";
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
	<style>
	.backgroundmodal
	{
		position: fixed;
		width: 100%;
		height: 100%;
		background-color: rgba(0,0,0,0.8);
		z-index: 1000;
		display:none;
	}
	
	.modal
	{
		width: 800px;
		background-color: #fff;
		margin-left: auto;
		margin-right: auto;
		margin-top: 50px;
		padding: 20px;
		border-radius: 5px;
		box-sizing: border-box;
	}
	</style>
	<div class="backgroundmodal" id="modal">
		<div class="modal" id="modal-form">
		Modal
		</div>
	</div>
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
	
	.item-template
	{
		float: left;
		margin-right: 10px;
		width: 300px;
		height: 300px;
		overflow: hidden;
	}
	
	.item-template img
	{
		width:100%;
		height:auto;
	}
	
	.bselected
	{
		border:2px solid #ff0000;
	}
	
	.name
	{
		position: absolute;
		margin-top: -43px;
		width: 300px;
		background-color: rgba(0,0,0,0.8);
		text-align: center;
		color: #fff;
		height: 32px;
		padding-top: 4px;
	}
	</style>
	<div class="container">
		<H1><?php echo $titleh1; ?></H1>
		<div class="info">
		<?php echo $description; ?>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<H2><?php echo $titleh2; ?></H2>
		<div style="overflow:auto;margin-bottom:20px;overflow-y: hidden;">
		<?php
		$dir = "../template/";
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
					   if(getConfig("template") == $file)
					   {
							?>
							<div class="item-template template-email-selected" id="item-<?php echo $file; ?>" onclick="selectTemplate('<?php echo $file; ?>');">
								<img src="../template/<?php echo $file; ?>/template-mini.jpg">
								<div class="name"><?php echo ucfirst($file); ?></div>
							</div>
							<?php
					   }
					   else
					   {
						   ?>
						   <div class="item-template" id="item-<?php echo $file; ?>" onclick="selectTemplate('<?php echo $file; ?>');">
								<img src="../template/<?php echo $file; ?>/template-mini.jpg">
								<div class="name"><?php echo ucfirst($file); ?></div>
						   </div>
						   <?php
					   }
					   $f = explode(".",$file);
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
		<input type="submit" value="<?php echo $btn_modifier; ?>" class="btn">
		</form>
		<script>
		var old = '<?php echo getConfig("template"); ?>';
		
		function selectTemplate(file)
		{
			$('#file').val(file);
			$('#item-'+old).removeClass('template-email-selected');
			$('#item-'+file).addClass('template-email-selected');
			
			old = file;
		}
		</script>
		<?php
		
		$data = file_get_contents("http://www.shua-creation.com/store/pas_template_website.php");
		echo $data;
		
		?>
	</div>
</body>
</html>