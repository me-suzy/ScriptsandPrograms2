<?php

//------------------------------------------------------------------------
// This script is a part of ABC eStore
//------------------------------------------------------------------------

include ("quote.php");

error_reporting(0);

$sql_select = mysql_query( "select * from ".$prefix."store_config");

while( $row = mysql_fetch_array( $sql_select ) )
{
	$site_country=$row["site_country"]; 
    	$site_name= $row["site_name"];
	$site_url=$row["site_url"]; 
    	$site_dir=$row["site_dir"];
	$site_currency=$row["site_currency"];
    	$site_email=$row["site_email"];
	$site_tax =$row["site_tax"];
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
		
	if ( is_dir ( "../design/" . $row['design'] ) || is_dir ( "design/" . $row['design'] ) )
		$design_directory = $row['design'];
	else	$design_directory = "default";
	
}

	// Get listing of fields in languages table

	$_languages_r=mysql_query("SHOW COLUMNS FROM ".$prefix."store_lang") or die(mysql_error());
	$_languages=array();
	while($tmp=mysql_fetch_assoc($_languages_r))
		if(!in_array($tmp['Field'], array('category', 'description', 'n')))
			$_languages[$tmp['Field']]=$tmp['Field'];
	
	if($_REQUEST['_action']=='change_language')
	{
		$_SESSION['selected_lng']=$_REQUEST['new_lng'];
		header("Location: ");
	}

	if($sys_lng==''&&count($_languages)>0||!isset ($_languages[$sys_lng]))
	{
		$_lngs=array_keys($_languages);
		$sys_lng=$_lngs[0];
		$_SESSION['selected_lng']=$_lngs[0];
	}

	if($_SESSION['selected_lng']!=''&&in_array($_SESSION['selected_lng'], $_languages))
		$sys_lng=$_SESSION['selected_lng'];
	
	
	
$lng=array();
$lng_res=mysql_query("select * from ".$prefix."store_lang");
while($row=mysql_fetch_assoc($lng_res))
	$lng[$row['n']]=$row[$sys_lng];

$charset_r=mysql_query("select * from ".$prefix."store_language_charsets where language='".$sys_lng."'");
$charset=mysql_fetch_assoc($charset_r);
$sys_charset=$charset['charset'];
	
if($site_currency=="usd") { $currency= "$"; $currency_desc= "USA Dollars"; }
if($site_currency=="aud") { $currency= "$"; $currency_desc= "Australian Dollars"; }
if($site_currency=="rub") { $currency= "ðóá."; $currency_desc= "Ðóáëü"; }
if($site_currency=="cad") { $currency= "$"; $currency_desc= "Canadian Dollars"; }
if($site_currency=="gbp") { $currency= "&pound;"; $currency_desc= "GB Pounds"; }
if($site_currency=="jpy") { $currency= "&yen;"; $currency_desc= "Japanese Yen"; }
if($site_currency=="eur") { $currency= "&euro;"; $currency_desc= "Euros"; }
if($site_currency=="sek") { $currency= "SEK "; $currency_desc= "Swedish Krona"; }

$PHP_SELF = $_SERVER['PHP_SELF'];


////////////////////////////////////////////////////////////////
// abcCountries
// 	countries drop down list
////////////////////////////////////////////////////////////////

function abcCountries($name,$selected_country,$prefix)
{
	$select = mysql_query ("select country from ".$prefix."store_countries");
	echo "<select name=$name>";
	while ($row = mysql_fetch_array($select))
	{
		$country = $row["country"];
  		echo "\t<option value=\"$country\" ";
  		if( $country == $selected_country )
  			echo "selected";
  		echo ">$country</option>\n";
	}
	echo "</select>";
}

////////////////////////////////////////////////////////////////
// abcFormatSize
// 	Format number size from bytes
//
////////////////////////////////////////////////////////////////

function abcFormatSize( $rawSize )
{
	if( $rawSize / 1048576 > 1 )
		return round($rawSize/1048576, 1) . 'MB';
	elseif( $rawSize / 1024 > 1 )
		return round( $rawSize / 1024, 1) . 'KB';
	else
		return round($rawSize, 1) . 'bytes';
}

////////////////////////////////////////////////////////////////
// abcGetCategoryPathArray
// 	Return full category path as array.
//
////////////////////////////////////////////////////////////////

function abcGetCategoryPathArray( $cat_id )
{
	global $prefix;
	
	$path = array();
	while( $cat_id != 0 )
	{
		$sql_lowercat = "select category, cat_father_id from ".$prefix."store_category where cat_id = $cat_id";
		$result0 = mysql_query( $sql_lowercat );
		$row0 = mysql_fetch_array( $result0 );
		$path[] = $row0['category'];
		$cat_id = $row0["cat_father_id"];
	}
	
	$path = array_reverse( $path );
	return $path;
}

function GetCategoryPathArray( $cat_id )
{
	global $prefix;
	
	$path = array();
	while( $cat_id != 0 )
	{
		$sql_lowercat = "select cat_id, cat_father_id from ".$prefix."store_category where cat_id = $cat_id";
		$result0 = mysql_query( $sql_lowercat );
		$row0 = mysql_fetch_array( $result0 );
		$path[] = $row0['cat_id'];
		$cat_id = $row0["cat_father_id"];
	}
	
	return $path;
}

////////////////////////////////////////////////////////////////
// abcGetCategoryPath
// 	Return full category path as string.
//
////////////////////////////////////////////////////////////////

function abcGetCategoryPath( $cat_id )
{
	return join( "/", abcGetCategoryPathArray( $cat_id ) );
}

////////////////////////////////////////////////////////////////
// abcFetchCategoryArray
// 	Return array with all category data
//	(including full path data).
//
////////////////////////////////////////////////////////////////

function abcFetchCategoryArray()
{
	global $prefix;
	
	$result = array();
	
	$select = mysql_query("select * from ".$prefix."store_category");
	
	// iterate all categories
	while( $row = mysql_fetch_array($select) )
	{
		// copy category data to the result hash-array
		$cid = $row['cat_id'];
		$result[ $cid ] = $row;
		$result[ $cid ][ 'path' ] = abcGetCategoryPathArray( $cid );
	}
	
	return $result;
}

////////////////////////////////////////////////////////////////
// abcFetchCategoryList
// 	Return array with list category data
//	(including path data only as text).
//
////////////////////////////////////////////////////////////////

function __abcSortCategoryList( $left, $right )
{
	if( $left['path'] == $right['path'] )
		return 0;
	else
		return -strcmp( $right['path'], $left['path'] );
}


function abcSortCategoryList( &$list )
{
	usort( $list, "__abcSortCategoryList" );
	
}

function abcFetchCategoryList( $sort = true )
{
	global $prefix;
	
	$result = array();
	$select = mysql_query("select * from ".$prefix."store_category");
	
	// iterate all categories
	while( $row = mysql_fetch_array( $select ) )
	{
		// copy category data to the result hash-array
		$cid = $row['cat_id'];
		$result[ $cid ] = $row;
		$result[ $cid ][ 'path' ] = abcGetCategoryPath( $cid );		
	}
	
	if( $sort )
		usort( $result, "__abcSortCategoryList" );
			
	
	return $result;
}


function abcFetchCategoryListPrior( $cat_father_id = 0)
{
	global $prefix, $result;
	
	$select = mysql_query("select * from ".$prefix."store_category where cat_father_id = '$cat_father_id' order by priority desc");
	
	// iterate all categories
	while( $row = mysql_fetch_array( $select ) )
	{
		// copy category data to the result hash-array
		$cid = $row['cat_id'];
		$result[ $cid ] = $row;
		$result[ $cid ][ 'path' ] = abcGetCategoryPath( $cid );		
	
		abcFetchCategoryListPrior( $row['cat_id'] );
	}
	
		
	return $result;
}

function abcFetchCountryList( $root = true )
{
			
	global $prefix;
	
	$result = array();
	$sql = "select country_id,country  from ".$prefix."store_countries";
	
	if ( $root == true )
		$sql .=  " where parent_id='0'";
	
	$sql .= " order by country";
	$select = mysql_query($sql);
	
	// iterate all categories
	while( $row = mysql_fetch_array( $select ) )
	{
		// copy category data to the result hash-array
		$cid = $row['country_id'];
		$result[ $cid ] = $row;
		
	}
	
	//if( $sort )
	//	usort( $result, "__abcSortCategoryList" );
			
	
	return $result;
}

function GetRegions ( $onlyregions = 0 ) {

	global $prefix;
	
	$countries = array ();
	
	$select = mysql_query ("select * from ".$prefix."store_countries where parent_id='0' order by country");
	
	while ($row = mysql_fetch_array($select)) {
		
		
		if ( !$onlyregions )
			$countries[] = array ( 'id'=>$row["country_id"], 'name'=>$row["country"] );	
						
		// Regions
		
		$select_parent = mysql_query ("select * from ".$prefix."store_countries where parent_id='".$row['country_id']."' order by country");
		
		$i = 0;
		
		while ($row_parent = mysql_fetch_array($select_parent)){
			
			$i++;
			
			$countries[] = array ( 'id'=>$row_parent['country_id'], 'name'=>$row['country'] . "->" . $row_parent['country'] );
			
		}
		
		if ( $onlyregions )
			if ( !$i )
				$countries[] = array ( 'id'=>$row["country_id"], 'name'=>$row["country"] );	
		
	}
	
	return ( $countries );
		
}




////////////////////////////////////////////////////////////////
// abcDbgOut
// 	Debug output helper.
//
////////////////////////////////////////////////////////////////

function abcDbgOut( $var )
{
	print '<pre>';
	print_r( $var );
	print '</pre>';
}

////////////////////////////////////////////////////////////////
// abcPageExit
// 	Exit current page and prints footer.
//
////////////////////////////////////////////////////////////////

function abcPageExit( $message = '' )
{
	if( $message != '' )
		echo "<p>$message</p>\n";
	include( "footer.inc.php" );
	exit;
}

////////////////////////////////////////////////////////////////
// abcGenPassword
// 	Generate random password.
//
////////////////////////////////////////////////////////////////

function abcGenPassword()
{
	$chars = array( 
		"a","A","b","B","c","C","d","D","e","E","f","F","g","G",
		"h","H","i","I","j","J", "k","K","l","L","m","M","n","N",
		"o","O","p","P","q","Q","r","R","s","S","t","T", "u","U",
		"v","V","w","W","x","X","y","Y","z","Z","1","2","3","4",
		"5","6","7","8","9","0");
	
	$max_chars = count($chars) - 1;
	srand((double)microtime()*1000000);
	
	for($i = 0; $i < 8; $i++)
		$new_pass = ($i == 0) ? $chars[rand(0, $max_chars)] : $new_pass . $chars[rand(0, $max_chars)];
	
	return $new_pass;
}

////////////////////////////////////////////////////////////////
// abcDelImage
// 	Delete user image.
//
////////////////////////////////////////////////////////////////

function abcDelImage( $image )
{
	global $site_dir;
	
	if( file_exists("$site_dir/images/$image") )
	{
		if( $image == 'nophoto.gif' || $image == 'nophoto_small.gif' )
			return;
			
		@unlink("$site_dir/images/$image");
		
	}
}

////////////////////////////////////////////////////////////////
// abcDelCategoryImage
// 	Delete user image.
//
////////////////////////////////////////////////////////////////

function abcDelCategoryImage( $image )
{
	abcDelImage( "category/$image" );
}

////////////////////////////////////////////////////////////////
// abcDelProductImage
// 	Delete user image.
//
////////////////////////////////////////////////////////////////

function abcDelProductImage( $image )
{
	abcDelImage( "product/$image" );
}

////////////////////////////////////////////////////////////////
// abcIsImageContentType
// 	determione if this image content type is allowed.
//
////////////////////////////////////////////////////////////////

function abcIsImageContentType( $image_ct )
{
	static $_allowed = array("image/gif", "image/pjpeg", "image/x-png" );
	return in_array( $image_ct, $_allowed );
}

////////////////////////////////////////////////////////////////
// abcPrintNavigationBar
// 	print page navigation bar
//
////////////////////////////////////////////////////////////////

function abcPrintNavigationBar( $totalrows, $count_result, $limit, $page, $searchStr )
{
	$PHP_SELF = $_SERVER["PHP_SELF"];
	
	// Count bounds
	$numofpages = ceil( $totalrows / $limit );

	if( $numofpages <= 1 )
	{
		echo "<br>";
		return;
	}

	$from = $limit * $page - $limit + 1;
	$to = $from + $count_result - 1;
	
	echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
		  <tr>
			<td width=\"50%\">";
			
	echo "Pages: $from - $to</td><td width=\"50%\" align=\"right\"><b>Go to page:</b> ";

	// prev link
	if( $page != 1 )
	{
		$pageprev = $page - 1; 
		echo "<a href=\"$PHP_SELF?page=$pageprev&ddlimit=$limit&searchStr=$searchStr\"><< prev</a>&nbsp;";
	}
	
	// page
	for( $i = 1; $i <= $numofpages; $i++ )
		if( $numofpages > 1 )
			if( $i == $page )
				echo "&nbsp;".$i."&nbsp;";
			else
				echo "&nbsp;<a href=\"$PHP_SELF?page=$i&ddlimit=$limit&searchStr=$searchStr\">$i</a>&nbsp;";
	
	// next link
	if( $totalrows - ($limit * $page) > 0 )
	{
		$pagenext = $page + 1; 
		echo "<a href=\"$PHP_SELF?page=$pagenext&ddlimit=$limit&searchStr=$searchStr\">next >></a>";
	}
	
	echo "</tr></td></table><br>";	
	
}

// Get name by id

function GetNameById ( $name, $id, $table, $value ) {
	
	global $prefix;
			
	if ( $value == "" )
		return "";
	
	$data = array ( "$name"=>'' );

	$sql = "SELECT `$name` FROM `".$prefix."$table` ";
	$sql .= "WHERE `$id`='$value' LIMIT 1";
	
	$res = mysql_query ($sql);
	$data = mysql_fetch_assoc($res);
	
	return ( $data["$name"] );
	
}

// Get design directories

function GetDirs( $dir ) {
		
	if( is_dir( $dir ) ) {
	
		$dirHandle = opendir( $dir );
	
		while ( false !== ( $dir_name = readdir($dirHandle) ) ) {
												
	        	if( is_dir( "../design/" . $dir_name ) && $dir_name != "." && $dir_name != ".." ) {
	        		
	        		$dirs[] = $dir_name;
	        				        
	        	}
		}
				
		closedir ($dirHandle);
		
		return( $dirs );
		
	} 
	else 	return;
}

//

$charsets[] = array ( 'value'=>"", 'name'=>"");
$charsets[] = array ( 'value'=>"iso-8859-1", 'name'=>"Afrikaans (af-iso-8859-1)");
$charsets[] = array ( 'value'=>"iso-8859-1", 'name'=>"Albanian (sq-iso-8859-1)");
$charsets[] = array ( 'value'=>"windows-1256", 'name'=>"Arabic (ar-win1256)");
$charsets[] = array ( 'value'=>"iso-8859-9", 'name'=>"Azerbaijani (az-iso-8859-9)");
$charsets[] = array ( 'value'=>"windows-1250", 'name'=>"Bosnian (bs-win1250)");
$charsets[] = array ( 'value'=>"iso-8859-1", 'name'=>"Brazilian portuguese (pt-br-iso-8859-1)");
$charsets[] = array ( 'value'=>"iso-8859-1", 'name'=>"Catalan (ca-iso-8859-1)");
$charsets[] = array ( 'value'=>"big-5", 'name'=>"Chinese traditional (zh-tw)");
$charsets[] = array ( 'value'=>"gb2312", 'name'=>"Chinese simplified (zh)");
$charsets[] = array ( 'value'=>"iso-8859-2", 'name'=>"Croatian (hr-iso-8859-2)");
$charsets[] = array ( 'value'=>"windows-1250", 'name'=>"Croatian (hr-win1250)");
$charsets[] = array ( 'value'=>"iso-8859-2", 'name'=>"Czech (cs-iso-8859-2)");
$charsets[] = array ( 'value'=>"windows-1250", 'name'=>"Czech (cs-win1250)");
$charsets[] = array ( 'value'=>"iso-8859-1", 'name'=>"Danish (da-iso-8859-1)");
$charsets[] = array ( 'value'=>"iso-8859-1", 'name'=>"Dutch (nl-iso-8859-1)");
$charsets[] = array ( 'value'=>"iso-8859-1", 'name'=>"English (en-iso-8859-1)");
$charsets[] = array ( 'value'=>"iso-8859-1", 'name'=>"Estonian (et-iso-8859-1)");
$charsets[] = array ( 'value'=>"iso-8859-1", 'name'=>"Finnish (fi-iso-8859-1)");
$charsets[] = array ( 'value'=>"iso-8859-1", 'name'=>"French (fr-iso-8859-1)");
$charsets[] = array ( 'value'=>"iso-8859-1", 'name'=>"Galician (gl-iso-8859-1)");
$charsets[] = array ( 'value'=>"iso-8859-1", 'name'=>"German (de-iso-8859-1)");
$charsets[] = array ( 'value'=>"iso-8859-7", 'name'=>"Greek (el-iso-8859-7)");
$charsets[] = array ( 'value'=>"iso-8859-8-i", 'name'=>"Hebrew (he-iso-8859-8-i)");
$charsets[] = array ( 'value'=>"iso-8859-2", 'name'=>"Hungarian (hu-iso-8859-2)");
$charsets[] = array ( 'value'=>"iso-8859-1", 'name'=>"Indonesian (id-iso-8859-1)");
$charsets[] = array ( 'value'=>"iso-8859-1", 'name'=>"Italian (it-iso-8859-1)");
$charsets[] = array ( 'value'=>"euc-jp", 'name'=>"Japanese (ja-euc)");
$charsets[] = array ( 'value'=>"SHIFT_JIS", 'name'=>"Japanese (ja-sjis)");
$charsets[] = array ( 'value'=>"ks_c_5601-1987", 'name'=>"Korean (ko-ks_c_5601-1987)");
$charsets[] = array ( 'value'=>"windows-1257", 'name'=>"Latvian (lv-win1257)");
$charsets[] = array ( 'value'=>"windows-1257", 'name'=>"Lithuanian (lt-win1257)");
$charsets[] = array ( 'value'=>"iso-8859-1", 'name'=>"Malay (ms-iso-8859-1)");
$charsets[] = array ( 'value'=>"iso-8859-1", 'name'=>"Norwegian (no-iso-8859-1)");
$charsets[] = array ( 'value'=>"windows-1256", 'name'=>"Persian (fa-win1256)");
$charsets[] = array ( 'value'=>"iso-8859-2", 'name'=>"Polish (pl-iso-8859-2)");
$charsets[] = array ( 'value'=>"iso-8859-1", 'name'=>"Portuguese (pt-iso-8859-1)");
$charsets[] = array ( 'value'=>"iso-8859-1", 'name'=>"Romanian (ro-iso-8859-1)");
$charsets[] = array ( 'value'=>"dos-866", 'name'=>"Russian (ru-dos-866)");
$charsets[] = array ( 'value'=>"koi8-r", 'name'=>"Russian (ru-koi8-r)");
$charsets[] = array ( 'value'=>"windows-1251", 'name'=>"Russian (ru-win1251)");
$charsets[] = array ( 'value'=>"windows-1251", 'name'=>"Serbian (sr-win1251)");
$charsets[] = array ( 'value'=>"windows-1250", 'name'=>"Serbian latin (sr-lat-win1250)");
$charsets[] = array ( 'value'=>"iso-8859-2", 'name'=>"Slovak (sk-iso-8859-2)");
$charsets[] = array ( 'value'=>"windows-1250", 'name'=>"Slovak (sk-win1250)");
$charsets[] = array ( 'value'=>"iso-8859-2", 'name'=>"Slovenian (sl-iso-8859-2)");
$charsets[] = array ( 'value'=>"windows-1250", 'name'=>"Slovenian (sl-win1250)");
$charsets[] = array ( 'value'=>"iso-8859-1", 'name'=>"Spanish (es-iso-8859-1)");
$charsets[] = array ( 'value'=>"iso-8859-1", 'name'=>"Swedish (sv-iso-8859-1)");
$charsets[] = array ( 'value'=>"tis-620", 'name'=>"Thai (th-tis-620)");
$charsets[] = array ( 'value'=>"iso-8859-9", 'name'=>"Turkish (tr-iso-8859-9)");
$charsets[] = array ( 'value'=>"windows-1251", 'name'=>"Ukrainian (uk-win1251)");

foreach ( $charsets as $ch )
	$charsets_unique[] = $ch['value'];
$charsets_unique = array_unique ( $charsets_unique );


//-----------------------------------------------------
// SaveDbDumpFile
//-----------------------------------------------------

function SaveDbDumpFile ($db) {
			
	$sqldump="";

	$tbl = mysql_query ("show tables"); // Get table names in database;
	while ( $res = mysql_fetch_array ( $tbl ) )
		$tables[] = $res[0];
	
	if( !empty ( $tables ) ) {
	
	foreach ($tables as $table) {
		
		$sqldump.="DROP TABLE IF EXISTS $table;"; // Instructions for drop table
		
		$tbl = mysql_query ("SHOW CREATE TABLE $table"); // Get table names in database;
		while ( $res = mysql_fetch_array ( $tbl ) )
			$sqldump .= $res[1] .';';
		
		// Insert instructions
		
		$insert_instr="INSERT INTO `$table` (";
		
		$tbl = mysql_query ("SHOW COLUMNS FROM $table"); // Get row names in table;
		while ( $res = mysql_fetch_row ( $tbl ) )
			$insert_instr .= "`".$res[0] . '`,';
		
		$insert_instr[strlen($insert_instr)-1]=')';
		$insert_instr.=' VALUES (';
		
		$tbl = mysql_query ("SELECT * FROM $table"); // Get data
		while ( $res = mysql_fetch_assoc ($tbl ) ) {
		
			$sqldump .= $insert_instr;
			
			foreach ( $res as $r ) {
				$r = addslashes( $r );
				$sqldump.= "\"" . $r . "\",";
			}
			
			$sqldump[strlen($sqldump)-1]=')';
			$sqldump.=';';
		}
	
	}
	
	
	$filename="backup_" . date ("Y-m-d");
	$ext="sql";
	
	
	
	header('Content-Type: application/octetstream');
	header('Content-Disposition: inline; filename="' . $filename . '.' . $ext . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
	
	echo $sqldump;
	
	}
	
}


//-----------------------------------------------------
// Restore from dump file
//-----------------------------------------------------

function RestoreDbFromFile( $db, $file ) {
	
	global $lng;
	
	set_time_limit (0); //Setting no time limit for execution
	
	$err = 0;
	
	if ( is_file($file) ) {
	
		$f = fopen($file,'r');
		$content = fread ( $f, filesize($file) );
				
		$sqlquery = array();
		splitSqlFile ( $sqlquery, $content, 0 );
		
		foreach ( $sqlquery as $sql ) {
		
			if ( !$res= mysql_query( $sql ) )
				$err = 1;
		
		}
		
		if ( $err == 0 )
			return '';
		else 	return $lng[911];
	
	} 
	else 	return $lng[912];
	
}


/**
 * Removes comment lines and splits up large sql files into individual queries
 *
 *
 * @param   array    the splitted sql commands
 * @param   string   the sql commands
 * @param   integer  the MySQL release number (because certains php3 versions
 *                   can't get the value of a constant from within a function)
 *
 * @return  boolean  always true
 *
 * @access  public
 */
function splitSqlFile(&$ret, $sql, $release)
{
    $sql          = trim($sql);
    $sql_len      = strlen($sql);
    $char         = '';
    $string_start = '';
    $in_string    = FALSE;
    $time0        = time();

    for ($i = 0; $i < $sql_len; ++$i) {
        $char = $sql[$i];

        // We are in a string, check for not escaped end of strings except for
        // backquotes that can't be escaped
        if ($in_string) {
            for (;;) {
                $i         = strpos($sql, $string_start, $i);
                // No end of string found -> add the current substring to the
                // returned array
                if (!$i) {
                    $ret[] = $sql;
                    return TRUE;
                }
                // Backquotes or no backslashes before quotes: it's indeed the
                // end of the string -> exit the loop
                else if ($string_start == '`' || $sql[$i-1] != '\\') {
                    $string_start      = '';
                    $in_string         = FALSE;
                    break;
                }
                // one or more Backslashes before the presumed end of string...
                else {
                    // ... first checks for escaped backslashes
                    $j                     = 2;
                    $escaped_backslash     = FALSE;
                    while ($i-$j > 0 && $sql[$i-$j] == '\\') {
                        $escaped_backslash = !$escaped_backslash;
                        $j++;
                    }
                    // ... if escaped backslashes: it's really the end of the
                    // string -> exit the loop
                    if ($escaped_backslash) {
                        $string_start  = '';
                        $in_string     = FALSE;
                        break;
                    }
                    // ... else loop
                    else {
                        $i++;
                    }
                } // end if...elseif...else
            } // end for
        } // end if (in string)

        // We are not in a string, first check for delimiter...
        else if ($char == ';') {
            // if delimiter found, add the parsed part to the returned array
            $ret[]      = substr($sql, 0, $i);
            $sql        = ltrim(substr($sql, min($i + 1, $sql_len)));
            $sql_len    = strlen($sql);
            if ($sql_len) {
                $i      = -1;
            } else {
                // The submited statement(s) end(s) here
                return TRUE;
            }
        } // end else if (is delimiter)

        // ... then check for start of a string,...
        else if (($char == '"') || ($char == '\'') || ($char == '`')) {
            $in_string    = TRUE;
            $string_start = $char;
        } // end else if (is start of string)

        // ... for start of a comment (and remove this comment if found)...
        else if ($char == '#'
                 || ($char == ' ' && $i > 1 && $sql[$i-2] . $sql[$i-1] == '--')) {
            // starting position of the comment depends on the comment type
            $start_of_comment = (($sql[$i] == '#') ? $i : $i-2);
            // if no "\n" exits in the remaining string, checks for "\r"
            // (Mac eol style)
            $end_of_comment   = (strpos(' ' . $sql, "\012", $i+2))
                              ? strpos(' ' . $sql, "\012", $i+2)
                              : strpos(' ' . $sql, "\015", $i+2);
            if (!$end_of_comment) {
                // no eol found after '#', add the parsed part to the returned
                // array if required and exit
                if ($start_of_comment > 0) {
                    $ret[]    = trim(substr($sql, 0, $start_of_comment));
                }
                return TRUE;
            } else {
                $sql          = substr($sql, 0, $start_of_comment)
                              . ltrim(substr($sql, $end_of_comment));
                $sql_len      = strlen($sql);
                $i--;
            } // end if...else
        } // end else if (is comment)

        // ... and finally disactivate the "/*!...*/" syntax if MySQL < 3.22.07
        else if ($release < 32270
                 && ($char == '!' && $i > 1  && $sql[$i-2] . $sql[$i-1] == '/*')) {
            $sql[$i] = ' ';
        } // end else if

        // loic1: send a fake header each 30 sec. to bypass browser timeout
        $time1     = time();
        if ($time1 >= $time0 + 30) {
            $time0 = $time1;
            header('X-pmaPing: Pong');
        } // end if
    } // end for

    // add any rest to the returned array
    if (!empty($sql) && ereg('[^[:space:]]+', $sql)) {
        $ret[] = $sql;
    }

    return TRUE;
} // end of the 'PMA_splitSqlFile()' function


?>