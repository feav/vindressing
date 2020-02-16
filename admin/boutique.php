<?php

include "../config.php";
include "version.php";
include "../engine/class.store.php";

$class_store = new Store();

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
	
	$email_store = getConfig("email_store");
	$password_store = getConfig("password_store");
	
	$isStoreConnected = false;
	
	if($email_store == '')
	{
		$isStoreConnected = false;
	}
	if($password_store == '')
	{
		$isStoreConnected = false;
	}
	
	?>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
	<style>
	.stats-box
	{
		float: left;
		padding: 20px;
		box-sizing: border-box;
		background-color: #00afff;
		border-radius: 5px;
		text-align: center;
		color: #fff;
		font-size: 18px;
		margin-right: 1%;
		width: 24%;
	}
	
	.select-tabs-item-store
	{
		float: left;
		padding: 20px;
		background-color: #e7e7e7;
		border: 1px solid #000;
		border-top-left-radius: 5px;
		border-top-right-radius: 5px;
		font-size: 14px;
		font-weight: bold;
	}
	
	.background-connect
	{
		position: fixed;
		width: 100%;
		height: 100%;
		background-color: rgba(0,0,0,0.8);
		top: 0;
		left: 0;
		z-index: 1000;
	}
	
	.login-connect
	{
		width: 524px;
		background-color: #fff;
		height: 300px;
		margin-left: auto;
		margin-right: auto;
		margin-top: 150px;
		border-radius: 5px;
		padding: 20px;
	}
	</style>
	<div class="container">
		<H1><i class="fas fa-store"></i> Boutique</H1>
		<div class="select-tabs-store">
			<div class="select-tabs-item-store">
			Template de site
			</div>
			<div class="select-tabs-item-store">
			Template d'email
			</div>
			<div class="select-tabs-item-store">
			Carte
			</div>
		</div>
		<?php
		if($class_store->isStoreConnected)
		{
			echo 'Connecter';
		}
		else
		{
			?>
			<div class="background-connect">
				<div class="login-connect">
					<div style="text-align:center;">
					<img src="https://www.shua-creation.com/admin/assets/img/logo.png" width=103>
					</div>
					<H2>Connecter votre compte Shua-Creation à votre produit</H2>
					<input type="email" id="email" name="email" placeholder="Votre adresse email" class="inputbox">
					<input type="password" id="password" name="password" placeholder="Votre mot de passe" class="inputbox">
					<button class="btn blue" onclick="connect();">Connecter</button>
				</div>
			</div>
			<script>
			function connect()
			{
				var email = $('#email').val();
				var password = $('#password').val();
				var error = 0;
				
				$('#email').css('border','1px solid #dddddd');
				$('#password').css('border','1px solid #dddddd');
				
				if(email == '')
				{
					error = 1;
					$('#email').css('border','1px solid #ff0000');
				}
				if(password == '')
				{
					error = 1;
					$('#password').css('border','1px solid #ff0000');
				}
				
				if(error == 0)
				{
					$.post("http://www.shua-creation.com/store/api.php?command=connect&email="+encodeURIComponent(email)+"&password="+encodeURIComponent(password), function( data ) 
					{
						if(data == 'yes')
						{
							alert('ok');
						}
						else
						{
							$('#email').css('border','1px solid #ff0000');
							$('#password').css('border','1px solid #ff0000');
						}
					});
				}
			}
			</script>
			<?php
		}
		?>
	</div>
	</div>
</body>
</html>