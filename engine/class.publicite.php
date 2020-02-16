<?php

/* Class publicité Shua-Creation 2018 */

class Publicite
{
	function __construct()
	{
	}
	
	function updatePublicite($templateloader)
	{
		global $pdo;
		
		$SQL = "SELECT * FROM pas_publicite";
		$reponse = $pdo->query($SQL);
		while($req = $reponse->fetch())
		{
			$templateloader->assign("{".$req['identifiant']."}",$req['code']);
		}
	}
	
	function getPublicite($identifiant)
	{
		global $pdo;
		
		$SQL = "SELECT * FROM pas_publicite WHERE identifiant = '$identifiant'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		return $req['code'];
	}
}

?>