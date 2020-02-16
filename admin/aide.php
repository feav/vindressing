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

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Administration PAS Script v<?php echo $version; ?></title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<script src="js/jscolor.min.js"></script>
</head>
<body>
	<?php
	
	include "header.php";
	include "sidebar.php";
	
	?>
	<div class="container">
		<?php
		
		$data = file_get_contents("http://www.shua-creation.com/help/aide-en-ligne-pas-script-v1.26.5.php");
		echo $data;
		
		?>
		<H2>Design & Apparence</H2>
		Dans la section "Design & Apparence" vous aurez la possibilité de regler les couleurs et certain élément de votre site internet, sont aspect esthétique. Comme par exemple changer la couleur de la carte, des boutons, etc, etc...
		Pour modifier une couleur il vous suffit de cliquez sur l'élément à changer et de selectionner la couleur choisi dans le nuancier. La couleur apparait ensuite ainsi que sa référence hexadecimal qui vous pourrez utiliser directement
		pour regler vos couleur si vous souhaitez être précis.
		<H2>Maintenance</H2>
		Dans cette section vous aurez la possibilité de mettre votre site en maintenance et d'autoriser ou non un IP à la visiter en maintenance, par exemple la votre. Vous pouvez également personnaliser le message que les utilisateurs veront
		lors de cette maintenance et vous avez la possibilité d'ajouter du HTML pour l'agrémenter.
		<H2>Annonces</H2>
		<p>
		Vous retrouverez dans cette partie toute les annonces poster par les utilisateur sur votre site internet, les annonces sont ranger par Ordre des dernières annonces poster. Vous aurez la possibilité de voir le STATUS de l'annonce qui pourra vous indiquer
		dans quel état est l'annonces, voici les différents état disponible qui vous permettrons également de detecter un problème en particulier :<br><br>
		- "Payer avec Paypal" l'annonce à été regler avec succés avec Paypal.<br>
		- "Payer avec Stripe" l'annonce à été regler avec succés avec Stripe.<br>
		- "Erreur de paiement Stripe" lors du paiement la carte de l'utilisateur à été rejeté ou une erreur à été rencontrer.<br>
		- "Erreur de reception IPN Paypal" le retour de paiement IPN de Paypal n'as pas pu être récuperer vérifier votre configuration IPN et si votre serveur ou hebergement supporte le SSL.<br>
		- "En attente de réglement" l'annonce est en attente de réglement.<br>
		- "Annonce gratuite" l'annonce est terminée et l'utilisateur n'as pas choisi d'option supplémentaire.<br><br>
		Vous pourrez également dans cette interface valider les annonces, les controlés, les supprimer et les voir une fois valider sur le site.
		</p>
		<H2>Signalement</H2>
		<p>
		Dans cette partie de l'administration vous pouvez retrouver l'ensemble des signalements fait par vos utilisateur et ainsi consulter les annonces qui pourrait poser problème. Vous aurez le choix entre supprimer le signalement, supprimer l'annonces
		concernée et la visionner.
		</p>
		<H2>Utilisateurs</H2>
		<p>
		Dans cette partie vous retrouverez tous les utilisateur de votre site internet et verifier les compte activer ou non, les supprimer et les bannir de votre site internet au besoin.
		</p>
		<H2>Langue du site</H2>
		<p>
		Cette partie est encore en développement et est partiellement disponible en v1.25, elle permettra à long terme de changer les termes de traduction du site, de pouvoir changer la langue de celui-ci facilement et
		d'integrer un système multilangue pour laisser le choix au utilisateur de changer la langue si vous souhaitez que votre site soit multilangue.
		</p>
		<H2>Publicité</H2>
		<p>
		Cette partie vous permettra d'integrer de la publicité sur votre site internet, deux encart sont prévu sur le script :<br><br>
		- "Publicités Top" qui correspond à la page d'accueil en Haut.<br>
		- "Publicités Left Annonce" qui correspond à la page d'une annonce à droite.<br><br>
		Vous pouvez y inserez du code HTML pour y inserez vos propre Publicité ou bien utiliser une régie Publicitaire pour monetiser le traffic de votre site internet.<br>
		Quelque régie publicitaire que nous vous conseillons :<br><br>
		- "<a href="https://www.google.com/adsense/" target="newpage">Google Adsence</a>" la régie publicitaire de Google, paiement par tranche de 70€ de revenue.<br>
		- "<a href="http://www.adnow.com" target="newpage">AdNow</a>" une régie internationale qui paye à partir de 20$ de gain sur Paypal.<br><br>
		Vous trouverez bien d'autre régie publicitaire à vous de faire votre choix, j'utilise depuis de nombreuse année Google Adsence qui reste la référence au niveau stabilité, fiabilité de paiement et revenue,
		sacher que plus votre site génére de traffic plus vos revenu publicitaire seront élever, les revenus publicitaire reste aujourd'hui un complement de revenu et pour garantir un gain grâce à votre site internet
		ne doit pas être basé uniquement sur cette monetisation, il peux avoir des exceptions et certain site peuvent vivre uniquement de celà.
		</p>
		<H2>Configuration de la remonter automatique des annonces</H2>
		<p>
		La remonter automatique des annonces doit être configurer depuis votre hebergement grâce au tache CRON ou "CRONTAB" de votre heberger, si vous ne configurer pas les remonter vos annonces ne remontera
		pas en tête de liste comme vos client les ont commander. En fonction de votre hebergement faite executer le fichier "cron.php" qui se trouve à la racine du script et faite en sorte qu'il soit executer
		toute les heures. Le script inspecte les annonces à remonter et effectue les modification automatiquement une fois configurer.<br><br>
		Votre hebergement se trouve chez OVH voici une petite aide <a href="https://docs.ovh.com/fr/hosting/mutualise-taches-automatisees-cron/" target="newpage">ici</a><br>
		Votre hebergement se trouve sur 1and1 voici une petite aide <a href="https://assistance.1and1.fr/hebergement-c65619/programmation-c65626/taches-cron-c65659/creer-une-nouvelle-tache-cron-a703882.html" target="newpage">ici</a>
		</p>
		<H2>SEO</H2>
		<p>
		La partie SEO vous permet d'indiquer sur chaque page de votre site internet, les balises TITLE et META DESCRIPTION pour améliorer le référencement de votre site internet, vous pourrez indiquer dans la page_annonce
		des informations spécifique qui se rempliront en fonction des annonces par exemple :<br><br>
		<b>%title%</b> - Le titre de l'annonce<br>
		<b>%region%</b> - Le departement de l'annonce<br>
		<b>%ville%</b> - La ville de l'annonce<br>
		</p>
		<H2>Page(s)</H2>
		<p>
		Cette partie vous permet de créer des pages statique pour votre site internet, facilement éditable avec un editeur WYSIWYG supportant autant le HTML que l'édition classique comme sous Word. Vous aurez
		la possibilité de modifier la balise TITLE et META DESCRIPTION de chaque page créer pour optimiser vos page pour la référencement SEO. Chaque page peux être ensuite disposer dans le footer de votre site
		dans la colonne appropriée.
		</p>
		<H2>Footer</H2>
		<p>
		Vous aurez la possibilité de personnaliser le nombre de colonne que vous souhaitez afficher dans le Footer de votre site, de changer ou supprimer des colonnes, de modifier leur titre facilement et
		d'ajouter des liens dans chaque colonne qui pointeront sur une page statique que vous aurez préalablement créer. Voir partie Page(s).
		</p>
	</div>
</body>
</html>