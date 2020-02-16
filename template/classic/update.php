<?php

/* Update du template */

include "../../config.php";

echo "*** Installation du template Classic ***<br>";

updateConfig("pictogram_categorie","false");

/* On change les texte des menus */
$SQL = "DELETE FROM pas_menu";
$pdo->query($SQL);

$SQL = "INSERT INTO `pas_menu` (`id`, `title`, `language`, `url`, `url_rewriting`, `method`) VALUES
(53, 'DEPOSER UNE ANNONCE', 'fr', 'deposer-une-annonce.php', 'deposer-une-nouvelle-annonce.html', ''),
(54, 'OFFRES', 'fr', 'offre.php', 'offre.html', ''),
(55, 'DEMANDES', 'fr', 'demande.php', 'demande.html', ''),
(56, 'MES ANNONCES', 'fr', 'mesannonces.php', 'mes-annonces.html', ''),
(57, 'BOUTIQUE', 'fr', 'boutique.php', 'boutique.php', ''),
(58, 'INSCRIPTION', 'fr', 'subcribe.php', 'inscription.html', 'inscriptionmobile'),
(59, 'CONNEXION', 'fr', 'login.php', 'connexion.html', 'connexionmobile'),
(60, 'MON COMPTE', 'fr', 'moncompte.php', 'moncompte.php', 'moncomptemobile'),
(61, 'SE DECONNECTER', 'fr', 'logout.php', 'logout.php', 'logoutmobile');";
$pdo->query($SQL);

echo "*** Integration du nouveau menu ***<br>";

/* On clean les catégories */

$remplacecat = $_REQUEST['remplacecat'];
if($remplacecat == 'yes')
{
	$SQL = "DELETE FROM pas_categorie";
	$pdo->query($SQL);

	$SQL = "INSERT INTO `pas_categorie` (`id`, `subcategorie`, `titre`, `slug`, `meta_title`, `meta_description`) VALUES
	(1, 0, 'Emploi', 'emploi', '', '<img src=\"template/classic/css/images/pictogram-job.png\">'),
	(2, 1, 'Offres d''emploi', 'offres-d-emploi', '', ''),
	(3, 0, 'VEHICULES', 'vehicules', '', ''),
	(4, 3, 'Voitures', 'voitures', '', ''),
	(5, 3, 'Motos', 'motos', '', ''),
	(6, 3, 'Caravaning', 'caravaning', '', ''),
	(7, 3, 'Utilitaires', 'utilitaires', '', ''),
	(8, 3, 'Equipement Auto', 'equipement-auto', '', ''),
	(9, 3, 'Equipement Moto', 'equipement-moto', '', ''),
	(10, 3, 'Equipement Caravaning', 'equipement-caravaning', '', ''),
	(11, 3, 'Nautisme', 'nautisme', '', ''),
	(12, 0, 'IMMOBILIER', 'immobilier', '', ''),
	(13, 12, 'Ventes immobiliéres', 'ventes-immobilieres', '', ''),
	(14, 12, 'Locations', 'locations', '', ''),
	(15, 12, 'Colocations', 'colocations', '', ''),
	(16, 12, 'Bureaux & Commerces', 'bureaux-commerces', '', ''),
	(17, 0, 'VACANCES', 'vacances', '', ''),
	(18, 17, 'Locations & Gîtes', 'locations-gites', '', ''),
	(19, 17, 'Chambres d''hôtes', 'chambres-d-hotes', '', ''),
	(20, 17, 'Campings', 'campings', '', ''),
	(21, 17, 'Hôtels', 'hotels', '', ''),
	(22, 17, 'Hébergements insolites', 'hebergements-insolites', '', ''),
	(23, 0, 'MAISON', 'maison', '', ''),
	(24, 23, 'Ameublement', 'ameublement', '', ''),
	(25, 23, 'Electroménager', 'electromenager', '', ''),
	(26, 23, 'Arts de la table', 'arts-de-la-table', '', ''),
	(27, 23, 'Décoration', 'decoration', '', ''),
	(28, 23, 'Linge de maison', 'linge-de-maison', '', ''),
	(29, 23, 'Bricolage', 'bricolage', '', ''),
	(30, 23, 'Jardinage', 'jardinage', '', ''),
	(31, 23, 'Vêtements', 'vetements', '', ''),
	(32, 23, 'Chaussures', 'chaussures', '', ''),
	(33, 23, 'Accessoires & Bagagerie', 'accessoires-bagagerie', '', ''),
	(34, 23, 'Montres & Bijoux', 'montres-bijoux', '', ''),
	(35, 23, 'Equipement bébé', 'equipement-bebe', '', ''),
	(36, 23, 'Vêtements bébé', 'vetements-bebe', '', ''),
	(37, 0, 'MULTIMEDIA', 'multimedia', '', ''),
	(38, 37, 'Informatique', 'informatique', '', ''),
	(39, 37, 'Consoles & Jeux vidéo', 'consoles-jeux-video', '', ''),
	(40, 37, 'Image & Son', 'image-son', '', ''),
	(41, 37, 'Téléphonie', 'telephonie', '', ''),
	(42, 0, 'LOISIRS', 'loisirs', '', ''),
	(43, 42, 'DVD / Films', 'dvd-films', '', ''),
	(44, 42, 'CD / Musique', 'cd-musique', '', ''),
	(45, 42, 'Livres', 'livres', '', ''),
	(46, 42, 'Animaux', 'animaux', '', ''),
	(47, 42, 'Vélos', 'velos', '', ''),
	(48, 42, 'Sports & Hobbies', 'sports-hobbies', '', ''),
	(49, 42, 'Instruments de musique', 'instruments-de-musique', '', ''),
	(50, 42, 'Collection', 'collection', '', ''),
	(51, 42, 'Jeux & Jouets', 'jeux-et-jouets', '', ''),
	(52, 42, 'Vins & Gastronomie', 'vins-et-gastronomie', '', ''),
	(53, 0, 'MATERIEL PROFESSIONNEL', '', '', ''),
	(54, 53, 'Matériel Agricole', 'materiel-agricole', '', ''),
	(55, 53, 'Transport - Manutention', 'transport-manutention', '', ''),
	(56, 53, 'BTP - Chantier Gros-oeuvre', 'btp-chantier-gros-oeuvre', '', ''),
	(57, 53, 'Outillage - Matériaux 2nd-oeuvre', 'outillage-materiaux-2nd-oeuvre', '', ''),
	(58, 53, 'Equipements Industriels', 'equipements-industriels', '', ''),
	(59, 53, 'Restauration - Hôtellerie', 'restauration-hotellerie', '', ''),
	(60, 53, 'Fournitures de Bureau', 'fournitures-de-bureau', '', ''),
	(61, 53, 'Commerces & Marchés', 'commerces-et-marchÃ©s', '', ''),
	(62, 53, 'Matériel Médical', 'materiel-medical', '', ''),
	(63, 0, 'SERVICES', '', '', ''),
	(64, 63, 'Prestations de services', 'prestations-de-services', '', ''),
	(65, 63, 'Billetterie', 'billetterie', '', ''),
	(66, 63, 'Evénements', 'evenements', '', ''),
	(67, 63, 'Cours particuliers', 'cours-particuliers', '', ''),
	(68, 63, 'Covoiturage', 'covoiturage', '', ''),
	(69, 0, 'AUTRES', '', '', ''),
	(70, 69, 'Autres', 'autres', '', ''),
	(76, 1, 'Occasions', 'occasions', '', '');";
	$pdo->query($SQL);
}

echo "*** Integration des categorie ***<br>";
echo "======================================================<br><br>";
echo "*** L'installation c'est terminée avec success ***<br><br>";
echo '<a href="'.$url_script.'/admin/template.php">Revenir à l\'administration</a>';

?>