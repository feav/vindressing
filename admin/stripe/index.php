<html>
<body>
<H1>Exemple de paiement Stripe</H1>
<?php

include "class.stripe.php";

$stripe = new StripeSC("pk_test_nKwkqzEeFmvNbVcYoNbw1kzQ","sk_test_cRQp8dTiBlqGahCxGfYO37L2","","");
$stripe->setInfoPaiement("Produit test","1","EUR","Produit de test");
$stripe->showButton(1,10,12);

?>
</body>
</html>