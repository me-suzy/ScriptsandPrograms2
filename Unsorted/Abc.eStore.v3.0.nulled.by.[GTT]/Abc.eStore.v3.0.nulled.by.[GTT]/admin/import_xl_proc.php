<?php

//------------------------------------------------------------------------
// This script is a part of ABC eStore
//------------------------------------------------------------------------

session_start();

//
// Global includes

include ("config.php");
include ("settings.inc.php");
$url = "index";
include_once ("header.inc.php");

//
// If guest
if ( $_SESSION['demo'] ) {
	abcPageExit( "<Script language=\"javascript\">window.location=\"import_xl.php\"</script>" );
	exit;
}


//
// if session is not registered go to login page

if( !isset( $_SESSION["admin"] ) && !isset( $_SESSION["demo"] ) )
	abcPageExit("<Script language=\"javascript\">window.location=\"login.php\"</script>");

echo "<h2>".$lng[651]."</h2>";

//
// This script utility functions

require_once ("import_xl_util.php");


// Processing import config file

if (!$cfg_file = fopen ("xlupload/xlupload.rc", 'r'))
	abcPageError($lng[652]);

$config = parse_ini_file( 'xlupload/xlupload.rc', 1 );


// Getting content of required fields

$exc = new ExcelFileParser("debug.log", ABC_NO_LOG );
$res = $exc->ParseFromFile( 'xlupload/uploaded.xls' );

if( $res != 0 ) {
	
	abcHandleParserError( $res );
}

// Processing selected worksheets

if( !empty($_POST['s']) ) {
	
	$succ_ins=0;
	$succ_upd=0;
	$err=0;
	
	$log="";
		
	$p_ws = 0; // Number of selected worksheets
	
	$select = $_POST['s'];
		
	foreach ($select as $wn=>$cat) { // Processing worksheets and categories
		
		if ($cat != -1) {
					
			$p_ws++;
			
			// Getting category name
			
			$cats = abcFetchCategoryArray();
			$catname = $cats [$cat]['category'];
			
			
			if( $exc->worksheet['unicode'][$wn] ) {  // Getting worksheet name
					$wsname = uc2html($exc->worksheet['name'][$wn]); } 
			else		$wsname = $exc->worksheet['name'][$wn];
																		
			$log .= "<br><i>".$lng[653]." '$wsname' ($catname):</i><br><br>";
			
			$max_row = $exc->worksheet['data'][$wn]['max_row'];
			$max_col = $exc->worksheet['data'][$wn]['max_col'];
				
			for ( $row=0; $row <= $max_row; $row++) { // Processing rows
					
				$globtype = 1; // Right type flag
				$require = 1;
				
				// Preparing SQL Query
				
				$SQL_ins="INSERT INTO " . $prefix . "store_inventory (";
				$SQL_upd="UPDATE " . $prefix . "store_inventory SET ";
				
				
				foreach ($config as $it) {
					$field = key($config);
					next($config);				
					
					$SQL_ins .= $field . ", ";
				}
				
				$SQL_ins .= "cat_id) VALUES (";
					
				foreach ($config as $it) { // Processing columns and types
									
					$col = $it[position];
					$data = $exc->worksheet['data'][$wn]['cell'][$row][$col]['data'];
					$type = $exc->worksheet['data'][$wn]['cell'][$row][$col]['type'];
					
					if ($type == 0) {
					
						if( $exc->sst['unicode'][$data] ) {
							$data = uc2html($exc->sst['data'][$data]);
						} else
							$data = $exc->sst['data'][$data];
					}			
					
					
					// Processing reqiure instructions
					
					$req = $it['require'];
					
					if ( $req == 1 )
					if ( empty ($data) ) {
					
						$require = 0;
						$log .= "<font color='red'>$row. ".$lng[654].": $row, column: $col -> ".$lng[655].".</font><br>";				
						$err++;
						break;
					}
					
					
					
					// Finding key (insert or update ?)
					
					$field = key($config);
					next($config);				
					
					if ($field == 'title')
					$title = $data;
									
					$upd=0; // Update or insert flag
									
					if($it[key] == 1) {
						
						if (!isset($keyname)) {
							
							$keyname = $field;
							$key = $data;
						
						} else 	if ($keyname==$field) {
								$key=$data;
							} else continue;
							
					
					}
					
					//
	
					if (!empty ($data) ) {
					
						$it_type = explode ( ',', $it[type] ); // Right or wrong type
						$righttype=0;
						
						foreach ($it_type as $t)
						if ( $type == $t )
						$righttype=1;
						
						if($righttype==0) {
							$globtype=0;
							$log .= "<font color='red'>$row. ".$lng[654].": $row, column: $col -> ".$lng[656].".</font><br>";
							$err++;
							break;
						}
					
					}	
					
									
					// Generating SQL Query
								
					$SQL_ins .= "\"". mysql_escape_string($data) . "\", ";
					$SQL_upd .= $field . "=\"". mysql_escape_string($data) . "\", ";
					
								
				}
					
				$SQL_ins .= "\"$cat\"" . ")";
				
				$SQL_upd .= "cat_id=\"$cat\"";
				$SQL_upd .= " WHERE $keyname=\"$key\"";
			
				
				// Processing key instructions
							
				if (isset($keyname) && isset($key)) {
				
					$sql_rep="SELECT COUNT(*) as cnt FROM " . $prefix . "store_inventory WHERE $keyname=\"$key\"";
					$res_rep = mysql_query ($sql_rep);
					while ($row_rep = mysql_fetch_array($res_rep))
					$cnt = $row_rep ['cnt'];
				} else 	$cnt=0;
								
						
				// Database write
				
				if ( ($globtype==1) && ($require==1)) {
					
					if ($cnt==0) {
						$res = mysql_query ($SQL_ins);
						if ($res) {	$log .= "$row. <b>$title</b> added successfully.<br>"; $succ_ins++; }
						else { 	$log .= "<font color='red'>$row. ".$lng[657]." <b>$title</b>.</font><br>"; $err++; }
					} else {
						$res = mysql_query ($SQL_upd);
						if ($res) {	$log .= "$row. <b>$title</b> updated successfully.<br>"; $succ_upd++; }
						else { 	$log .= "<font color='red'>$row. ".$lng[658]." <b>$title</b>.</font><br>"; $err++; }
					}
				}
				
							
			}
		
		}
	
	}
	
if(!empty($log))
echo "<br><font color='black'><b>".$lng[659].":</b><br>" . $log . "</font>";

if ($p_ws != 0) {
echo "<br>------------------------";
echo "<br>$succ_ins ".$lng[660].", $succ_upd ".$lng[661].".";
echo "<br>$err error(s) occured.";
}

if ( $p_ws==0 )
	abcPageError($lng[662]);
else 	abcRemoveLock();

} else 	abcPageError($lng[662]);


// Footer

include ("footer.inc.php");

?>