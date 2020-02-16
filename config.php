<?php

/* v1.70.1 */

include "sql.php";

if($debug_mode)
{
	ini_set('display_startup_errors',1);
	ini_set('display_errors',1);
	error_reporting(-1);
}
else
{
	ini_set('display_startup_errors',0);
	ini_set('display_errors',0);
	error_reporting(0);
}

if($demo)
{
	$upload_path = "/clone/pas";
}
else
{
	$upload_path = "";
}

try
{
	$pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
}
catch(Exception $e)
{
	echo "Echec de la connexion à la base de données";
	exit();
}

session_start();

/* Retourne le code de la publicite */
function getPublicite($identifiant)
{
	global $pdo;
	
	$SQL = "SELECT * FROM pas_publicite WHERE identifiant = '$identifiant'";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	return $req['code'];
}

/* Renvoie la langue demander */
function getLangue($identifiant,$language)
{
	global $pdo;
	
	$SQL = "SELECT * FROM pas_langue WHERE identifiant = '$identifiant' AND language = '$language'";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	return $req['texte'];
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

/* Mettre en maintenance le site */
function setMaintenance()
{
	global $pdo;
	
	$isMaintenance = getConfig("isMaintenance");
	if($isMaintenance == 'yes')
	{
		if(getConfig("maintenance_ip") == '')
		{
			echo getConfig("message_maintenance");
			exit;
		}
		else if($_SERVER['REMOTE_ADDR'] != getConfig("maintenance_ip"))
		{
			echo getConfig("message_maintenance");
			exit;
		}
	}
}

/* Retoune une comparaison de deux date en heure / minute / seconde */
function dateDiff($date1, $date2)
{
	$diff = abs($date1 - $date2); // abs pour avoir la valeur absolute, ainsi éviter d'avoir une différence négative
	$retour = array();
 
	$tmp = $diff;
	$retour['second'] = $tmp % 60;
 
	$tmp = floor( ($tmp - $retour['second']) /60 );
	$retour['minute'] = $tmp % 60;
 
	$tmp = floor( ($tmp - $retour['minute'])/60 );
	$retour['hour'] = $tmp % 24;
 
	$tmp = floor( ($tmp - $retour['hour'])  /24 );
	$retour['day'] = $tmp;
 
	return $retour;
}

/* Mets à jour une publicite */
function updatePublicite($identifiant,$code)
{
	global $pdo;
	
	$code = str_replace("'","''",$code);
	
	$SQL = "SELECT COUNT(*) FROM pas_publicite WHERE identifiant = '$identifiant'";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	if($req[0] == 0)
	{
		// On créer la publicité
		$SQL = "INSERT INTO pas_publicite (identifiant,code) VALUES ('$identifiant','$code')";
		$pdo->query($SQL);
	}
	else
	{
		$SQL = "UPDATE pas_publicite SET code = '$code' WHERE identifiant = '$identifiant'";
		$pdo->query($SQL);
	}
}

/* Retourne le code de configuration */
function getConfig($identifiant)
{
	global $pdo;
	
	$SQL = "SELECT * FROM pas_configuration WHERE identifiant = '$identifiant'";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	return $req['code'];
}

/* Mets à jour une configuration */
function updateConfig($identifiant,$code)
{
	global $pdo;
	
	$code = str_replace("'","''",$code);
	
	$SQL = "SELECT COUNT(*) FROM pas_configuration WHERE identifiant = '$identifiant'";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	if($req[0] == 0)
	{
		// On créer la publicité
		$SQL = "INSERT INTO pas_configuration (identifiant,code) VALUES ('$identifiant','$code')";
		$pdo->query($SQL);
	}
	else
	{
		$SQL = "UPDATE pas_configuration SET code = '$code' WHERE identifiant = '$identifiant'";
		$pdo->query($SQL);
	}
}

/* Renvoie le code de verification HTML de Google Webmaster Tool */
function getWebmasterTool()
{
	global $pdo;
	
	$google_webmaster_tool = getConfig("google_webmaster_tool");
	if($google_webmaster_tool != '')
	{
	?>
	<meta name="google-site-verification" content="<?php echo $google_webmaster_tool; ?>" />
	<?php
	}
}

/* Methode de slugify */
function slugify($text)
{
  // replace non letter or digits by -
  $text = preg_replace('~[^\pL\d]+~u', '-', $text);

  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);

  // trim
  $text = trim($text, '-');

  // remove duplicate -
  $text = preg_replace('~-+~', '-', $text);

  // lowercase
  $text = strtolower($text);

  if (empty($text)) {
    return 'n-a';
  }

  return $text;
}

?>
