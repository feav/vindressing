<?php

class regiepub
{
	function __construct()
	{
	}
	
	function load($data)
	{
		$data = str_replace("{test}","ok ca marche",$data);
		return $data;
	}
}

?>