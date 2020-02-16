<?php

include "main.php";

$payload = @file_get_contents('php://input');
$event = null;

try 
{
	$event = \Stripe\Event::constructFrom(json_decode($payload, true));
} 
catch(\UnexpectedValueException $e)
{
	// Invalid payload
	http_response_code(400);
	exit();
}

// Handle the event
switch ($event->type) 
{
	case 'payment_intent.succeeded':
	
		$paymentIntent = $event->data->object;
		
		/* Paiement success */
		$charge = $paymentIntent->charges->data;
		$id = $paymentIntent->id;
		
		for($x=0;$x<count($charge);$x++)
		{
			$amount = $charge[$x]->amount;
			$amount = round($amount / 100);
			$email = $charge[$x]->billing_details->email;
			$currency = $charge[$x]->currency;
			$risk_level = $charge[$x]->outcome->risk_level;
			
			if($risk_level == 'normal')
			{
				// Normal
				$SQL = "INSERT INTO pas_paiement (type_paiement,montant,email_user,id_transaction,md5,date_paiement) VALUES ('stripe','$amount','$email','$id','',NOW())";
				$pdo->query($SQL);
			}
			else
			{
				// Paiement avec risk Level trop élever
			}
		}
	break;
	case 'checkout.session.completed':
	
		$paid = $event->data->object;
		$id = $paid->payment_intent;
		/* MD5 de l'annonce */
		$md5 = $paid->client_reference_id;
		
		sleep(2);

		/* On Update le paiement */
		$SQL = "UPDATE pas_paiement SET md5 = '$md5' WHERE id_transaction = '$id'";
		$pdo->query($SQL);
		
		$SQL = "SELECT * FROM pas_annonce WHERE md5 = '$md5'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		$idannonce = $req['id'];
		
		/* On fini par valider l'annonce */
		$SQL = "UPDATE pas_annonce SET valider = 'yes' WHERE id = $idannonce";
		$pdo->query($SQL);
		
		$SQL = "UPDATE pas_annonce SET status = 'PAID_STRIPE' WHERE id = $idannonce";
		$pdo->query($SQL);
		
		$option = $req['option_annonce'];
		$option = explode(",",$option);
		
		for($x=0;$x<count($option);$x++)
		{
			$idoption = $option[$x];
			$SQL = "SELECT * FROM pas_grille_tarif WHERE id = $idoption";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$emplacement = $req['emplacement'];
			if($emplacement == 'classic')
			{
				/* On paye donc un tarif sans rien de plus */
			}
			else if($emplacement == 'bandeau')
			{
				/* On paye un bandeau URGENT */
				$SQL = "UPDATE pas_annonce SET urgente = 2 WHERE id = $idannonce";
				$pdo->query($SQL);
			}
			else if($emplacement == 'photo')
			{
				/* On paye pour un Pack Photo les photo sont déjà incluse dans l'annonce rien n'as faire */
			}
			else if($emplacement == 'remonter')
			{
				$duree = $req['duree'];
				$remonter_x_jour = $req['remonter_x_jour'];
				
				$date = date('Y-m-d');
				
				$nbr_rotation = ceil($duree / $remonter_x_jour);
				$jour = 0;
				for($x=0;$x<$nbr_rotation;$x++)
				{
					$jour = $jour + $remonter_x_jour;
					$SQL = "INSERT INTO pas_remonter (md5,date_remonter) VALUES ('$md5',now() + interval $jour day)";
					$pdo->query($SQL);
				}
			}
		}
		
	break;
	default:
	
	// Unexpected event type
	http_response_code(400);
	exit();

}

http_response_code(200);

?>