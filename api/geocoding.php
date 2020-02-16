<?php

class geocoding
{
	function __construct($apiKey,$adress)
	{
		$adress = urlencode($adress);
		$url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$adress.'key='.$apiKey;
		$data = file_get_contents($url);
		
		print_r($data);
	}
}

$geocoding = new geocoding("AIzaSyCTHv9M-ACHp_JPLVHzVRmT1Fw45xDBjfs","33690 Lavazan");

?>