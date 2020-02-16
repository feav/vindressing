<?php

/* Class Ban par Shua-Creation.com 2019 */

class Ban
{
	function __construct()
	{
		global $pdo;
		
		$ip = $_SERVER["REMOTE_ADDR"];
		
		$SQL = "SELECT * FROM pas_firewall";
		$reponse = $pdo->query($SQL);
		while($req = $reponse->fetch())
		{
			if($ip == $req['ip'])
			{
				exit;
			}
		}
	}
}

?>