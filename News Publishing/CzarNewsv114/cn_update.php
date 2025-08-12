<?
$pagetitle = "Check for Updates";
include("cn_auth.php");
include("cn_head.php");
?>

<form method="post" action="<? print $_SERVER['PHP_SELF']; ?>" name="theform">
<table width="100%" border="0" cellspacing="1" cellpadding="2" align="center">
<tr><td align="center">
	<table width="250" border="0" cellpadding="1" cellspacing="0" align="center" bgcolor="#000000">
	  <tr>
	   <td>
	   <table width="100%" border="0" cellpadding="5" cellspacing="0" align="center" bgcolor="#DDDDFF">
		 <tr>
		  <td>
		  <p>Your Version: <? print $cnver; ?></p>
<?
if($_POST['op'] == "check") {
	
	if(function_exists('curl_init')) { 
		// New way of checking current version
		$url = "http://www.czaries.net/scripts/autoupdate/czarnews.php";
		$params = "ver=$cnver&site=$set[siteurl]";
		$user_agent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";
	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
		curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  // this line makes it work under https
		
		$streamcontent=curl_exec($ch);
		curl_close ($ch);
		
	} else { // old way
	
		// Get contents of text file to see what latest version is
		$czarstream = @fopen("http://www.czaries.net/scripts/autoupdate/czarnews.php", "rb");
		$streamcontent = @fread($czarstream,2048);
		@fclose($czarstream);
	
	}

	$newver = 0;
	list($newver,$scripturl,$dlurl) = explode(" | ",$streamcontent);
		
	if(!$czarstream  && !$ch) {
		print "Czaries.net could not be contacted, or the server is down.  Please try again later.";
	} elseif($newver > $cnver) {
		// Update is available
		?>
		<p><font color="#FF0000"><u>Update is Available!</u><br>
		Current Version: <b><? print $newver; ?></b></font></p>
		<p>
		<a href="<? print $scripturl; ?>" target="_blank">View Update and Changelog</a><br>
		<a href="<? print $dlurl; ?>" target="_blank">Download Update</a>
		</p>
		<?
	} else {
		// No update available
		?>
		<p><font color="#006600">Your version is up to date</font></p>
		<?
	}
	
}
?>
		  </td>
		 </tr>
	   </table>
	   </td>
	  </tr>
	</table>
	
	<br>
	
<input type="hidden" name="op" value="check">
<input type="hidden" name="ver" value="<? echo $cnver; ?>">
<input type="submit" name="submit" value="Check for Updates" class="input">
</td></tr>
</table><br>
</form>
<?
include("cn_foot.php");
?>