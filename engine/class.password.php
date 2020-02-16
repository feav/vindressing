<?php

/* Classe Password Shua-Creation 2019 */
/* ================================== */
/* Permet d'effectuer une verification des mots de passe d'un point de vue validité */
/* mais également sécurité, le minimum de la longueur d'un mot de passe est de 12 caractères pour rester */
/* sur un modèle sécurisé. */
/* ================================== */
/* Methode : */
/* ============ */
/* encodePassword($password) = Permet d'encoder un mot de passe avec un grain de sel $salt défini dans un fichier de config */
/* checkPassword($password,$longueur) = Verifie un mot de passe avec une longueur donnée. */
/* checkPasswordIdentique($password1,$password2) = Verifie qu'un mot de passe et sa confirmation sont identique */

class Password
{
	function __construct()
	{
	}
	
	/* checkConnect permet de verifier qu'un mot de passe n'as pas été entrer de manière éronnée de nombreuse fois */
	/* si c'est le cas, a mettre en place si erreur dans la connexion, sinon l'utilisateur et l'ip est blacklister, renvoie false */
	/* cette methode à été créer pour empecher les attaque de brute force avec dictionnaire sur un formulaire de connexion */
	function checkConnect($username,$nombre_erreur_max)
	{
		global $pdo;
		
		/* On récupere l'IP de l'utilisateur */
		$ip = $_SERVER['REMOTE_ADDR'];
		
		/* On verifie la base avec cette IP et nom d'utilisateur */
		$SQL = "SELECT COUNT(*) FROM connect_check WHERE username = '$username' AND ip = '$ip'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		$count = $req[0];
		
		if($count > $nombre_erreur_max)
		{
			return false;
		}
		
		return true;
	}
	
	/* Encode un mot de passe en MD5 avec un grain de sel */
	function encodePassword($password)
	{
		global $salt;
		
		$password = md5($salt.$password);
		return $password;
	}
	
	/* Check la validater d'un mot de passe suivant des critères de sécurité */
	function checkPassword($password,$longueur)
	{
		$data = NULL;
		
		/* On check la longueur du mot de passe */
		if(strlen($password) < $longueur)
		{
			$data['validate'] = false;
			$data['msg_error'] = "Le mot de passe est trop court, il doit être de $longueur caractères";
			return $data;
		}
		
		/* Si des espaces sont présent, interdit */
		if(strpos($password, ' ') !== true) 
		{
			$data['validate'] = false;
			$data['msg_error'] = "Votre mot de passe ne doit pas contenir d'espaces";
			return $data;
		}
		
		/* On test la présence de Majuscule, minuscule, et chiffre */
		if(!preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)#',$password)) 
		{
			$data['validate'] = false;
			$data['msg_error'] = "Votre mot de passe doit contenir au minimum 1 Majuscule et des chiffres.";
			return $data;
		}
		
		$data['validate'] = true;
		return $data;
	}
	
	/* Check le mot de passe et sa confirmation s'il sont identique */
	function checkPasswordIdentique($password1,$password2)
	{
		$data = NULL;
		
		if($password1 != $password2)
		{
			$data['validate'] = false;
			$data['msg_error'] = "Votre mot de passe et sa confirmation ne sont pas identique, veuillez réessayez.";
			return $data;
		}
		
		$data['validate'] = true;
		return $data;
	}
}

?>