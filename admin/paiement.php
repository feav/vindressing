<?php
include "../config.php";
include "../engine/class.monetaire.php";
include "version.php";

$monetaire = new Monetaire();
$sigle = $monetaire->getSigle();
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
	if($action == 1)
	{
		$id = $_REQUEST['id'];
		$SQL = "DELETE FROM pas_paiement WHERE id = $id";
		$pdo->query($SQL);
		
		header("Location: paiement.php");
		exit;
	}
	
	/* Marquer un paiement comme Payer (Et activer les options manuellement) */
	if($action == 6)
	{
		$idtransaction = $_REQUEST['id'];
		$type = $_REQUEST['type'];
		$SQL = "SELECT * FROM pas_paiement WHERE id = $idtransaction";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		$md5 = $req['md5'];
		
		$SQL = "SELECT * FROM pas_annonce WHERE md5 = '$md5'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		$idannonce = $req['id'];
		
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
		
		/* On fini par valider l'annonce */
		$SQL = "UPDATE pas_annonce SET valider = 'yes' WHERE id = $idannonce";
		$pdo->query($SQL);
		
		if($type == 'emoney')
		{
			$SQL = "UPDATE pas_annonce SET status = 'PAID_MOBICASH' WHERE id = $idannonce";
			$pdo->query($SQL);
		
			/* On update le paiement */
			$SQL = "UPDATE pas_paiement SET type_paiement = 'mobicash_payer' WHERE id = $idtransaction";
			$pdo->query($SQL);
		}
		if($type == 'virement')
		{
			$SQL = "UPDATE pas_annonce SET status = 'PAID_VIREMENT' WHERE id = $idannonce";
			$pdo->query($SQL);
		
			/* On update le paiement */
			$SQL = "UPDATE pas_paiement SET type_paiement = 'virement_payer' WHERE id = $idtransaction";
			$pdo->query($SQL);
		}
		if($type == 'cheque')
		{
			$SQL = "UPDATE pas_annonce SET status = 'PAID_CHECK' WHERE id = $idannonce";
			$pdo->query($SQL);
		
			/* On update le paiement */
			$SQL = "UPDATE pas_paiement SET type_paiement = 'cheque_payer' WHERE id = $idtransaction";
			$pdo->query($SQL);
		}
		
		header("Location: paiement.php");
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
</head>
<body>
<?php	

include "header.php";
include "sidebar.php";

?>
<style>	
td
{
	font-size:14px;
	height:30px;
}
.bButton
{
	height: 38px;
	margin-top: 5px;
	margin-bottom: 5px;
}
.boxChiffre
{
	float:left;
	width: 30%;
	padding: 20px;
	box-sizing: border-box;
	background-color: #00aeff;
	border-radius: 5px;
	margin-bottom: 20px;
	color: #ffffff;
	margin-right:3.33%;
}
.ChiffreAffaire
{
	font-size: 48px;
	color: #ffffff;
}
.boxStatistique
{
	overflow:auto;
}
.greenvente
{
	background-color:#049b10;
}
.orangevente
{
	background-color:#ffb300;
}
</style>
<div class="container">
	<H1>Paiement</H1>
	<div class="info">
	Retrouvez sur cette page tous les réglements effectués sur votre site internet.
	</div>
	<?php
	
	$total = 0;
	$nbr = 0;
	$SQL = "SELECT * FROM pas_paiement";
	$reponse = $pdo->query($SQL);
	while($req = $reponse->fetch())
	{
		$total = $total + $req['montant'];
		$nbr = $nbr + 1;
	}
	$SQL = "SELECT * FROM pas_paiement ORDER BY id DESC LIMIT 1";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	$montantlast = $req['montant'];
	?>
	<div class="boxStatistique">
		<div class="boxChiffre">
			<div class="ChiffreAffaire">
			<?php echo number_format($total,2,",",""); ?> <?php echo $sigle; ?>
			</div>
			<div class="Chiffretexte">Chiffre d'affaire</div>
		</div>
		<div class="boxChiffre orangevente">
			<div class="ChiffreAffaire">
			<?php echo $nbr; ?>
			</div>
			<div class="Chiffretexte">
			Nombre de ventes
			</div>
		</div>
		<div class="boxChiffre greenvente">
			<div class="ChiffreAffaire">
			<?php echo number_format($montantlast,2,",",""); ?> <?php echo $sigle; ?>
			</div>
			<div class="Chiffretexte">
			Dernière ventes
			</div>
		</div>
	</div>
	<table>
		<tr>
			<th>Date</th>
			<th>N° Transaction</th>
			<th>Email</th>
			<th>Montant</th>
			<th>Type</th>
			<th>Statut</th>
			<th>Action</th>
		</tr>
		<?php
			$SQL = "SELECT COUNT(*) FROM pas_paiement";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			if($req[0] == 0)
			{
			?>
			</table>
			<center><H1>Aucune transaction pour le moment</H1></center>
			<?php
			}
			else
			{
				$SQL = "SELECT * FROM pas_paiement ORDER BY id DESC";
				$reponse = $pdo->query($SQL);
				while($req = $reponse->fetch())
				{
					?>
					<tr>
						<td>
						<?php 
						$date_paiement = $req['date_paiement']; 
						$date_paiement = explode(" ",$date_paiement);
						$heure_paiement = $date_paiement[1];
						$date_paiement = $date_paiement[0];
						
						$date_paiement = explode("-",$date_paiement);
						
						echo "<b>".$date_paiement[2]."/".$date_paiement[1]."/".$date_paiement[0]." à ".$heure_paiement."</b>";
						?>
						</td>
						<td><?php echo $req['id_transaction']; ?></td>
						<td><a href="mailto:<?php echo $req['email_user']; ?>" target="_blank" style="color:#000000;"><?php echo $req['email_user']; ?></a></td>
						<td><b><font color=green><?php echo number_format($req['montant'],2,",",""); ?> <?php echo $sigle; ?></b></font></td>
						<td style="font-size:20px;">
						<?php 
						if($req['type_paiement'] == 'paypal')
						{
							?>
							<i class="fab fa-paypal" title="Payer avec Paypal"></i>
							<?php
						}
						else if($req['type_paiement'] == 'stripe')
						{
							?>
							<i class="fab fa-stripe" title="Payer avec Stripe"></i>
							<?php
						}
						else if($req['type_paiement'] == 'cheque')
						{
							?>
							<i class="fas fa-money-check" title="En attente de réglement par Chèque"></i>
							<?php
						}
						else if($req['type_paiement'] == 'cheque_payer')
						{
							?>
							<i class="fas fa-money-check" title="Payer par Chèque"></i>
							<?php
						}
						else if($req['type_paiement'] == 'virement')
						{
							?>
							<i class="fas fa-university" title="En attente de validation par Virement Bancaire"></i>
							<?php
						}
						else if($req['type_paiement'] == 'virement_payer')
						{
							?>
							<i class="fas fa-university" title="Payer par Virement Bancaire"></i>
							<?php
						}
						else if($req['type_paiement'] == 'mobicash')
						{
							?>
							<i class="fas fa-mobile-alt" title="En attente de validation par Emoney"></i>
							<?php
						}
						else if($req['type_paiement'] == 'mobicash_payer')
						{
							?>
							<i class="fas fa-mobile-alt" title="Payer par Emoney"></i>
							<?php
						}
						else
						{
							echo $req['type_paiement'];
						}
						?>
						</td>
						<td style="font-size:10px;">
						<?php 
						if($req['type_paiement'] == 'paypal')
						{
							?>
							Payer avec Paypal
							<?php
						}
						else if($req['type_paiement'] == 'stripe')
						{
							?>
							Payer avec Stripe
							<?php
						}
						else if($req['type_paiement'] == 'cheque')
						{
							?>
							En attente de reglement par Chèque
							<?php
						}
						else if($req['type_paiement'] == 'cheque_payer')
						{
							?>
							Payer par Chèque
							<?php
						}
						else if($req['type_paiement'] == 'virement')
						{
							?>
							En attente de validation par Virement Bancaire
							<?php
						}
						else if($req['type_paiement'] == 'virement_payer')
						{
							?>
							Payer par Virement Bancaire
							<?php
						}
						else if($req['type_paiement'] == 'mobicash')
						{
							?>
							En attente de validation par Emoney
							<?php
						}
						else if($req['type_paiement'] == 'mobicash_payer')
						{
							?>
							Payer par Emoney
							<?php
						}
						else
						{
							echo $req['type_paiement'];
						}
						?>
						</td>
						<td>
							<a href="paiement.php?id=<?php echo $req['id']; ?>&action=1" class="btn red">Supprimer</a>
							<?php
							if($req['type_paiement'] == 'mobicash')
							{
								?>
								<a href="paiement.php?id=<?php echo $req['id']; ?>&action=6&type=emoney" class="btn green">Paiement reçu</a>
								<?php
							}
							if($req['type_paiement'] == 'virement')
							{
								?>
								<a href="paiement.php?id=<?php echo $req['id']; ?>&action=6&type=virement" class="btn green">Paiement reçu</a>
								<?php
							}
							if($req['type_paiement'] == 'cheque')
							{
								?>
								<a href="paiement.php?id=<?php echo $req['id']; ?>&action=6&type=cheque" class="btn green">Paiement reçu</a>
								<?php
							}
							?>
							<a href="annonce.php?md5=<?php echo $req['md5']; ?>&action=5" class="btn blue">Voir l'annonce</a>
						</td>
					</tr>
					<?php
				}
				?>
				</table>
				<?php
			}
			?>
			</div>
		</body>
	</html>