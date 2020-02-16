<?php

/* Classe Option Shua-Creation 2019 */

class Option
{
	function __construct()
	{
	}
	
	function updateOption($templateloader)
	{
		global $pdo;
		$data = $templateloader->getData();
		
		if(str_pos($data,'{last_annonce}'))
		{
			$SQL = "SELECT * FROM pas_annonce WHERE valider = 'oui' ORDER BY id DESC LIMIT 5";
			$reponse = $pdo->query($SQL);
			while($req = $reponse->fetch())
			{
				
			}
		}
	}
}

?>