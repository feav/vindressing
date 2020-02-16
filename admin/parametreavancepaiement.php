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

if(isset($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
	
	/* Changement monétaire */
	if($action == 1)
	{
		$currency = $_REQUEST['currency'];
		updateConfig("currency",$currency);
		
		$currency_show = $_REQUEST['currency_show'];
		updateConfig("currency_show",$currency_show);
		
		$currency_position = $_REQUEST['currency_position'];
		updateConfig("currency_position",$currency_position);
		
		header("Location: parametreavancepaiement.php");
		exit;
	}
		
	/* Compte professionel */
	if($action == 4)
	{
		$compte_pro_payant = $_REQUEST['compte_pro_payant'];
		if($compte_pro_payant == 'yes')
		{
			$compte_pro_payant = 'yes';
		}
		else
		{
			$compte_pro_payant = 'no';
		}
		
		$compte_pro_type_paiement = $_REQUEST['compte_pro_type_paiement'];
		updateConfig("compte_pro_type_paiement",$compte_pro_type_paiement);
		
		updateConfig("compte_pro_payant",$compte_pro_payant);
		
		$pro_paiement_price_unique = $_REQUEST['pro_paiement_price_unique'];
		updateConfig("pro_paiement_price_unique",$pro_paiement_price_unique);
		
		$pro_paiement_price_mois = $_REQUEST['pro_paiement_price_mois'];
		updateConfig("pro_paiement_price_mois",$pro_paiement_price_mois);
		
		$pro_paiement_price_annonces = $_REQUEST['pro_paiement_price_annonces'];
		updateConfig("pro_paiement_price_annonces",$pro_paiement_price_annonces);
		
		header("Location: parametreavancepaiement.php");
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
</head>
<body>
	<?php
	
	include "header.php";
	include "sidebar.php";
	
	?>
	<div class="container">
		<H1>Paramètres avancés des paiements</H1>
		<div class="info">
		Depuis cette interface vous pourrez régler tous les aspect monétaire des paiements.
		</div>
		<H2>Réglages monétaires</H2>
		<label>Transaction sur le site en :</label>
		<form>
		<select name="currency" class="inputbox">
		<?php
		$currency = getConfig("currency");
		$allcurrency = $class_monetaire->getAllCurrencySupported();
		for($x=0;$x<count($allcurrency);$x++)
		{
			if($currency == $allcurrency[$x]['currency'])
			{
				?>
				<option value="<?php echo $allcurrency[$x]['currency']; ?>" selected><?php echo $allcurrency[$x]['titre']; ?> (<?php echo $allcurrency[$x]['sigle']; ?>)</option>
				<?php
			}
			else
			{
				?>
				<option value="<?php echo $allcurrency[$x]['currency']; ?>"><?php echo $allcurrency[$x]['titre']; ?> (<?php echo $allcurrency[$x]['sigle']; ?>)</option>
				<?php
			}
		}
		?>
		</select>
		<label>Affichage des prix :</label>
		<select name="currency_show" class="inputbox">
		<?php
		if(getConfig("currency_show") == 1)
		{
			?>
			<option value="1" selected>10000 €</option>
			<?php
		}
		else
		{
			?>
			<option value="1">10000 €</option>
			<?php
		}
		if(getConfig("currency_show") == 2)
		{
			?>
			<option value="2" selected>10000,00 €</option>
			<?php
		}
		else
		{
			?>
			<option value="2">10000,00 €</option>
			<?php
		}
		if(getConfig("currency_show") == 3)
		{
			?>
			<option value="3" selected>10000.00 €</option>
			<?php
		}
		else
		{
			?>
			<option value="3">10000.00 €</option>
			<?php
		}
		if(getConfig("currency_show") == 4)
		{
			?>
			<option value="4" selected>10 000 €</option>
			<?php
		}
		else
		{
			?>
			<option value="4">10 000 €</option>
			<?php
		}
		if(getConfig("currency_show") == 5)
		{
			?>
			<option value="5" selected>10 000,00 €</option>
			<?php
		}
		else
		{
			?>
			<option value="5">10 000,00 €</option>
			<?php
		}
		if(getConfig("currency_show") == 6)
		{
			?>
			<option value="6" selected>10 000.00 €</option>
			<?php
		}
		else
		{
			?>
			<option value="6">10 000.00 €</option>
			<?php
		}
		?>
		</select>
		<label>Position de la devise :</label>
		<select name="currency_position" class="inputbox">
		<?php
		if(getConfig("currency_position") == 1)
		{
			?>
			<option value="1" selected>A gauche (€ 100)</option>
			<option value="2">A droite (100 €)</option>
			<?php
		}
		else
		{
			?>
			<option value="1">A gauche (€ 100)</option>
			<option value="2" selected>A droite (100 €)</option>
			<?php
		}
		?>
		</select>
		<input type="hidden" name="action" value="1">
		<div style="margin-top:20px;margin-bottom:20px;">
			<input type="submit" value="Modifier" class="btn blue">
		</div>
		</form>
		<HR>
		<H2>Tarif des compte professionel</H2>
		<form method="POST">
		<?php
		if(getConfig("compte_pro_payant") == 'yes')
		{
			$display_pro = 'display:block;';
			?>
			<input type="checkbox" name="compte_pro_payant" value="yes" onchange="updatePayantPro();" checked> Compte professionel Payant<br>
			<?php
		}
		else
		{
			$display_pro = 'display:none;';
			?>
			<input type="checkbox" name="compte_pro_payant" value="yes" onchange="updatePayantPro();"> Compte professionel Payant<br>
			<?php
		}
		?>
		<div id="compte_pro_payant" style="<?php echo $display_pro; ?>">
			<select name="compte_pro_type_paiement" class="inputbox" id="compte_pro_payant_select" onchange="updatePrice();">
			<?php
			if(getConfig("compte_pro_type_paiement") == 'paiement_unique')
			{
				?>
				<option value="paiement_unique" selected>Forfait à paiement unique</option>
				<?php
			}
			else
			{
				?>
				<option value="paiement_unique">Forfait à paiement unique</option>
				<?php
			}
			if(getConfig("compte_pro_type_paiement") == 'paiement_mensuel')
			{
				?>
				<option value="paiement_mensuel" selected>Forfait à paiement mensuelle</option>
				<?php
			}
			else
			{
				?>
				<option value="paiement_mensuel">Forfait à paiement mensuelle</option>
				<?php
			}
			if(getConfig("compte_pro_type_paiement") == 'paiement_annonce')
			{
				?>
				<option value="paiement_annonce" selected>Gratuit mais toute les annonces sont payante</option>
				<?php
			}
			else
			{
				?>
				<option value="paiement_annonce">Gratuit mais toute les annonces sont payante</option>
				<?php
			}
			?>
			</select>
			<div id="price_unique">
				<label>Prix du paiement unique :</label>
				<input type="text" name="pro_paiement_price_unique" value="<?php echo getConfig("pro_paiement_price_unique"); ?>" class="inputbox">
			</div>
			<div id="price_mensuelle" style="display:none;">
				<label>Prix du paiement mensuelle :</label>
				<input type="text" name="pro_paiement_price_mois" value="<?php echo getConfig("pro_paiement_price_mois"); ?>" class="inputbox">
			</div>
			<div id="price_annonce_pro" style="display:none;">
				<label>Prix des annonces :</label>
				<input type="text" name="pro_paiement_price_annonces" value="<?php echo getConfig("pro_paiement_price_annonces"); ?>" class="inputbox">
			</div>
		</div>
		<input type="hidden" name="action" value="4">
		<div style="margin-top:20px;margin-bottom:20px;">
		<input type="submit" value="Modifier" class="btn blue">
		</div>
		</form>
		<script>
		function updatePrice()
		{
			var selection = $('#compte_pro_payant_select').val();
			if(selection == 'paiement_unique')
			{
				$('#price_unique').css('display','block');
				$('#price_mensuelle').css('display','none');
				$('#price_annonce_pro').css('display','none');
			}
			else if(selection == 'paiement_mensuel')
			{
				$('#price_unique').css('display','none');
				$('#price_mensuelle').css('display','block');
				$('#price_annonce_pro').css('display','none');
			}
			else
			{
				$('#price_unique').css('display','none');
				$('#price_mensuelle').css('display','none');
				$('#price_annonce_pro').css('display','block');
			}
		}
		
		function updatePayantPro()
		{
			if($('#compte_pro_payant').css('display') == 'none')
			{
				$('#compte_pro_payant').css('display','block');
			}
			else
			{
				$('#compte_pro_payant').css('display','none');
			}
		}
		
		updatePrice();
		</script>
	</div>
</body>
</html>