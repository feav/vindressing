<?php
include "../main.php";
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
	/* Supprimer un plugin */
	if($action == 1)
	{
		$id = AntiInjectionSQL($_REQUEST['id']);
		$SQL = "DELETE FROM plugin WHERE id = $id";
		$pdo->query($SQL);
		
		header("Location: plugin.php");
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
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
</head>
<body>
	<?php
	
	include "header.php";
	include "sidebar.php";
	
	?>
	<style>
	.item-plugin
	{
		width: 100%;
		height: 60px;
		overflow-y: hidden;
		border: 1px solid #333;
		margin-bottom: 7px;
		background-color: #fff;
	}
	
	.item-plugin-image
	{
		float: left;
		margin-right: 10px;
		width: 60px;
		height: 60px;
		margin-left: 7px;
	}
	
	.item-plugin-name
	{
		float: left;
		padding-top: 19px;
		font-size: 12px;
		font-weight: bold;
	}
	
	.item-plugin-image img
	{
		width:100%;
		height:auto;
	}
	
	.item-plugin-option
	{
		float: right;
		padding-top: 18px;
		padding-right: 8px;
	}
	
	.item-plugin-info
	{
		font-size: 12px;
		padding-top: 19px;
		float: left;
		margin-left: 10px;
	}
	</style>
	<div class="container">
		<H1><img src="<?php echo $url_script; ?>/admin/images/plugin-gestion-big-icon.png"> <?php echo $title_gestion_plugins; ?></H1>
		<div class="info">
		<?php echo $title_plugins_description; ?>
		</div>
		<H3><?php echo $title_plugins_installer; ?></H3>
		<?php
		
		/* On check le repertoire des plugins pour trouver les plugins non installer */
		$dir = "plugin/";
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
					   if(is_dir($dir.$file))
					   {
						   if(file_exists($dir.$file.'/install.php'))
						   {
							   $SQL = "SELECT COUNT(*) FROM plugin WHERE directory = '$file'";
							   $reponse = $pdo->query($SQL);
							   $req = $reponse->fetch();
							   
							   if($req[0] == 0)
							   {
							   ?>
							   <div class="item-plugin">
									<div class="item-plugin-image"><img src="<?php echo $url_script; ?>/admin/plugin/<?php echo $file; ?>/bigicon.png"></div>
									<div class="item-plugin-name"><?php echo $file; ?></div>
									<div class="item-plugin-option"><a href="<?php echo $url_script; ?>/admin/plugin/<?php echo $file; ?>/install.php" class="btn blue"> Installer</a></div>
								</div>
							   <?php
							   }
						   }
					   }
					   $f = explode(".",$file);
				   }
				   
				   $x++;
			   }
			   // on ferme la connection
			   closedir($dh);
		   }
		}
		
		$SQL = "SELECT COUNT(*) FROM plugin";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		if($req[0] == 0)
		{
			?>
			<div class="unknow-result">
				<?php echo $title_plugins_installer_aucun; ?>
			</div>
			<?php
		}
		else
		{		
			$SQL = "SELECT * FROM plugin";
			$reponse = $pdo->query($SQL);
			while($req = $reponse->fetch())
			{
				$nom = $req['nom'];
				$directory = $req['directory'];
				?>
				<div class="item-plugin">
					<div class="item-plugin-image"><img src="<?php echo $url_script; ?>/admin/plugin/<?php echo $directory; ?>/bigicon.png"></div>
					<div class="item-plugin-name"><?php echo $nom; ?></div>
					<div class="item-plugin-option"><a href="plugin.php?action=1&id=<?php echo $req['id']; ?>" class="btn red"><i class="fas fa-trash-alt"></i> <?php echo $btn_delete; ?></a></div>
				</div>
				<?php
			}
		}
		
		?>
		<H3><?php echo $title_plugins_boutique; ?></H3>
		<?php
		
		$data = file_get_contents("http://www.shua-creation.com/store/pas_plugin.php");
		echo $data;
		
		?>
	</div>
</body>
</html>