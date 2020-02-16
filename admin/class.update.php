<?php

// Class Update par Shua-Creation 2019

class Update
{
	function __construct()
	{
	}
	
	// Check si une mise à jour est disponible
	function getUpdateAvaible($version)
	{
		$data = file_get_contents("http://www.shua-creation.com/cms/maj_pas.php?version=".$version);
		$data = json_decode($data);
		
		if($data->update == 'yes')
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	// On demande la mise à jour
	function getUpdate($version)
	{
		$data = file_get_contents("http://www.shua-creation.com/cms/maj_pas.php?version=".$version);
		$data = json_decode($data);
		return $data;
	}
	
	function downloadUpdate($version)
	{
		$data = file_get_contents("http://www.shua-creation.com/cms/maj_pas.php?version=".$version);
		$data = json_decode($data);
		
		$zip = file_get_contents($data->url);
		file_put_contents("../data.zip",$zip);
		
		return "50%";
	}
	
	function dezipUpdate($version)
	{
		// Extraction du zip
		$zip = new ZipArchive;
		$res = $zip->open("../data.zip");
		$zip->extractTo("../");
		$zip->close();
		
		// Suppression du zip
		unlink("../data.zip");
	}
	
	function executeUpdate()
	{
		// Application de l'update
		$data = file_get_contents($url_script."/update.php");
	}
	
	function deleteUpdate()
	{
		unlink("../update.php");
	}
	
	// On update
	function startUpdate($version)
	{
		$data = file_get_contents("http://www.shua-creation.com/cms/maj_pas.php?version=".$version);
		$data = json_decode($data);
		
		// On telecharger le zip du patch
		$zip = file_get_contents($data->url);
		file_put_contents("../data.zip",$zip);
		
		// Extraction du zip
		$zip = new ZipArchive;
		$res = $zip->open("../data.zip");
		$zip->extractTo("../");
		$zip->close();
		
		// Suppression du zip
		unlink("../data.zip");
		
		// Application de l'update
		$data = file_get_contents($url_script."/update.php");
		
		// Suppression de l'update
		unlink("../update.php");
	}
}

?>