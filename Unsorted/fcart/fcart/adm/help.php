<script language='javascript'>
function Help() {
	window.open('<? echo ($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location") ?>/h1.php?topic=<? echo $PHP_SELF ?>','_blank','width=500,height=300,toolbar=no,scrollbars=yes,resize=yes');
}
</script>
<font size="-1"><b><i>
<a href="javascript:Help()" style="text-decoration: underline">Get help...</a>
</i></b></font>
