<?php

/* Class Template Loader Shua-Creation 2018 */

class TemplateLoader
{
	var $data;
	var $initialdata;
	
	function __construct()
	{
		$this->data = NULL;
	}
	
	/* Fonction globale de renvoie de config */
	function getConfig($identifiant)
	{
		global $pdo;
		
		$SQL = "SELECT * FROM pas_configuration WHERE identifiant = '$identifiant'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		return $req['code'];
	}
	
	/* Renvoie le titre de la page concerner SEO */
	function getTitleSEO($identifiant)
	{
		global $pdo;
		
		$SQL = "SELECT * FROM pas_seo WHERE page = '$identifiant'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		return $req['title'];
	}

	/* Renvoie la description de la page concerner SEO */
	function getDescriptionSEO($identifiant)
	{
		global $pdo;
		
		$SQL = "SELECT * FROM pas_seo WHERE page = '$identifiant'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		return $req['description'];
	}
	
	function getTitleSocialSEO($identifiant)
	{
		global $pdo;
		
		$SQL = "SELECT * FROM pas_seo WHERE page = '$identifiant'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		return $req['title_social'];
	}
	
	function getDescriptionSocialSEO($identifiant)
	{
		global $pdo;
		
		$SQL = "SELECT * FROM pas_seo WHERE page = '$identifiant'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		return $req['description_social'];
	}
	
	function getImageSocialSEO($identifiant)
	{
		global $pdo;
		
		$SQL = "SELECT * FROM pas_seo WHERE page = '$identifiant'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		return $req['image_social'];
	}
	
	/* Affichage du Favicon dans la balise Head */
	function showFavicon()
	{
		global $url_script;
		$data = NULL;
		$data .= '<link rel="apple-touch-icon" sizes="57x57" href="'.$url_script.'/favicon/apple-icon-57x57.png">'."\n";
		$data .= '<link rel="apple-touch-icon" sizes="60x60" href="'.$url_script.'/favicon/apple-icon-60x60.png">'."\n";
		$data .= '<link rel="apple-touch-icon" sizes="72x72" href="'.$url_script.'/favicon/apple-icon-72x72.png">'."\n";
		$data .= '<link rel="apple-touch-icon" sizes="76x76" href="'.$url_script.'/favicon/apple-icon-76x76.png">'."\n";
		$data .= '<link rel="apple-touch-icon" sizes="114x114" href="'.$url_script.'/favicon/apple-icon-114x114.png">'."\n";
		$data .= '<link rel="apple-touch-icon" sizes="120x120" href="'.$url_script.'/favicon/apple-icon-120x120.png">'."\n";
		$data .= '<link rel="apple-touch-icon" sizes="144x144" href="'.$url_script.'/favicon/apple-icon-144x144.png">'."\n";
		$data .= '<link rel="apple-touch-icon" sizes="152x152" href="'.$url_script.'/favicon/apple-icon-152x152.png">'."\n";
		$data .= '<link rel="apple-touch-icon" sizes="180x180" href="'.$url_script.'/favicon/apple-icon-180x180.png">'."\n";
		$data .= '<link rel="icon" type="image/png" sizes="192x192"  href="'.$url_script.'/favicon/android-icon-192x192.png">'."\n";
		$data .= '<link rel="icon" type="image/png" sizes="32x32" href="'.$url_script.'/favicon/favicon-32x32.png">'."\n";
		$data .= '<link rel="icon" type="image/png" sizes="96x96" href="'.$url_script.'/favicon/favicon-96x96.png">'."\n";
		$data .= '<link rel="icon" type="image/png" sizes="16x16" href="'.$url_script.'/favicon/favicon-16x16.png">'."\n";
		
		return $data;
	}
	
	function showHead($seo)
	{
		global $url_script;
		
		$titleSEO = $this->getTitleSEO($seo);
		$descriptionSEO = $this->getDescriptionSEO($seo);
		$titleSocialSEO = $this->getTitleSocialSEO($seo);
		$descriptionSocialSEO = $this->getDescriptionSocialSEO($seo);
		$imageSocialSEO = $this->getImageSocialSEO($seo);
		
		$template = $this->getConfig("template");
				
		echo '<!DOCTYPE html>'."\n";
		echo '<html>'."\n";
		echo '<head>'."\n";
		echo '<meta charset="UTF-8">'."\n";
		echo '<title>'.$titleSEO.'</title>'."\n";
		echo '<meta name="Description" content="'.$descriptionSEO.'">'."\n";
		echo '<meta name="publisher" content="PAS Script">'."\n";
		echo '<meta name="copyright" content="www.shua-creation.com">'."\n";
		echo '<meta name="viewport" content="width=device-width, initial-scale=1.0" />'."\n";
		echo '<link rel="stylesheet" type="text/css" href="'.$url_script.'/template/'.$template.'/css/main.css">'."\n";
		echo '<link rel="stylesheet" type="text/css" href="'.$url_script.'/template/'.$template.'/css/responsive.css">'."\n";
		echo '<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">'."\n";
		echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>'."\n";
		echo '<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">'."\n";
		echo '<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.css" />'."\n";
		
		/* Social */
		echo '<meta property="og:url" content="'.$url_script.$_SERVER['REQUEST_URI'].'" />'."\n";
		echo '<meta property="og:type" content="article" />'."\n";
		echo '<meta property="og:title" content="'.$titleSocialSEO.'" />'."\n";
		echo '<meta property="og:description" content="'.$descriptionSocialSEO.'" />'."\n";
		echo '<meta property="og:image" content="'.$url_script.'/images/'.$imageSocialSEO.'" />'."\n";
		
		/* Favicon */
		echo $this->showFavicon();
		/* Google Webmaster Tool */
		$google_webmaster_tool = $this->getConfig("google_webmaster_tool");
		if($google_webmaster_tool != '')
		{
			echo '<meta name="google-site-verification" content="'.$google_webmaster_tool.'" />'."\n";
		}
		/* Bing Webmaster Tool */
		$bing_verification = $this->getConfig("bing_verification");
		if($bing_verification != '')
		{
			echo '<meta name="msvalidate.01" content="'.$bing_verification.'" />'."\n";
		}
		/* Pinterest verification */
		$pinterest_verification = $this->getConfig("pinterest_verification");
		if($pinterest_verification != '')
		{
			echo '<meta name="p:domain_verify" content="'.$pinterest_verification.'"/>';
		}
		echo '<script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.js"></script>'."\n";
		
		/* Cookie */
		echo '<script>'."\n";
		
		$palette_color_cookie = $this->getConfig("palette_color_cookie");
		if($palette_color_cookie == 1)
		{
			$background = "#000000";
			$button = "#f1d600";
		}
		else if($palette_color_cookie == 2)
		{
			$background = "#eaf7f7";
			$button = "#56cbdb";
		}
		else if($palette_color_cookie == 3)
		{
			$background = "#252e39";
			$button = "#14a7d0";
		}
		else if($palette_color_cookie == 4)
		{
			$background = "#000";
			$button = "#0f0";
		}
		else if($palette_color_cookie == 5)
		{
			$background = "#3937a3";
			$button = "#e62576";
		}
		else if($palette_color_cookie == 6)
		{
			$background = "#64386b";
			$button = "#f8a8ff";
		}
		else if($palette_color_cookie == 7)
		{
			$background = "#237afc";
			$button = "#fff";
		}
		else if($palette_color_cookie == 8)
		{
			$background = "#aa0000";
			$button = "#ff0000";
		}
		else if($palette_color_cookie == 9)
		{
			$background = "#383b75";
			$button = "#f1d600";
		}
		else if($palette_color_cookie == 10)
		{
			$background = "#1d8a8a";
			$button = "#62ffaa";
		}
		else
		{
			$background = "#000000";
			$button = "#f1d600";
		}
		
		$cookie_activer = $this->getConfig("cookie_activer");
		if($cookie_activer == 'yes')
		{
			echo 'window.addEventListener("load", function(){'."\n";
			echo 'window.cookieconsent.initialise({'."\n";
			echo '"palette": {'."\n";
			echo '"popup": {'."\n";
			echo '"background": "'.$background.'"'."\n";
			echo '},'."\n";
			echo '"button": {'."\n";
			echo '"background": "'.$button.'"'."\n";
			echo '}'."\n";
			echo '},'."\n";
			echo '"theme": "'.$this->getConfig("layout_cookie").'",'."\n";
			echo '"position": "'.$this->getConfig("position_cookie").'",'."\n";
			echo '"type": "opt-in",'."\n";
			echo '"content": {'."\n";
			echo '"message": "'.$this->getConfig("message_cookie").'",'."\n";
			echo '"allow": "'.$this->getConfig("allow_button_cookie").'",'."\n";
			echo '"dismiss": "'.$this->getConfig("dissmiss_cookie").'",'."\n";
			echo '"link": "'.$this->getConfig("policy_link_text_cookie").'",'."\n";
			echo '"href": "'.$this->getConfig("policy_link_cookie").'"'."\n";
			echo '}'."\n";
			echo '})});'."\n";
		}
		
		echo '</script>'."\n";
		echo '</head>'."\n";
		
		/* On ajoute le style pour la carte */
		?>
		<style>
		#francemap
		{
			margin-top: <?php echo getConfig("pixel_top_1024"); ?>px !important;
		}
		@media only screen and (max-width: 960px) 
		{
			#francemap
			{
				margin-top: <?php echo getConfig("pixel_top_960"); ?>px !important;
			}
		}
		@media only screen and (max-width: 640px) 
		{
			#francemap
			{
				margin-top: <?php echo getConfig("pixel_top_640"); ?>px !important;
			}
		}
		@media only screen and (max-width: 320px) 
		{
			#francemap
			{
				margin-top: <?php echo getConfig("pixel_top_320"); ?>px !important;
			}
		}
		</style>
		<?php
	}
	
	/* Fonction de chargement des fichiers .TPL */
	function loadTemplate($filename)
	{
		global $url_script;
		
		$template = $this->getConfig("template");
		if($this->getConfig("langue_principal") != 'fr')
		{
			$filename = explode(".",$filename);
			$filename = $filename[0]."_".$this->getConfig("langue_principal").".tpl";
		}
		
		$filename = "template/$template/$filename";
		
		$this->data = NULL;
		$size = filesize($filename);
		
		$handle = fopen($filename,"r");
		$data = fread($handle,$size);
		fclose($handle);
		
		$this->data = $data;
		
		/* Variable de base */
		$this->data = str_replace("{url_script}",$url_script,$this->data);
		$this->data = str_replace("{template}",$template,$this->data);
		
		$this->initialdata = $data;
	}
	
	/* Ouverture du Body */
	function openBody()
	{
		echo '<body>'."\n";
		/* Ajout du code de suivi Google Analytics si non vide */
		$google_analytics = $this->getConfig("google_analytics");
		if($google_analytics != '')
		{
			echo '<!-- Global site tag (gtag.js) - Google Analytics -->'."\n";
			echo '<script async src="https://www.googletagmanager.com/gtag/js?id='.$google_analytics.'"></script>'."\n";
			echo '<script>'."\n";
			echo 'window.dataLayer = window.dataLayer || [];'."\n";
			echo 'function gtag(){dataLayer.push(arguments);}'."\n";
			echo "gtag('js', new Date());"."\n";
			echo "gtag('config', '".$google_analytics."');"."\n";
			echo '</script>'."\n";
		}
	}
	
	function closeBody()
	{
		echo '</body>'."\n";
	}
	
	function closeHTML()
	{
		echo '</html>'."\n";
	}
	
	/* Rechargement sans accés disque (optimisation) */
	function reload()
	{
		$this->data = $this->initialdata;
	}
	
	/* Assignation de code à une variable {variable} */
	function assign($variable,$data)
	{
		$this->data = str_replace($variable,$data,$this->data);
	}
	
	/* Renvoie le template sous forme de données */
	function getData()
	{
		return $this->data;
	}

	/* Réinjecte le template dans le template courant */
	function setData($data)
	{
		$this->data = $data;
	}
	
	/* Affichage du bloc */
	function show()
	{
		echo $this->data;
	}
}

?>