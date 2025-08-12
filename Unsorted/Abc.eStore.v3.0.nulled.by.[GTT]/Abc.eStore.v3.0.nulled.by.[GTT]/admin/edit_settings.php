<?php

//------------------------------------------------------------------------
// This script is a part of ABC eStore
//------------------------------------------------------------------------

session_start();

include("config.php");
include ("settings.inc.php");

// Design directories

$design_directories = GetDirs ("../design");

//

//
// if session is not registered go to login page

if( !isset( $_SESSION["admin"] ) && !isset( $_SESSION["demo"] ) )
	abcPageExit("<Script language=\"javascript\">window.location=\"login.php\"</script>");

if( $_SERVER["REQUEST_METHOD"] == "POST" )
	extract( $_POST );
elseif( $_SERVER["REQUEST_METHOD"] == "GET" )
	extract( $_GET );

if( $task=="settings" && !$_SESSION['demo'] ) {
	
  	$site_dir = addslashes($site_dir);
  	$site_url = addslashes($site_url);
  	
  	$sql_update = "update ".$prefix."store_config set
  		site_country='$site_country', site_name='$site_name',
  		site_url='$site_url', site_dir='$site_dir',
  		site_currency='$site_currency', bg_colour='$bg_colour',
  		site_email='$site_email',colour_1='$colour_1', colour_2='$colour_2', colour_3='$colour_3',
  		colour_4='$colour_4', routine='$routine', acc='$acc',
  		test='$test', date='$date', site_address='$site_address',
  		site_phone='$site_phone', site_fax='$site_fax',
  		online='$online', offmsg='$offmsg', sale='$sale', cat_num='$cat_num'
  		, sys_lng='$sys_lng', design='$design_directory', main_page='$main_page', special_num='$special_num'";
	
	$result = mysql_query ($sql_update);
	

	$sql_select = mysql_query( "select * from ".$prefix."store_config");
	
	while( $row = mysql_fetch_array( $sql_select ) ) {
		
		$site_country=$row["site_country"]; 
	    	$site_name= $row["site_name"];
		$site_url=$row["site_url"]; 
	    	$site_dir=$row["site_dir"];
		$site_currency=$row["site_currency"];
	    	$site_email=$row["site_email"];
		$bg_colour=$row["bg_colour"];
		$colour_1=$row["colour_1"];
		$colour_2=$row["colour_2"];
		$colour_3=$row["colour_3"];
		$colour_4=$row["colour_4"];
		$routine=$row["routine"];
		$acc=$row["acc"];
		$test=$row["test"];
		$user=$row["user"];
		$date_style=$row["date"];
		$site_phone=$row["site_phone"];
		$site_fax=$row["site_fax"];
		$site_address=$row["site_address"];
		$offmsg=$row["offmsg"];
		$online=$row["online"];
		$sale=$row["sale"];
		$cat_num=$row["cat_num"];
		$sys_lng=$row['sys_lng'];
		$main_page=$row['main_page'];
		$special_num=$row['special_num'];
		
		if ( is_dir ( "../design/" . $row['design'] ) )
			$design_directory = $row['design'];
		else	$design_directory = "default";
		
	}
	
	if($site_currency=="usd") { $currency= "$"; $currency_desc= "USA Dollars"; }
	if($site_currency=="rub") { $currency= "ðóá.$"; $currency_desc= "Ðóáëü"; }
	if($site_currency=="aud") { $currency= "$"; $currency_desc= "Australian Dollars"; }
	if($site_currency=="cad") { $currency= "$"; $currency_desc= "Canadian Dollars"; }
	if($site_currency=="gbp") { $currency= "&pound;"; $currency_desc= "GB Pounds"; }
	if($site_currency=="jpy") { $currency= "&yen;"; $currency_desc= "Japanese Yen"; }
	if($site_currency=="eur") { $currency= "&euro;"; $currency_desc= "Euros"; }
	if($site_currency=="sek") { $currency= "SEK "; $currency_desc= "Swedish Krona"; }
	
	$PHP_SELF = $_SERVER['PHP_SELF'];

}// end if ($submit)  

include ("header.inc.php");

echo "<h2>".$lng[536]."</h2>";

if ( $_SESSION['demo'] )
echo "<p><font color='red'>".$lng[537]."</font></p>";

if( eregi( "(powered by eStore)", $site_name ) )
	$site_name = str_replace("(powered by eStore)", "",$site_name);

$goback = "<a href=\"javascript:history.back()\">".$lng[538]."</a>";

if( $task == "pass" && !$_SESSION['demo'])
{
	// Check password entered is correct
	if ($new_pass && $old_pass)
	{
		$old_pass = md5($old_pass);
		$query = "select * from ".$prefix."store_config where user='$user' and pass = '$old_pass'";
		$result = mysql_query($query);
		if( mysql_num_rows($result) == 0 )
			abcPageExit("<p><font color='red'>".$lng[539].":</font> ".$lng[540]."</p><br>$goback");
	}

	// Check passwords match and are between 6 and 20 characters long
	if( $new_pass != $conf_new_pass )
		abcPageExit("<p><font color='red'><b>".$lng[539]."</b></font>".$lng[541]."</p><br>$goback");
	
	if( strlen($new_pass) < 6 || strlen($conf_new_pass) > 20 )
		abcPageExit("<p><font color='red'><b>".$lng[539]."</b></font>".$lng[542]."</p><br>$goback");

	$new_pass = md5($new_pass);
	$res = mysql_query ("update ".$prefix."store_config set pass = '$new_pass' where user = '$user'");
	if( $res )
		echo "<p><b>".$lng[543]."</b><br>".$lng[544]."</p>";     
	else
		echo "<p>".$lng[545]."</p>";     
}

echo "<p>$message</p>";

// Check languages if enabled
	
foreach ( $_languages as $key=>$val ) {
	
	$enabled = 0;
	$sql_lng = "select enabled from `".$prefix."store_language_charsets` WHERE language='$val' limit 1";
	if ( $tbl_lng = mysql_query ($sql_lng) )
		if ( $res_lng = mysql_fetch_assoc ($tbl_lng) )
			$enabled = $res_lng['enabled'];
	
	if ( !$enabled )
		unset ( $_languages[$key] );
	
}

if ( sizeof ( $_languages ) == 1 ) {
	
	foreach ( $_languages as $l )
		$_SESSION['selected_lng'] = $sys_lng = $l;
}

if ( empty ( $_languages ) ) {
	
	$_languages['EN'] = 'EN';
	$_SESSION['selected_lng'] = $sys_lng = 'EN';
}

//

echo "
<form method=\"post\" action=\"edit_settings.php\">
<input type=\"hidden\" name=\"task\" value=\"settings\">

<table border=\"1\" bordercolor=\"#e0e0e0\" cellpadding=\"4\" cellspacing=\"0\" width=\"95%\">
<tr bgcolor=\"#e6e6e6\">
<td colspan=\"3\" height=\"25\"><b>".$lng[546]."</b></td>
</tr>
<tr>
<td>$lng[932]:</td>
<td><select name=\"sys_lng\">";
foreach($_languages as $key=>$value)
	echo "<option".($sys_lng==$key?' selected':'').">".$key;
echo "</select>";
	  	  echo "</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>".$lng[547].":</td>
<td>";

	$countries = GetRegions ();
			
	// Drop down menu to build full category!
	print "<select name=\"site_country\">";

	foreach( $countries as $cntr ){
		
		echo"\t\t\t\t\t\t<option value=\"".$cntr['id']."\"";
		
		if ( $cntr['id'] == $site_country )
			echo "selected";
		
		echo ">".$cntr['name']."</option>\n";
	}
    	echo"\t\t\t\t\t</select>";
      
	//abcCountries("site_country",$site_country,$prefix);

	echo "</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>".$lng[548].":</td>
<td><input type=\"text\" class=\"textbox\" name=\"site_name\" value=\"$site_name\"></td>
<td>".$lng[549]."</td>
</tr>
<tr> 
<td>".$lng[550].":</td>
<td><input name=\"site_url\" class=\"textbox\" type=\"text\" id=\"site_url\" value=\"$site_url\"></td>
<td>".$lng[551]."</td>
</tr>
<tr> 
<td>".$lng[552].":</td>
<td><input name=\"site_phone\" class=\"textbox\" type=\"text\" id=\"site_phone\" value=\"$site_phone\"></td>
<td>".$lng[553]."</td>
</tr>
<tr> 
<td>".$lng[554].":</td>
<td><input name=\"site_fax\" class=\"textbox\" type=\"text\" id=\"site_fax\" value=\"$site_fax\"></td>
<td>".$lng[555]."</td>
</tr>
<tr> 
<td valign=\"top\">".$lng[556].":</td>
<td><textarea name=\"site_address\" value=\"$site_address\" cols=\"30\" rows=\"5\">".htmlspecialchars($site_address)."</textarea>
</td>
<td valign=\"top\">".$lng[557]."</td>
</tr>
    <tr> 
      <td>".$lng[558].":</td>
      <td><input name=\"site_dir\" class=\"textbox\" type=\"text\" id=\"site_dir\" value=\"$site_dir\"></td>
      <td>".$lng[559]."</td>
    </tr>
    <tr> 
      <td>".$lng[560].":</td>
      <td><select name=\"site_currency\">
          <option value=\"usd\" ";if($site_currency=="usd"){echo "selected";}echo" >$ (US Dollar)</option>
		  <option value=\"cad\" ";if($site_currency=="cad"){echo "selected";}echo" >$ (CAN Dollar)</option>
		  <option value=\"rub\" ";if($site_currency=="rub"){echo "selected";}echo" >ðóá. (Ðóáëü)</option>
		  <option value=\"aud\" ";if($site_currency=="aud"){echo "selected";}echo" >$ (AUS Dollar)</option>
		  <option value=\"jpy\" ";if($site_currency=="jpy"){echo "selected";}echo" >&yen; (Yen)</option>
		  <option value=\"gbp\" ";if($site_currency=="gbp"){echo "selected";}echo" >&pound; (Pound)</option>
		  <option value=\"eur\" ";if($site_currency=="eur"){echo "selected";}echo" >&euro; (Euro)</option>
		  <option value=\"sek\" ";if($site_currency=="sek"){echo "selected";}echo" >SEK (Swedish Krona)</option>
        </select></td>
      <td>".$lng[561]."</td>
    </tr>
	<tr> 
      <td height=\"20\">".$lng[562]."</td>
      <td height=\"20\">DD/MM/YY <input name=\"date\" type=\"radio\" value=\"1\"";if($date_style=="1"){echo "checked";}echo"> MM/DD/YYYY <input name=\"date\" type=\"radio\" value=\"0\" ";if($date_style=="0"){echo "checked";}echo"></td>
      <td height=\"20\">".$lng[563]."</td>
    </tr>
    
    <tr> 
      <td>".$lng[940].":</td>
      <td><select name=\"main_page\">
		<option value=\"0\" ";if($main_page==0){echo "selected";}echo" >$lng[942]</option>
		<option value=\"1\" ";if($main_page==1){echo "selected";}echo" >$lng[943]</option>
		<option value=\"2\" ";if($main_page==2){echo "selected";}echo" >$lng[944]</option>
        </select></td>
      <td>".$lng[941]."</td>
    </tr>
    
    <tr> 
      <td>".$lng[564].":</td>
      <td><select name=\"cat_num\">
		<option value=\"1\" ";if($cat_num=="1"){echo "selected";}echo" >1</option>
		<option value=\"2\" ";if($cat_num=="2"){echo "selected";}echo" >2</option>
		<option value=\"3\" ";if($cat_num=="3"){echo "selected";}echo" >3</option>
		<option value=\"4\" ";if($cat_num=="4"){echo "selected";}echo" >4</option>
		<option value=\"5\" ";if($cat_num=="5"){echo "selected";}echo" >5</option>
        </select></td>
      <td>".$lng[565]."</td>
    </tr>
    
    <tr> 
      <td>".$lng[945].":</td>
      <td><select name=\"special_num\">
		<option value=\"1\" ";if($special_num==1){echo "selected";}echo" >1</option>
		<option value=\"2\" ";if($special_num==2){echo "selected";}echo" >2</option>
		<option value=\"3\" ";if($special_num==3){echo "selected";}echo" >3</option>
		<option value=\"4\" ";if($special_num==4){echo "selected";}echo" >4</option>
		<option value=\"5\" ";if($special_num==5){echo "selected";}echo" >5</option>
		<option value=\"6\" ";if($special_num==6){echo "selected";}echo" >6</option>
		<option value=\"7\" ";if($special_num==7){echo "selected";}echo" >7</option>
		<option value=\"8\" ";if($special_num==8){echo "selected";}echo" >8</option>
		<option value=\"9\" ";if($special_num==9){echo "selected";}echo" >9</option>
		<option value=\"10\" ";if($special_num==10){echo "selected";}echo" >10</option>
		<option value=\"12\" ";if($special_num==12){echo "selected";}echo" >12</option>
		<option value=\"15\" ";if($special_num==15){echo "selected";}echo" >15</option>
		<option value=\"20\" ";if($special_num==20){echo "selected";}echo" >20</option>
		<option value=\"25\" ";if($special_num==25){echo "selected";}echo" >25</option>
		<option value=\"30\" ";if($special_num==30){echo "selected";}echo" >30</option>
        </select></td>
      <td>".$lng[946]."</td>
    </tr>
    
    <tr> 
      <td height=\"20\">".$lng[568].":</td>
      <td height=\"20\"><input name=\"site_email\" class=\"textbox\" type=\"text\" value=\"$site_email\"></td>
      <td height=\"20\">".$lng[569]."</td>
    </tr>
	<tr> 
      <td height=\"20\">".$lng[570].":</td>
      <td height=\"20\"><select name=\"routine\">
        <option value=\"other\"";if($routine=="other"){echo "selected";}echo">E-mail order</option>
        <option value=\"2checkout\"";if($routine=="2checkout"){echo "selected";}echo">2Checkout</option>
	<option value=\"paypal\"";if($routine=="paypal"){echo "selected";}echo">PayPal</option>
	<option value=\"skipjack\"";if($routine=="skipjack"){echo "selected";}echo">SkipJack</option>
	</select></td>
	  <td height=\"20\">".$lng[571]."</td>
    </tr>
	<tr> 
      <td height=\"20\">".$lng[572].":</td>
      <td height=\"20\"><b>$site_url/finish.php</b></td>
	  <td height=\"20\">".$lng[573]."</td>
    </tr>
	<tr> 
      <td height=\"20\">".$lng[574].":</td>
      <td height=\"20\"><input name=\"acc\" class=\"textbox\" type=\"text\" value=\"$acc\"></td>
      <td height=\"20\">".$lng[575]."</td>
    </tr>
	<tr> 
      <td height=\"20\">".$lng[576]."</td>
      <td height=\"20\">Yes <input name=\"test\" type=\"radio\" value=\"Y\"";if($test=="Y"){echo "checked";}echo"> No <input name=\"test\" type=\"radio\" value=\"N\" ";if($test=="N"){echo "checked";}echo"></td>
      <td height=\"20\">".$lng[577]."</td>
    </tr>
	<tr> 
      <td height=\"20\">".$lng[578]."</td>
      <td height=\"20\">Yes <input name=\"online\" type=\"radio\" value=\"Y\"";if($online=="Y"){echo "checked";}echo"> No <input name=\"online\" type=\"radio\" value=\"N\" ";if($online=="N"){echo "checked";}echo"></td>
      <td height=\"20\">".$lng[579]."</td>
    </tr>
	<tr> 
      <td valign=\"top\">".$lng[580].":</td>
      <td valign=\"top\"><textarea name=\"offmsg\" value=\"\" cols=\"55\" rows=\"7\">".htmlspecialchars($offmsg)."</textarea>
      </td>
      <td bgcolor=\"$bg_colour\" valign=\"top\">".$lng[581]."</td>
    </tr>
	<tr> 
      <td>".$lng[582]."</td>
      <td>On <input name=\"sale\" type=\"radio\" value=\"Y\"";if($sale=="Y"){echo "checked";}echo"> Off<input name=\"sale\" type=\"radio\" value=\"N\" ";if($sale=="N"){echo "checked";}echo">
      </td>
      <td bgcolor=\"$bg_colour\" valign=\"top\">".$lng[583]."</td>
    </tr>
    
    <tr> 
      	<td height=\"20\">".$lng[862].":</td>
      	<td height=\"20\">
      	<select name=\"design_directory\">";

foreach ( $design_directories as $dd ) {
	echo "<option value=\"$dd\"";
	if($design_directory == $dd){echo "selected";}
	echo">$dd</option>";
}
	
echo "
	</select></td>
	<td height=\"20\">".$lng[863]."</td>
    </tr>
    
    <tr> 
      <td>&nbsp;</td>
      <td colspan=\"2\"><input class=\"submit\" type=\"submit\" value=\"".$lng[584]."\">
      </td>
    </tr>
</table>
</form>


<form method=\"post\" action=\"edit_settings.php\">
<input type=\"hidden\" name=\"task\" value=\"pass\">
<table border=\"1\" bordercolor=\"#e0e0e0\" cellspacing=\"0\" cellpadding=\"4\" width=\"95%\">
<tr bgcolor=\"#e6e6e6\">
<td height=\"25\" colspan=\"2\"><b>".$lng[585].":</b></td>
</tr>

<tr> 
<td><b>".$lng[586].":</b></td>
<td><input type=\"password\" class=\"textbox\" name=\"old_pass\"></td>
</tr>

<tr> 
<td><b>".$lng[587].":</b></td>
<td><input type=\"password\" class=\"textbox\" name=\"new_pass\"></td>
</tr>

<tr> 
<td><b>".$lng[588].":</b></td>
<td><input type=\"password\" class=\"textbox\" name=\"conf_new_pass\">
</td>
</tr>

<tr> 
<td>&nbsp;</td>
<td><input class=\"submit\" type=\"submit\" value=\"".$lng[589]."\">
</td>
</tr>
</table>
</form>

";

include_once('footer.inc.php');

?>