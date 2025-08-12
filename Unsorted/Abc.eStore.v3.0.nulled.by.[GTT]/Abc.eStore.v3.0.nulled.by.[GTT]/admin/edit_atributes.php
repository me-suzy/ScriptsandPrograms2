<?php

//------------------------------------------------------------------------
// This script is a part of ABC eStore
//------------------------------------------------------------------------

session_start();
	
include ("config.php");
include ("settings.inc.php");
$url = "index";
include_once ("header.inc.php");

$err = "";

//
// if session is not registered go to login page

if( !isset( $_SESSION["admin"] ) && !isset( $_SESSION["demo"] ) )
	abcPageExit("<Script language=\"javascript\">window.location=\"login.php\"</script>");

echo "<h2>".$lng[439]."</h2>";

// Demo

if ( ( isset ( $_POST['add_group'] ) || isset ( $_POST['add_atribute'] ) || isset ( $_POST['delete'] ) || isset ( $_GET['del_n'] ) ) && $_SESSION['demo'] ) { 
		
	echo ("<font color='red'>".$lng[440]."</font><br><br>");
}

// Add group

if ( isset ( $_POST['add_group'] ) && !$_SESSION['demo']  ) {
	
	if ( empty ( $_POST['add_group_name'] ) )
		$err = $lng[441]."<br>";
	
	$sql = "SELECT * FROM `".$prefix."store_atributes_groups` WHERE name='" . $_POST['add_group_name'] . "'";
	$result = mysql_query ($sql);
	if ( $num = mysql_affected_rows() != 0 )
		$err = $lng[442]." '" . $_POST['add_group_name'] . "' ".$lng[443];
	
		
	
	if ( !$err ) {
	
		$sql = "INSERT INTO `".$prefix."store_atributes_groups` SET
		name=\"$_POST[add_group_name]\"
		";
		$succ = $result = mysql_query ($sql);
	}
	
	if ( $err )
		echo "<b>" . $err . "</b>";
	else	echo "<b>".$lng[442]." '" . $_POST['add_group_name'] . "' ".$lng[444]."</b>";
}

// Add attribute

if ( isset ( $_POST['add_atribute'] ) && !isset ( $_POST['delete'] ) && !$_SESSION['demo'] ) {
	
	if ( empty ( $_POST['add_atribute_name'] ) )
		$err = $lng[445]."<br>";
	
	$sql = "SELECT * FROM `".$prefix."store_atributes` WHERE name='" . $_POST['add_atribute_name'] . "' AND parent='" . $_POST['group'] . "'";
	$result = mysql_query ($sql);
	if ( $num = mysql_affected_rows() != 0 )
		$err = $lng[446]." '" . $_POST['add_atribute_name'] . "' ".$lng[443];
	
		
	
	if ( !$err ) {
	
		$sql = "INSERT INTO `".$prefix."store_atributes` SET
		name=\"$_POST[add_atribute_name]\",
		parent=\"$_POST[group]\"
		";
		$succ = $result = mysql_query ($sql);
	}
	
	if ( $err )
		echo "<b>" . $err . "</b>";
	else	echo "<b>".$lng[446]." '" . $_POST['add_atribute_name'] . "' ".$lng[444]."</b>";
}


// Delete group

if ( isset ( $_GET['del_n'] ) && !$_SESSION['demo'] ) {
			
	$sql = "DELETE FROM `".$prefix."store_atributes` WHERE parent='" . $_GET['del_n'] . "'";
	mysql_query ( $sql );

	$sql = "DELETE FROM `".$prefix."store_atributes_groups` WHERE n='" . $_GET['del_n'] . "'";
	mysql_query ( $sql );
	
	$sql = "DELETE FROM `".$prefix."store_atributes_link` WHERE group_id='" . $_GET['del_n'] . "'";
	mysql_query ( $sql );

}	


// Delete attributes

if ( isset ( $_POST['delete'] ) && !$_SESSION['demo'] ) {
	
	
	// Delete attribute
	
	if ( is_array ( $_POST['delete_atribute'] ) ) {
		
		foreach ( $_POST['delete_atribute'] as $key=>$delatr ) {
			
			$sql = "DELETE FROM `".$prefix."store_atributes` WHERE n='" . $key . "'";
			mysql_query ( $sql );
		}
		
	}
	
}


// -----------------


// Add group form

echo <<<FORM
<p>
<form action="" method="post">
<table border="0">
<tr><td>$lng[447]: 
<input type="text" name="add_group_name" value="">
<input type="submit" name="add_group" value="$lng[448]">
<input type="hidden" name="add_group" value=" Add ">
</td></tr>
</table>
</form>
</p>
<br>
FORM;


// Output groups

$sql = "SELECT * FROM `".$prefix."store_atributes_groups` ORDER BY name";
if (  $result = mysql_query ($sql) ) {
	
echo <<<GROUP
<table border="0" bgcolor="#e6e6e6" cellspacing="2" cellpadding="4" width="95%" align="center">
GROUP;

	while ( $res = mysql_fetch_array( $result ) ) {
		
		echo "<tr bgcolor=\"#f1f1f1\"><td><b>" . $res['name'];
		echo "</b></td><form action=\"\" method=\"post\"><td>".$lng[449].": <input type=\"text\" name=\"add_atribute_name\" value=\"\"> <input type=\"submit\" name=\"add_atribute\" value=\"".$lng[448]."\">
		<input type=\"hidden\" name=\"add_atribute\" value=\" Add \"><input type=\"hidden\" name=\"group\" value=\"$res[n]\">";
		echo "</td><td align=\"center\">";
		echo "<a href=\"javascript:decision('".$lng[450]."','edit_atributes.php?del_n=$res[n]')\">".$lng[451]."</a>";
		echo "</td></tr><tr bgcolor=\"#fefefe\"><td colspan=\"2\" valign=\"center\">";
		
		$result_atr = array ();
		
		$sql_atr = "SELECT * FROM `" . $prefix . "store_atributes` WHERE parent='" . $res['n'] . "' ORDER BY name";
		if (  $result_atr = mysql_query ($sql_atr) ) {
						
			if ( !empty ( $result_atr ) ) {
			
				$i = 0;
				
				while ( $res_atr = mysql_fetch_array( $result_atr ) ) {
					
					$i = 1;
					
					echo "<input type=\"checkbox\" name=\"delete_atribute[$res_atr[n]]\">$res_atr[name] ";
					
				}
				
				if ( $i == 0 )
					echo "<br><i>".$lng[452]."</i><br>&nbsp;";
			}
			
		
		}
		
		
		echo "</td><td align=\"center\" width=\"90\"><input type=\"submit\" name=\"delete\" value=\"".$lng[453]."\"></td></form></tr>";
		
	}

echo <<<GROUP
</table>
GROUP;

}

// 

include("footer.inc.php");

?>