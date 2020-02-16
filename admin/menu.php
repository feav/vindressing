<?php

include "../config.php";
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
	
	/* Modifie l'ordre des onglets */
	if($action == 1)
	{
		$order = $_REQUEST['order'];
		$order = explode(",",$order);
		
		$array = NULL;
		
		for($x=0;$x<count($order);$x++)
		{
			$SQL = "SELECT * FROM pas_menu WHERE id = ".$order[$x];
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			
			$count = count($array);
			$array[$count]['title'] = $req['title'];
			$array[$count]['language'] = $req['language'];
			$array[$count]['url'] = $req['url'];
			$array[$count]['url_rewriting'] = $req['url_rewriting'];
			$array[$count]['method'] = $req['method'];
		}
		
		$SQL = "DELETE FROM pas_menu";
		$pdo->query($SQL);
		
		for($x=0;$x<count($array);$x++)
		{
			$title = $array[$x]['title'];
			$language = $array[$x]['language'];
			$url = $array[$x]['url'];
			$url_rewriting = $array[$x]['url_rewriting'];
			$method = $array[$x]['method'];
			
			$SQL = "INSERT INTO pas_menu (title,language,url,url_rewriting,method) VALUES ('$title','$language','$url','$url_rewriting','$method')";
			$pdo->query($SQL);
		}
		
		header("Location: menu.php");
		exit;
	}
	
	/* Supprimer un onglet */
	if($action == 2)
	{
		$id = $_REQUEST['id'];
		$SQL = "DELETE FROM pas_menu WHERE id = $id";
		$pdo->query($SQL);
		
		header("Location: menu.php");
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
		<H1>Menu</H1>
		<div class="info">
		Gerer le menu de votre site internet depuis cette interface, réorganiser, modifier, supprimer et ajouter de nouveau onglet.
		</div>
		<div style="margin-top:20px;margin-bottom:20px;">
			<a href="addmenu.php" class="btn blue">Ajouter un nouvel onglet</a>
		</div>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<style>
		ul { list-style-type: none; margin: 0; padding: 0; margin-bottom: 10px; }
		#sortable li
		{ 
			margin: 5px;
			padding: 5px;
			padding-left: 10px;
			height: 30px;
			padding-top: 12px;
		}
		
		.btnboxdrag
		{
			float:right;
		}
		
		.btn
		{
			color:#ffffff !important;
		}
		</style>
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script>
		$( function() {
			$( "#sortable" ).sortable({
			revert: true
		});
		$( "#draggable" ).draggable({
			connectToSortable: "#sortable",
			helper: "clone",
			revert: "invalid"
		});
		$( "ul, li" ).disableSelection();
		} );
		</script>

		<ul id="sortable">
			<?php
			
			$SQL = "SELECT * FROM pas_menu";
			$reponse = $pdo->query($SQL);
			while($req = $reponse->fetch())
			{
				?>
				<li class="ui-state-default" id="<?php echo $req['id']; ?>"><?php echo $req['title']; ?> <div class="btnboxdrag"><a href="menu.php?action=2&id=<?php echo $req['id']; ?>" class="btn red">Supprimer</a> <a href="editmenu.php?id=<?php echo $req['id']; ?>" class="btn blue">Modifier</a></div></li>
				<?php
			}
			
			?>
		</ul>
		<button class="btn blue" onclick="saveOrder();">Sauvegarder l'ordre</button>
		<script>
		function saveOrder()
		{
			var tab = [];
			jQuery('#sortable li').each(function()
			{
				tab.push(this.id);
			});
			window.location.href = 'menu.php?action=1&order='+tab;
		}
		</script>
	</div>
</body>
</html>