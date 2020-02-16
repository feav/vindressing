<?php

/* Classe de paiement Paydunya Shua-Creation.com 2018 */

class Paydunya
{
	function __construct()
	{		
	}
	
	/* Fonction globale de renvoie de config */
	function getConfig($identifiant)
	{
		global $pdo;
		
		$SQL = "SELECT * FROM pas_configuration WHERE identifiant = '$identifiant'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		return $req['code'];
	}
	
	/* Créer un paiement Paydunya en PER (Redirection sur le site pour paiement) */
	function createPaydunyaPaymentPER($sandbox = true,$montant,$description,$store_name)
	{
		$cle_principal = $this->getConfig("paydunya_cle_principal");
		$cle_publique = $this->getConfig("paydunya_cle_publique");
		$cle_privee = $this->getConfig("paydunya_cle_privee");
		$token = $this->getConfig("paydunya_token");
		
		if($sandbox)
		{
			$url = "https://app.paydunya.com/sandbox-api/v1/checkout-invoice/create";
		}
		else
		{
			$url = "https://app.paydunya.com/api/v1/checkout-invoice/create";
		}

		$json['invoice']['total_amount'] = $montant;
		$json['invoice']['description'] = $description;
		$json['store']['name'] = $store_name;
		$json = json_encode($json);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$json);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		'Content-Type: application/json',
		'PAYDUNYA-MASTER-KEY: '.$cle_principal,
		'PAYDUNYA-PRIVATE-KEY: '.$cle_privee,
		'PAYDUNYA-TOKEN: '.$token
		));
		$reponse = curl_exec($ch);
		curl_close($ch);
		
		$reponse = json_decode($reponse);

		// Redirection vers Paydunya avec leur réponse
		header("Location: ".$reponse->response_text);
	}
	
	/* Créer un paiement PSR Paydunya , il faut indiquer le telephone du client */
	/* Completement transparent en deux étape */
	/* Etape 1 : Création du paiement => Retour du TOKEN , Le client reçoit un SMS */
	/* Etape 2 : Finalisation du paiement avec le TOKEN et le code SMS */
	/* Minimum de paiement à 200 F CFA */
	function createPaydunyaPaymentPSR($sandbox = true,$phone_number,$montant,$description,$store_name)
	{
		$cle_principal = $this->getConfig("paydunya_cle_principal");
		$cle_publique = $this->getConfig("paydunya_cle_publique");
		$cle_privee = $this->getConfig("paydunya_cle_privee");
		$token = $this->getConfig("paydunya_token");
		
		if($sandbox)
		{
			$url = "https://app.paydunya.com/sandbox-api/v1/opr/create";
		}
		else
		{
			$url = "https://app.paydunya.com/api/v1/opr/create";
		}
		
		$json['invoice_data']['invoice']['total_amount'] = $montant;
		$json['invoice_data']['invoice']['description'] = $description;
		$json['invoice_data']['store']['name'] = $store_name;
		$json['opr_data']['account_alias'] = $phone_number;
		$json = json_encode($json);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$json);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		'Content-Type: application/json',
		'PAYDUNYA-MASTER-KEY: '.$cle_principal,
		'PAYDUNYA-PRIVATE-KEY: '.$cle_privee,
		'PAYDUNYA-TOKEN: '.$token
		));
		$reponse = curl_exec($ch);
		curl_close($ch);
		
		$reponse = json_decode($reponse);
		
		return $reponse->response_text;
	}
	
	function finalPaydunyaPaymentPSR($sandbox = true,$token,$code)
	{
		$cle_principal = $this->getConfig("paydunya_cle_principal");
		$cle_publique = $this->getConfig("paydunya_cle_publique");
		$cle_privee = $this->getConfig("paydunya_cle_privee");
		$tokenw = $this->getConfig("paydunya_token");
		
		if($sandbox)
		{
			$url = "https://app.paydunya.com/sandbox-api/v1/opr/charge";
		}
		else
		{
			$url = "https://app.paydunya.com/api/v1/opr/charge";
		}
		
		$json['token'] = $token;
		$json['confirm_token'] = $code;
		$json = json_encode($json);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$json);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		'Content-Type: application/json',
		'PAYDUNYA-MASTER-KEY: '.$cle_principal,
		'PAYDUNYA-PRIVATE-KEY: '.$cle_privee,
		'PAYDUNYA-TOKEN: '.$tokenw
		));
		$reponse = curl_exec($ch);
		curl_close($ch);
		
		$reponse = json_decode($reponse);

		$charge = $reponse->response_text;
		$status = $reponse->invoice_data->status;
		$name = $reponse->invoice_data->customer->name;
		$phone = $reponse->invoice_data->customer->phone;
		$email = $reponse->invoice_data->customer->email;
		
		$array['reponse'] = $charge;
		$array['status'] = $status;
		$array['name'] = $name;
		$array['phone'] = $phone;
		$array['email'] = $email;
		$array['reponse_code'] = $reponse->response_code;
		
		/* Code erreur */
		/* 1002 - SMS Confirmation erronnée */
		/* 00 - Success */
		
		return $array;
	}
}

?>