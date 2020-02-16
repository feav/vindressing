<?php

include "../config.php";

$marque = $_REQUEST['marque'];

?>
<option value="0">Tous</option>
<?php

$SQL = "SELECT * FROM pas_filtre_auto_modele WHERE marque = '$marque'";
$reponse = $pdo->query($SQL);
while($req = $reponse->fetch())
{
	?>
	<option value="<?php echo $req['modele']; ?>"><?php echo $req['modele']; ?></option>
	<?php
}

?>