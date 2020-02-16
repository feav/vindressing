<?php

/* Classe de Maintenance PAS Script - Shua-Creation.com 2019 */

class Maintenance
{
	var $isMaintenance;
	var $maintenance_ip;
	var $message_maintenance;
	
	function __construct()
	{
		global $pdo;
		$this->isMaintenance = $this->getConfig("isMaintenance");
		$this->maintenance_ip = $this->getConfig("maintenance_ip");
		$this->message_maintenance = $this->getConfig("message_maintenance");
		$this->message_maintenance = html_entity_decode($this->message_maintenance);
	}
	
	function getConfig($identifiant)
	{
		global $pdo;
		
		$SQL = "SELECT * FROM pas_configuration WHERE identifiant = '$identifiant'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		return $req['code'];
	}
	
	function checkMaintenance()
	{
		if($this->isMaintenance == 'yes')
		{
			if($this->maintenance_ip == '')
			{
				echo $this->message_maintenance;
				exit;
			}
			else if($_SERVER['REMOTE_ADDR'] != $this->maintenance_ip)
			{
				echo $this->message_maintenance;
				exit;
			}
		}
	}
}

?>