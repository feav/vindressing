<?php

/* Classe Horraire Ouverture Shua-Creation.com 2018 */

class Horaire_Ouverture
{
	function __construct()
	{
		global $pdo;
		$SQL = "CREATE TABLE `horaire_ouverture` (`id` int(16) NOT NULL AUTO_INCREMENT,`jour` text NOT NULL,`heure_ouverture1` time NOT NULL,`heure_fermeture1` time NOT NULL,`heure_ouverture2` time NOT NULL,`heure_fermeture2` time NOT NULL,`isOpen` TEXT NOT NULL,PRIMARY KEY (`id`))";
		$pdo->query($SQL);
		
		/* On check si la base est vide ou non */
		$SQL = "SELECT COUNT(*) FROM horaire_ouverture";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		/* Si vide on remplie par default */
		if($req[0] == 0)
		{
			$SQL = "INSERT INTO `horaire_ouverture` (`jour`, `heure_ouverture1`, `heure_fermeture1`, `heure_ouverture2`, `heure_fermeture2`, `isOpen`) VALUES ('Mon', '09:00:00', '18:00:00', '09:00:00', '18:00:00', 'oui')";
			$pdo->query($SQL);
			$SQL = "INSERT INTO `horaire_ouverture` (`jour`, `heure_ouverture1`, `heure_fermeture1`, `heure_ouverture2`, `heure_fermeture2`, `isOpen`) VALUES ('Tue', '09:00:00', '18:00:00', '09:00:00', '18:00:00', 'oui')";
			$pdo->query($SQL);
			$SQL = "INSERT INTO `horaire_ouverture` (`jour`, `heure_ouverture1`, `heure_fermeture1`, `heure_ouverture2`, `heure_fermeture2`, `isOpen`) VALUES ('Wed', '09:00:00', '18:00:00', '09:00:00', '18:00:00', 'oui')";
			$pdo->query($SQL);
			$SQL = "INSERT INTO `horaire_ouverture` (`jour`, `heure_ouverture1`, `heure_fermeture1`, `heure_ouverture2`, `heure_fermeture2`, `isOpen`) VALUES ('Thu', '09:00:00', '18:00:00', '09:00:00', '18:00:00', 'oui')";
			$pdo->query($SQL);
			$SQL = "INSERT INTO `horaire_ouverture` (`jour`, `heure_ouverture1`, `heure_fermeture1`, `heure_ouverture2`, `heure_fermeture2`, `isOpen`) VALUES ('Fri', '09:00:00', '18:00:00', '09:00:00', '18:00:00', 'oui')";
			$pdo->query($SQL);
			$SQL = "INSERT INTO `horaire_ouverture` (`jour`, `heure_ouverture1`, `heure_fermeture1`, `heure_ouverture2`, `heure_fermeture2`, `isOpen`) VALUES ('Sat', '09:00:00', '18:00:00', '09:00:00', '18:00:00', 'oui')";
			$pdo->query($SQL);
			$SQL = "INSERT INTO `horaire_ouverture` (`jour`, `heure_ouverture1`, `heure_fermeture1`, `heure_ouverture2`, `heure_fermeture2`, `isOpen`) VALUES ('Sun', '09:00:00', '18:00:00', '09:00:00', '18:00:00', 'oui')";
			$pdo->query($SQL);
		}
	}
	
	/* Renvoie le status d'ouverture d'un jour en particulier */
	function isDayisOpen($day)
	{
		global $pdo;
		
		$SQL = "SELECT * FROM horaire_ouverture WHERE jour = '$day'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		return $req['isOpen'];
	}
	
	/* Renvoie true si c'est ouvert, false si ce n'est pas le cas */
	function isOpen()
	{
		global $pdo;
		
		$jour_actuelle = date('D');
		
		$SQL = "SELECT COUNT(*) FROM horaire_ouverture WHERE jour = '$jour_actuelle' AND isOpen = 'oui'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		if($req[0] != 0)
		{		
			$SQL = "SELECT * FROM horaire_ouverture WHERE jour = '$jour_actuelle'";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$heure_ouverture1 = $req['heure_ouverture1'];
			$heure_fermeture1 = $req['heure_fermeture1'];
			
			$heure_ouverture2 = $req['heure_ouverture2'];
			$heure_fermeture2 = $req['heure_fermeture2'];
			
			$heure_actuelle = date('Y').'-'.date('m').'-'.date('d').' '.date('H').':'.date('i').':'.date('s');
			$heure_fermeture = date('Y').'-'.date('m').'-'.date('d').' '.$heure_fermeture1;
			$heure_ouverture = date('Y').'-'.date('m').'-'.date('d').' '.$heure_ouverture1;

			$heure_ouverture2 = date('Y').'-'.date('m').'-'.date('d').' '.$heure_ouverture2;
			$heure_fermeture2 = date('Y').'-'.date('m').'-'.date('d').' '.$heure_fermeture2;

			$date_actuelle = new \DateTime($heure_actuelle);
			$date_ouverture = new \DateTime($heure_ouverture);
			$date_fermeture = new \DateTime($heure_fermeture);

			$date_ouverture2 = new \DateTime($heure_ouverture2);
			$date_fermeture2 = new \DateTime($heure_fermeture2);

			$isOpen = false;
			if($date_actuelle>=$date_ouverture)
			{
				$isOpen = true;
				if($date_actuelle<$date_fermeture)
				{
					$isOpen = true;
				}
				else
				{
					$isOpen = false;
				}
			}

			if($date_actuelle>=$date_ouverture2)
			{
				$isOpen = true;
				if($date_actuelle<$date_fermeture2)
				{
					$isOpen = true;
				}
				else
				{
					$isOpen = false;
				}
			}
		}
		else
		{
			$isOpen = false;
		}
		
		return $isOpen;
	}
}

?>