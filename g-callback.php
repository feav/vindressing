<?php

include "main.php";

$gClient = $class_google_connect->GoogleClient;
$google_oauthV2 = $class_google_connect->GoogleOAuthv2;

if(isset($_GET['code']))
{
    $gClient->authenticate($_GET['code']);
    $_SESSION['token'] = $gClient->getAccessToken();
    header("Location: ".$url_script."/g-callback.php");
	exit;
}

if(isset($_SESSION['token']))
{
    $gClient->setAccessToken($_SESSION['token']);
}

if($gClient->getAccessToken())
{
    $gpUserProfile = $google_oauthV2->userinfo->get();
	$id = $gpUserProfile['id'];
	$email = $gpUserProfile['email'];
	
	/* Une fois les informations récuperer on check en base */
	$SQL = "SELECT COUNT(*) FROM pas_user WHERE email = '$email' AND password = '$id'";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	if($req[0] == 0)
	{
		/* On insere les données */
		$md5 = md5(microtime());
		
		$SQL = "INSERT INTO pas_user (email,username,password,date_inscription,optin,md5,validate_account,ban,type_compte) VALUES ('$email','$email','$id',NOW(),'oui','$md5','yes','no','particulier')";
		$pdo->query($SQL);
	}
	
	$_SESSION['email'] = $email;
	$_SESSION['password'] = $id;
	$_SESSION['pseudo'] = $email;
	$_SESSION['type_compte'] = 'particulier';
	
	header("Location: ".$url_script);
	exit;
	//print_r($gpUserProfile);
}

?>