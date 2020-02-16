<?php

class Favoris
{
	var $array_favoris;
	
	function __construct()
	{
		global $isConnected;
		global $pdo;
		
		if($isConnected)
		{
			/* On recupere les favoris dans la base */
			$SQL = "SELECT * FROM pas_user WHERE email = '".$_SESSION['email']."' AND password = '".$_SESSION['password']."'";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$iduser = $req['id'];
			$x = 0;
			
			$SQL = "SELECT * FROM pas_favoris WHERE iduser = $iduser";
			$reponse = $pdo->query($SQL);
			while($req = $reponse->fetch())
			{
				$this->array_favoris[$x] = $req['md5'];
				$x++;
			}
		}
		else
		{
			if(isset($_SESSION['favoris']))
			{
				$this->array_favoris = $_SESSION['favoris'];
			}
		}
	}
	
	/* Mise à jour des Favoris lors de la connexion utilisateur si des favoris on été mis entre temps avant la connexion */
	function updateFavoris()
	{
		global $pdo;
		
		$SQL = "SELECT * FROM pas_user WHERE email = '".$_SESSION['email']."' AND password = '".$_SESSION['password']."'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		$iduser = $req['id'];
		
		for($x=0;$x<count($this->array_favoris);$x++)
		{
			$md5 = $this->array_favoris[$x];
			$SQL = "SELECT COUNT(*) FROM pas_favoris WHERE md5 = '$md5' AND iduser = $iduser";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			if($req[0] == 0)
			{
				$SQL = "INSERT INTO pas_favoris (iduser,md5) VALUES ($iduser,'$md5')";
				$pdo->query($SQL);
			}
		}
	}
	
	/* Retourne les favoris de l'utilisateur */
	function getFavoris()
	{
		return $this->array_favoris;
	}
	
	/* Test si un favoris exist */
	function isFavorisExist($md5)
	{
		$fav = $this->array_favoris;
		$fav_exist = false;
		for($y=0;$y<@count($fav);$y++)
		{
			if($fav[$y] == $md5)
			{
				$fav_exist = true;
			}
		}
		
		return $fav_exist;
	}
	
	/* Ajout d'un favoris */
	function addFavoris($md5)
	{
		global $isConnected;
		global $pdo;
		
		if($isConnected)
		{
			$SQL = "SELECT * FROM pas_user WHERE email = '".$_SESSION['email']."' AND password = '".$_SESSION['password']."'";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$iduser = $req['id'];
			
			$SQL = "SELECT COUNT(*) FROM pas_favoris WHERE md5 = '$md5' AND iduser = $iduser";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			if($req[0] == 0)
			{
				$SQL = "INSERT INTO pas_favoris (iduser,md5) VALUES ($iduser,'$md5')";
				$pdo->query($SQL);
			}
		}
		
		if($md5 != '')
		{
			$favori_exist = false;
			if($this->array_favoris != NULL)
			{
				for($x=0;$x<count($this->array_favoris);$x++)
				{
					if($this->array_favoris[$x] == $md5)
					{
						$favori_exist = true;
					}
				}
			}

			if($favori_exist == false)
			{
				$this->array_favoris[@count($this->array_favoris)] = $md5;
			}

			/* On ajoute les nouveau favoris à la SESSION */
			$_SESSION['favoris'] = $this->array_favoris;
		}
	}
	
	/* Enlever un favoris */
	function removeFavoris($md5)
	{
		global $isConnected;
		global $pdo;
		
		if($isConnected)
		{
			$SQL = "SELECT * FROM pas_user WHERE email = '".$_SESSION['email']."' AND password = '".$_SESSION['password']."'";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$iduser = $req['id'];
			
			$SQL = "DELETE FROM pas_favoris WHERE md5 = '$md5' AND iduser = $iduser";
			$pdo->query($SQL);
		}
		
		for($x=0;$x<count($this->array_favoris);$x++)
		{
			if($this->array_favoris[$x] == $md5)
			{
				unset($this->array_favoris[$x]);
			}
		}
		
		$this->array_favoris = array_values($this->array_favoris);
		$_SESSION['favoris'] = $this->array_favoris;
	}
}

?>