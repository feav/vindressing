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
	if($action == 1)
	{
		$position_cookie = AntiInjectionSQL($_REQUEST['position_cookie']);
		updateConfig("position_cookie",$position_cookie);
		
		$policy_link_cookie = AntiInjectionSQL($_REQUEST['policy_link_cookie']);
		updateConfig("policy_link_cookie",$policy_link_cookie);
		
		$message_cookie = AntiInjectionSQL($_REQUEST['message_cookie']);
		updateConfig("message_cookie",$message_cookie);
		
		$policy_link_text_cookie = AntiInjectionSQL($_REQUEST['policy_link_text_cookie']);
		updateConfig("policy_link_text_cookie",$policy_link_text_cookie);
		
		$allow_button_cookie = AntiInjectionSQL($_REQUEST['allow_button_cookie']);
		updateConfig("allow_button_cookie",$allow_button_cookie);
		
		$layout_cookie = AntiInjectionSQL($_REQUEST['layout_cookie']);
		updateConfig("layout_cookie",$layout_cookie);
		
		$dissmiss_cookie = AntiInjectionSQL($_REQUEST['dissmiss_cookie']);
		updateConfig("dissmiss_cookie",$dissmiss_cookie);
		
		$palette_color_cookie = AntiInjectionSQL($_REQUEST['palette_color_cookie']);
		updateConfig("palette_color_cookie",$palette_color_cookie);
		
		$cookie_activer = AntiInjectionSQL($_REQUEST['cookie_activer']);
		updateConfig("cookie_activer",$cookie_activer);
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
	<style>
	.theme-preview-button
	{
		height: 10px;
		width: 15px;
		margin-top: 10px;
		margin-left: 20px;
	}
	
	.palette-block
	{
		float: left;
		margin-right: 5px;
		width: 40px;
		height: 28px;
	}
	
	.bselected
	{
		border:2px solid #ff0000;
	}
	</style>
	<div class="container">
		<H1>Avertissement Cookie</H1>
		<div class="info">
		Gérer depuis cette interface la fenêtre d'autorisation des cookies de vos utilisateurs. Aujourd'hui cette obligation d'informer l'utilisateur sur l'utilisation des données est obligatoire en France, comme dans l'Union européenne.
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<form method="POST" enctype="multipart/form-data">
			<?php
			
			$cookie_activer = getConfig("cookie_activer");
			if($cookie_activer == 'yes')
			{
				?>
				<input type="checkbox" name="cookie_activer" value="yes" checked> Activer la visibilité de la fenêtre de cookie<br>
				<?php
			}
			else
			{
				?>
				<input type="checkbox" name="cookie_activer" value="yes"> Activer la visibilité de la fenêtre de cookie<br>
				<?php
			}
			
			?>
			<H2>Position</H2>
			<p>
			Position de la fenêtre d'avertissement
			</p>
			<?php
			
			$position_cookie = getConfig("position_cookie");
			$layout_cookie = getConfig("layout_cookie");
			$palette_color_cookie = getConfig("palette_color_cookie");
			
			if($palette_color_cookie == '')
			{
				$palette_color_cookie = 1;
			}
			
			if($position_cookie == 'bottom')
			{
				?>
				<input type="radio" name="position_cookie" value="bottom" checked>En bas<br>
				<?php
			}
			else
			{
				?>
				<input type="radio" name="position_cookie" value="bottom">En bas<br>
				<?php
			}
			
			if($position_cookie == 'top')
			{
				?>
				<input type="radio" name="position_cookie" value="top" checked>En haut<br>
				<?php
			}
			else
			{
				?>
				<input type="radio" name="position_cookie" value="top">En haut<br>
				<?php
			}
			
			if($position_cookie == 'top_push')
			{
				?>
				<input type="radio" name="position_cookie" value="top_push" checked>En haut (pushdown)<br>
				<?php
			}
			else
			{
				?>
				<input type="radio" name="position_cookie" value="top_push">En haut (pushdown)<br>
				<?php
			}
			
			if($position_cookie == 'bottom-left')
			{
				?>
				<input type="radio" name="position_cookie" value="bottom-left" checked>Flottant à gauche<br>
				<?php
			}
			else
			{
				?>
				<input type="radio" name="position_cookie" value="bottom-left">Flottant à gauche<br>
				<?php
			}
			
			if($position_cookie == 'bottom-right')
			{
				?>
				<input type="radio" name="position_cookie" value="bottom-right" checked>Flottant à droite<br>
				<?php
			}
			else
			{
				?>
				<input type="radio" name="position_cookie" value="bottom-right">Flottant à droite<br>
				<?php
			}
			?>
			<H2>Layout</H2>
			<?php
			if($layout_cookie == 'block')
			{
				?>
				<input type="radio" name="layout_cookie" value="block" checked>Bloc<br>
				<?php
			}
			else
			{
				?>
				<input type="radio" name="layout_cookie" value="block">Bloc<br>
				<?php
			}
			
			if($layout_cookie == 'classic')
			{
				?>
				<input type="radio" name="layout_cookie" value="classic" checked>Classique<br>
				<?php
			}
			else
			{
				?>
				<input type="radio" name="layout_cookie" value="classic">Classique<br>
				<?php
			}
			
			if($layout_cookie == 'edgeless')
			{
				?>
				<input type="radio" name="layout_cookie" value="edgeless" checked>Edgeless<br>
				<?php
			}
			else
			{
				?>
				<input type="radio" name="layout_cookie" value="edgeless">Edgeless<br>
				<?php
			}
			
			if($layout_cookie == 'wire')
			{
				?>
				<input type="radio" name="layout_cookie" value="wire" checked>Wire<br>
				<?php
			}
			else
			{
				?>
				<input type="radio" name="layout_cookie" value="wire">Wire<br>
				<?php
			}
			?>
			<H2>Palette</H2>
			<div style="overflow:auto;">
				<?php
				if($palette_color_cookie == 1)
				{
					?>
					<div class="palette-block bselected" id="palette-1" style="background-color: #000000;" onclick="selectPalette(1);">
						<div class="theme-preview-button" style="background:#f1d600;"></div>
					</div>
					<?php
				}
				else
				{
					?>
					<div class="palette-block" id="palette-1" style="background-color: #000000;" onclick="selectPalette(1);">
						<div class="theme-preview-button" style="background:#f1d600;"></div>
					</div>
					<?php
				}
				
				if($palette_color_cookie == 2)
				{
					?>
					<div class="palette-block bselected" id="palette-2" style="background-color:#eaf7f7;" onclick="selectPalette(2);">
						<div class="theme-preview-button" style="background:#56cbdb;"></div>
					</div>
					<?php
				}
				else
				{
					?>
					<div class="palette-block" id="palette-2" style="background-color:#eaf7f7;" onclick="selectPalette(2);">
						<div class="theme-preview-button" style="background:#56cbdb;"></div>
					</div>
					<?php
				}
				
				if($palette_color_cookie == 3)
				{
					?>
					<div class="palette-block bselected" id="palette-3" style="background:#252e39;" onclick="selectPalette(3);">
						<div class="theme-preview-button" style="background:#14a7d0;"></div>
					</div>
					<?php
				}
				else
				{
					?>
					<div class="palette-block" id="palette-3" style="background:#252e39;" onclick="selectPalette(3);">
						<div class="theme-preview-button" style="background:#14a7d0;"></div>
					</div>
					<?php
				}
				
				if($palette_color_cookie == 4)
				{
					?>
					<div class="palette-block bselected" id="palette-4" style="background:#000;" onclick="selectPalette(4);">
						<div class="theme-preview-button" style="background:#0f0;"></div>
					</div>
					<?php
				}
				else
				{
					?>
					<div class="palette-block" id="palette-4" style="background:#000;" onclick="selectPalette(4);">
						<div class="theme-preview-button" style="background:#0f0;"></div>
					</div>
					<?php
				}
				
				if($palette_color_cookie == 5)
				{
					?>
					<div class="palette-block bselected" id="palette-5" style="background:#3937a3;" onclick="selectPalette(5);">
						<div class="theme-preview-button" style="background:#e62576;"></div>
					</div>
					<?php
				}
				else
				{
					?>
					<div class="palette-block" id="palette-5" style="background:#3937a3;" onclick="selectPalette(5);">
						<div class="theme-preview-button" style="background:#e62576;"></div>
					</div>
					<?php
				}
				
				if($palette_color_cookie == 6)
				{
					?>
					<div class="palette-block bselected" id="palette-6" style="background:#64386b;" onclick="selectPalette(6);">
						<div class="theme-preview-button" style="background:#f8a8ff;"></div>
					</div>
					<?php
				}
				else
				{
					?>
					<div class="palette-block" id="palette-6" style="background:#64386b;" onclick="selectPalette(6);">
						<div class="theme-preview-button" style="background:#f8a8ff;"></div>
					</div>
					<?php
				}
				
				if($palette_color_cookie == 7)
				{
					?>
					<div class="palette-block bselected" id="palette-7" style="background:#237afc;" onclick="selectPalette(7);">
						<div class="theme-preview-button" style="background:#fff;"></div>
					</div>
					<?php
				}
				else
				{
					?>
					<div class="palette-block" id="palette-7" style="background:#237afc;" onclick="selectPalette(7);">
						<div class="theme-preview-button" style="background:#fff;"></div>
					</div>
					<?php
				}
				
				if($palette_color_cookie == 8)
				{
					?>
					<div class="palette-block bselected" id="palette-8" style="background:#aa0000;" onclick="selectPalette(8);">
						<div class="theme-preview-button" style="background:#ff0000;"></div>
					</div>
					<?php
				}
				else
				{
					?>
					<div class="palette-block" id="palette-8" style="background:#aa0000;" onclick="selectPalette(8);">
						<div class="theme-preview-button" style="background:#ff0000;"></div>
					</div>
					<?php
				}
				
				if($palette_color_cookie == 9)
				{
					?>
					<div class="palette-block bselected" id="palette-9" style="background:#383b75;" onclick="selectPalette(9);">
						<div class="theme-preview-button" style="background:#f1d600;"></div>
					</div>
					<?php
				}
				else
				{
					?>
					<div class="palette-block" id="palette-9" style="background:#383b75;" onclick="selectPalette(9);">
						<div class="theme-preview-button" style="background:#f1d600;"></div>
					</div>
					<?php
				}
				
				if($palette_color_cookie == 10)
				{
					?>
					<div class="palette-block bselected" id="palette-10" style="background:#1d8a8a;" onclick="selectPalette(10);">
						<div class="theme-preview-button" style="background:#62ffaa;"></div>
					</div>
					<?php
				}
				else
				{
					?>
					<div class="palette-block" id="palette-10" style="background:#1d8a8a;" onclick="selectPalette(10);">
						<div class="theme-preview-button" style="background:#62ffaa;"></div>
					</div>
					<?php
				}
				?>
				<input type="hidden" name="palette_color_cookie" id="palette_color_cookie" value="<?php echo $palette_color_cookie; ?>">
			</div>
			<H2>Link Policy</H2>
			<?php
			$policy_link_cookie = getConfig("policy_link_cookie");
			$message_cookie = getConfig("message_cookie");
			$policy_link_text_cookie = getConfig("policy_link_text_cookie");
			$allow_button_cookie = getConfig("allow_button_cookie");
			$dissmiss_cookie = getConfig("dissmiss_cookie");
			?>
			<input type="text" name="policy_link_cookie" placeholder="http://www.example.com/cookiepolicy" value="<?php echo $policy_link_cookie; ?>" class="inputbox">
			<H2>Bouton et Message</H2>
			<input type="text" name="message_cookie" value="<?php echo $message_cookie; ?>" class="inputbox">
			<label>Dissmiss button</label>
			<input type="text" name="dissmiss_cookie" value="<?php echo $dissmiss_cookie; ?>" class="inputbox">
			<label>Allow button</label>
			<input type="text" name="allow_button_cookie" value="<?php echo $allow_button_cookie; ?>" class="inputbox">
			<label>Privacy button</label>
			<input type="text" name="policy_link_text_cookie" value="<?php echo $policy_link_text_cookie; ?>" class="inputbox">
			<input type="hidden" name="action" value="1">
			<input type="submit" value="Modifier" class="btn blue">
		</form>
		<script>
		var oldpalette = 0;
		function selectPalette(id)
		{
			$('#palette-'+oldpalette).css('border','0px solid');
			$('#palette-'+id).css('border','2px solid #ff0000');
			$('#palette_color_cookie').val(id);
			oldpalette = id;
		}
		</script>
	</div>
</body>
</html>