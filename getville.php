<?php

include "config.php";

$SQL = "SELECT * FROM pas_ville";
$reponse = $pdo->query($SQL);
while($req = $reponse->fetch())
{
	$iddepartement = $req['iddepartement'];
	$idregion = $req['idregion'];
	$departement = $req['departement'];
	$nom = $req['nom'];
	$slug = $req['slug'];
	$codepostal = $req['codepostal'];
	$latitude = $req['latitude'];
	$longitude = $req['longitude'];
	
	echo '$SQL = "INSERT INTO pas_ville (iddepartement,idregion,departement,nom,slug,codepostal,latitude,longitude) VALUES ('.$iddepartement.','.$idregion.','.$departement.',\''.$nom.'\',\''.$slug.'\',\''.$codepostal.'\',\''.$latitude.'\',\''.$longitude.'\')";<br>';
	echo '$pdo->query($SQL);<br>';
}

?>