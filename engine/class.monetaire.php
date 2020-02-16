<?php

/* Classe Monetaire Shua-Creation.com 2018 */

class Monetaire
{
	var $currency;
	var $currencyposition;
	var $currencysigle;
	
	/* Fonction globale de renvoie de config */
	function getConfig($identifiant)
	{
		global $pdo;
		
		$SQL = "SELECT * FROM pas_configuration WHERE identifiant = '$identifiant'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		return $req['code'];
	}
	
	/* Renvoie le code ISO de la currency selectionnez */
	function getCurrencyCode()
	{
		return strtolower($this->currency);
	}
	
	/* Renvoie toute les monnaies supporter */
	function getAllCurrencySupported()
	{
		$array[0]['titre'] = 'Euro';
		$array[0]['currency'] = 'EUR';
		$array[0]['sigle'] = '€';
		$array[1]['titre'] = 'Dollars US';
		$array[1]['currency'] = 'USD';
		$array[1]['sigle'] = '$';
		$array[2]['titre'] = 'Dollars Canadien';
		$array[2]['currency'] = 'CAD';
		$array[2]['sigle'] = '$';
		$array[3]['titre'] = 'Franc CFA';
		$array[3]['currency'] = 'FCFA';
		$array[3]['sigle'] = 'FCFA';
		$array[4]['titre'] = 'Francs Suisse';
		$array[4]['currency'] = 'CHF';
		$array[4]['sigle'] = 'CHF';
		$array[5]['titre'] = 'Livre sterling';
		$array[5]['currency'] = 'GBP';
		$array[5]['sigle'] = '£';
		$array[6]['titre'] = 'Dollars Australien';
		$array[6]['currency'] = 'AUD';
		$array[6]['sigle'] = '$';
		$array[7]['titre'] = 'Réal Brézilien';
		$array[7]['currency'] = 'BRL';
		$array[7]['sigle'] = 'R$';
		$array[8]['titre'] = 'Dinar Algérien';
		$array[8]['currency'] = 'DZD';
		$array[8]['sigle'] = 'DA';
		$array[9]['titre'] = 'Dinar Tunisien';
		$array[9]['currency'] = 'TND';
		$array[9]['sigle'] = 'DT';
		$array[10]['titre'] = 'Dirham Marocain';
		$array[10]['currency'] = 'MAD';
		$array[10]['sigle'] = 'DH';
		$array[11]['titre'] = 'Leva Bulgare';
		$array[11]['currency'] = 'BGN';
		$array[11]['sigle'] = 'лв';
		$array[12]['titre'] = 'Livre égyptienne';
		$array[12]['currency'] = 'EGP';
		$array[12]['sigle'] = 'E£';
		$array[13]['titre'] = 'Dinar libyen';
		$array[13]['currency'] = 'LYD';
		$array[13]['sigle'] = 'dinar';
		$array[14]['titre'] = 'Livre soudanaise';
		$array[14]['currency'] = 'SDG';
		$array[14]['sigle'] = 's£';
		$array[15]['titre'] = 'Escudo du Cap-Vert';
		$array[15]['currency'] = 'CVE';
		$array[15]['sigle'] = 'escudo';
		$array[16]['titre'] = 'Dalasi';
		$array[16]['currency'] = 'GMD';
		$array[16]['sigle'] = 'dalasi';
		$array[17]['titre'] = 'Cedi';
		$array[17]['currency'] = 'GHS';
		$array[17]['sigle'] = '₵';
		$array[18]['titre'] = 'Franc Guinéen';
		$array[18]['currency'] = 'GNF';
		$array[18]['sigle'] = 'F';
		$array[18]['titre'] = 'Naira';
		$array[18]['currency'] = 'NGN';
		$array[18]['sigle'] = 'N';
		$array[19]['titre'] = 'Leone';
		$array[19]['currency'] = 'SLL';
		$array[19]['sigle'] = 'leone';
		$array[20]['titre'] = 'Franc Congolais';
		$array[20]['currency'] = 'CDF';
		$array[20]['sigle'] = 'F/FC';
		$array[21]['titre'] = 'Dobra';
		$array[21]['currency'] = 'STD';
		$array[21]['sigle'] = 'dobra';
		$array[22]['titre'] = 'Rouble russe';
		$array[22]['currency'] = 'RUB';
		$array[22]['sigle'] = '₽';
		$array[23]['titre'] = 'Yuan renminbi';
		$array[23]['currency'] = 'CNY';
		$array[23]['sigle'] = '¥';
		$array[24]['titre'] = 'Won Nord-Coréen';
		$array[24]['currency'] = 'KPW';
		$array[24]['sigle'] = '￦';
		$array[25]['titre'] = 'Tugrik';
		$array[25]['currency'] = 'MNT';
		$array[25]['sigle'] = '₮';
		$array[26]['titre'] = 'Nouveau dollar de Taïwan';
		$array[26]['currency'] = 'TWD';
		$array[26]['sigle'] = '元';
		$array[27]['titre'] = 'Livre turque';
		$array[27]['currency'] = 'TRY';
		$array[27]['sigle'] = '₺';
		$array[28]['titre'] = 'Couronne danoise';
		$array[28]['currency'] = 'DKK';
		$array[28]['sigle'] = 'kr';
		$array[29]['titre'] = 'forint hongrois';
		$array[29]['currency'] = 'HUF';
		$array[29]['sigle'] = 'Ft';
		$array[30]['titre'] = 'Shekel';
		$array[30]['currency'] = 'ILS';
		$array[30]['sigle'] = '₪';
		$array[31]['titre'] = 'Baht';
		$array[31]['sigle'] = '฿';
		$array[31]['currency'] = 'THB';
		$array[32]['titre'] = 'Florin Néerlandais';
		$array[32]['currency'] = 'NLG';
		$array[32]['sigle'] = 'ƒ';
		$array[33]['titre'] = 'Roupie indienne';
		$array[33]['currency'] = 'INR';
		$array[33]['sigle'] = '₹';
		$array[34]['titre'] = 'Shilling kényan';
		$array[34]['currency'] = 'KES';
		$array[34]['sigle'] = 'KSh';
		$array[35]['titre'] = 'Yen';
		$array[35]['currency'] = 'JPY';
		$array[35]['sigle'] = '¥';
		$array[36]['titre'] = 'Franc guinéen';
		$array[36]['currency'] = 'GNF';
		$array[36]['sigle'] = 'Fr';
		
		return $array;
	}
	
	/* Convertie un prix selon les options choisie */
	function getReturnPrice($prix)
	{
		$this->currency_show = $this->getConfig("currency_show");
		
		if($this->currencyposition == 2)
		{		
			if($this->currency_show == 1)
			{
				return number_format((float)$prix,0,'.','').' '.$this->currencysigle;
			}
			else if($this->currency_show == 2)
			{
				return number_format((float)$prix,2,',','').' '.$this->currencysigle;
			}
			else if($this->currency_show == 3)
			{
				return number_format((float)$prix,2,'.','').' '.$this->currencysigle;
			}
			else if($this->currency_show == 4)
			{
				return number_format((float)$prix,0,'.',' ').' '.$this->currencysigle;
			}
			else if($this->currency_show == 5)
			{
				return number_format((float)$prix,2,',',' ').' '.$this->currencysigle;
			}
			else if($this->currency_show == 6)
			{
				return number_format((float)$prix,2,'.',' ').' '.$this->currencysigle;
			}		
		}
		if($this->currencyposition == 1)
		{		
			if($this->currency_show == 1)
			{
				return $this->currencysigle.' '.number_format((float)$prix,0,'.','');
			}
			else if($this->currency_show == 2)
			{
				return $this->currencysigle.' '.number_format((float)$prix,2,',','');
			}
			else if($this->currency_show == 3)
			{
				return $this->currencysigle.' '.number_format((float)$prix,2,'.','');
			}
			else if($this->currency_show == 4)
			{
				return $this->currencysigle.' '.number_format((float)$prix,0,'.',' ');
			}
			else if($this->currency_show == 5)
			{
				return $this->currencysigle.' '.number_format((float)$prix,2,',',' ');
			}
			else if($this->currency_show == 6)
			{
				return $this->currencysigle.' '.number_format((float)$prix,2,'.',' ');
			}		
		}
	}
	
	function __construct()
	{
		$this->currency = $this->getConfig("currency");
		$this->currencyposition = $this->getConfig("currency_position");
		if($this->currency == 'EUR')
		{
			$this->currencysigle = "€";
		}
		if($this->currency == 'USD')
		{
			$this->currencysigle = "$";
		}
		if($this->currency == 'CAD')
		{
			$this->currencysigle = "$";
		}
		if($this->currency == 'FCFA')
		{
			$this->currencysigle = "F CFA";
		}
		if($this->currency == 'CHF')
		{
			$this->currencysigle = "CHF";
		}
		if($this->currency == 'GBP')
		{
			$this->currencysigle = "£";
		}
		if($this->currency == 'AUD')
		{
			$this->currencysigle = "$";
		}
		if($this->currency == 'BRL')
		{
			$this->currencysigle = "R$";
		}
		if($this->currency == 'DZD')
		{
			$this->currencysigle = "DA";
		}
		if($this->currency == 'MAD')
		{
			$this->currencysigle = "DH";
		}
		if($this->currency == 'BGN')
		{
			$this->currencysigle = "лв";
		}
		if($this->currency == 'TND')
		{
			$this->currencysigle = 'DT';
		}
		if($this->currency == 'CNY')
		{
			$this->currencysigle = '¥';
		}
		if($this->currency == 'THB')
		{
			$this->currencysigle = '฿';
		}
		if($this->currency == 'TWD')
		{
			$this->currencysigle = 'NT$';
		}
		if($this->currency == 'ILS')
		{
			$this->currencysigle = '₪';
		}
		if($this->currency == 'EGP')
		{
			$this->currencysigle = 'E£';
		}
		if($this->currency == 'LYD')
		{
			$this->currencysigle = 'Dinar';
		}
		if($this->currency == 'SDG')
		{
			$this->currencysigle = 's£';
		}
		if($this->currency == 'CVE')
		{
			$this->currencysigle = 'escudo';
		}
		if($this->currency == 'GMD')
		{
			$this->currencysigle = 'dalasi';
		}
		if($this->currency == 'GHS')
		{
			$this->currencysigle = '₵';
		}
		if($this->currency == 'NGN')
		{
			$this->currencysigle = 'N';
		}
		if($this->currency == 'SLL')
		{
			$this->currencysigle = 'leone';
		}
		if($this->currency == 'CDF')
		{
			$this->currencysigle = 'F/FC';
		}
		if($this->currency == 'STD')
		{
			$this->currencysigle = 'dobra';
		}
		if($this->currency == 'RUB')
		{
			$this->currencysigle = '₽';
		}
		if($this->currency == 'CNY')
		{
			$this->currencysigle = '¥';
		}
		if($this->currency == 'KPW')
		{
			$this->currencysigle = '￦';
		}
		if($this->currency == 'MNT')
		{
			$this->currencysigle = '₮';
		}
		if($this->currency == 'TWD')
		{
			$this->currencysigle = '元';
		}
		if($this->currency == 'TRY')
		{
			$this->currencysigle = '₺';
		}
		if($this->currency == 'DKK')
		{
			$this->currencysigle = 'kr';
		}
		if($this->currency == 'HUF')
		{
			$this->currencysigle = 'Ft';
		}
		if($this->currency == 'THB')
		{
			$this->currencysigle = '฿';
		}
		if($this->currency == 'NLG')
		{
			$this->currencysigle = 'ƒ';
		}
		if($this->currency == 'INR')
		{
			$this->currencysigle = '₹';
		}
		if($this->currency == 'KES')
		{
			$this->currencysigle = 'KSh';
		}
		if($this->currency == 'JPY')
		{
			$this->currencysigle = '¥';
		}
		if($this->currency == 'GNF')
		{
			$this->currencysigle = 'Fr';
		}
	}
	
	function getSigle()
	{
		return $this->currencysigle;
	}
}

?>