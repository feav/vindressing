<div class="container topMargin" style="margin-bottom: 90px;">
	{validation_annonce}
	<H1>Le mie pubblicità</H1>
	<p>Trova tutti i tuoi annunci pubblicati sul sito, puoi eliminarli una volta venduti.</p>
	{block}
</div>
<script>
function showOption(id)
{
	if($('#more_option_action-'+id).css('display') == 'none')
	{
		$('#more_option_action-'+id).css('display','block');
	}
	else
	{
		$('#more_option_action-'+id).css('display','none');
	}
}
</script>