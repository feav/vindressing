<?php

/* Class logo Shua-Creation 2018 */

class Logo
{
	function __construct()
	{
		
	}
	
	function showLogo()
	{
		global $pdo;
		global $url_script;
		
		$SQL = "SELECT * FROM pas_configuration WHERE identifiant = 'logo'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		return $url_script."/images/".$req['code'];
	}
}

?>