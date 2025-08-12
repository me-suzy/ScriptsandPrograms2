<?

// Include required files
include("includes/php/globals.php");
include("includes/php/functions.php");

CreateForm("index.php", $stage);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>PHP CSV Importer: v<?=$scriptVersion?> by <?=$authorName?></title>
	<link rel="stylesheet" href="includes/css/csv_importer.css" type="text/css">
	<script>
		<?=$js_block?>
	</script>
	<?=$js_scripts?>
</head>

<body onLoad="<?=$bodyOnLoad?>">
<table width="780" border="0" cellspacing="1" cellpadding="2" align="center">
  <tr class="title"> 
    <td>PHP CSV Importer</td>
  </tr>
  <tr>
    <td>
    		<table border="0" cellspacing="0" cellpadding="2" width="<? echo ($tableWidth) ? $tableWidth : "640" ?>" align="center">
			<tr class="instructions">
				<td><?= $instructions?></td>
			</tr>
			<tr class="spacer">
				<td>&nbsp;</td>
			</tr>
			<form name="form1" action="<?=$PHP_SELF?>" method="POST" onSubmit="return(ValidateForm(this<?=$validateFormArgs?>))">
				<tr>
					<td>
						<table cellspacing=1 cellpadding=0 border=0 width=100%>
							<?=$display_block?>
						</table>
					</td>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr class="submit">
					<td><input type="submit" name="mainSubmit" value="<? echo ($submitValue) ? $submitValue : "Next &raquo;"?>"></td>
				</tr>
				<input type="hidden" name="stage" value="<?=$stage?>">			
			</form>
		</table>
	</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr class="footer"> 
    <td>Author: <?=$authorName?> | Version: <?=$scriptVersion?> | <a href="mailto:sir_tripod@hotmail.com?subject=CSV Importer Feeback ~">Send Feedback</a></td>
  </tr>
</table>

</body>
</html>
