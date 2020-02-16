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
		$file = $_REQUEST['file'];
		$template = getConfig("template");
		$data = file_get_contents("$url_script/template/$template/tpl/$file");
		$data = htmlentities($data);
	}
	
	if($action == 2)
	{
		$file = $_REQUEST['file'];
		$code = $_REQUEST['code'];
		
		$template = getConfig("template");
		
		if($file != '')
		{
			if(!$demo)
			{
				file_put_contents("../template/$template/tpl/$file",$code);
				header("Location: edittemplate.php?action=1&file=$file");
				exit;
			}
			else
			{
				header("Location: edittemplate.php?action=1&file=$file");
			}
		}
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
	.editortemplate
	{
		overflow:auto;
	}
	
	.editorfile
	{
		float:left;
		width: 30%;
		padding: 20px;
		background-color: #ffffff;
		border-radius: 5px;
		box-shadow: 0px 0px 4px #aaaaaa;
		box-sizing: border-box;
	}
	
	.editorcode
	{
		width: 65%;
		background-color: #303030;
		height: 650px;
		float: left;
		margin-left: 20px;
		-webkit-border-bottom-right-radius: 5px;
		-webkit-border-bottom-left-radius: 5px;
		-moz-border-radius-bottomright: 5px;
		-moz-border-radius-bottomleft: 5px;
		border-bottom-right-radius: 5px;
		border-bottom-left-radius: 5px;
		padding: 20px;
		box-sizing: border-box;
		color: #117801;
		margin-bottom: 10px;
	}
	
	.textarea-code
	{
		width: 100%;
		height: 100%;
		border: none;
		background-color: #303030;
		color: #2aef0c;
	}
	
	.link
	{
		text-decoration:none;
		color:#000000;
	}
	
	.file-editor:hover
	{
		background-color:#96d2ff;
		padding:5px;
		box-sizing: border-box;
	}
	
	.selected
	{
		background-color:#06ba61;
		padding:5px;
		box-sizing: border-box;
		color:#ffffff;
	}
	
	.editorcode-title
	{
		float: left;
		margin-left: 20px;
		width: 65%;
		padding: 5px;
		background-color: #06ba61;
		box-sizing: border-box;
		color: #ffffff;
		font-weight: bold;
		padding-left: 15px;
		-webkit-border-top-left-radius: 5px;
		-webkit-border-top-right-radius: 5px;
		-moz-border-radius-topleft: 5px;
		-moz-border-radius-topright: 5px;
		border-top-left-radius: 5px;
		border-top-right-radius: 5px;
	}
	</style>
	<div class="container">
		<H1>Edition du template</H1>
		<div class="info">
		Vous pouvez éditer directement votre template depuis cette interface. Attention : faites une sauvegarde de votre template avant toutes modifications.
		</div>
		<div class="editortemplate">
			<div class="editorfile">
			<?php
			
			$template = getConfig("template");
			
			$pointeur = opendir("../template/$template/tpl/");
 
			//pour chaque fichier et dossier
			while ($fichier = readdir($pointeur))
			{
				//on ne traite pas les . et ..
				if(($fichier != '.') && ($fichier != '..') && ($fichier != '.DS_Store'))
				{
					$extension = explode(".",$fichier);
					$extension = $extension[1];
					if($extension == 'tpl')
					{
						if($file == $fichier)
						{
							?>
							<a href="edittemplate.php?action=1&file=<?php echo $fichier; ?>" class="link">
								<div class="file-editor selected">
									<img src="images/fichier.png"> <?php echo $fichier; ?>
								</div>
							</a>
							<?php
						}
						else
						{
							?>
							<a href="edittemplate.php?action=1&file=<?php echo $fichier; ?>" class="link">
								<div class="file-editor">
									<img src="images/fichier.png"> <?php echo $fichier; ?>
								</div>
							</a>
							<?php
						}
					}
				}
			}
		 
			//fermeture du pointeur
			closedir($pointeur);
			
			?>
			</div>
			<div class="editorcode-title">
			Fichier : "<?php echo $file; ?>"
			</div>
			<form method="POST">
			<div class="editorcode">
				<textarea class="textarea-code" name="code"><?php echo $data; ?></textarea>
			</div>
			<input type="hidden" name="action" value="2">
			<input type="hidden" name="file" value="<?php echo $file; ?>">
			<div style="width:95%;text-align:right;margin-left:20px;margin-top:20px;">
			<input type="submit" value="Sauvegarder" class="btn blue">
			</div>
			</form>
		</div>
	</div>
</body>
</html>