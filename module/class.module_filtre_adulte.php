<?php

class Module_filtre_Adulte
{
	function __construct()
	{
		$this->init();
	}
	
	function init()
	{
		session_start();
	}
	
	function show()
	{
		global $url_script;
		$actual_link = "http://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI];
		
		if($_SESSION['adulte'] == NULL)
		{
			$_SESSION['adulte'] = 0;
		}
		
		if($_SESSION['adulte'] == 0)
		{
		?>
		<style>
		.mask
		{
			position: fixed;
			z-index: 100;
			background-color: rgba(0,0,0,0.8);
			width: 100%;
			height: 100%;
			top: 0;
		}
		
		.popup
		{
			width: 30%;
			height: auto;
			background-color: #ffffff;
			margin-left: auto;
			margin-right: auto;
			margin-top: 20px;
			border-radius: 5px;			
			padding: 10px;
			padding-bottom: 10px;
			padding-bottom: 20px;
		}
		
		.btnAccept
		{
			padding-left: 10px;
			padding-right: 10px;
			padding-top: 5px;
			padding-bottom: 5px;
			background-color: #1fcb05;
			border-radius: 5px;
			color: #ffffff;
			text-decoration: none;
			font-weight: bold;
			margin-right: 10px;
		}
		
		.btnRefus
		{
			padding-left: 10px;
			padding-right: 10px;
			padding-top: 5px;
			padding-bottom: 5px;
			background-color: #cb0505;
			border-radius: 5px;
			color: #ffffff;
			text-decoration: none;
			font-weight: bold;
			margin-right: 10px;
		}
		</style>
		<div class="mask" id="mask">
			<div class="popup">
				<center><H3>Avertissement</H3></center>
				La rubrique dans laquelle vous êtes sur le point d'entrer contient des petites annonces pour adultes. La consultation et l'insertion d'annonces dans cette rubrique n'est pas autorisé à tous les utilisateurs.<br>
				En cliquant sur le bouton "J'accepte", vous déclarez être autorisé(e) à entrer dans la rubrique "Erotique" et certifiez :<br><br>
				1. Avoir l'âge de la majorité légale telle que définie dans votre pays ou région<br>
				2. Etre conscient(e) du caractère choquant de certains textes ou images de certaines annonces<br>
				3. Ne jamais promouvoir, ou dévoiler l'existence des rubriques de petitesannonces.ch destinées à un publique d'adultes avertis auprès de mineurs<br>
				4. Mettre en oeuvre tous les moyens existants à ce jour pour empêcher les mineurs d'utiliser votre ordinateur pour parvenir à ce service<br>
				5. Etre conscient(e) du fait que <?php echo $url_script; ?> n'a nullement l'obligation d'effacer les annonces ou parties d'annonces vous offensant<br><br>
				<center><a href="javascript:void(0);" onclick="closepopup();" class="btnAccept">J'accepte et j'ai plus de 18 ans</a> <a href="javascript:void(0);" onclick="refus();" class="btnRefus">Je refuse</a></center>
			</div>
		</div>
		<script>
		function closepopup()
		{
			$('#mask').css('display','none');
			window.location.href = 'module/acceptadulte.php?url=<?php echo $actual_link; ?>';
		}
		
		function refus()
		{
			window.location.href = '<?php echo $url_script; ?>';
		}
		</script>
		<?php
		}
	}
}

?>