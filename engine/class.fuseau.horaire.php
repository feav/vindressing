<?php

/* Classe fuseau horaire by Shua-Creation 2019 */
/* =========================================== */

class FuseauHoraire
{
	function __construct()
	{
	}
	
	/* Convertie une date dans un Timezone bien précis */
	function convertTimezone($dateF,$format,$timezone)
	{
		$d = new DateTime($dateF, new DateTimeZone('UTC'));
		$d->setTimezone(new DateTimeZone($timezone));
		return $d->format($format);
	}
}

?>