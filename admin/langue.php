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

if(isset($_REQUEST['language']))
{
	$language = $_REQUEST['language'];
}
else
{
	$language = 'fr';
}

if(isset($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
	/* Langue principale du site internet */
	if($action == 1)
	{
		$langue_principal = $_REQUEST['langue_principal'];
		updateConfig("langue_principal",$langue_principal);
		
		if($langue_principal == 'fr')
		{
			updateConfig("currency","EUR");
		}
		if($langue_principal == 'en')
		{
			updateConfig("currency","USD");
		}
		if($langue_principal == 'it')
		{
			updateConfig("currency","EUR");
		}
		if($langue_principal == 'bg')
		{
			updateConfig("currency","BGN");
		}
		
		$langue_administration = $_REQUEST['langue_administration'];
		updateConfig("langue_administration",$langue_administration);
	}
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
	
	.block-traduction
	{
		margin-bottom: 10px;
		padding: 10px;
		background-color: #aaaaaa;
		border-radius: 5px;
		padding-top: 18px;
	}
	
	.block-traduction-item
	{
		float: left;
		margin-right: 10px;
	}
	
	.language-change
	{
		height: 35px;
		width: 400px;
		margin-top: -10px;
		padding-left: 10px;
	}
	</style>
	<div class="container">
		<H1>Langue du site internet</H1>
		<div class="info">
		Gérez les traductions du site et activez votre site internet en multilingue depuis cette interface.
		</div>
		<div class="tabs-cover">
			<a href="javascript:void(0);" onclick="showTabs(1)">
				<div class="tabs-element">
					Traduction
				</div>
			</a>
			<a href="javascript:void(0);" onclick="showTabs(2)">
				<div class="tabs-element">
				Configuration
				</div>
			</a>
		</div>
		<div class="tabs" id="tabs-1">
			<H1>Traduction</H1>
			<div class="block-traduction">
				<form method="POST">
					<div class="block-traduction-item">
						<b>Choisir les traductions à afficher : </b>
					</div>
					<div class="block-traduction-item">
						<select name="language" class="language-change">
							<?php
							
							$SQL = "SELECT * FROM pas_langue_add";
							$reponse = $pdo->query($SQL);
							while($req = $reponse->fetch())
							{
								if($language == $req['language'])
								{
								?>
								<option value="<?php echo $req['language']; ?>" selected><?php echo utf8_encode($req['language_texte']); ?></option>
								<?php	
								}
								else
								{
								?>
								<option value="<?php echo $req['language']; ?>"><?php echo utf8_encode($req['language_texte']); ?></option>
								<?php
								}
							}
							
							?>
						</select>
					</div>
				<input type="submit" value="Modifier" class="btn blue"><br>
				</form>
			</div>
			<table>
				<tr>
					<th>Identifiant</th>
					<th>Texte</th>
					<th>Action</th>
				</tr>
				<?php
				
				/* On check si la traduction pour les numeros existe */
				$SQL = "SELECT COUNT(*) FROM pas_langue WHERE language = 'fr' AND identifiant = 'erreur_nombre_numero'";
				$reponse = $pdo->query($SQL);
				$req = $reponse->fetch();
				
				if($req[0] == 0)
				{
					$SQL = "INSERT INTO pas_langue (language,identifiant,texte) VALUES ('fr','erreur_nombre_numero','Le numéro doit contenir 10 Chiffres')";
					$pdo->query($SQL);
				}
				
				$SQL = "SELECT * FROM pas_langue WHERE language = '$language' ORDER BY identifiant ASC";
				$reponse = $pdo->query($SQL);
				while($req = $reponse->fetch())
				{
					?>
					<tr>
						<td><?php echo $req['identifiant']; ?></td>
						<td><?php echo $req['texte']; ?></td>
						<td>
							<a href="edit_traduction.php?id=<?php echo $req['id']; ?>" class="btn blue">Modifier la traduction</a>
						</td>
					</tr>
					<?php
				}
				
				?>
			</table>
		</div>
		<div class="tabs" id="tabs-2" style="display:none;">
			<H1>Configuration</H1>
			<form method="POST">
				<label>Langue principale du site :</label>
				<select name="langue_principal" class="inputbox">
					<?php
					
					$languep = getConfig("langue_principal");
				
					$SQL = "SELECT * FROM pas_langue_add";
					$reponse = $pdo->query($SQL);
					while($req = $reponse->fetch())
					{
						if($languep == $req['language'])
						{
						?>
						<option value="<?php echo $req['language']; ?>" selected><?php echo utf8_encode($req['language_texte']); ?></option>
						<?php	
						}
						else
						{
						?>
						<option value="<?php echo $req['language']; ?>"><?php echo utf8_encode($req['language_texte']); ?></option>
						<?php
						}
					}
					
					?>
				</select>
				<label>Langue de l'administration :</label>
				<select name="langue_administration" class="inputbox">
					<?php
					
					$languep = getConfig("langue_administration");
				
					$SQL = "SELECT * FROM pas_langue_add";
					$reponse = $pdo->query($SQL);
					while($req = $reponse->fetch())
					{
						if($languep == $req['language'])
						{
						?>
						<option value="<?php echo $req['language']; ?>" selected><?php echo utf8_encode($req['language_texte']); ?></option>
						<?php	
						}
						else
						{
						?>
						<option value="<?php echo $req['language']; ?>"><?php echo utf8_encode($req['language_texte']); ?></option>
						<?php
						}
					}
					
					?>
				</select>
				<input type="hidden" name="action" value="1">
				<input type="submit" value="Modifier" class="btn blue">
			</form>
			<H2>Multilanguage</H2>
			<input type="checkbox" name="activate_multilanguage" value="yes"> Activer le multilanguage du site<br><br>
			<table>
				<tr>
					<th>Langue</th>
					<th>Monnaie</th>
					<th>Action</th>
				</tr>
				<?php
				
				$SQL = "SELECT COUNT(*) FROM pas_langue_multi";
				$reponse = $pdo->query($SQL);
				$req = $reponse->fetch();
				
				if($req[0] == 0)
				{
					?>
					</table>
					<div style="text-align: center;padding-top: 150px;padding-bottom: 150px;background-color: #fff;">
						<h1>Aucune langue configurer pour le Multilanguage</h1>
					</div>
					<?php
				}
				else
				{
					$SQL = "SELECT * FROM pas_langue_multi";
					$reponse = $pdo->query($SQL);
					while($req = $reponse->fetch())
					{
						?>
						<tr>
							<td><?php echo $req['langue']; ?></td>
							<td><?php echo $req['currency']; ?></td>
							<td>
							
							</td>
						</tr>
						<?php
					}
					?>
					</table>
					<?php
				}
				
				?>
				<a href="addlanguemulti.php" class="btn blue">Ajouter une langue</a>
		</div>
		<script>
		var oldtabs = 1;
		function showTabs(id)
		{
			$('#tabs-'+oldtabs).css('display','none');
			$('#tabs-'+id).css('display','block');
			oldtabs = id;
		}
		</script>
	</div>
</body>
</html>