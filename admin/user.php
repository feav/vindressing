<?php

include "../main.php";
include "version.php";

$search = NULL;
$type_search = NULL;

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
	/* On banni l'utilisateur */
	if($action == 1)
	{
		$id = AntiInjectionSQL($_REQUEST['id']);
		$SQL = "UPDATE pas_user SET ban = 'yes' WHERE id = $id";
		$pdo->query($SQL);
		header("Location: user.php");
	}
	/* Deban */
	if($action == 2)
	{
		$id = AntiInjectionSQL($_REQUEST['id']);
		$SQL = "UPDATE pas_user SET ban = 'no' WHERE id = $id";
		$pdo->query($SQL);
		header("Location: user.php");
	}
	/* Supprimer un utilisateur */
	if($action == 3)
	{
		$id = AntiInjectionSQL($_REQUEST['id']);
		
		/* On check si l'utilisateur est un utilisateur PRO */
		$SQL = "SELECT * FROM pas_user WHERE id = $id";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		$type_compte = $req['type_compte'];
		$md5 = $req['md5'];
		
		/* Si compte professionel on supprime les infos */
		if($type_compte == 'professionel')
		{
			$SQL = "DELETE FROM pas_compte_pro WHERE md5 = '$md5'";
			$pdo->query($SQL);
		}
		
		/* On supprime toute les annonces de l'utilisateur */
		$SQL = "DELETE FROM pas_annonce WHERE iduser = $id";
		$pdo->query($SQL);
			
		/* On supprimer l'utilisateur */
		$SQL = "DELETE FROM pas_user WHERE id = $id";
		$pdo->query($SQL);
		
		header("Location: user.php");
		exit;
	}	
	/*Valider manuellement un utilisateur */
	if($action == 4)
	{
		$id = AntiInjectionSQL($_REQUEST['id']);
		$SQL = "UPDATE pas_user SET validate_account = 'yes' WHERE id = $id";
		$pdo->query($SQL);
		header("Location: user.php");
		exit;
	}
	
	/* Suppression des utilisateur en masse */
	if($action == 5)
	{
		$delete = $_REQUEST['delete'];
		for($x=0;$x<count($delete);$x++)
		{
			$id = $delete[$x];
			$id = AntiInjectionSQL($id);
			$SQL = "DELETE FROM pas_user WHERE id = $id";
			$pdo->query($SQL);
		}
		header("Location: user.php");
		exit;
	}
	
	/* Export de la liste des emails des utilisateur au format CSV */
	if($action == 6)
	{
		$data = NULL;
		$SQL = "SELECT * FROM pas_user";
		$reponse = $pdo->query($SQL);
		while($req = $reponse->fetch())
		{
			$data .= $req['email'].";"."\n";
		}
		
		echo $data;
		
		header("Content-disposition: attachment; filename=exportation-email-utilisateur-".date(d)."-".date('m')."-".date('Y').".csv");
		header("Content-Type: application/force-download");
		header("Content-Transfer-Encoding: text/plain\n");
		exit;
	}
}

$langue = getConfig("langue_administration");
if($langue == 'fr')
{
	$titre = "Gestion des utilisateurs";
	$description = "Gérer les utilisateurs du site internet depuis cette interface";
	$filtre_user = "Par nom d'utilisateur";
	$filtre_email = "Par email";
	$btn_rechercher = "Rechercher";
	$btn_supprimer = "Supprimer";
	$label_date = "Date d'inscription";
}
if($langue == 'en')
{
	$titre = "User Management";
	$description = "Manage users of the website from this interface";
	$filtre_user = "By username";
	$filtre_email = "By email";
	$btn_rechercher = "Search";
	$btn_supprimer = "Delete";
	$label_date = "Registration date";
}
if($langue == 'it')
{
	$titre = "Gestione degli utenti";
	$description = "Gestisci gli utenti del sito web da questa interfaccia";
	$filtre_user = "Per nome utente";
	$filtre_email = "Per email";
	$btn_rechercher = "Ricercare";
	$btn_supprimer = "Rimuovere";
	$label_date = "Data di registrazione";
}
if($langue == 'bg')
{
	$titre = "Управление на потребителите";
	$description = "Управлявайте потребителите на уебсайта от този интерфейс";
	$filtre_user = "С потребителско име";
	$filtre_email = "По имейл";
	$btn_rechercher = "търсене";
	$btn_supprimer = "премахнете";
	$label_date = "Дата на регистрация";
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
	
	$SQL = "SELECT COUNT(*) FROM pas_user";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	$count = $req[0];
	
	?>
	<div class="container">
		<H1><div class="round-count"><?php echo $count; ?></div> <?php echo $titre; ?></H1>
		<div class="info">
		<?php echo $description; ?>
		</div>
		<?php
		
		if(isset($_REQUEST['type_search']))
		{
			$type_search = $_REQUEST['type_search'];
			$search = $_REQUEST['search'];
			
			if($type_search == 'pseudo')
			{
				$SQLCount = "SELECT COUNT(*) FROM pas_user WHERE username like '%$search%'";
			}
			else
			{
				$SQLCount = "SELECT COUNT(*) FROM pas_user WHERE email like '%$search%'";
			}
		}
		else
		{
			$SQLCount = "SELECT COUNT(*) FROM pas_user";
		}
		
		?>
		<div class="search-bar-user">
			<form>
				<select name="type_search" class="select-search-user">
					<?php
					if($type_search == 'pseudo')
					{
						?>
						<option value="pseudo" selected><?php echo $filtre_user; ?></option>
						<option value="email"><?php echo $filtre_email; ?></option>
						<?php
					}
					else if($type_search == 'email')
					{
						?>
						<option value="pseudo"><?php echo $filtre_user; ?></option>
						<option value="email" selected><?php echo $filtre_email; ?></option>
						<?php
					}
					else
					{
						?>
						<option value="pseudo"><?php echo $filtre_user; ?></option>
						<option value="email"><?php echo $filtre_email; ?></option>
						<?php
					}
					?>
				</select>
				<input type="text" name="search" placeholder="Votre recherche" value="<?php echo $search; ?>" class="input-search-user">
				<input type="submit" value="<?php echo $btn_rechercher; ?>" class="btn blue">
			</form>
			<style>
			.export-user
			{
				float: right;
				margin-top: -29px;
			}
			</style>
			<div class="export-user">
				<a href="user.php?action=6" class="btn blue">Exporter les emails des utilisateurs</a>
			</div>
		</div>
		<form method="POST">
		<table>
			<tr>
				<th>#</th>
				<th><?php echo $label_date; ?></th>
				<th>Username</th>
				<th>Email</th>
				<th>Compte activée</th>
				<th>Type de compte</th>
				<th>Action</th>
			</tr>
			<?php
						
			
			$reponse = $pdo->query($SQLCount);
			$req = $reponse->fetch();
			
			$count = $req[0];
			$limit = 20;
			
			if($count == 0)
			{
				?>
				</table>
				<div style="text-align: center;padding-top: 150px;padding-bottom: 150px;background-color: #fff;">
				<h1>Aucun utilisateur pour le moment</h1>
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
				
				if($type_search != '')
				{
					if($type_search == 'pseudo')
					{
						$SQL = "SELECT * FROM pas_user WHERE username like '%$search%' ORDER BY id DESC LIMIT ".($page*$limit).",$limit";
					}
					else
					{
						$SQL = "SELECT * FROM pas_user WHERE email like '%$search%' ORDER BY id DESC LIMIT ".($page*$limit).",$limit";
					}
				}
				else
				{
					$SQL = "SELECT * FROM pas_user ORDER BY id DESC LIMIT ".($page*$limit).",$limit";
				}
				
				$reponse = $pdo->query($SQL);
				while($req = $reponse->fetch())
				{
					?>
					<tr id="item-<?php echo $req['id']; ?>">
						<td>
							<input type="checkbox" name="delete[]" onchange="updateDeleteMode('<?php echo $req['id']; ?>');" value="<?php echo $req['id']; ?>">
						</td>
						<td>
						<?php 
						
						$date_inscription = $req['date_inscription']; 
						$date_inscription = explode(" ",$date_inscription);
						$date = $date_inscription[0];
						$heure = $date_inscription[1];
						
						$date = explode("-",$date);
						
						echo "le ".$date[2]."/".$date[1]."/".$date[0]." à ".$heure;
						
						?>
						</td>
						<td><?php echo $req['username']; ?></td>
						<td><?php echo $req['email']; ?></td>
						<td>
						<?php
						if($req['validate_account'] == 'yes')
						{
							?>
							<img src="images/valid-icon.png" title="Compte valider par Email" width=15>
							<?php
						}
						else
						{
							?>
							<img src="images/invalid-icon.png" title="Compte non valider par Email" width=15>
							<?php
						}
						?>
						</td>
						<td>
						<?php echo ucfirst($req['type_compte']); ?>
						</td>
						<td>
							<a href="user.php?id=<?php echo $req['id']; ?>&action=3" class="btn red"><?php echo $btn_supprimer; ?></a>
							<a href="user.php?id=<?php echo $req['id']; ?>&action=4" class="btn green">Valider</a>
							<?php
							if($req['ban'] == 'yes')
							{
							?>
							<a href="user.php?id=<?php echo $req['id']; ?>&action=2" class="btn red">Debannir</a>
							<?php
							}
							else
							{
							?>
							<a href="user.php?id=<?php echo $req['id']; ?>&action=1" class="btn red">Bannir</a>
							<?php
							}
							?>
						</td>
					</tr>
					<?php
				}
				?>
				</table>
				<?php
			}
			
			?>
		<div class="bar-options-plus">
			<input type="hidden" name="action" value="5">
			<input type="submit" value="Supprimer les utilisateur cocher" class="btn blue">
		</div>
		</form>
		<script>
		function updateDeleteMode(id)
		{
			var item = $('#item-'+id).css('background-color');
			if(item == 'rgba(0, 0, 0, 0)')
			{
				$('#item-'+id).css('background-color','#ddd');
			}
			else if(item == 'rgb(242, 242, 242)')
			{
				$('#item-'+id).css('background-color','#ddd');
			}
			else
			{
				$('#item-'+id).css('background-color','rgba(0,0,0,0)');
			}
		}
		</script>
		<style>
		.bar-options-plus
		{
			width: 100%;
			height: 35px;
			background-color: #7e7e7e;
			padding-top: 5px;
			padding-bottom: 5px;
			padding-left: 5px;
			margin-top: 10px;
			margin-bottom: 22px;
		}
		
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
			margin-top: 10px;
			text-align: center;
		}
		</style>
		<div class="paging-box">
		<?php
		
		$totalPage = ceil($count / $limit);
		
		if($page==0)
		{
			?>
			<a href="javascript:void(0);" class="paging disabled">Précedent</a>
			<?php
		}
		else
		{
			?>
			<a href="user.php?page=<?php echo ($page-1); ?>" class="paging">Précedent</a>
			<?php
		}
		?>
		<b>Page n°<?php echo ($page+1); ?> sur <?php echo $totalPage; ?></b>
		<?php
		if($page >= $totalPage)
		{
			?>
			<a href="javascript:void(0);" class="paging disabled">Suivant</a>
			<?php
		}
		else
		{
			?>
			<a href="user.php?page=<?php echo ($page+1); ?>" class="paging">Suivant</a>
			<?php
		}
		
		?>
		</div>
	</div>
</body>
</html>