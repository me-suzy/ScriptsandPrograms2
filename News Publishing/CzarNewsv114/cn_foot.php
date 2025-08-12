<?
if(file_exists("install.php") && $useri[admin] == "on") {
	print "<font color=#ff0000>Warning: \"install.php\" is a security risk, please delete the file from your server.</font><br>";
}
?>
<br>
</td></tr>
</table>

		</td>
	</tr>
	<tr>
		<td align="right">

<?

/*

PLEASE DO NOT REMOVE THE LINK BELOW
A lot of effort went into the creation of this script, and I give it away for free without
asking for any money in return.  The least you can do is link back to www.czaries.net to
give me credit for making the script.

Please either:
a) Leave this link intact where it is
b) Remove the link and link to www.czaries.net somewhere else on your website
c) Pay $35 for full rights to remove the link and all mention of my authorship of the script
   [ Paypal email: czaries@czaries.net ].  Please type in the URL of your website
   in the 'comments' field if you choose this route.

Note: If you are re-selling CzarNews (like installing it on a client's website and charging for
      it), please pay the $35 (option c) and remove the copyrights for a professional look.

I appreciate that you are using my news script

- Vance Lucas aka "Czaries"
  http://www.czaries.net

*/

?>

CzarNews v<? print $set[version]; ?> copyright &copy; <?=date("Y");?> Vance Lucas<br>
Please visit <a href="http://www.czaries.net/scripts/" target="_blank" class="creditlink">Czaries.net</a> for more scripts & updates
		</td>
	</tr>
</table>

</body>

</html>