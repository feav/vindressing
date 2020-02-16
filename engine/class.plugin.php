<?php

/* Classe de chargement des plugins Shua-Creation.com 2019 */

class Plugin
{
	var $array_class;
	
	function __construct()
	{
		global $pdo;
		global $upload_path;
		
		$SQL = "SELECT * FROM plugin";
		$reponse = $pdo->query($SQL);
		while($req = $reponse->fetch())
		{
			$class = $req['class'];
			$nameclass = $req['nameclass'];
			$directory = $req['directory'];
			require($_SERVER['DOCUMENT_ROOT']."/".$upload_path."/admin/plugin/$directory/$class");
			
			$this->array_class[] = new $nameclass();
		}
	}
	
	/* Permet d'utiliser le template pour appliquer des modifications sur un Plugin */
	function useTemplate($data)
	{
		for($x=0;$x<count($this->array_class);$x++)
		{
			$data = $this->array_class[$x]->load($data);
		}
		
		return $data;
	}
}

?>