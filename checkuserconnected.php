<?php

global $isConnected;
$isConnected = false;

if(isset($_SESSION['email']))
{
	if(isset($_SESSION['password']))
	{
		$SQL = "SELECT COUNT(*) FROM pas_user WHERE email = '".$_SESSION['email']."' AND password = '".$_SESSION['password']."'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();

		if($req[0] == 0)
		{
			unset($_SESSION['email']);
			unset($_SESSION['password']);
			unset($_SESSION['pseudo']);
			unset($_SESSION['type_compte']);
			$isConnected = false;
		}
		else
		{
			$isConnected = true;
		}
	}
	else
	{
		unset($_SESSION['email']);
		unset($_SESSION['password']);
		unset($_SESSION['pseudo']);
		unset($_SESSION['type_compte']);
	}
}
else
{
	unset($_SESSION['email']);
	unset($_SESSION['password']);
	unset($_SESSION['pseudo']);
	unset($_SESSION['type_compte']);
}

?>