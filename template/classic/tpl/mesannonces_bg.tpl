<div class="container topMargin" style="margin-bottom: 90px;">
	{validation_annonce}
	<H1>Моите реклами</H1>
	<p>Намерете всичките си реклами, публикувани на сайта, можете да ги изтриете, след като ги продадете.</p>
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