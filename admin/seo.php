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

function checkValidSEOTitle($id)
{
	global $pdo;
	
	$SQL = "SELECT * FROM pas_seo WHERE id = $id";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	if(strlen($req['title']) > 65)
	{
		return false;
	}
	else
	{
		return true;
	}
}

function checkValidSEODescription($id)
{
	global $pdo;
	
	$SQL = "SELECT * FROM pas_seo WHERE id = $id";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	if(strlen($req['description']) > 165)
	{
		return false;
	}
	else
	{
		return true;
	}
}

if(isset($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
	if($action == 1)
	{
		if(!$demo)
		{
			$url_rewriting = $_REQUEST['url_rewriting'];
			updateConfig("url_rewriting",$url_rewriting);
			header("Location: seo.php");
			exit;
		}
		else
		{
			?>
			<script>
			alert('Cette fonction est désactiver dans la demonstration');
			</script>
			<?php
		}
	}
	/* Generation du fichier Sitemap */
	if($action == 2)
	{
		$handle = fopen("../sitemap.xml","w");
		fwrite($handle,'<?xml version="1.0" encoding="UTF-8"?>'."\n");
		fwrite($handle,'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">'."\n");
		fwrite($handle,'<url>'."\n");
		fwrite($handle,'<loc>'.$url_script.'</loc>'."\n");
		fwrite($handle,'<lastmod>'.date('Y').'-'.date('m').'-'.date('d').'T'.date('h').':'.date('i').':'.date('s').'+00:00</lastmod>'."\n");
		fwrite($handle,'<priority>1.00</priority>'."\n");
		fwrite($handle,'</url>'."\n");
		
		$url_rewriting = getConfig("url_rewriting");
		
		/* Toute les annonces du site */
		$SQL = "SELECT * FROM pas_annonce";
		$reponse = $pdo->query($SQL);
		while($req = $reponse->fetch())
		{
			if($url_rewriting == 'yes')
			{
				$url = $url_script.'/'.$req['md5'].'/annonce-'.$req['slug'].'.html';
			}
			else
			{
				$url = $url_script.'/showannonce.php?md5='.$req['md5'];
			}
			fwrite($handle,'<url>'."\n");
			fwrite($handle,'<loc>'.$url.'</loc>'."\n");
			fwrite($handle,'<lastmod>'.date('Y').'-'.date('m').'-'.date('d').'T'.date('h').':'.date('i').':'.date('s').'+00:00</lastmod>'."\n");
			fwrite($handle,'<priority>0.80</priority>'."\n");
			fwrite($handle,'</url>'."\n");
		}
		
		fwrite($handle,'</urlset>'."\n");
		
		fclose($handle);
		header("Location: seo.php");
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
	
	.badge-success
	{
		background-color: #0bba06;
		color: #fff;
		font-size: 12px;
		padding: 4px;
		border-radius: 5px;
		font-weight: bold;
	}
	
	.badge-warning
	{
		background-color: #d89000;
		color: #fff;
		font-size: 12px;
		padding: 4px;
		border-radius: 5px;
		font-weight: bold;
	}
	</style>
	<div class="container">
		<H1>SEO</H1>
		<div class="help-video" onclick="showHelpVideo();">
			<i class="fas fa-video"></i>
			<div class="littletext">Aide video</div>
		</div>
		<div style="overflow:auto;">
			<div class="help-video-container" id="help-video" style="display:none;">
				<video width="400" height="222" controls="controls">
				  <source src="https://www.shua-creation.com/video/pas_script_seo.mp4.filepart" type="video/mp4" />
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
		Depuis cette page vous pourrez régler les informations SEO concernant les pages de votre site internet pour obtenir un meilleur référencement de vos pages.
		</div>
		<div class="tabs-cover">
			<a href="javascript:void(0);" onclick="showTabs(1)">
				<div class="tabs-element">
					Edition SEO des pages
				</div>
			</a>
			<a href="javascript:void(0);" onclick="showTabs(2)">
				<div class="tabs-element">
				Configuration SEO
				</div>
			</a>
		</div>
		<div class="tabs" id="tabs-1">
			<H1>Edition SEO des pages du site internet</H1>
			<table>
				<tr>
					<th>Nom de la page</th>
					<th>Validité SEO</th>
					<th>Option</th>
				</tr>
				<?php
				
				$SQL = "SELECT * FROM pas_seo";
				$reponse = $pdo->query($SQL);
				while($req = $reponse->fetch())
				{
					?>
					<tr>
						<td><?php echo ucfirst(str_replace("_"," ",$req['page'])); ?></td>
						<td>
						<?php
						if(checkValidSEOTitle($req['id']))
						{
							if(checkValidSEODescription($req['id']))
							{
								?>
								<span class="badge badge-secondary badge-success">Optimisée</span>
								<?php
							}
							else
							{
								?>
								<span class="badge badge-secondary badge-warning">Non Optimisée</span>
								<?php
							}
						}
						else
						{
							?>
							<span class="badge badge-secondary badge-warning">Non Optimisée</span>
							<?php
						}
						?>
						</td>
						<td>
							<a href="editseo.php?id=<?php echo $req['page']; ?>" class="btn blue">Modifier</a>
						</td>
					</tr>
					<?php
				}
				
				?>
			</table>
		</div>
		<div class="tabs" id="tabs-2" style="display:none;">
			<H1>Configuration SEO</H1>
			<div class="info">
			L'URL Rewriting permet d'avoir des URLS propres pour votre site internet et d'optimiser le referencement de votre site internet sur les moteurs de recherche tel que Google
			</div>
			<form>
				<?php
				
				$url_rewriting = getConfig("url_rewriting");
				
				if($url_rewriting == 'yes')
				{
					?>
					<input type="checkbox" name="url_rewriting" value="yes" checked> Activer l'URL REWRINTING<br>
					<?php
				}
				else
				{
					?>
					<input type="checkbox" name="url_rewriting" value="yes"> Activer l'URL REWRINTING<br>
					<?php
				}
				?>
				<input type="hidden" name="action" value="1">
				<div style="margin-top:20px;margin-bottom:20px;">
				<input type="submit" value="Modifier" class="btn blue">
				</div>
			</form>
			<H2>Sitemap</H2>
			<div class="info">
			Pour améliorer le référencement de vos page et aider les moteurs de recherche à indexer les pages de votre site internet, générer un fichier sitemap.xml régulièrement
			de votre site internet depuis cette outils.
			</div>
			<a href="seo.php?action=2" class="btn blue">Générer le fichier Sitemap.xml</a>
			<a href="<?php echo $url_script; ?>/sitemap.xml" target="newpage" class="btn blue">Visualiser le fichier Sitemap.xml</a>
			<?php
			if(!$demo)
			{
			?>
				<a href="https://www.google.com/ping?sitemap=<?php echo $url_script; ?>/sitemap.xml" target="newpage" class="btn blue">Pinger le Sitemap.xml vers Google</a>
				<a href="https://www.bing.com/webmaster/ping.aspx?siteMap=<?php echo $url_script; ?>/sitemap.xml" target="newpage" class="btn blue">Pinger le Sitemap.xml vers Bing</a>
			<?php
			}
			else
			{
				?>
				<a href="#" target="newpage" class="btn blue">Pinger le Sitemap.xml vers Google</a>
				<a href="#" target="newpage" class="btn blue">Pinger le Sitemap.xml vers Bing</a>
				<?php
			}
			?>
		</div>
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
</body>
</html>