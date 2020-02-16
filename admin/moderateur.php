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
		if(!$demo)
		{
			$id = $_REQUEST['id'];
			$SQL = "DELETE FROM pas_admin_user WHERE id = $id";
			$pdo->query($SQL);
			header("Location: moderateur.php");
			exit;
		}
		else
		{
			?>
			<script>
			alert('Cette fonction est désactiver dans la demonstration');
			</script>
			<?php
			header("Location: moderateur.php");
			exit;
		}
	}
	
	/* Régle des moderateur */
	if($action == 2)
	{
		$option_moderateur = $_REQUEST['option_moderateur'];
		$option_moderateur = implode(",",$option_moderateur);
		
		updateConfig("option_moderateur",$option_moderateur);
		
		header("Location: moderateur.php");
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
	
	$SQL = "SELECT COUNT(*) FROM pas_admin_user WHERE type_compte = 'moderateur'";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	$count = $req[0];
	
	?>
	<style>
	.infomap
	{
		position: absolute;
		margin-top: -30px;
		width: 300px;
		background-color: rgba(0,0,0,0.8);
		color: #ffffff;
		font-size: 12px;
		height: 30px;
		box-sizing: border-box;
		padding: 6px;
	}
	
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
	</style>
	<div class="container">
		<H1><div class="round-count"><?php echo $count; ?></div> Gestion des Modérateur</H1>
		<div class="info">
		Gérer les compte des Modérateur depuis cette page, ajoutez de nouveaux modérateur qui pourront avoir le droit d'utiliser ou non l'administration et certain aspect de celle-ci.
		</div>
		<div class="tabs-cover">
			<a href="javascript:void(0);" onclick="showTabs(1)">
				<div class="tabs-element">
					Gestion des modérateur
				</div>
			</a>
			<a href="javascript:void(0);" onclick="showTabs(2)">
				<div class="tabs-element">
					Accès des modérateur
				</div>
			</a>
		</div>
		<div class="tabs" id="tabs-1">
			<div style="margin-top:20px;margin-bottom:20px;">
			<a href="addmoderateur.php" class="btn blue">Ajouter un compte modérateur</a>
			</div>
			<table>
				<tr>
					<th>Compte</th>
					<th>Action</th>
				</tr>
				<?php
				
				$SQL = "SELECT * FROM pas_admin_user WHERE type_compte = 'moderateur'";
				$reponse = $pdo->query($SQL);
				while($req = $reponse->fetch())
				{
				?>
				<tr>
					<td><?php echo $req['username']; ?></td>
					<td>
						<a href="moderateur.php?action=1&id=<?php echo $req['id']; ?>" class="btn red">Supprimer</a>
						<a href="editadministrateur.php?id=<?php echo $req['id']; ?>" class="btn blue">Modifier le compte</a>
					</td>
				</tr>
				<?php
				}
				
				?>
			</table>
		</div>
		<div class="tabs" id="tabs-2" style="display:none;">
			<H2>Accès des modérateur</H2>
			<div class="info">
			Cocher les sections disponible pour vos modérateur depuis leur compte.
			</div>
			<form method="POST">
				<?php
				if(getAutorisationModerateur("configuration"))
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="configuration" checked> Configuration<br>
					<?php
				}
				else
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="configuration"> Configuration<br>
					<?php
				}
				if(getAutorisationModerateur("plugin"))
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="plugin" checked> Plugins<br>
					<?php
				}
				else
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="plugin"> Plugins<br>
					<?php
				}
				if(getAutorisationModerateur("design"))
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="design" checked> Design & Apparence<br>
					<?php
				}
				else
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="design"> Design & Apparence<br>
					<?php
				}
				if(getAutorisationModerateur("firewall"))
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="firewall" checked> Firewall<br>
					<?php
				}
				else
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="firewall"> Firewall<br>
					<?php
				}
				if(getAutorisationModerateur("service"))
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="service" checked> Service<br>
					<?php
				}
				else
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="service"> Service<br>
					<?php
				}
				if(getAutorisationModerateur("maintenance"))
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="maintenance" checked> Maintenance<br>
					<?php
				}
				else
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="maintenance"> Maintenance<br>
					<?php
				}
				if(getAutorisationModerateur("annonces"))
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="annonces" checked> Annonces<br>
					<?php
				}
				else
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="annonces"> Annonces<br>
					<?php
				}
				if(getAutorisationModerateur("gestion_annonces"))
				{
					?>
					&nbsp;&nbsp;<input type="checkbox" name="option_moderateur[]" value="gestion_annonces" checked> Gestion des annonces<br>
					<?php
				}
				else
				{
					?>
					&nbsp;&nbsp;<input type="checkbox" name="option_moderateur[]" value="gestion_annonces"> Gestion des annonces<br>
					<?php
				}
				if(getAutorisationModerateur("ajouter_annonces"))
				{
					?>
					&nbsp;&nbsp;<input type="checkbox" name="option_moderateur[]" value="ajouter_annonces" checked> Ajouter une annonces<br>
					<?php
				}
				else
				{
					?>
					&nbsp;&nbsp;<input type="checkbox" name="option_moderateur[]" value="ajouter_annonces"> Ajouter une annonces<br>
					<?php
				}
				if(getAutorisationModerateur("parametre_annonces"))
				{
					?>
					&nbsp;&nbsp;<input type="checkbox" name="option_moderateur[]" value="parametre_annonces" checked> Paramètres des annonces<br>
					<?php
				}
				else
				{
					?>
					&nbsp;&nbsp;<input type="checkbox" name="option_moderateur[]" value="parametre_annonces"> Paramètres des annonces<br>
					<?php
				}
				if(getAutorisationModerateur("signalement"))
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="signalement" checked> Signalement<br>
					<?php
				}
				else
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="signalement"> Signalement<br>
					<?php
				}
				if(getAutorisationModerateur("region"))
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="region" checked> Région / Département / Ville<br>
					<?php
				}
				else
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="region"> Région / Département / Ville<br>
					<?php
				}
				if(getAutorisationModerateur("utilisateur"))
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="utilisateur" checked> Utilisateurs<br>
					<?php
				}
				else
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="utilisateur"> Utilisateurs<br>
					<?php
				}
				if(getAutorisationModerateur("gestion_utilisateur"))
				{
					?>
					&nbsp;&nbsp;<input type="checkbox" name="option_moderateur[]" value="gestion_utilisateur" checked> Gestion des utilisateurs<br>
					<?php
				}
				else
				{
					?>
					&nbsp;&nbsp;<input type="checkbox" name="option_moderateur[]" value="gestion_utilisateur"> Gestion des utilisateurs<br>
					<?php
				}
				if(getAutorisationModerateur("gestion_administrateur"))
				{
					?>
					&nbsp;&nbsp;<input type="checkbox" name="option_moderateur[]" value="gestion_administrateur" checked> Gestion des administrateurs<br>
					<?php
				}
				else
				{
					?>
					&nbsp;&nbsp;<input type="checkbox" name="option_moderateur[]" value="gestion_administrateur"> Gestion des administrateurs<br>
					<?php
				}
				if(getAutorisationModerateur("gestion_moderateur"))
				{
					?>
					&nbsp;&nbsp;<input type="checkbox" name="option_moderateur[]" value="gestion_moderateur" checked> Gestion des modérateurs<br>
					<?php
				}
				else
				{
					?>
					&nbsp;&nbsp;<input type="checkbox" name="option_moderateur[]" value="gestion_moderateur"> Gestion des modérateurs<br>
					<?php
				}
				if(getAutorisationModerateur("language"))
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="language" checked> Languages<br>
					<?php
				}
				else
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="language"> Languages<br>
					<?php
				}
				if(getAutorisationModerateur("hotline"))
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="hotline" checked> Hotline<br>
					<?php
				}
				else
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="hotline"> Hotline<br>
					<?php
				}
				if(getAutorisationModerateur("tchat"))
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="tchat" checked> Module Tchat<br>
					<?php
				}
				else
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="tchat"> Module Tchat<br>
					<?php
				}
				if(getAutorisationModerateur("paiement"))
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="paiement" checked> Paiement<br>
					<?php
				}
				else
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="paiement"> Paiement<br>
					<?php
				}
				if(getAutorisationModerateur("categorie"))
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="categorie" checked> Catégories<br>
					<?php
				}
				else
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="categorie"> Catégories<br>
					<?php
				}
				if(getAutorisationModerateur("seo"))
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="seo" checked> SEO<br>
					<?php
				}
				else
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="seo"> SEO<br>
					<?php
				}
				if(getAutorisationModerateur("pages"))
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="pages" checked> Pages<br>
					<?php
				}
				else
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="pages"> Pages<br>
					<?php
				}
				if(getAutorisationModerateur("social"))
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="social" checked> Réseaux Sociaux<br>
					<?php
				}
				else
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="social"> Réseaux Sociaux<br>
					<?php
				}
				if(getAutorisationModerateur("cookie"))
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="cookie" checked> Cookies<br>
					<?php
				}
				else
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="cookie"> Cookies<br>
					<?php
				}
				if(getAutorisationModerateur("email"))
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="email" checked> Email<br>
					<?php
				}
				else
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="email"> Email<br>
					<?php
				}
				if(getAutorisationModerateur("statistique"))
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="statistique" checked> Statistique de visite<br>
					<?php
				}
				else
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="statistique"> Statistique de visite<br>
					<?php
				}
				if(getAutorisationModerateur("publicite"))
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="publicite" checked> Publicité<br>
					<?php
				}
				else
				{
					?>
					<input type="checkbox" name="option_moderateur[]" value="publicite"> Publicité<br>
					<?php
				}
				?>
				<input type="hidden" name="action" value="2">
				<div style="margin-top:20px;margin-bottom:20px;">
				<input type="submit" value="Modifier" class="btn blue">
				</div>
			</form>
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