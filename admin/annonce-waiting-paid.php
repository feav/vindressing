<?php

include "../config.php";
include "../engine/class.monetaire.php";
include "../engine/class.mail.php";
include "../engine/class.fuseau.horaire.php";
include "version.php";

$monetaire = new Monetaire();
$sigle = $monetaire->getSigle();

$class_email = new Email();
$class_fuseau_horaire = new FuseauHoraire();

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
}
else
{
	$action = 0;
}

/* Marquer un paiement comme Payer (Et activer les options manuellement) */
if($action == 6)
{
	$md5 = $_REQUEST['md5'];	
	$type = $_REQUEST['type'];
	
	$SQL = "SELECT * FROM pas_paiement WHERE md5 = '$md5'";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	$idtransaction = $req['id'];
	
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
	
	header("Location: annonce-waiting-paid.php");
	exit;
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
	<div class="container">
	<?php
			$SQL = "SELECT COUNT(*) FROM pas_annonce WHERE status = 'WAITING_PAID' OR status = 'WAIT_CHEQUE' OR status = 'WAIT_VIREMENT' OR status = 'WAIT_MOBICASH'";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$count = $req[0];
			
			?>
			<H1><div class="round-count"><?php echo $count; ?></div> Gestion des annonces en attente de paiement</H1>
			<style>
			.englobehelp
			{
				display:initial;
			}
			
			.help-video
			{
				float: right;
				margin-top: -65px;
				font-size: 25px;
				text-align: center;
			}
			
			.littletext
			{
				font-size: 12px;
				font-weight: bold;
			}
			
			.help-video-container
			{
				padding: 10px;
				background-color: #323131;
				width: 400px;
				border-radius: 5px;
				margin-bottom: 10px;
				float: right;
			}
			
			.tabs-annonce
			{
				float: left;
				border-top-left-radius: 5px;
				border-top-right-radius: 5px;
				background-color: #96d2ff;
				font-weight: bold;
				font-size: 12px;
				padding-top: 10px;
				padding-bottom: 10px;
				padding-left: 20px;
				padding-right: 20px;
				margin-right: 3px;
			}
			
			.tabs-annonce:hover
			{
				background-color:#82b6dd;
			}
			
			.tabs-selected
			{
				background-color:#82b6dd;
			}
			
			.roundsticky
			{
				background-color: #ff9300;
				border-radius: 13px;
				padding-left: 5px;
				padding-right: 6px;
				color: #fff;
			}
			
			th
			{
				background-color:#78A9CE;
			}
			</style>
			<div class="help-video" onclick="showHelpVideo();">
			<i class="fas fa-video"></i>
			<div class="littletext">Aide video</div>
			</div>
			<div style="overflow:auto;">
				<div class="help-video-container" id="help-video" style="display:none;">
					<video width="400" height="222" controls="controls">
					  <source src="https://www.shua-creation.com/video/pas_script_gestion_annonces.mp4.filepart" type="video/mp4" />
					</video>
				</div>
			</div>
			<script>
			function showHelpVideo()
			{
				var help = $('#help-video').css('display');
				if(help == 'none')
				{
					$('#help-video').css('display','block');
				}
				else
				{
					$('#help-video').css('display','none');
				}
			}
			</script>
			<div class="info">
			Gérer les annonces en attente de réglement sur votre site internet. Les annonces en attente de réglement sont des annonces qui n'ont pas été payer des options on été choisi par l'utilisateur mais il n'as pas finaliser l'achat de celui-ci ou leur réglement en cours de reception pour les virement bancaire, chèque, etc...
			</div>
			<?php
			
			if(isset($_REQUEST['search']))
			{
				$search = $_REQUEST['search'];
				$addSearch = "WHERE titre LIKE '%$search%' AND status = 'WAITING_PAID' OR status = 'WAIT_CHEQUE' OR status = 'WAIT_VIREMENT' OR status = 'WAIT_MOBICASH'";
			}
			else
			{
				$addSearch = "WHERE status = 'WAITING_PAID' OR status = 'WAIT_CHEQUE' OR status = 'WAIT_VIREMENT' OR status = 'WAIT_MOBICASH'";
			}
			
			?>
			<div class="search-bar-user">
				<form>
					<input type="text" name="search" placeholder="Titre de l'annonce rechercher ?" value="<?php echo $search; ?>" class="input-search-user">
					<input type="submit" value="Rechercher" class="btn blue">
				</form>
			</div>
			<?php
			
			$SQL = "SELECT COUNT(*) FROM pas_annonce WHERE valider = 'no' AND status = 'FREE'";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$count_a_valider = $req[0];
			
			$SQL = "SELECT COUNT(*) FROM pas_annonce WHERE valider = 'expired'";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$count_expired = $req[0];
			
			$SQL = "SELECT COUNT(*) FROM pas_annonce WHERE valider = 'yes'";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$count = $req[0];
			
			$SQL = "SELECT COUNT(*) FROM pas_annonce WHERE status = 'WAITING_PAID' OR status = 'WAIT_CHEQUE' OR status = 'WAIT_VIREMENT' OR status = 'WAIT_MOBICASH'";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$count_waiting_paid = $req[0];
			
			?>
			<a href="annonce-a-valider.php">
				<div class="tabs-annonce">
					<span class="roundsticky"><?php echo $count_a_valider; ?></span> Annonce à valider
				</div>
			</a>
			<a href="annonce-expired.php">
				<div class="tabs-annonce">
					<span class="roundsticky"><?php echo $count_expired; ?></span> Annonce expirer
				</div>
			</a>
			<a href="annonce-waiting-paid.php">
				<div class="tabs-annonce tabs-selected">
					<span class="roundsticky"><?php echo $count_waiting_paid; ?></span> Annonce en attente de paiement
				</div>
			</a>
			<a href="annonce.php">
				<div class="tabs-annonce">
					<span class="roundsticky"><?php echo $count; ?></span> Toutes les annonces
				</div>
			</a>
			<table>
				<tr>
					<th>Date de soumission</th>
					<th>Valider</th>
					<th>Type d'Offre</th>
					<th>Titre</th>
					<th>Status</th>
					<th>Action</th>
				</tr>
				<?php
				
				if($count_waiting_paid == 0)
				{
					?>
					</table>
					<div style="text-align: center;padding-top: 150px;padding-bottom: 150px;background-color: #fff;">
					<h1>Aucune annonce en attente de réglement pour le moment</h1>
					</div>
					<?php
				}
				else
				{
				
					if(isset($_REQUEST['page']))
					{
						$page = $_REQUEST['page'];
					}
					else
					{
						$page = 0;
					}
					
					$nombre_annonce = 20;
					$totalp = $page * $nombre_annonce;
					
					$SQL = "SELECT COUNT(*) FROM pas_annonce $addSearch";
					$reponse = $pdo->query($SQL);
					$req = $reponse->fetch();
					
					$count = $req[0];
					
					$nombre_page = ceil($count / $nombre_annonce);
					
					$SQL = "SELECT * FROM pas_annonce $addSearch ORDER BY date_soumission DESC LIMIT $totalp,$nombre_annonce";
					$reponse = $pdo->query($SQL);
					while($req = $reponse->fetch())
					{
						?>
						<tr>
							<td><?php echo $class_fuseau_horaire->convertTimezone($req['date_soumission'],getConfig("format_date"),getConfig("fuseau")); ?></td>
							<td>
							<?php 
							if($req['valider'] == 'yes')
							{
								?>
								<img src="images/valid-icon.png" width=20 title="Annonce valider">
								<?php
							}
							else if($req['valider'] == 'no')
							{
								?>
								<img src="images/invalid-icon.png" width=20 title="Annonce non valider">
								<?php
							}
							else
							{
								?>
								<img src="images/icon-expirer.png" width=20 title="Annonce expirer">
								<?php
							}
							?></td>
							<td><?php echo $req['offre_demande']; ?></td>
							<td><?php echo substr($req['titre'],0,110); ?></td>
							<td>
							<?php 
							if($req['status'] == 'PAID_PAYPAL')
							{
								echo 'Payer avec Paypal';
							}
							else if($req['status'] == 'WAIT_CHEQUE')
							{
								echo 'En attente de réglement par chèque';
							}
							else if($req['status'] == 'PAID_STRIPE')
							{
								echo 'Payer avec Stripe';
							}
							else if($req['status'] == 'ERROR_PAID_STRIPE')
							{
								echo 'Erreur de paiement Stripe';
							}
							else if($req['status'] == 'ERROR_IPN')
							{
								echo 'Erreur de reception IPN Paypal';
							}
							else if($req['status'] == 'WAITING_PAID')
							{
								echo 'En attente de réglement';
							}
							else if($req['status'] == 'WAIT_VIREMENT')
							{
								echo 'En attente de réglement par Virement bancaire';
							}
							else if($req['status'] == 'WAIT_MOBICASH')
							{
								echo 'En attente confirmation de paiement Mobicash';
							}
							else if($req['status'] == 'FREE')
							{
								echo 'Annonce gratuite';
							}
							else if($req['status'] == 'PAID_PAYDUNYA')
							{
								echo 'Payer avec Paydunya';
							}
							else if($req['status'] == 'PAID_CHECK')
							{
								echo 'Payer par chèque';
							}
							else if($req['status'] == 'PAID_VIREMENT')
							{
								echo 'Payer par virement bancaire';
							}
							else if($req['status'] == 'PAID_MOBICASH')
							{
								echo 'Payer par Mobicash';
							}
							?>
							</td>
							<td>
								<?php
								if($req['status'] == 'WAIT_CHEQUE')
								{
									?>
									<a href="annonce-waiting-paid.php?action=6&md5=<?php echo $req['md5']; ?>&type=cheque" class="btn green">Paiement reçu</a>
									<?php
								}
								if($req['status'] == 'WAIT_VIREMENT')
								{
									?>
									<a href="annonce-waiting-paid.php?action=6&md5=<?php echo $req['md5']; ?>&type=virement" class="btn green">Paiement reçu</a>
									<?php
								}
								if($req['status'] == 'WAIT_MOBICASH')
								{
									$md5 = $req['md5'];
									$SQL = "SELECT * FROM pas_paiement WHERE md5 = '$md5'";
									$r = $pdo->query($SQL);
									$rr = $r->fetch();
									
									$id_transaction = $rr['id_transaction'];
									?>
									<a href="annonce-waiting-paid.php?action=6&md5=<?php echo $req['md5']; ?>&type=emoney" class="btn green">Paiement reçu (SMS <?php echo $id_transaction; ?>)</a>
									<?php
								}
								?>
								<a href="annonce.php?id=<?php echo $req['id']; ?>&action=1" class="btn red">Supprimer</a>
								<a href="annonce.php?md5=<?php echo $req['md5']; ?>&action=5" class="btn blue">Modifier</a>
								<a href="annonce.php?action=4&md5=<?php echo $req['md5']; ?>" class="btn blue">Controler l'annonce</a>
							</td>
						</tr>
						<?php
					}
					?>
					</table>
					<?php
				}
		?>
		<style>
		.paging
		{
			border-radius: 5px;
			background-color: #28a2fe;
			color: #ffffff;
			margin-right: 5px;
			text-decoration: none;
			box-sizing: border-box;
			padding: 10px;
		}
		
		.paging-box
		{
			margin-top: 25px;
			text-align: center;
		}
		</style>
		<div class="paging-box">
		<?php
		if($page==0)
		{
			?>
			<a href="javascript:void(0);" class="paging disabled">Précedent</a>
			<?php
		}
		else
		{
			?>
			<a href="annonce-expired.php?page=<?php echo ($page-1); ?>" class="paging">Précedent</a>
			<?php
		}
		?>
		<b>Page n°<?php echo ($page+1); ?> sur <?php echo $nombre_page; ?></b>
		<?php
		if($page >= $nombre_page)
		{
			?>
			<a href="javascript:void(0);" class="paging disabled">Suivant</a>
			<?php
		}
		else
		{
			?>
			<a href="annonce-expired.php?page=<?php echo ($page+1); ?>" class="paging">Suivant</a>
			<?php
		}
		?>
		</div>
	</div>
</body>
</html>