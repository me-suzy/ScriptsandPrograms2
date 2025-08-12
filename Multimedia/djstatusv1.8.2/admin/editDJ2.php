<?php
//////////////////////////////////////////////////////////////////////////////
// DJ Status v1.8.2															//
// ©2005 Nathan Bolender www.nathanbolender.com								//
// Free to use on any website												//
//////////////////////////////////////////////////////////////////////////////

include ("../config.php");	
include ("header.inc");
if (!empty($_GET['pass'])) {
	$pass = $_GET['pass'];
} else {
	$pass = $_POST['pass'];
}
if ($pass != $adminpass) {
echo "<strong>Incorrect password</strong>";
} else {

//////////////////

$query="SELECT * FROM currentdj WHERE dj = '$edj'";
$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$edj = "$row[dj]";
		$epass = "$row[password]";
		$eactive = "$row[active]";
		$ename = "$row[name]";
		$eaddress = "$row[address]";
		$eaim = "$row[aim]";
		$emsn = "$row[msn]";
		$eyim = "$row[yim]";
		$eicq = "$row[icq]";
		$eals1 = "$row[alias1]";
		$eals2 = "$row[alias2]";
		$eals3 = "$row[alias3]";
    }
?>
<SCRIPT LANGUAGE="JavaScript">
function checkrequired(which) {
var pass=true;
if (document.images) {
for (i=0;i<which.length;i++) {
var tempobj=which.elements[i];
if (tempobj.name.substring(0,8)=="required") {
if (((tempobj.type=="text"||tempobj.type=="textarea")&&
tempobj.value=='')||(tempobj.type.toString().charAt(0)=="s"&&
tempobj.selectedIndex==0)) {
pass=false;
break;
         }
      }
   }
}
if (!pass) {
shortFieldName=tempobj.name.substring(8,30).toUpperCase();
alert("Please make sure the "+shortFieldName+" field was properly completed.");
return false;
}
else
return true;
}
</script>
<style type="text/css">
<!--
.style2 {color: #FF0000}
.style4 {color: #000033}
-->
</style>
	<p><strong>DJ Management > Edit DJ</strong></p>
	<form name="editDJ2" method="post" action="editDJ3.php" onSubmit="return checkrequired(this)"><span class="style2">*</span> Required<br><table width="333" border="2" cellpadding="2" cellspacing="0" bordercolor="#666666">
      <tr>
        <td width="207"><strong>Name<span class="style2">*</span></strong></td>
        <td width="144">
          <input name="requireddjname" type="text" id="requireddjname" value="<?php echo "$ename"; ?>">
        </td>
      </tr>
	  <tr>
        <td width="207"><strong>Password<span class="style2">*</span></strong></td>
        <td width="144">
          <input name="requireddjpassword" type="password" id="requireddjpassword" value="<?php echo "$epass"; ?>">
        </td>
      </tr>
	  <tr>
        <td width="207"><strong>Password Confirm<span class="style2">*</span></strong></td>
        <td width="144">
          <input name="requireddjpassword2" type="password" id="requireddjpassword2" value="<?php echo "$epass"; ?>">
        </td>
      </tr>
      <tr>
        <td><strong>Song Requester Address </strong></td>
        <td><input name="newaddress" type="text" id="newaddress" value="<?php echo "$eaddress"; ?>"></td>
      </tr>
      <tr>
        <td><strong>AIM Handle </strong></td>
        <td><input name="newaim" type="text" id="newaim" value="<?php echo "$eaim"; ?>"></td>
      </tr>
      <tr>
        <td><strong>MSN Handle </strong></td>
        <td><input name="newmsn" type="text" id="newmsn" value="<?php echo "$emsn"; ?>"></td>
      </tr>
      <tr>
        <td><strong>YIM Handle </strong></td>
        <td><input name="newyim" type="text" id="newyim" value="<?php echo "$eyim"; ?>"></td>
      </tr>
      <tr>
        <td><strong>ICQ Handle </strong></td>
        <td><input name="newicq" type="text" id="newicq" value="<?php echo "$eicq"; ?>"></td>
      </tr>
      <tr>
        <td><strong>Alias 1<span class="style2">*</span> </strong></td>
        <td><input name="requiredalias1" type="text" id="requiredalias1" value="<?php echo "$eals1"; ?>"></td>
      </tr>
      <tr>
        <td><strong>Alias 2<span class="style2">*</span></strong></td>
        <td><input name="requiredalias2" type="text" id="requiredalias2" value="<?php echo "$eals2"; ?>"></td>
      </tr>
      <tr>
        <td><strong>Alias 3<span class="style2">*</span> </strong></td>
        <td><input name="requiredalias3" type="text" id="requiredalias3" value="<?php echo "$eals3"; ?>"></td>
      </tr>
    </table>
	<?php
	if ("$dj" == "$edj") {
	echo "<br><b>Note: This DJ is currently signed on as an active DJ.</b>";
	} ?>
	<input type="hidden" name="pass" value="<?php echo "$pass"; ?>">
	<input type="hidden" name="edj" value="<?php echo "$edj"; ?>"><br>
    <input type="submit" name="Submit" value="Submit">
	  <p><strong><u><span class="style4">Explainations</span></u><br>
	    <u>SongRequester Address</u> - DJ's specific address to their Song Request page. For use with <a href="http://www.oddsock.org/tools/gen_songrequester/" target="_blank">Oddsock Song Requester Winamp plugin.</a> Static address required.<br>
      <u>Aliases</u> - Names to search server title for. Three are required, but there can be duplicates or be all the same if needed. </strong></p>
	  
	</form>
<br><br><a href="main.php?pass=<?php echo "$pass"; ?>">Main</a>
	<?php
}
include ("footer.inc");
 ?>