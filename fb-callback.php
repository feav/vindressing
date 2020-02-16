<?php

include "main.php";

$email = AntiInjectionSQL($_REQUEST['email']);
$id = AntiInjectionSQL($_REQUEST['id']);
$first_name = AntiInjectionSQL($_REQUEST['first_name']);
$last_name = AntiInjectionSQL($_REQUEST['last_name']);
$picture = "http://graph.facebook.com/".$id."/picture?type=normal";

$SQL = "SELECT COUNT(*) FROM pas_user WHERE email = '$email' AND password = '$id'";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

if($req[0] == 0)
{
	/* On insere les données */
	$md5 = md5(microtime());
		
	$SQL = "INSERT INTO pas_user (email,username,password,date_inscription,optin,md5,validate_account,ban,type_compte) VALUES ('$email','$email','$id',NOW(),'oui','$md5','yes','no','particulier')";
	$pdo->query($SQL);
		
	$_SESSION['email'] = $email;
	$_SESSION['password'] = $id;
	$_SESSION['pseudo'] = $email;
	$_SESSION['type_compte'] = 'particulier';
	
	header("Location: ".$url_script);
	exit;
}
else
{
	$_SESSION['email'] = $email;
	$_SESSION['password'] = $id;
	$_SESSION['pseudo'] = $email;
	$_SESSION['type_compte'] = 'particulier';
	header("Location: ".$url_script);
	exit;
}

?>