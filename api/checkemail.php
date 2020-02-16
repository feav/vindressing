<?php

include "../config.php";

$email = $_REQUEST['email'];

$SQL = "SELECT COUNT(*) FROM pas_user WHERE email = '$email'";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

if($req[0] == 0)
{
	echo 'ok';
}
else
{
	echo 'no';
}

?>