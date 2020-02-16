<?php

include "main.php";

$code = AntiInjectionSQL($_REQUEST['code']);

if(is_numeric($code))
{
	$SQL = "SELECT * FROM pas_ville WHERE codepostal like '$code%'";
}
else
{
	$code = strtoupper($code);
	$SQL = "SELECT * FROM pas_ville WHERE nom like '$code%'";
}

$listing_type_recherche = getConfig("listing_type_recherche");

if($listing_type_recherche == 'ville')
{
	?>
	<ul>
	<?php
	$reponse = $pdo->query($SQL);
	while($req = $reponse->fetch())
	{
		$nom = $req['nom'];
		$nom = str_replace("'","\'",$nom);
		?>
		<a href="javascript:void(0);" onclick="updateText('<?php echo $req['codepostal']; ?>','<?php echo $nom; ?>','<?php echo $req['idregion']; ?>');"><li><?php echo $req['nom']; ?></li></a>
		<?php
	}
	?>
	</ul>
	<?php
}
else if($listing_type_recherche == 'codepostal')
{
	?>
	<ul>
	<?php
	$reponse = $pdo->query($SQL);
	while($req = $reponse->fetch())
	{
		$nom = $req['nom'];
		$nom = str_replace("'","\'",$nom);
		?>
		<a href="javascript:void(0);" onclick="updateText('<?php echo $req['codepostal']; ?>','<?php echo $nom; ?>','<?php echo $req['idregion']; ?>');"><li><?php echo $req['codepostal']; ?></li></a>
		<?php
	}
	?>
	</ul>
	<?php
}
else if($listing_type_recherche == 'codepostalville')
{
	?>
	<ul>
	<?php
	$reponse = $pdo->query($SQL);
	while($req = $reponse->fetch())
	{
		$nom = $req['nom'];
		$nom = str_replace("'","\'",$nom);
		?>
		<li><a href="javascript:void(0);" onclick="updateText('<?php echo $req['codepostal']; ?>','<?php echo $nom; ?>','<?php echo $req['idregion']; ?>');"><?php echo $req['codepostal']; ?> - <?php echo $req['nom']; ?></a></li>
		<?php
	}
	?>
	</ul>
	<?php
}

?>