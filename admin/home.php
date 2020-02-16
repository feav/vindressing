<?php

include "../config.php";
include "version.php";
include "../engine/class.monetaire.php";
include "../engine/class.statistique-visiteur.php";

$class_statistique_visiteur = new Statistique_Visiteur();

@session_start();

$SQL = "SELECT COUNT(*) FROM pas_admin_user WHERE username = '".$_SESSION['admin_username']."' AND password = '".$_SESSION['admin_password']."'";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

if($req[0] == 0)
{
	header("Location: index.php");
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
	
	$lc = file_get_contents("http://www.shua-creation.com/lc/lc.php?i=pas_script&u=".$_SERVER['SERVER_NAME']."&v=".$version);
	
	?>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
	<div class="container">
		<H1><i class="fas fa-tachometer-alt"></i> <?php echo $title_dashboard; ?></H1>
		<?php
		
		$monetaire = new Monetaire();
		$sigle = $monetaire->getSigle();
		
		$SQL = "SELECT COUNT(*) FROM pas_user";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		$count_user = $req[0];
		
		$SQL = "SELECT COUNT(*) FROM pas_annonce WHERE valider = 'yes'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		$count_annonce = $req[0];
		
		$SQL = "SELECT * FROM pas_paiement ORDER BY id DESC LIMIT 1";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		$montantlast = $req['montant'];
		
		?>
	<div class="stats-englobe">
		<div class="stats-box">
			<div class="number-box">
				<i class="fas fa-users"></i> <?php echo $count_user; ?>
			</div>
			<div class="title-box">
			<?php echo $dashboard_title_block_user; ?>
			</div>
		</div>
		<div class="stats-box orange-stat">
			<div class="number-box">
				<i class="fas fa-vote-yea"></i> <?php echo $count_annonce; ?>
			</div>
			<div class="title-box">
			<?php echo $dashboard_title_block_annonces; ?>
			</div>
		</div>
		<div class="stats-box green-stat">
			<div class="number-box">
				<i class="fas fa-money-bill-wave"></i> <?php echo number_format($montantlast,2); ?> <?php echo $sigle; ?>
			</div>
			<div class="title-box">
			<?php echo $dashboard_title_block_paid; ?>
			</div>
		</div>
		<div class="stats-box purple-stat">
			<div class="number-box">
				<i class="fas fa-user"></i> <?php echo $class_statistique_visiteur->getVisiteur(date('Y')."-".date('m')."-".date('d')); ?>
			</div>
			<div class="title-box">
			<?php echo $dashboard_title_block_user_today; ?>
			</div>
		</div>
	</div>
	<br>
		<div style="overflow:auto;">
			<div style="float:left;width:69%;margin-right:1%;box-sizing:box-border;">
			<?php
			
			$data = file_get_contents("http://www.shua-creation.com/help/aide-en-ligne-pas-script-v1.26.5.php");
			echo $data;
			
			?>
			</div>
			<div style="float:left;width:30%;box-sizing:box-border;">
			<?php
			
			$data = file_get_contents("http://www.shua-creation.com/help/newspas.php");
			echo $data;
			
			?>
			</div>
		</div>
	</div>
	</div>
</body>
</html>