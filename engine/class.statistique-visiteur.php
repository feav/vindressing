<?php

/* Shua-Creation 2018 Class Statistique Visiteur */

class Statistique_Visiteur
{
	function __construct()
	{
		global $pdo;
		
		$SQL = "CREATE TABLE `statistique_visiteur` (`id` int(16) NOT NULL AUTO_INCREMENT,`ip` text NOT NULL,`user_agent` text NOT NULL,`date_de_la_visite` datetime NOT NULL,PRIMARY KEY (`id`))";
		$pdo->query($SQL);
	}
	
	/* Ajoute une visite */
	function addVisite()
	{
		global $pdo;
		
		$ip = $_SERVER['REMOTE_ADDR'];
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		
		$SQL = "INSERT INTO statistique_visiteur (ip,user_agent,date_de_la_visite) VALUES ('$ip','$user_agent',NOW())";
		$pdo->query($SQL);
	}
	
	/* Renvoie le mois en Français par rapport à un chiffre */
	function getMoisFrancais($mois)
	{
		if($mois == '01')
		{
			return "Janvier";
		}
		else if($mois == '02')
		{
			return "Février";
		}
		else if($mois == '03')
		{
			return "Mars";
		}
		else if($mois == '04')
		{
			return "Avril";
		}
		else if($mois == '05')
		{
			return "Mai";
		}
		else if($mois == '06')
		{
			return "Juin";
		}
		else if($mois == '07')
		{
			return "Juillet";
		}
		else if($mois == '08')
		{
			return "Août";
		}
		else if($mois == '09')
		{
			return "Septembre";
		}
		else if($mois == '10')
		{
			return "Octobre";
		}
		else if($mois == '11')
		{
			return "Novembre";
		}
		else if($mois == '12')
		{
			return "Décembre";
		}
	}
	
	/* Renvoie le nombre de page vue à une date données */
	function getPageVu($date)
	{
		global $pdo;
		
		$SQL = "SELECT COUNT(*) FROM statistique_visiteur WHERE date_de_la_visite like '$date%'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		return $req[0];
	}
	
	/* Renvoie le nombre de visiteur unique à une date données sans robot */
	function getVisiteur($date)
	{
		global $pdo;
		
		$total = 0;
		
		/* Optimisation */
		$year = date('Y');
		$month = date('m');
		$day = date('d');
		
		$dayDemande = explode("-",$date);
		$dayDemande = $dayDemande[2];
		
		$SQL = "SELECT DISTINCT(ip) FROM statistique_visiteur WHERE date_de_la_visite like '$date%' AND `user_agent` NOT LIKE '%bot%'";
		$reponse = $pdo->query($SQL);
		while($req = $reponse->fetch())
		{
			$total++;
		}
		
		return $total;
	}
	
	/* Renvoie le nombre de robot à une date donnée */
	function getBot($date)
	{
		global $pdo;
		
		$total = 0;
		
		$SQL = "SELECT DISTINCT(ip) FROM statistique_visiteur WHERE date_de_la_visite like '$date%' AND `user_agent` LIKE '%bot%'";
		$reponse = $pdo->query($SQL);
		while($req = $reponse->fetch())
		{
			$total++;
		}
		
		return $total;
	}
}

?>