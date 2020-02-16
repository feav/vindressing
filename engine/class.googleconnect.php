<?php

/* Class Google Connect Shua-Creation.com 2019 */

include_once "Google/Google_Client.php";
include_once "Google/contrib/Google_Oauth2Service.php";

class GoogleConnect
{
	var $GoogleClient;
	var $GoogleOAuthv2;
	
	function __construct()
	{
		global $pdo;
		global $url_script;
		
		$this->GoogleClient = new Google_Client();
		$this->GoogleClient->setClientId($this->getParametre("google_connect_id_client"));
		$this->GoogleClient->setClientSecret($this->getParametre("google_connect_secret_client"));
		$this->GoogleClient->setApplicationName($this->getParametre("google_connect_name_application"));
		$this->GoogleClient->setRedirectUri($url_script."/g-callback.php");
		
		$this->GoogleOAuthv2 = new Google_Oauth2Service($this->GoogleClient);
	}
	
	function getParametre($parametre)
	{
		global $pdo;
		
		$SQL = "SELECT * FROM pas_configuration WHERE identifiant = '$parametre'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		return $req['code'];
	}
	
	/* Lien de connexion à la Google API */
	function getURL()
	{
		return $this->GoogleClient->createAuthUrl();
	}
}

?>