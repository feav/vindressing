<?php

/* Code by Jlaurent for Shua-Creation.com 2017 */

require_once('stripe/init.php');

class StripeSC
{
	var $apiPublishableKey;
	var $apiSecretKey;
	var $item_name;
	var $price;
	var $currency;
	var $description;
	var $url_cancel;
	var $url_paiementok;

	function __construct($apiPublishableKey,$apiSecretKey,$urlCancel,$urlPaiementOk)
	{	
		$this->apiPublishableKey = $apiPublishableKey;
		$this->apiSecretKey = $apiSecretKey;
		$this->url_cancel = $urlCancel;
		$this->url_paiementok = $urlPaiementOk;
	}
	
	function setInfoPaiement($item_name,$price,$currency,$description)
	{
		$this->item_name = $item_name;
		/* Transform price for Stripe */
		$price = str_replace(",","",$price);
		$price = str_replace(".","",$price);
		$price = $price * 100;
		$this->price = $price;
		$this->currency = $currency;
		$this->description = $description;
	}
	
	function showButton($quantity,$idproduct,$iduser)
	{
		?>
		<div class="btnPayment">
			<div style="float:left;">
				
			</div>
			<div style="float:left;">
			<form id="formstripe" action="" method="POST" style="margin: 0;">
			  <script
				src="https://checkout.stripe.com/checkout.js" class="stripe-button"
				data-key="<?php echo $this->apiPublishableKey; ?>"
				data-amount="<?php echo $this->price; ?>"
				data-name="<?php echo $this->item_name; ?>"
				data-locale="fr"
				data-label="Payer par carte bancaire"
				data-currency="<?php echo $this->currency; ?>">
			  </script>
			  <input type="hidden" name="idproduct" value="<?php echo $idproduct; ?>">
			  <input type="hidden" name="iduser" value="<?php echo $iduser; ?>">
			</form>
			</div>
		</div>
		<?php
	}
	
	function showPrepaid()
	{
		global $pdo;
		
		if(isset($_POST['stripeToken']))
		{
			// Permet de recuperer un paiement
			$token = $_POST['stripeToken'];
			$iduser = $_REQUEST['iduser'];
			$idproduct = $_REQUEST['idproduct'];
			
			\Stripe\Stripe::setApiKey($this->apiSecretKey);
			
			try 
			{
			  $charge = \Stripe\Charge::create(array(
				"amount" => $this->price, // amount in cents, again
				"currency" => $this->currency,
				"source" => $token,
				"description" => $this->item_name
				));
				
			  if ($charge->paid == true) 
			  {
					/*print_r($charge);
					exit;*/
					$SQL = "INSERT INTO paiement_produit VALUES ('','Completed','".$charge->id."','Stripe',$idproduct,$iduser,'','','".$charge->source->name."','".$charge->amount."','".$charge->currency."',NOW())";
					$pdo->query($SQL);
					
					header("Location: ".$this->url_paiementok."?paid=stripe&idproduct=$idproduct&email=".$charge->source->name."&amt=".$charge->amount."&currency=".$charge->currency."&idcmd=".$charge->id."&item_name=".$charge->description);
			  }
			}
			catch(\Stripe\Error\Card $e) 
			{
				// The card has been declined
				header("Location: ".$this->url_cancel);
			}
		}
	}
	
	function getTransactionInformation()
	{
		if(isset($_REQUEST['paid']))
		{
			$array = NULL;
			$paid = $_REQUEST['paid'];
			if($paid == 'stripe')
			{
				$idproduct = $_REQUEST['idproduct'];
				$item_name = $_REQUEST['item_name'];
				$email = $_REQUEST['email'];
				$amt = $_REQUEST['amt'];
				$currency = $_REQUEST['currency'];
				$idcmd = $_REQUEST['idcmd'];
				
				$array['reference_produit'] = $idproduct;
				$array['email_du_client'] = $email;
				$array['montant_de_la_transaction'] = number_format(($amt / 100),2);
				$array['nom_du_produit'] = $item_name;
				$array['devise_de_la_transaction'] = $currency;
				$array['identifiant_transaction'] = $idcmd;
				$array['type_paiement'] = 'stripe';
			}
			
			return $array;
		}
	}
}

?>