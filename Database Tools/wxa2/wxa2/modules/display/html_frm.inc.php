<div id=form><form name=<?=$record_form_name?> method=POST>

<?=join("",$str_fields_frm)?>
<script>
function valid_submit(frm_action)
{

frm=document.<?=$record_form_name?>;
frm.action=get_href(frm_action);
var str_msg="";

<?=join("",$str_fields_valid_submit)?>
if (str_msg!="")
	alert("<?=msg("invalid_form_submit")?>" + str_msg)
else
	frm.submit();
}
</script>
</form></div>