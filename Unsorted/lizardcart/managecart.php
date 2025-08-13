<? include ("config.inc.php");?>
<? include ("header.php");?>


<FONT FACE="ARIAL,HELVETICA" SIZE=2>
<div align="center"><B>The items listed above are currently in your shopping cart:</B></div>
<p>
<!-- confirm.siml -->
<FORM ACTION="checkout.php" NAME="form" METHOD="get" onsubmit="return ValidateCart(this)">

<table border=0 cellspacing=2 width=700 align=center>
<tr>
<td align=center>
 <script>
    ManageCart();
</script>
<INPUT TYPE=IMAGE SRC="graphics/checkout.gif" BORDER=0>

</FORM>

<P>
<BR>
<div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="1">
<a href="index.php"><font color="#336699">Back to products</font></a></font></div>
</td>
</tr>
</table>
</FONT>
<? include ("footer.php");?>




