<?php

/* Declaration des classes */
include "class.fuseau.horaire.php";
include "class.footer.php";
include "class.social.php";
include "class.map.php";
include "class.monetaire.php";
include "class.logo.php";
include "class.user.php";
include "class.menu.php";
include "class.template.loader.php";
include "class.colordesign.php";
include "class.publicite.php";
include "class.paydunya.php";
include "class.search.php";
include "class.mail.php";
include "class.imagehelper.php";
include "class.statistique-visiteur.php";
include "class.horaire-ouverture.php";
include "class.maintenance.php";
include "class.imageuploader.php";
include "class.immo.graphique.php";
include "class.mobicash.php";
include "class.ban.php";
include "class.googleconnect.php";
include "class.plugin.php";
include "class.facebookconnect.php";
include "class.favoris.php";
include "class.stripe.php";

global $class_templateloader;
global $class_imagehelper;
global $class_horaire_ouverture;

/* Création des classes pour leur disponibilité */
$class_fuseau_horaire = new FuseauHoraire();
$class_footer = new Footer();
$class_social = new Social();
$class_map = new Map();
$class_monetaire = new Monetaire();
$class_logo = new Logo();
$class_user = new User();
$class_menu = new Menu();
$class_colordesign = new ColorDesign();
$class_templateloader = new TemplateLoader();
$class_publicite = new Publicite();
$class_paydunya = new Paydunya();
$class_search = new Search();
$class_email = new Email();
$class_imagehelper = new ImageHelper();
$class_statistique_visiteur = new Statistique_Visiteur();
$class_horaire_ouverture = new Horaire_Ouverture();
$class_maintenance = new Maintenance();
$class_image_uploader = new ImageUploader();
$class_immo_graphique = new Graphique_Immo();
$class_mobicash = new Mobicash();
$class_ban = new Ban();
$class_google_connect = new GoogleConnect();
$class_plugin = new Plugin();
$class_facebook_connect = new FacebookConnect();
$class_favoris = new Favoris();
$class_stripe = new StripePaiement();

$class_maintenance->checkMaintenance();
$class_statistique_visiteur->addVisite();

?>