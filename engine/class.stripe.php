<?php

/* Classe Stripe par Shua-Creation.com 2019 avec SCA */

include "engine/stripe-php-master/init.php";

class StripePaiement
{
	var $api_secret_key;
	var $api_publisher_key;
	var $api_endpoint_secret;
	
	function __construct()
	{
	}
	
	/* Set de la clé API SECRET KEY */
	function setApiSecretAndPublisherKey($secret,$publisher)
	{
		$this->api_secret_key = $secret;
		$this->api_publisher_key = $publisher;
	}
	
	function setApiEndpointSecret($endpointsecret)
	{
		$this->api_endpoint_secret = $endpointsecret;
	}
	
	/* Paiement simple */
	function stripePaidSimple($name,$description,$image,$prix,$currency,$qty,$cancelurl,$successurl,$ref)
	{
		Stripe\Stripe::setApiKey($this->api_secret_key);

		$prix = $prix * 100;

		$session = \Stripe\Checkout\Session::create([
		  'payment_method_types' => ['card'],
		  'line_items' => [[
			'name' => $name,
			'description' => $description,
			'images' => [$image],
			'amount' => $prix,
			'currency' => $currency,
			'quantity' => $qty,
		  ]],
		  'client_reference_id' => $ref,
		  'success_url' => $successurl.'?session_id={CHECKOUT_SESSION_ID}',
		  'cancel_url' => $cancelurl,
		]);
		
		?>
		<script src="https://js.stripe.com/v3/"></script>
		<script>
		var stripe = Stripe('<?php echo $this->api_publisher_key; ?>');
		stripe.redirectToCheckout({
		  // Make the id field from the Checkout Session creation API response
		  // available to this file, so you can provide it as parameter here
		  // instead of the {{CHECKOUT_SESSION_ID}} placeholder.
		  sessionId: '<?php echo $session->id; ?>'
		}).then(function (result) {
		  // If `redirectToCheckout` fails due to a browser or network
		  // error, display the localized error message to your customer
		  // using `result.error.message`.
		});
		</script>
		<?php
	}
	
	function checkPaiement()
	{
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
		switch ($event->type) {
			case 'payment_intent.succeeded':
				$paymentIntent = $event->data->object; // contains a \Stripe\PaymentIntent
				handlePaymentIntentSucceeded($paymentIntent);
				break;
			case 'payment_method.attached':
				$paymentMethod = $event->data->object; // contains a \Stripe\PaymentMethod
				handlePaymentMethodAttached($paymentMethod);
				break;
			// ... handle other event types
			default:
				// Unexpected event type
				http_response_code(400);
				exit();
		}

		http_response_code(200);
	}
	
	/* Capture du paiement */
	function stripeCapturePaid()
	{
		$session_id = $_REQUEST['session_id'];
		echo "SESSION ID = $session_id";
		
		\Stripe\Stripe::setApiKey($this->api_secret_key);
		$object = \Stripe\Checkout\Session::retrieve($session_id);
		print_r($object);
	}
}

?>