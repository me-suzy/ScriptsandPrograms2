<?php
	include ("vars.inc.php");
	
	$altavista=(int)get_pop_altavista($domain)."<br>";
	$google=(int)get_pop_google($domain)."<br>";
	$msn=(int)get_pop_ms($domain)."<br>";
	$yahoo=(int)get_pop_yahoo($domain)."<br>";

?>

<link href="slim.css" rel="stylesheet" type="text/css">
<form name="form1" method="post" action="link_pop.php">
  <blockquote>
    <p>Enter a domain to check:</p>
    <p>&nbsp;</p>
    <p>
      <input name="domain" type="text" id="domain">
      <input type="submit" name="Submit" value="Submit">
        </p>
  </blockquote>
</form>
<blockquote>
  <p>Query for <font color="#FF0000"><?php echo $domain; ?></font></p>
  <p>&nbsp;</p>
  <p>Google: <font color="#FF0000"><?php echo $google; ?></font><br>
    Altavista: <font color="#FF0000"><?php echo $altavista; ?></font><br>
    Yahoo: <font color="#FF0000"><?php echo $yahoo; ?></font><br>
    MSN:<font color="#FF0000"> <?php echo $msn; ?></font><br>
    Total: <font color="#FF0000"><?php echo $google+$altavista+$yahoo+$msn ?></font></p>
</blockquote>
