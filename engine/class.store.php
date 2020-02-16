<?php

/* Classe Store Shua-Creation.com 2019 */

class Store
{
	var $email_store;
	var $password_store;
	var $isConnectedStore;
	
	function __construct()
	{
		global $pdo;
		
		$this->isConnectedStore = false;
		
		$SQL = "SELECT * FROM pas_configuration WHERE identifiant = 'email_store'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		$this->email_store = $req['code'];
		
		$SQL = "SELECT * FROM pas_configuration WHERE identifiant = 'password_store'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		$this->password_store = $req['code'];
		
		if($this->email_store == '')
		{
			$this->isConnectedStore = false;
		}
		else if($this->password_store == '')
		{
			$this->isConnectedStore = false;
		}
		else
		{
			$return = file_get_contents("http://www.shua-creation.com/store/api.php?command=connect&email=".$this->email_store."&password=".$this->password_store);
			if($return == 'yes')
			{
				$this->isConnectedStore = true;
			}
			else
			{
				$this->isConnectedStore = false;
			}
		}
	}
}

?>