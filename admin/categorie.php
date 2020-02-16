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
	/* Suppression d'une catégorie */
	if($action == 1)
	{
		$id = AntiInjectionSQL($_REQUEST['id']);
		$SQL = "DELETE FROM pas_categorie WHERE id = $id";
		$pdo->query($SQL);
		header("Location: categorie.php");
		exit;
	}
	
	/* Supression catégorie principale et sous-categorie */
	if($action == 2)
	{
		$id = AntiInjectionSQL($_REQUEST['id']);
		$SQL = "DELETE FROM pas_categorie WHERE id = $id";
		$pdo->query($SQL);
		$SQL = "DELETE FROM pas_categorie WHERE subcategorie = $id";
		$pdo->query($SQL);
		
		header("Location: categorie.php");
		exit;
	}
	
	/* Modification du nombre de catégorie par colonne */
	if($action == 3)
	{
		$nbr_categorie_1 = $_REQUEST['nbr_categorie_1'];
		$nbr_categorie_2 = $_REQUEST['nbr_categorie_2'];
		$nbr_categorie_3 = $_REQUEST['nbr_categorie_3'];
		$nbr_categorie_4 = $_REQUEST['nbr_categorie_4'];
		
		$data = implode(",",array($nbr_categorie_1,$nbr_categorie_2,$nbr_categorie_3,$nbr_categorie_4));
		
		updateConfig("nbr_categorie_par_colonne",$data);
		header("Location: categorie.php");
		exit;
	}
}

$langue = getConfig("langue_administration");
if($langue == 'fr')
{
	$titre_cat = "Gestion des categories";
	$description = "Gérer les catégorie de votre site internet.";
	$cat_avancee = "Catégorie avancées";
	$btn_add_categorie = "Ajouter une categorie";
	$titre_label = "Titre";
	$action_label = "Action";
	$btn_modifier = "Modifier";
	$btn_supprimer = "Supprimer";
	$message_delete_normal = "Etes-vous sur de vouloir supprimer cette sous-catégorie ?";
	$message_delete_all_cat = "Attention la suppression d'un categorie principale, supprimera toute les sous-categorie ratacher à lui-meme, voulez-vous continuer ?";
}
if($langue == 'en')
{
	$titre_cat = "Category Management";
	$description = "Manage the categories of your website.";
	$cat_avancee = "Advanced category";
	$btn_add_categorie = "Add a category";
	$titre_label = "Title";
	$action_label = "Action";
	$btn_modifier = "Edit";
	$btn_supprimer = "Delete";
	$message_delete_normal = "Are you sure you want to delete this sub-category?";
	$message_delete_all_cat = "Beware the deletion of a main category, delete all the subcategories ratacher to himself, do you want to continue?";
}
if($langue == 'it')
{
	$titre_cat = "Gestione delle categorie";
	$description = "Gestisci le categorie del tuo sito web.";
	$cat_avancee = "Categoria avanzata";
	$btn_add_categorie = "Aggiungi una categoria";
	$titre_label = "Titolo";
	$action_label = "Azione";
	$btn_modifier = "Cambiamento";
	$btn_supprimer = "Rimuovere";
	$message_delete_normal = "Sei sicuro di voler eliminare questa sottocategoria?";
	$message_delete_all_cat = "Stai attento alla cancellazione di una categoria principale, elimina tutte le sottocategorie ratacher a se stesso, vuoi continuare?";
}
if($langue == 'bg')
{
	$titre_cat = "Управление на категории";
	$description = "Управлявайте категориите на уебсайта си.";
	$cat_avancee = "Разширена категория";
	$btn_add_categorie = "Добавете категория";
	$titre_label = "заглавие";
	$action_label = "действие";
	$btn_modifier = "промяна";
	$btn_supprimer = "премахнете";
	$message_delete_normal = "Наистина ли искате да изтриете тази подкатегория?";
	$message_delete_all_cat = "Пазете се от изтриването на основна категория, изтрийте всички подкатегории ratacher за себе си, искате ли да продължите?";
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Administration PAS Script v<?php echo $version; ?></title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
</head>
<body>
	<?php
	
	include "header.php";
	include "sidebar.php";
	
	?>
	<style>
	.tabs
	{
		padding: 10px;
		background-color: #ffffff;
		box-sizing: border-box;
		border-bottom-right-radius: 5px;
		border-bottom-left-radius: 5px;
	}
	
	.tabs-element
	{
		-webkit-border-top-left-radius: 5px;
		-webkit-border-top-right-radius: 5px;
		-moz-border-radius-topleft: 5px;
		-moz-border-radius-topright: 5px;
		border-top-left-radius: 5px;
		border-top-right-radius: 5px;
		float: left;
		padding: 10px;
		background-color: #dddddd;
		margin-right: 1px;
	}
	
	.tabs-element:hover
	{
		background-color: #ffdddd;
	}
	
	.tabs-cover
	{
		overflow:auto;
	}
	
	.badge
	{
		background-color: #aaaaaa;
		font-size: 12px;
		padding: 5px;
		border-radius: 5px;
	}
	
	.preCol
	{
		width: 50%;
		float: left;
		background-color: #009cff;
		padding-top: 10px;
		padding-bottom: 10px;
		padding-left: 10px;
		box-sizing: border-box;
		color: #ffffff;
		font-weight: bold;
	}
	
	.nCol
	{
		width: 50%;
		float: left;
		padding-top: 10px;
		padding-bottom: 10px;
		background-color: #d7d7d7;
		padding-left: 10px;
		box-sizing: border-box;
	}
	
	.nColHidden
	{
		width: 50%;
		float: left;
		padding-top: 10px;
		padding-bottom: 10px;
		background-color: #ffffff;
		padding-left: 10px;
		box-sizing: border-box;
	}
	
	.unknowcat
	{
		width: 100%;
		text-align: center;
		padding-top: 150px;
		padding-bottom: 150px;
		font-size: 21px;
		font-weight: bold;
	}
	</style>
	<div class="container">
		<H1><?php echo $titre_cat; ?></H1>
		<div class="info">
		<?php echo $description; ?>
		</div>
		<div class="tabs-cover">
			<a href="javascript:void(0);" onclick="showTabs(1)">
				<div class="tabs-element">
					<?php echo $titre_cat; ?>
				</div>
			</a>
			<a href="javascript:void(0);" onclick="showTabs(2)">
				<div class="tabs-element">
					<?php echo $cat_avancee; ?>
				</div>
			</a>
		</div>
		<div class="tabs" id="tabs-1">
			<H2><?php echo $titre_cat; ?></H2>
			<div style="margin-top:20px;margin-bottom:20px;">
				<a href="addcategorie.php" class="btn blue"><i class="fas fa-plus-square"></i> <?php echo $btn_add_categorie; ?></a>
			</div>
			<?php
			
			$SQL = "SELECT COUNT(*) FROM pas_categorie WHERE subcategorie = 0";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$count_principale = $req[0];
			
			$SQL = "SELECT COUNT(*) FROM pas_categorie WHERE subcategorie != 0";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$count_sub = $req[0];
			
			?>
			<H2><span class="badge badge-secondary"><?php echo $count_principale; ?></span> Catégorie principale | <span class="badge badge-secondary"><?php echo $count_sub; ?></span> Sous-catégorie</H2>
			<div class="row" style="margin-left:1px;overflow: auto;">
				<div class="col-sm-4 preCol">
				<?php echo $titre_label; ?>
				</div>
				<div class="col-sm-4 preCol">
				<?php echo $action_label; ?>
				</div>
				<?php
				
				if($count_principale == 0)
				{
					?>
					<div class="unknowcat">Aucune catégorie principale</div>
					<?php
				}
				else
				{				
					$x = 0;
					
					$SQL = "SELECT * FROM pas_categorie WHERE subcategorie = 0 ORDER BY titre ASC";
					$reponse = $pdo->query($SQL);
					while($req = $reponse->fetch())
					{
						$id = $req['id'];
						?>
							<div class="col-sm-4 nCol">
								<?php
								$SQL = "SELECT COUNT(*) FROM pas_categorie WHERE subcategorie = $id";
								$r = $pdo->query($SQL);
								$rr = $r->fetch();
								
								$count = $rr[0];
								
								if($count != 0)
								{
									?>
									<a href="javascript:void(0);" id="link-tabs-<?php echo $x; ?>" onclick="openTab('<?php echo $x; ?>');"><i class="fas fa-plus-square"></i></a>&nbsp;<span class="badge badge-primary"><?php echo $count; ?></span>&nbsp;
									<?php
								}
								else
								{
									?>
									<i class="fas fa-window-close"></i>&nbsp;<span class="badge badge-primary"><?php echo $count; ?></span>&nbsp;
									<?php
								}
								echo $req['titre'];
								?>
							</div>
							<div class="col-sm-4 nCol">
								<a href="editcategorie.php?id=<?php echo $id; ?>" class="btn blue"><i class="fas fa-edit"></i> <?php echo $btn_modifier; ?></a>
								<a href="javascript:void(0);" onclick="confirmDelete('<?php echo $id; ?>');" class="btn red"><i class="fas fa-trash-alt"></i> <?php echo $btn_supprimer; ?></a>
							</div>
							<div class="tabs-cat" id="tabsc-<?php echo $x; ?>" style="display:none;">
								<?php
								
								if($count != 0)
								{
									$SQL = "SELECT * FROM pas_categorie WHERE subcategorie = $id ORDER BY titre ASC";
									$r = $pdo->query($SQL);
									while($rr = $r->fetch())
									{
										if($rr['meta_title'] == '')
										{
											$filter = 'Aucun filtre';
										}
										else
										{
											if($rr['meta_title'] == 'module_filtre_adulte')
											{
												$filter = 'Filtre avertissement Adulte (+18 ans)';
											}
											if($rr['meta_title'] == 'module_filtre_auto')
											{
												$filter = 'Filtre Automobile';
											}
											if($rr['meta_title'] == 'module_filtre_immo')
											{
												$filter = 'Filtre Immobilier';
											}
										}
										?>
										<div class="col-sm-4 nColHidden">
											<b><?php echo $rr['titre'];	?> <i class="fas fa-filter"></i> <?php echo $filter; ?></b>
										</div>
										<div class="col-sm-4 nColHidden">
											<a href="editcategorie.php?id=<?php echo $rr['id']; ?>" class="btn blue"><i class="fas fa-edit"></i> <?php echo $btn_modifier; ?></a>
											<a href="javascript:void(0);" onclick="confirmDeleteNormal('<?php echo $rr['id']; ?>');" class="btn red"><i class="fas fa-trash-alt"></i> <?php echo $btn_supprimer; ?></a>
										</div>
										<?php
									}
								}
								
								?>
							</div>
						<?php
						$x++;
					}
				}
				
				?>
			</div>
			<script>
			function confirmDeleteNormal(id)
			{
				var r = confirm("<?php echo $message_delete_normal; ?>");
				if (r == true) 
				{
					window.location.href = 'categorie.php?action=1&id='+id;
				}
			}
			
			function confirmDelete(id)
			{
				var r = confirm("<?php echo $message_delete_all_cat; ?>");
				if (r == true) 
				{
					window.location.href = 'categorie.php?action=2&id='+id;
				}
			}
			</script>
			<script>
			function openTab(id)
			{
				if($('#tabsc-'+id).css('display') == 'none')
				{
					$('#link-tabs-'+id).html('<i class="fas fa-minus-square"></i>');
				}
				else
				{
					$('#link-tabs-'+id).html('<i class="fas fa-plus-square"></i>');
				}
				$('#tabsc-'+id).toggle('slow');
			}
			</script>
		</div>
		<div class="tabs" id="tabs-2" style="display:none;">
			<H2><?php echo $cat_avancee; ?></H2>
			<p>
			Nombre de catégorie par colonnes
			</p>
			<?php

			$data = getConfig("nbr_categorie_par_colonne");
			$data = explode(",",$data);

			?>
			<form>
				<label>Nombre de catégories sur la colonne n°1 :</label>
				<input type="text" name="nbr_categorie_1" value="<?php echo $data[0]; ?>" class="inputbox">
				<label>Nombre de catégories sur la colonne n°2 :</label>
				<input type="text" name="nbr_categorie_2" value="<?php echo $data[1]; ?>" class="inputbox">
				<label>Nombre de catégories sur la colonne n°3 :</label>
				<input type="text" name="nbr_categorie_3" value="<?php echo $data[2]; ?>" class="inputbox">
				<label>Nombre de catégories sur la colonne n°4 :</label>
				<input type="text" name="nbr_categorie_4" value="<?php echo $data[3]; ?>" class="inputbox">
				<input type="hidden" name="action" value="3">
				<input type="submit" value="Modifier" class="btn blue">
			</form>
		</div>
		<script>
		var oldtabs = 1;
		function showTabs(id)
		{
			$('#tabs-'+oldtabs).css('display','none');
			$('#tabs-'+id).css('display','block');
			oldtabs = id;
		}
		</script>
	</div>
</body>
</html>