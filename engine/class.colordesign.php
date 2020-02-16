<?php

/* Color Design (Class optionel) Shua-Creation 2018 */

class ColorDesign
{
	function __construct()
	{
		
	}
	
	/* Fonction globale de renvoie de config */
	function getConfig($identifiant)
	{
		global $pdo;
		
		$SQL = "SELECT * FROM pas_configuration WHERE identifiant = '$identifiant'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		return $req['code'];
	}
	
	function showColorDesign()
	{
		$data = NULL;
		$data .= '<style>';
		$data .= 'header'."\n";
		$data .= '{';
		$data .= 'background-color:#'.$this->getConfig("color_header").' !important;';
		$data .= '}';
		$data .= '.btnConfirm';
		$data .= '{';
		$data .= 'background-color:#'.$this->getConfig("color_button_confirm").' !important;';
		$data .= '}';
		$data .= '.btnConfirm:hover';
		$data .= '{';
		$data .= 'background-color:#'.$this->getConfig("color_button_confirm_survol").' !important;';
		$data .= '}';
		$data .= '.btnConnect';
		$data .= '{';
		$data .= 'background-color:#'.$this->getConfig("color_button_connexion").' !important;';
		$data .= '}';
		$data .= '.btnConnect:hover';
		$data .= '{';
		$data .= 'background-color:#'.$this->getConfig("color_button_connexion_hover").' !important;';
		$data .= '}';
		$data .= '.paging-blue';
		$data .= '{';
		$data .= 'background-color:#'.$this->getConfig("color_button_paging").' !important;';
		$data .= '}';
		$data .= '.pageselected';
		$data .= '{';
		$data .= 'background-color:#'.$this->getConfig("color_button_paging_select").' !important;';
		$data .= '}';
		$data .= '.prefooter';
		$data .= '{';
		$data .= 'background-color:#'.$this->getConfig("color_footer").' !important;';
		$data .= '}';
		$data .= '.prix-block-annonce';
		$data .= '{';
		$data .= 'color:#'.$this->getConfig("color_price_annonce").' !important;';
		$data .= '}';
		//$data .= '.nbr-photo';
		//$data .= '{';
		//$data .= 'background-color:#'.$this->getConfig("color_number_annonce").' !important;';
		//$data .= '}';
		$data .= '.orangBtn';
		$data .= '{';
		$data .= 'background-color:#'.$this->getConfig("color_button_phone").' !important;';
		$data .= '}';
		$data .= '.social-box-container';
		$data .= '{';
		$data .= 'background-color:#'.$this->getConfig("color_social_button").' !important;';
		$data .= '}';
		$data .= '.social-box-container:hover';
		$data .= '{';
		$data .= 'background-color:#'.$this->getConfig("color_social_button_survol").' !important;';
		$data .= '}';
		$data .= '</style>';
		
		return $data;
	}
}

?>