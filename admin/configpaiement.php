<?php
include "../main.php";
include "version.php";

@session_start();

$SQL = "SELECT COUNT(*) FROM pas_admin_user WHERE username = '".$_SESSION['admin_username']."' AND password = '".$_SESSION['admin_password']."'";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

if($req[0] == 0)
{
	header("Location: index.php");
}

function br2nl($string)
{
	$string = str_replace("<br />","",$string);
	return $string;
}

if(isset($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
	
	/* Paypal */
	if($action == 1)
	{
		$paypal_activate = $_REQUEST['paypal_activate'];
		updateConfig("paypal_activate",$paypal_activate);
		
		$paypal_email = $_REQUEST['paypal_email'];
		updateConfig("paypal_email",$paypal_email);
	}
	
	/* Stripe */
	if($action == 2)
	{
		$stripe_publishable_key = $_REQUEST['stripe_publishable_key'];
		$stripe_secret_key = $_REQUEST['stripe_secret_key'];
		
		updateConfig("stripe_publishable_key",$stripe_publishable_key);
		updateConfig("stripe_secret_key",$stripe_secret_key);
		
		$stripe_activate = $_REQUEST['stripe_activate'];
		updateConfig("stripe_activate",$stripe_activate);
	}
	
	/* Cheque */
	if($action == 3)
	{
		$cheque_ordre = $_REQUEST['cheque_ordre'];
		updateConfig("cheque_ordre",$cheque_ordre);
		
		$cheque_activate = $_REQUEST['cheque_activate'];
		updateConfig("cheque_activate",$cheque_activate);
		
		$expedition_cheque = $_REQUEST['expedition_cheque'];
		$expedition_cheque = nl2br($expedition_cheque);
		updateConfig("expedition_cheque",$expedition_cheque);
	}
	
	/* Virement */
	if($action == 4)
	{
		$virement_activate = $_REQUEST['virement_activate'];
		updateConfig("virement_activate",$virement_activate);
		
		$virement_bancaire_instruction = $_REQUEST['virement_bancaire_instruction'];
		updateConfig("virement_bancaire_instruction",$virement_bancaire_instruction);
	}
	
	/* Paydunya */
	if($action == 5)
	{
		$paydunya_activate = $_REQUEST['paydunya_activate'];
		updateConfig("paydunya_activate",$paydunya_activate);
		
		$paydunya_cle_principal = $_REQUEST['paydunya_cle_principal'];
		updateConfig("paydunya_cle_principal",$paydunya_cle_principal);
		
		$paydunya_cle_publique = $_REQUEST['paydunya_cle_publique'];
		updateConfig("paydunya_cle_publique",$paydunya_cle_publique);
		
		$paydunya_cle_privee = $_REQUEST['paydunya_cle_privee'];
		updateConfig("paydunya_cle_privee",$paydunya_cle_privee);
		
		$paydunya_token = $_REQUEST['paydunya_token'];
		updateConfig("paydunya_token",$paydunya_token);
	}
	
	/* Obvy */
	if($action == 6)
	{
		$obvy_activate = $_REQUEST['obvy_activate'];
		updateConfig("obvy_activate",$obvy_activate);
		
		$obvy_api_key = $_REQUEST['obvy_api_key'];
		updateConfig("obvy_api_key",$obvy_api_key);
		
		header("Location: configpaiement.php");
		exit;
	}
	
	/* Emoney */
	if($action == 7)
	{
		$mobicash_activate = $_REQUEST['mobicash_activate'];
		updateConfig("mobicash_activate",$mobicash_activate);
		
		$mobicash_pays = $_REQUEST['mobicash_pays'];
		updateConfig("mobicash_pays",$mobicash_pays);
		
		$mobicash_phone = $_REQUEST['mobicash_phone'];
		updateConfig("mobicash_phone",$mobicash_phone);
		
		header("Location: configpaiement.php");
		exit;
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Administration PAS Script v<?php echo $version; ?></title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
	<script src="<?php echo $url_script; ?>/admin/js/sweetalert.js"></script>
</head>
<body>
	<?php
	
	include "header.php";
	include "sidebar.php";
	
	?>
	<div class="container">
		<H1>Configuration des systèmes de Paiement</H1>
		<div class="info">
		Depuis cette interface vous pourrez configurer les systèmes de paiement de votre site internet.
		</div>
		<H2><img src="images/paypal-icon.png"> Paypal</H2>
		<div class="help-video" onclick="showHelpVideo(1);">
			<i class="fas fa-video"></i>
			<div class="littletext">Aide video</div>
		</div>
		<div style="overflow:auto;">
			<div class="help-video-container" id="help-video-1" style="display:none;">
				<iframe width="400" height="222" src="https://www.youtube.com/embed/F4NSnwnY8Ho" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
			</div>
		</div>
		<script>
		function showHelpVideo(id)
		{
			var help = $('#help-video-'+id).css('display');
			if(help == 'none')
			{
				$('#help-video-'+id).css('display','block');
			}
			else
			{
				$('#help-video-'+id).css('display','none');
			}
		}
		</script>
		<p>
		Paypal est un système de paiement très utilisé sur internet, il vous permettra de recevoir de l'argent dans de multiples devises et permettre à l'utilisateur de payer par "Compte Paypal" ou par "Carte bancaire",
		les frais d'inscription et d'utilisation sont gratuits, sur chaque transaction est prélevé 3.5% + 0.25 € (en commission). Vous pouvez effectuer des demandes de virement vers votre compte bancaire depuis votre compte Paypal, il faut généralement 1 à 2 jours ouvrés pour le recevoir sur votre compte bancaire. Pour vous inscrire <br><br><a href="http://www.paypal.fr" class="btn blue" target="newpage">Créer un compte Paypal</a><br><br>
		</p>
		<form>
		<?php
		if(getConfig("paypal_activate") == 'yes')
		{
			?>
			<input type="checkbox" name="paypal_activate" value="yes" checked> <b>Activer les paiements Paypal sur votre site internet</b><br><br>
			<?php
		}
		else
		{
			?>
			<input type="checkbox" name="paypal_activate" value="yes"> <b>Activer les paiements Paypal sur votre site internet</b><br><br>
			<?php
		}
		?>
		<label><b>Adresse email de votre compte Paypal :</b></label>
		<input type="text" name="paypal_email" placeholder="Email de votre compte Paypal pour les paiements" value="<?php echo getConfig("paypal_email"); ?>" class="inputbox">
		<input type="hidden" name="action" value="1">
		<input type="submit" value="Modifier" class="btn blue">
		</form>
		<hr>
		<H2><img src="images/stripe-icon.png"> Stripe</H2>
		<a href="<?php echo $url_script; ?>/admin/help.php?page=stripe_configuration" style="color:#000000;" title="Tutoriel">
			<div class="help-video">
				<i class="fas fa-file"></i>
				<div class="littletext">Tutoriel</div>
			</div>
		</a>
		<p>
		Stripe est un système de paiement qui vous permettra de faire payer vos utilisateurs par carte Bancaire, contrairement à Paypal, le paiement s'effectue uniquement par Carte Bancaire et les numéros de carte sont demandés après redirection
		sur la plateforme de Stripe. Leur système est 100% sécurisé, l'inscription est totalement gratuite, sur chaque transaction est prélevé 2.9% + 0.30 € (en commission). Vous pouvez effectuer des demandes de virement depuis votre compte Stripe où ils seront automatiquement reversés sur votre compte bancaire sous 7 jours. Pour vous inscrire <br><br><a href="http://www.stripe.com" class="btn blue" target="newpage">Créer un compte Stripe</a><br><br>
		</p>
		<form>
		<?php
		if(getConfig("stripe_activate") == 'yes')
		{
			?>
			<input type="checkbox" name="stripe_activate" value="yes" checked> <b>Activer les paiements Stripe sur votre site internet</b><br><br>
			<?php
		}
		else
		{
			?>
			<input type="checkbox" name="stripe_activate" value="yes"> <b>Activer les paiements Stripe sur votre site internet</b><br><br>
			<?php
		}
		?>
		<label><b>Stripe API Clé publique :</b></label>
		<input type="text" name="stripe_publishable_key" placeholder="Entrer la clé publique stripe situer dans le menu developpeurs de votre compte dans Clé API" value="<?php echo getConfig("stripe_publishable_key"); ?>" class="inputbox">
		<label><b>Stripe API Clé secrète :</b></label>
		<input type="text" name="stripe_secret_key" placeholder="Entrer la clé secrète stripe situer dans le menu developpeurs de votre compte dans Clé API" value="<?php echo getConfig("stripe_secret_key"); ?>" class="inputbox">
		<input type="hidden" name="action" value="2">
		<input type="submit" value="Modifier" class="btn blue">
		</form>
		<hr>
		<H2><img src="images/mobile-money-icon.png"> Emoney</H2>
		<div class="help-video" onclick="showHelpVideo(2);">
			<i class="fas fa-video"></i>
			<div class="littletext">Aide video</div>
		</div>
		<div style="overflow:auto;">
			<div class="help-video-container" id="help-video-2" style="display:none;">
				<iframe width="400" height="222" src="https://www.youtube.com/embed/6kZH0G3NEVE" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
			</div>
		</div>
		<p>
		Les services de Emoney est un service utiliser dans les pays d'Afrique et de Maghreb pour Payer par SMS. PAS Script integre plusieurs système de paiement Emoney pour réaliser les paiements des options
		des annonces et autres. Toute les transaction effectuer par ce biais et annonce déposer par vos utilisateurs qui doivent réaliser un paiement, il leur sera proposer d'effectuer le paiement depuis leur
		téléphone. Vous recevrez un SMS de paiement du service proposer que vous aurez selectionner, vous pourrez valider le paiement manuellement depuis l'onglet "Paiement" puis "Transaction & Chiffre d'affaire".
		</p>
		<form>
		<?php
		if(getConfig("mobicash_activate") == 'yes')
		{
			?>
			<input type="checkbox" name="mobicash_activate" value="yes" checked> <b>Activer les paiements Emoney sur votre site internet</b><br><br>
			<?php
		}
		else
		{
			?>
			<input type="checkbox" name="mobicash_activate" value="yes"> <b>Activer les paiements Emoney sur votre site internet</b><br><br>
			<?php
		}
		?>
			<label><b>Votre numéro de téléphone pour recevoir les paiements Emoney</b></label>
			<input type="text" name="mobicash_phone" value="<?php echo getConfig("mobicash_phone"); ?>" class="inputbox" placeholder="Numéro de téléphone mobicash">
			<label><b>Système de paiement :</b></label>
			<select name="mobicash_pays" class="inputbox">
			<?php
			if(getConfig("mobicash_pays") == 'TG_MOOV_MONEY')
			{
				?>
				<option value="TG_MOOV_MONEY" selected>Moov Flooz (Togo)</option>
				<?php
			}
			else
			{
				?>
				<option value="TG_MOOV_MONEY">Moov Flooz (Togo)</option>
				<?php
			}
			if(getConfig("mobicash_pays") == 'BN_MOOV_MONEY')
			{
				?>
				<option value="BN_MOOV_MONEY" selected>Moov Money (Benin)</option>
				<?php
			}
			else
			{
				?>
				<option value="BN_MOOV_MONEY">Moov Money (Benin)</option>
				<?php
			}
			if(getConfig("mobicash_pays") == 'BF')
			{
				?>
				<option value="BF" selected>Mobicash (Burkina-Faso) - Onatel</option>
				<?php
			}
			else
			{
				?>
				<option value="BF">Mobicash (Burkina-Faso) - Onatel</option>
				<?php
			}
			if(getConfig("mobicash_pays") == 'ML')
			{
				?>
				<option value="ML" selected>Mobicash (Mali) - Malitel</option>
				<?php
			}
			else
			{
				?>
				<option value="ML">Mobicash (Mali) - Malitel</option>
				<?php
			}
			if(getConfig("mobicash_pays") == 'GA')
			{
				?>
				<option value="GA" selected>Mobicash (Gabon)</option>
				<?php
			}
			else
			{
				?>
				<option value="GA">Mobicash (Gabon)</option>
				<?php
			}
			?>
			</select>
			<input type="hidden" name="action" value="7">
			<input type="submit" value="Modifier" class="btn blue">
		</form>
		<hr>
		<H2><img src="images/paydunya-icon.png"> Paydunya</H2>
		<p>
		Paydunya est un système de paiement pour le Sénégal, il permet d'encaisser des paiements mobiles sur Orange Money, Joni Joni et Vitfè, la transaction s'effectue en deux étapes
		le client pour payer sa facture devra fournir son numéro de téléphone, à la validation de l'achat il recevra un code par SMS pour finaliser la transaction. Le transfert effectué
		se retrouvera sur votre compte Paydunya et pourra être retiré sur un compte bancaire Sénégalais ou Autre. Le paiement s'exprime en Francs CFA et 100 FCFA sont demandés à chaque
		retrait sur un compte bancaire.
		<br><br>
		<a href="http://www.paydunya.com" class="btn blue">Créer un compte Paydunya</a>
		<br><br>
		</p>
		<form>
		<?php
		if(getConfig("paydunya_activate") == 'yes')
		{
			?>
			<input type="checkbox" name="paydunya_activate" value="yes" checked> <b>Activer les paiements Paydunya sur votre site internet</b><br><br>
			<?php
		}
		else
		{
			?>
			<input type="checkbox" name="paydunya_activate" value="yes"> <b>Activer les paiements Paydunya sur votre site internet</b><br><br>
			<?php
		}
		?>
		<label><b>Paydunya Clé principal API :</b></label>
		<input type="text" name="paydunya_cle_principal" placeholder="Entrer la clé principal de votre compte Paydunya" value="<?php echo getConfig("paydunya_cle_principal"); ?>" class="inputbox">
		<label><b>Paydunya Clé publique API :</b></label>
		<input type="text" name="paydunya_cle_publique" placeholder="Entrer la clé publique de votre compte Paydunya" value="<?php echo getConfig("paydunya_cle_publique"); ?>" class="inputbox">
		<label><b>Paydunya Clé privée API :</b></label>
		<input type="text" name="paydunya_cle_privee" placeholder="Entrer la clé privée de votre compte Paydunya" value="<?php echo getConfig("paydunya_cle_privee"); ?>" class="inputbox">
		<label><b>Paydunya Token API :</b></label>
		<input type="text" name="paydunya_token" placeholder="Entrer le token de votre compte Paydunya" value="<?php echo getConfig("paydunya_token"); ?>" class="inputbox">
		<input type="hidden" name="action" value="5">
		<input type="submit" value="Modifier" class="btn blue">
		</form>
		<hr>
		<H2><img src="images/obvy-icon.png"> Obvy</H2>
		<div class="help-video" onclick="showHelpVideo(3);">
			<i class="fas fa-video"></i>
			<div class="littletext">Aide video</div>
		</div>
		<div style="overflow:auto;">
			<div class="help-video-container" id="help-video-3" style="display:none;">
				<iframe width="400" height="222" src="https://www.youtube.com/embed/_ktBHUGsj60" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
			</div>
		</div>
		<p>
		Obvy est un système de paiement sécurisé qui permet au internaute de payer directement un objet vendu sur votre site internet, l'internaute à la possibilité de regler par carte bancaire et
		d'effectuer des propositions de prix (si cette option est activée sur Obvy). Pour utiliser Obvy avec PAS Script vous devrez possèdez un compte et indiquer la Clé API de votre compte. Obvy
		et utilisable uniquement en EUR pour les transactions Européenne. Une retro-commision sur chaque transaction est effectuer par Obvy et vous sera retribuer sur votre compte Obvy dans le tableau de bord.
		</p>
		<a href="https://www.obvy-app.com/sites-petites-annonces-prestations" target="newpage" class="btn blue">Créer un compte Obvy</a>
		<br><br>
		<form>
			<?php
			if(getConfig("obvy_activate") == 'yes')
			{
				?>
				<input type="checkbox" name="obvy_activate" value="yes" checked> <b>Activer les paiements Obvy sur votre site internet</b><br><br>
				<?php
			}
			else
			{
				?>
				<input type="checkbox" name="obvy_activate" value="yes"> <b>Activer les paiements Obvy sur votre site internet</b><br><br>
				<?php
			}
			?>
			<label><b>Obvy Clé API :</b></label>
			<input type="text" name="obvy_api_key" value="<?php echo getConfig("obvy_api_key"); ?>" placeholder="Entrer la clé API de votre compte Obvy Partners" class="inputbox">
			<input type="hidden" name="action" value="6">
			<input type="submit" value="Modifier" class="btn blue">
		</form>
		<hr>
		<H2><img src="images/cheque-icon.png"> Paiement par chèque</H2>
		<p>
		Accepter les paiements par chèque depuis votre site internet, des informations spécifiques seront indiquées à l'utilisateur pour indiquer l'ordre, l'adresse et le bénéficiaire pour régler sa commande, les annonces payées
		par ce biais seront en attente de validation.
		</p>
		<form>
		<?php
		if(getConfig("cheque_activate") == 'yes')
		{
			?>
			<input type="checkbox" name="cheque_activate" value="yes" checked> <b>Activer les paiements par Chèque sur votre site internet</b><br><br>
			<?php
		}
		else
		{
			?>
			<input type="checkbox" name="cheque_activate" value="yes"> <b>Activer les paiements par Chèque sur votre site internet</b><br><br>
			<?php
		}
		
		?>
		<label><b>Chèque à l'ordre de :</b></label>
		<input type="text" name="cheque_ordre" class="inputbox" value="<?php echo getConfig("cheque_ordre"); ?>" placeholder="Veuillez indiquez l'ordre du chèque">
		<label><b>Expedition du chèque à l'adresse :</b></label>
		<textarea class="textareabox" name="expedition_cheque"><?php echo br2nl(getConfig("expedition_cheque")); ?></textarea>
		<input type="hidden" name="action" value="3">
		<input type="submit" value="Modifier" class="btn blue">
		</form>
		<hr>
		<H2><img src="images/virement-icon.png"> Paiement par virement bancaire</H2>
		<p>
		Accepter les paiements par virement bancaire depuis votre site internet, des informations spécifiques seront indiquées à l'utilisateur pour effectuer son virement, les annonces payées
		par ce biais seront en attente de validation.
		</p>
		<form>
		<?php
		if(getConfig("virement_activate") == 'yes')
		{
			?>
			<input type="checkbox" name="virement_activate" value="yes" checked> <b>Activer les paiements par Virement bancaire sur votre site internet</b><br><br>
			<?php
		}
		else
		{
			?>
			<input type="checkbox" name="virement_activate" value="yes"> <b>Activer les paiements par Virement bancaire sur votre site internet</b><br><br>
			<?php
		}
		
		?>
		<textarea class="textareabox" name="virement_bancaire_instruction"><?php echo getConfig("virement_bancaire_instruction"); ?></textarea>
		<input type="hidden" name="action" value="4">
		<input type="submit" value="Modifier" class="btn blue">
		</form>
	</div>
</body>
</html>