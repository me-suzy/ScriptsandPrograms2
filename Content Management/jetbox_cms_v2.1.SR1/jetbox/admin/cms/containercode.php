<?
//primarykey of the table
	$primarykey="id";
//table name
	$tablename="container";
//title of the administration page
$pagetitle="Container code generation";
//name of this file, starting with a slash
$thisfile =		"/".array_pop(explode("/", $_SERVER["SCRIPT_NAME"]));
require("../../includes/includes.inc.php");
//get container id
$result = mysql_prefix_query("SELECT id, uid FROM container WHERE cfile='".$thisfile."'");
$container_id= @mysql_result($result,0,'id');
$mailuid= @mysql_result($result,0,'uid');
//Show left menu and top tabs.
//$nomenu=true;
// format: fieldname,fieldtype,display text
// if you wish to hide a field set the fieldtype to 'hidden'
$records= array(array("id","hidden","id",""),// unique id
								array("cfile",	"display","File name",""),//container file name
								array("cname",	"string","Name in navigation","required"),//Container name in navigation
								array("corder",	"string","Position","required"),//Relative position in navigation
								array("uid",		"dropdown","Request for approval email receiver","required"),//User who receives email for approval
								array("level",	"radio3","Visible for",""),// Requird user level
								array("inuseradmin","radio","Userrights managed",""),// Editable in user administration
								array("generalview","radio","General status overview",""),// Viewed @ general overview after login
			 );

// format:  dropdownfieldname=> lookuptablename, lookuptableprimarykey, lookuptabledisplayfield, argument
$dropdown = array("uid"=>array("user, userrights","user.uid","user.display_name", "WHERE  (user.type='administrator') OR ( user.uid=userrights.uid AND userrights.container_id='".$container_id."' AND userrights.type IN ('administrator', 'editor')) GROUP BY user.uid"),
			);
//array configuration
$jetstream_nav = array (
		"2.1"		=>  array($jetstream_url . $thisfile => "Start"),		//2.1 general overview
		"2.2"		=>  array($jetstream_url . $thisfile . "?task=createrecord" => "New container"),	//2.2 New item
		"2.4"		=>  array($jetstream_url ."/index.php?task=edit&container_id=".$container_id => "Generate container code"),//2.4 preferences
);

//language
$generallanguage = array(
	"beingedited"=>"This page is locked.",
	"norights"=>"You don't have the right permissions to preform this action.",
	"undeleteunable"=>"This container can not be undeleted (its not deleted).",
);

// First segment is header of information section and Title of link (help, general instructions)
// Other segments a are seen if section is folded out
// Help under list
$hul=array('Help'=>'What is this generator for',
						'What is a container'=>'Containers are placeholders for administration sections in Jetbox CMS. A container provides an interface to administate information. All items in the left main menu of Jetbox are containers. Per container userrights are configurable.',
						'Use of this generator'=>'With this generator you can create containers that make use of all standard features in Jetbox. For instance it is possible to create a container like "Links" & "Links categorie" with this generator.',
);
$hul2=array(''=>'Generation process',
						'Step 1: design the section'=>'First you need to know what type of information you want on your website. You need to know exact from which fields this information is built up.<br> A News item for instance is built up from a title, content, date & author',
						'Step 2: design the database'=>'Create a database table with the fields you need, we recommend using PHPmyAdmin for this process.',
						'Step 3: generate the container'=>'Select the appropriate database table from the pull down menu. Follow this wizard. You may experiment with this wizard as much as you want, you won\'t break anything.',
						'Step 4: save the generated container'=>'At the end of this wizard you will be presented with a page that contains the PHP code for the container. Copy & paste this code in a file and save this in the /admin/cms/ folder.',
						'Step 5: plug the container in Jetbox'=>'Select "Container" from the left main menu. Select the "New container" tab. Fill out all the required information, please note the help information below that form.',
						'Step 6: configure user rights'=>'If you only want this container to be available for "Administrators, you are ready. To make the new container available for "Editor" & "Auhors" you need to configure the user rights by selecting "Users" from the main left menu.',
						'Final:'=>'You are all setup to use the new container.',
						'<b>Please note:<b>'=>'<b>By generation a new container and plugging it into Jetbox, the front end will not magically display this new section. You have to integrate it manually in the website and you need understanding of PHP to do so. For more information about this subject please open the main "index.php" file from the /jetbox/ folder.</b>',
);

// Help under form
$h_step2_1=array(	'Help'=>'General configuration for container',
									'Primarykey'=>'Database table field name of the primary key. Jetbox cms supports one primary key per table only. This fields must be auto incrementing.',
									'Page title'=>'Shortest description if the items administrated here.',
									'Name of single item'=>'Descriptive name of a single item. This description is used in the action tabs. (New "item" and "Edit item")',
									'System title field'=>'The contents of this field is used in the editorial overview as a general description of the item. ',
									'Work flow'=>'Enable or disable work flow for this container.',
									'Add items'=>'Enable or disable the ability to create new items.',
									'Remove items'=>'Enable or disable the ability to remove items.',
									'Restricted access, <i>only for non-work flow enabled items.'=>'Restricts the ability to change or remove items. If enabled only the owner may do so.',
);

$h_step2_2=array(	'First'=>'Form configuration for container',
									'Field'=>'Name of the database table fields.',
									'Type'=>'Type of the field.',
									'Standard value'=>'Name of the database table fields.',
									'Nice field name'=>'Descriptive name of the field. This is the title of the field the user will see in the form.',
									'<i>Form field type</i>'=>'',
									'Input (79):'=>'Is a standard text field, this one aligns with the rich text field. 10, 20, 30 and 40 have different field lenghts.',
									'Rich text:'=>'Standard texarea with link to rich text editor, has ability to insert images.',
									'Drop down menu:'=>'dropdown menu fills with records form other database table. in the "dropdown menu configuration" section a database table must be selected for the corresponding field.',
									'File:'=>'Upload a file. Extra items must be configured manualy.',
									'Only show:'=>'The value is display as text, the user won\'t be able to change this value. A hidden field contains the value.',
									'No value:'=>'Used for compatibility.',
									'Value from url:'=>'When a new item is made the hidden field gets the value from the url. If the item is edited the value remains the same. Not seen by the user.',
									'Value from url referred:'=>'When a new item is made the hidden field gets the value from the url. But the value shown to the user is matched with an other database table (like a dropdown). <br>If the item is edited the value remains the same. The value can\'t be changed by the user.',
									'User:'=>'When a new item is made the hidden field gets the value from the user identifier. Not seen by the user.',
									'Required'=>'The field must contain a value. To indicate this a small gray bar is displayed in front of the form field.',
									'Listed'=>'Show this field in the list overview of the items.',
									'<i>List type</i>'=>'',
									'Edit link:'=>'Link to the edit form. Shows the value of the field. ',
									'Referref edit link:'=>'Links to the edit form. But the value shown to the user is matched with an other database table (like a dropdown).',
									'Show value:'=>'Value of field is show.',
									'Referred show:'=>'Value shown to the user is matched with an other database table (like a dropdown).',
									'Show image:'=>'Creates a new window for the image preview.',
									'Show file link:'=>'Link to uploaded file.',
									'Yes/no:'=>'Shows yes or no, but field value is true or false.',
									'Date:'=>'Shows date in several formats.',
);

$h_step2_3=array(	''=>'Dropdown menu configuration',
									'Formfield types'=>'Formfield types with a * require extra configuration in this section.<br>A dropdown menu is used to link an other database table. The primary key of the linked table is stored, but an other field can be shown to the user.',
									'Dropdownfield'=>'Indicates the corresponding field in the "Form configuration section".',
									'Tablename'=>'Select the table to link to.',
);
$drop2array= array (
	"-Select-"=>"string",
	"Primary key"=>"hidden",
	"Input (79)"=>"string",
	"Input 10"=>"string10",
	"Input 20"=>"string20",
	"Input 30"=>"string30",
	"Input 40"=>"string40",

	"Password"=>"password",
	"Password 30"=>"password30",

	"Textarea"=>"blob",
	"Rich text"=>"richblob",


	"Date"=>"date",
	"checkbox yes/no"=>"checkbox",
	"Radio yes/no (standard)"=>"radio",
	"Dropdown menu *"=>"dropdown",
	"Image (popup select)"=>"image",
	"File"=>"file",

	"Hidden"=>"hidden",
	"Only show"=>"display",
	"No value"=>"novalue",
	"Radio no/yes [no/yes]"=>"radioyesno",
	"Radio no/yes [false/true]"=>"radiotruefalse",
	"Value from url"=>"valuefromurl",
	"Value from url referred *"=>"valuefromurlshowlinked",
	"User"=>"uid",

);

$drop3array= array (
	"-Select-"=>"editlink",
	"Edit link"=>"editlink",
	"Referred edit link **"=>"refeditlink",
	"Show value"=>"string",
	"Referred show **"=>"reflink",
	"Show image"=>"imagelink",
	"Show file link"=>"showfilelink",
	"Yes/no"=>"radioyesno",
	"Date dd-mm-yyyy"=>"date",
	"Date dd-mm-yy"=>"date2",

);

general_date_process();
ob_start();
session_start();
if (isset($_SESSION["uid"])){
	jetstream_header($pagetitle);
	loggedin();
}
elseif (new_visit()){
	jetstream_header($pagetitle);
	loggedin();
}

function loggedin($uid=''){
	switch($_REQUEST["task"]) {

		case 'step2':
			step2('edit');
		break;
		case 'step3':
		case 'step4':
			step3('edit');
		break;
		default : step1();
	}

}
//

function gettables($database, $field_name=''){
	$result     = mysql_list_tables($database);
	$num_tables = @mysql_numrows($result);
	$field_name ? $tables.="" :$tables.=  "\n<select name=\"table".$field_name."\">";
	$field_name ? $tables.=  "\n<option class=\"form_select\" value=\"\"> -Select-" : $tables.="";
	for ($i = 0; $i < $num_tables; $i++) {
		$tables.=  "\n<option class=\"form_select\" value=\"". mysql_tablename($result, $i) . "\">" . mysql_tablename($result, $i);
	}
	$field_name ? $tables.="" : $tables.=  "\n</select>";
	mysql_free_result($result);
	return $tables;
}

function show_ruler($type, $span, $id='', $show=''){
	$ruler = "<tr";
	if(strlen($id)<>0){
		$ruler.=" id=\"".$id."\"";
	}
	if(!is_string($show) && $show==1){
		$ruler.=" style=\"display:none\"";
	}

	if($type==1){
		$ruler.= '><td colspan="'.$span.'" class="ruler"></td></tr>';
	}
	else{
		$ruler.= '><td colspan="'.$span.'" class="ruler2"></td></tr>';
	}
	return $ruler;
}

function step1($error='', $blurbtype='notify') {
	global $records, $tablename, $primarykey, $jetstream_url, $jetstream_nav, $thisfile, $generalconfig, $container_id, $status, $userright, $uid, $database, $hul, $hul2, $hul3;
	$tabs[]="2.1";
	$span=1;
	jetstream_ShowSections($tabs, $jetstream_nav, "2.1");
	errorbox ($error, $blurbtype);
	//Step one select database table";
	$span=1;
	$ruler = '<tr><td colspan="'.$span.'" class="ruler"></td></tr>';
	$ruler2 = '<tr><td colspan="'.$span.'" class="ruler2"></td></tr>';

	echo "\n<FORM ACTION=\"". $jetstream_url . $thisfile. "\" METHOD=\"POST\">";
	$tables=gettables($database);
	echo "\n<table border=0 width=100% cellspacing=\"0\" cellpadding=\"4\">";
	echo $ruler;
	echo "\n<tr bgcolor=\"#F6F6F6\">";
	echo "\n<td class='tab-g'>Select the table you want to administrate</td>";
	echo "\n</tr>";
	echo $ruler;
	echo "\n<tr bgcolor=\"#ffffff\">";
	echo "\n<td valign=\"top\" width=20% nowrap>$tables</td>\n";
	echo "\n</tr>";
	echo "\n<input type=\"Hidden\" name=\"task\" value=\"step2\">";
	echo "\n<tr><td><br></td></tr>";
	echo "\n<tr>\n<td colspan=2><input class=\"form_button\" type=\"submit\" value=\"Step One of Three\">";
	echo "\n</td></tr>";
	echo help_section($hul, $ruler, $ruler2, $span);
	echo help_section($hul2, $ruler, $ruler2, $span, false);
	echo help_section($hul3, $ruler, $ruler2, $span, false);
	echo "</TABLE>\n</FORM>";
}

function step2($action, $error='') {
	global $records, $tablename, $primarykey, $dropdown, $jetstream_url, $jetstream_nav, $thisfile, $sessid, $sesscode, $userright, $status, $uid;
	global $container_id, $generalconfig, $containerdropdown, $drop2array, $drop3array, $database;
	global $h_step2_1, $h_step2_2, $h_step2_3;
	$span=10;

	//Step two show general form";
	$ruler = '<tr><td colspan="'.$span.'" class="ruler"></td></tr>';
	$ruler2 = '<tr><td colspan="'.$span.'" class="ruler2"></td></tr>';
	$tabs[]="2.1";
	$userright=get_userrights($_SESSION["uid"]);
	if ($userright=="administrator"){
		$tabs[]="2.4";
		$selectedtab="2.4";
	}
	$droptables=gettables($database, 'a');
	jetstream_ShowSections($tabs, $jetstream_nav, $selectedtab);
	errorbox ($error, $blurbtype);
	echo "\n<FORM ACTION=\"". $jetstream_url . $thisfile. "\" METHOD=\"POST\">";
	// 2. Gets table keys and retains them
	$table = $_REQUEST["table"];
	$local_query = 'SHOW KEYS FROM ' . $table;
	$result      = mysql_prefix_query($local_query) or die (mysql_error());
	$primary     = '';
	$ret_keys    = array();
	$pk_array    = array(); // will be use to emphasis prim. keys in the table view
	while ($row = mysql_fetch_array($result)) {
		$ret_keys[]  = $row;
			if ($row['Key_name'] == 'PRIMARY') {
				$primary .= $row['Column_name'] . ', ';
				$pk_array[$row['Column_name']] = 1;
		}
	}
	mysql_free_result($result);

	// 3. Get fields
	$local_query = 'SHOW FIELDS FROM ' . $table;
	$fields_rs   = mysql_prefix_query($local_query) or die (mysql_error());
	$fields_cnt  = mysql_num_rows($fields_rs);

	$i         = 0;
	$aryFields = array();
	while ($row = mysql_fetch_array($fields_rs)) {
    $i++;
    $aryFields[]      = $row['Field'];
    $type             = $row['Type'];
    // reformat mysql query output - staybyte - 9. June 2001
    // loic1: set or enum types: slashes single quotes inside options
    if (eregi('^(set|enum)\((.+)\)$', $type, $tmp)) {
			$tmp[2]       = substr(ereg_replace('([^,])\'\'', '\\1\\\'', ',' . $tmp[2]), 1);
			$type         = $tmp[1] . '(' . str_replace(',', ', ', $tmp[2]) . ')';
			$type_nowrap  = '';
    }
		else {
			$type_nowrap  = ' nowrap="nowrap"';
    }
    $type             = eregi_replace('BINARY', '', $type);
    $type             = eregi_replace('ZEROFILL', '', $type);
    $type             = eregi_replace('UNSIGNED', '', $type);
    if (empty($type)) {
			$type         = '&nbsp;';
    }

    $binary           = eregi('BINARY', $row['Type'], $test);
    $unsigned         = eregi('UNSIGNED', $row['Type'], $test);
    $zerofill         = eregi('ZEROFILL', $row['Type'], $test);
    $strAttribute     = '&nbsp;';
    if ($binary) {
			$strAttribute = 'BINARY';
    }
    if ($unsigned) {
			$strAttribute = 'UNSIGNED';
    }
    if ($zerofill) {
			$strAttribute = 'UNSIGNED ZEROFILL';
    }
    if (!isset($row['Default'])) {
			if ($row['Null'] != '') {
				$row['Default'] = 'NULL';
			}
    } else {
			$row['Default'] = htmlspecialchars($row['Default']);
    }
    $field_name = htmlspecialchars($row['Field']);
    if (isset($pk_array[$row['Field']])) {
			//$field_name = '<b>' . $field_name . '</b> [Key]';
    }
    $ddfields.="\n<option class=\"form_select\" value=\"". $field_name . "\">" . $field_name;

		// START FIELDS
		// field name
		$ffields.= "\n<tr><td nowrap=\"nowrap\">&nbsp;".$field_name."&nbsp;</td>";
		$ffields.= "<td ".$type_nowrap.">".$type."</td>";
		$ffields.= "<td nowrap=\"nowrap\">";
		if (isset($row['Default'])){
			$ffields.= $row['Default'];
		}
		$ffields.= "&nbsp;</td>";

		// nice field name
		$ffields.= "<td><input class=\"form_input\" type=\"TEXT\" name=\"nicefieldname_" . $field_name. "\" value=\"".ucfirst(strtolower($field_name))."\" size=\"25\"></td>";

		// field type
		$ffields.= "<td>";
		$ffields.=  "\n<select name=\"dropdinput_".$field_name."\" onchange=\"dropdowncheck(this[this.selectedIndex].value, '".$field_name."')\">";
		foreach ($drop2array as $key => $value) {
			//echo "Key: $key; Value: $value<br>\n";
			$ffields.=  "\n<option class=\"form_select\" value=\"". $value . "\">" . $key;
		}
		$ffields.=  "\n</select></td>";

		// process
		$ffields.=  "\n<TD VALIGN=\"TOP\"><input type=\"checkbox\" name=\"process_$field_name\" value=\"1\"></td>";

		// default value
		$ffields.= "<td><input class=\"form_input\" type=\"TEXT\" name=\"defaultvalue_" . $field_name. "\" value=\"\" size=\"20\"></td>";

		// required,
		$ffields.=  "\n<TD VALIGN=\"TOP\"><input type=\"checkbox\" name=\"required_$field_name\" value=\"1\"></td>";

		// listed
		$ffields.=  "\n<td><input type=\"checkbox\" name=\"list_$field_name\" onClick=\"if(this.checked){listinput_".$field_name.".disabled=false}else{listinput_".$field_name.".disabled=true}\" value=\"1\"></td>";

		// list type
		$ffields.= "<td>";
		$ffields.=  "\n<select name=\"listinput_".$field_name."\" disabled>";
		foreach ($drop3array as $key => $value) {
			//echo "Key: $key; Value: $value<br>\n";
			$ffields.=  "\n<option class=\"form_select\" value=\"". $value . "\">" . $key;
		}
		$ffields.=  "\n</select></td>";

		$ffields.= "<td>";
		$ffields.=  "\n</tr>";
		$ffields.= '<tr><td colspan=10 style="PADDING-RIGHT: 0px; PADDING-LEFT: 0px; PADDING-BOTTOM: 0px; PADDING-TOP: 0px" valign="top" background="images/bg_dotpattern.gif" height=1></td></tr>';
		if (!isset($pk_array[$row['Field']])) {
			$dropdowns.="<tr id=\"row_".$row['Field']."\" style=\"DISPLAY: none\"><td>&nbsp;". $field_name."&nbsp;</td><td colspan=9>\n<select name=\"droptable_".$field_name."\" id=\"droptable_".$field_name."\" disabled>".$droptables."\n</select></td></tr>";
		}
		//$dropdowns.='<tr><td colspan=10 style="PADDING-RIGHT: 0px; PADDING-LEFT: 0px; PADDING-BOTTOM: 0px; PADDING-TOP: 0px" valign="top" background="images/bg_dotpattern.gif" height=1></td></tr>';
    if (isset($pk_array[$row['Field']])) {
			$dfields.="<tr id=\"row_".$row['Field']."\"><td nowrap=\"nowrap\">Primarykey</td><td  colspan=2>".$row['Field']."<input class=\"form_input\" type=\"hidden\" name=\"pkey\" value=\"".$row['Field']."\" size=\"20\" MAXLENGTH=\"255\"></td>";
    	$dfields.=$ruler;
		}
		// END FIELDS
	} // end while

	// START TABLE
		?>
	<script language=javascript>
	<!--
	function wf_options(a) {
		//alert(a);
		if (a==0){
			document.getElementById('sf_ruler').style.display="none";
			document.getElementById('sf_row').style.display="none";

			document.getElementById('ra_ruler').style.display="block";
			document.getElementById('ra_row').style.display="block";

		}
		else{
			document.getElementById('sf_ruler').style.display="block";
			document.getElementById('sf_row').style.display="block";

			document.getElementById('ra_ruler').style.display="none";
			document.getElementById('ra_row').style.display="none";
		}
		//var all_obj = new Array(), j = 0;
		//all_obj[j++]="dropdown";
		//all_obj[j++]="valuefromurlshowlinked";
		//c='droptable_'+b;
		//d='row_'+b;
		//if (document.getElementById(c)!=null) {
		//	document.getElementById(c).disabled=true;
		//	document.getElementById(d).style.display="none";
		//}
		//for ( i = 0; i < all_obj.length; i++ ) {
		//	if(all_obj[i]==a){
		//		if (document.getElementById(c)!=null) {
		//			document.getElementById(c).disabled=false;
		//			document.getElementById(d).style.display="block";
		//		}
		//		break;
		//	}
		//}
	
	}
	// -->
	</script>

	<?
	echo "\n<TABLE border=0 width=100% cellpadding=\"4\" cellspacing=\"0\">\n";
	echo $ruler2;
	echo "\n<tr bgcolor=\"#ffffff\">\n<td colspan=10  background='images/stab-bg.gif'><font color=\"\"><b>General configuration for ".$_REQUEST["table"]."</b></font></td></tr>";
	echo $ruler;
	echo $dfields;
	echo "<tr><td nowrap=\"nowrap\">Table</td><td colspan=2>".$_REQUEST["table"]."<input class=\"form_input\" type=\"hidden\" name=\"table\" value=\"".$_REQUEST["table"]."\" size=\"20\" MAXLENGTH=\"255\"></td>";
	echo $ruler;
	echo "<tr><td nowrap=\"nowrap\">Page title</td><td colspan=2><input class=\"form_input\" type=\"TEXT\" name=\"titlep\" value=\"\" size=\"20\" MAXLENGTH=\"255\"></td>";
	echo $ruler;
	echo "<tr><td nowrap=\"nowrap\">Name of single item</td><td colspan=2><input class=\"form_input\" type=\"TEXT\" name=\"nosi\" value=\"\" size=\"20\" MAXLENGTH=\"255\"></td>";
	echo show_ruler(1, 10);
	echo "\n<tr>\n<td nowrap=\"nowrap\">Workflow</td>\n<td colspan=2 valign=\"top\">
	<input type=\"radio\" name=\"workflow\" value=\"false\" onclick=\"javascript:wf_options(0);\">No &nbsp; &nbsp; 
	<input type=\"radio\" name=\"workflow\" checked value=\"true\" onclick=\"javascript:wf_options(1);\"> Yes</td><td colspan=\"5\"><font size=\"-2\">Enable workflow.</font></td>\n\n</tr>";
	echo show_ruler(1, 10, 'sf_ruler', 0);
	echo "<tr id=\"sf_row\"><td nowrap=\"nowrap\">System title field</td><td colspan=2><select name=\"systemtitlefield\">".$ddfields."\n</select></td>\n<td colspan=\"3\"><font size=\"-2\">The workflow system uses this field to title the item.</font></td>\n</tr>";
	echo show_ruler(1, 10, 'ra_ruler', 1);
	echo "\n<tr style=\"display: none\" id=\"ra_row\">\n<td nowrap=\"nowrap\">Restricted access</td>\n<td colspan=2 valign=\"top\"><input type=\"radio\" name=\"userowneditemsonly\" value=\"false\" checked>No &nbsp; &nbsp; <input type=\"radio\" name=\"userowneditemsonly\" value=\"true\"> Yes</td>\n<td colspan=\"5\"><font size=\"-2\">Only owned items can be seen in the list.</font></td>\n</tr>";

	echo $ruler;
	echo "\n<tr>\n<td nowrap=\"nowrap\">Add items</td>\n<td colspan=2 valign=\"top\"><input type=\"radio\" name=\"addnewitemoption\" value=\"false\">No &nbsp; &nbsp; <input type=\"radio\" name=\"addnewitemoption\" checked value=\"true\"> Yes</td><td colspan=\"5\"><font size=\"-2\">New items can be created.</font></td>\n\n</tr>";
	echo $ruler;
	echo "\n<tr>\n<td nowrap=\"nowrap\">Remove items</td>\n<td colspan=2 valign=\"top\"><input type=\"radio\" name=\"deleteitemoption\" value=\"false\">No &nbsp; &nbsp; <input type=\"radio\" name=\"deleteitemoption\" checked value=\"true\"> Yes</td><td colspan=\"5\"><font size=\"-2\">Existing item can be removed.</font></td>\n\n</tr>";

	
	echo $ruler;
	echo "\n<tr>\n<td nowrap=\"nowrap\">Default sort column</td>\n<td colspan=2 valign=\"top\"><select name=\"sort_column\">".$ddfields."\n</select><td colspan=\"5\"><font size=\"-2\">By defaults the overview list will be sorted by this column </font></td>\n</tr>";
	
	echo $ruler;
	echo "\n<tr>\n<td nowrap=\"nowrap\" valign=\"top\">Default sort order</td>\n<td colspan=2 valign=\"top\"><input type=\"radio\" name=\"sort_order\" value=\"ASC\" checked>Ascending<br><input type=\"radio\" name=\"sort_order\" value=\"DESC\">Descending</td>\n<td colspan=\"5\" valign=\"top\"><font size=\"-2\">Order of  the sorting in the overview list.</font></td>\n</tr>";

	echo $ruler;
	echo "\n<tr>\n<td class='tab-g'><br></td></tr>";
	echo $ruler2;
	echo "\n<tr bgcolor=\"#ffffff\">\n<td colspan=10  background='images/stab-bg.gif'><font color=\"\"><b>Form configuration for ".$_REQUEST["table"]."</b></font></td></tr>";

	// START HEADER
	echo $ruler;
	echo "\n<tr bgcolor=\"#F6F6F6\" id=\"test_row\">";
	?>
	<script language=javascript>
	<!--
	function getStyle(el,styleProp)
	{
		var x = document.getElementById(el);
		if (window.getComputedStyle)
			var y = window.getComputedStyle(x,null).getPropertyValue(styleProp);
		else if (x.currentStyle)
			var y = eval('x.currentStyle.' + styleProp);
		return y;
	}

	function MM_findObj(n, d) { //v4.01
		var p,i,x;
		if(!d) d=document;
		if((p=n.indexOf("?"))>0&&parent.frames.length) {
			d=parent.frames[n.substring(p+1)].document;
			n=n.substring(0,p);
		}
		if(!(x=d[n])&&d.all)
			x=d.all[n];
		for (i=0;!x&&i<d.forms.length;i++)
			x=d.forms[i][n];
		for(i=0;!x&&d.layers&&i<d.layers.length;i++)
			x=MM_findObj(n,d.layers[i].document);
		if(!x && d.getElementById)
			x=d.getElementById(n);
		return x;
	}

	function dropdowncheck(a,b) {
		var all_obj = new Array(), j = 0;
		all_obj[j++]="dropdown";
		all_obj[j++]="valuefromurlshowlinked";
		c='droptable_'+b;
		d='row_'+b;
		//alert(c);
		c_obj= MM_findObj(c);
		d_obj= MM_findObj(d);
		row_vis=getStyle('test_row','display');
		//alert(row_vis);
		//alert(document.getElementById(c));
		if (c_obj!=null) {
			c_obj.disabled=true;
			d_obj.style.display='none';
		}
		for ( i = 0; i < all_obj.length; i++ ) {
			if(all_obj[i]==a){
				if (c_obj!=null) {
					c_obj.disabled=false;
					d_obj.style.display=row_vis;
				}
				break;
			}
		}
	}
	// -->
	</script>

	<?
	echo "\n<td class='tab-g' nowrap=\"nowrap\">Field</td>";
	echo "\n<td class='tab-g' nowrap=\"nowrap\">Type</td>";
	echo "\n<td class='tab-g' nowrap=\"nowrap\">Standard value</td>";
	echo "\n<td class='tab-g' nowrap=\"nowrap\">Nice field name</td>";
	echo "\n<td class='tab-g' nowrap=\"nowrap\">Formfield type</td>";
	echo "\n<td class='tab-g' nowrap=\"nowrap\">No-Processing</td>";
	echo "\n<td class='tab-g' nowrap=\"nowrap\">Default value</td>";
	echo "\n<td class='tab-g' nowrap=\"nowrap\">Required</td>";

	echo "\n<td class='tab-g' nowrap=\"nowrap\">Listed</td>";
	echo "\n<td class='tab-g' nowrap=\"nowrap\">List type</td>";
	echo "\n</tr>";
	echo $ruler;
	echo $ffields;
	echo "\n<tr>";
	echo "\n<td colspan=10>* Also configure a dropdown menu for this type op formfield.</td>";
	echo "\n</tr>";
	echo "\n<tr>";
	echo "\n<td colspan=10>** This type of list item requires extra input.<br>4 extra fields must be filled out in the \$listformat array for references to the other database table. (like a dropdown box)<br>(4) Look up table name, (5) Look up table primary key, (6) Look up table display field, (7) Argument.</td>";
	echo "\n</tr>";
	echo $ruler;
	echo "\n<tr>\n<td class='tab-g'><br></td></tr>";
	echo $ruler2;
	echo "\n<tr bgcolor=\"#ffffff\">\n<td colspan=10  background='images/stab-bg.gif'><font color=\"\"><b>Dropdown menu configuration </b><i>Step 1 of 2</i></font></td></tr>";
	echo $ruler;
	echo "\n<tr bgcolor=\"#F6F6F6\">";
	echo "\n<td class='tab-g'>Dropdownfield</td>";
	echo "\n<td class='tab-g' colspan=9>Tablename</td>";
	echo "\n</tr>";
	echo $ruler;
	echo $dropdowns;
	echo $ruler;
	/*
	echo "\n<tr>\n<td class='tab-g'><br></td></tr>";
	echo $ruler2;
	echo "\n<tr bgcolor=\"#ffffff\">\n<td colspan=10  background='images/stab-bg.gif'><font color=\"\"><b>Grouping configuration </b><i>Step 1 of 2</i></font></td></tr>";
	echo $ruler;
	echo "\n<tr bgcolor=\"#F6F6F6\">";
	echo "\n<td colspan=9>Configure this option only if you have 'Group list view' enabled.</td>";
	echo "\n</tr>";
	echo $ruler;
	echo "<tr><td nowrap=\"nowrap\">&nbsp;Table&nbsp;</td><td colspan=2>\n<select name=\"grouptablename\">".gettables($database, 'group')."\n</select></td></tr>";
	echo $ruler;
	*/
	echo "\n<tr><td><br></td></tr>";
	echo "\n<tr>\n<td colspan=2><input class=\"form_button\" type=\"submit\" value=\"Step Two of Three\" name=\"statusactionsave\">";
	echo "\n</td></tr>";
	echo "\n<tr><td><br></td></tr>";
	while (list($key, $value) = each($_REQUEST)) {
		if($key<>'table' && $key<>"task"){
			echo "\n<input type=\"Hidden\" name=\"".$key."\" value=\"".urlencode($value)."\">";
		}
	}

	echo "\n<input type=\"Hidden\" name=\"table\" value=\"".$_REQUEST["table"]."\">";
	echo "\n<input type=\"Hidden\" name=\"task\" value=\"step3\">";
	echo help_section($h_step2_1, $ruler, $ruler2, $span);
	echo help_section($h_step2_2, $ruler, $ruler2, $span, false);
	echo help_section($h_step2_3, $ruler, $ruler2, $span, false);


	echo "</TABLE>\n</FORM>";
}

function step3($action) {
	global $records, $tablename, $primarykey, $dropdown, $jetstream_url, $jetstream_nav, $thisfile, $sessid, $sesscode, $userright, $status, $uid;
	global $container_id, $generalconfig, $containerdropdown, $drop2array;
	$ruler = '<tr><td colspan="'.$span.'" class="ruler"></td></tr>';
	$ruler2 = '<tr><td colspan="'.$span.'" class="ruler2"></td></tr>';
	$tabs[]="2.1";
	$userright=get_userrights($_SESSION["uid"]);
	if ($userright=="administrator"){
		$tabs[]="2.4";
		$selectedtab="2.4";
	}
	jetstream_ShowSections($tabs, $jetstream_nav, $selectedtab);
	errorbox ($error, $blurbtype);
	echo "\n<FORM ACTION=\"". $jetstream_url . $thisfile. "\" METHOD=\"POST\">";
	//$_REQUEST[step]
	$table = $_REQUEST["table"];

	if ($_REQUEST["task"]=='step4'){
		$local_query = 'SHOW FIELDS FROM ' . $table;
		$fields_rs   = mysql_prefix_query($local_query) or die (mysql_error());
		$fields_cnt  = mysql_num_rows($fields_rs);
		$i         = 0;
		$aryFields = array();
		while ($row = mysql_fetch_array($fields_rs)) {
			$i++;
			$aryFields[]      = $row['Field'];
			$type             = $row['Type'];
			$field_name = htmlspecialchars($row['Field']);
			if (isset($pk_array[$row['Field']])) {
				//$field_name = '<b>' . $field_name . '</b> [Key]';
			}
			if ($_REQUEST["droptable_".$field_name]) {
				$dropdowns.= "\n\t\"".$field_name."\"=>array(";
				$dropdowns.="\"".$_REQUEST["droptable_".$field_name]."\", ";
				$dropdowns.="\"".$_REQUEST["dropdlutpk_" . $field_name]."\", ";
				$dropdowns.="\"".$_REQUEST["dropdlutsf_" . $field_name]."\", ";
				$dropdowns.="\"".$_REQUEST["dropdlutargu_" . $field_name]."\"), ";
			}
		} // end while

//echo "\n<TABLE border=0 width=100% cellpadding=\"4\" cellspacing=\"0\">\n<tr>";
echo "\n<TABLE border=0 width=100% cellpadding=\"4\" cellspacing=\"0\">\n";
echo $ruler2;
echo "\n<tr bgcolor=\"#ffffff\">\n<td colspan=10  background='images/stab-bg.gif'><font color=\"\"><b>Final configuration for ".$_REQUEST["table"]."</b></font></td></tr>";
echo $ruler;
echo "\n<tr><td colspan=10>\n";

errorbox("Final instructions:</b><br />
<li>The code below contains three sections, copy the code to the appropriate files and location according to the instruction given per section.<br />
<li>After you have created the administration container, don't forget to copy the \"Container ID\" in the front-end controller file.<br>
<li>Finally create a new link on the website to the new container with the \"Navigation\" administration section.


<br /><br /><b>The administration container code:</b><br />
This code is used for the administration section, only the very basic options are available via the container generator. Check out the php code of the standard provided containers in /admin/cms/ for more information.
<br /><br />

<b>The front-end controller code:</b><br />
This code generates a simple front-end page. It uses the front-end template for the layout.
<br /><br />

<b>The front-end template code:</b><br />
The default layout file for this container.

<br /><b>","notify");

echo "<pre><font color=\"#588ab0\">ADMINISTRATION CONTAINER\n";
echo "======================\n";
echo "// copy the code below to /admin/cms/".$_REQUEST["titlep"].".php\n\n</font>";

echo "// START COPY BELOW THIS LINE\n";
echo urldecode($_REQUEST["codesstep2"]);
echo "\n\n// format:  dropdownfieldname => lookuptablename, lookuptableprimarykey, lookuptabledisplayfield, argument";
echo "\n\$dropdown = array(";
echo $dropdowns;
echo "\n);";
?>


//array configuration
$jetstream_nav = array (
	"2.1"	=>  array($jetstream_url . $thisfile => "Overview"),		//2.1 general overview
	"2.2"	=>  array($jetstream_url . $thisfile . "?task=createrecord" => "New <?echo $_REQUEST["nosi"];?>"), //2.2 New item
	"2.3"	=>  array($jetstream_url . $thisfile . "?task=editrecord&". $primarykey. "=".$$primarykey => "Edit <?echo $_REQUEST["nosi"];?>"),//2.3 Change item
<?
if ($_REQUEST["workflow"]==true){
?>
	"2.4"	=>  array($jetstream_url . $thisfile . "?showarchive=true" => "Archive"),	//2.4 Archive
<?
}
else{
?>
	//"2.4"	=>  array($jetstream_url . $thisfile . "?showarchive=true" => "Archive"),	//2.4 Archive
<?

}
	?>
);

//language
$generallanguage = array(
	"beingedited"=>"This <?echo $_REQUEST["nosi"];?> item is locked.",
	"norights"=>"You don't have the right permissions to perform this action.",
	"undeleteunable"=>"This <?echo $_REQUEST["nosi"];?> item can not be undeleted (its not deleted).",
);

//general configuration for structure info
$generalconfig = array(
	'keywords'=>'',//meta keywords
	'description'=>'',//meta description
	'robot'=>'index,follow',//robot tags, empty means "index,folow"
	'ondate'=>date("Y-m-d  H:i:s",(time())),//date:time when content should go online, empty is inmediatly online, when aproved
	'offdate'=>date("Y-m-d  H:i:s",(time()+315360000)),//date:time when content should go offline, empty means online for the next ten years
	'comment'=>'',//Comment for item
	'systemtitle'=>$_REQUEST["<?echo $_REQUEST["systemtitlefield"];?>"]//article title for general overview
);

// First segment is header of information section and Title of link (help, general instructions)
// Other segments a are seen if section is folded out
// Help under list
$hul=array(	'Help'=>'General information',
	'Item'=>'Information.',
);
// Help under form
$huf=array(	'Help'=>'General information for creating a new item',
	'Item'=>'Information.',
);


function on_before_process(){

}

function on_before_form(){

}

function on_before_list(){

}

//actual login
authenticate($uid);
function listrecords($error='', $blurbtype='notify'){
	general_listrecords($error, $blurbtype);
};

jetstream_footer();

// DON'T COPY THIS LINE
=======================


<font color="#588ab0">FRONT-END CONTROLLER
=======================
// Front-end (website) php file
// copy the code below to /<?echo $_REQUEST["titlep"];?>.php</font>

// START COPY BELOW THIS LINE
&lt;?
<?
if($_REQUEST["workflow"]=="true"){
?>
// CONFIGURATION
// Container ID
// After you have created the administration container in Jetbox check the container ID in the overview list

$container_id="Change this value";

if(!is_numeric($container_id)){
	echo "Set the \$container_id to the appropriate value";
	exit;
}
<?
}
?>
addbstack('', '<?echo $_REQUEST["titlep"];?>', $view);
addbstack('', 'Home');
$t->set_file("block", "main_tpl.html");
$t->set_var("breadcrum", $breadcrumstack);
$t->set_var("itemtitle", "<?echo $_REQUEST["titlep"];?>");
$t->set_var("pagetitle", $sitename." - <?echo $_REQUEST["titlep"];?>");

//output news for selected item

$date=date("Y-m-d");
<?
if($_REQUEST["workflow"]=="true"){
?>
if($option=='last10'){
	$sqlselect1 = "SELECT *, struct.id AS struct_id FROM <?echo $_REQUEST["table"];?>, struct WHERE struct.container_id=".$container_id." ".$wfqadd." AND struct.content_id=<?echo $_REQUEST["table"];?>.<? echo $_REQUEST["pkey"];?> ORDER BY <?echo $_REQUEST["table"];?>.<? echo $_REQUEST["pkey"];?> DESC LIMIT 10";
}
else{
	$sqlselect1 = "SELECT *, struct.id AS struct_id FROM <?echo $_REQUEST["table"];?>, struct WHERE struct.container_id=".$container_id." ".$wfqadd." AND struct.content_id=<?echo $_REQUEST["table"];?>.<? echo $_REQUEST["pkey"];?> ORDER BY <?echo $_REQUEST["table"];?>.<? echo $_REQUEST["pkey"];?> DESC";
}
<?
}
else{
?>
if($option=='last10'){
	$sqlselect1 = "SELECT * FROM <?echo $_REQUEST["table"];?> ORDER BY <? echo $_REQUEST["pkey"];?> DESC LIMIT 10";
}
else{
	$sqlselect1 = "SELECT * FROM <?echo $_REQUEST["table"];?>";
}
<?
}
?>

$result1 = mysql_prefix_query ($sqlselect1) or die (mysql_error());
$<?echo strtolower($_REQUEST["titlep"]);?>count= mysql_num_rows($result1);
if ($<?echo strtolower($_REQUEST["titlep"]);?>count>'0') {
	$view_tpl = new Template("./");
	$view_tpl->set_file("block", "<?echo $_REQUEST["titlep"];?>_item_tpl.html");	    
	$view_tpl->set_block("block", "<?echo strtolower($_REQUEST["titlep"]);?>","<?echo strtolower($_REQUEST["titlep"]);?>z");
	$view_tpl->set_var(array("absolutepathfull"=>$absolutepathfull ));

	while ($resultarray = mysql_fetch_array($result1)){
		if($resultarray["keywords"]<>''){
			$t->set_var("pagekeywords", $resultarray["keywords"]);
		}
		if($resultarray["description"]<>''){
			$t->set_var("pagedescription", $resultarray["description"]);
		}
		if($resultarray["robot"]<>''){
			$t->set_var("robots", $resultarray["robot"]);
		}

<?
		$local_query = 'SHOW FIELDS FROM ' . $_REQUEST["table"];
		$fields_rs   = mysql_prefix_query($local_query) or die (mysql_error());
		$fields_cnt  = mysql_num_rows($fields_rs);
		$i         = 0;
		$aryFields = array();
		$tpl="";
		$linked="";
		while ($row = mysql_fetch_array($fields_rs)) {
			$field_name = htmlspecialchars($row['Field']);

			if($_REQUEST["droptable_".$field_name]<>''){
				//echo $field_name.":".$_REQUEST["droptable_".$field_name].": ".$_REQUEST["dropdlutpk_" . $field_name].": ".$_REQUEST["dropdlutsf_" . $field_name]."<br>";

			$linked.="		\$result".$field_name." = mysql_prefix_query (\"SELECT * FROM ".$_REQUEST["droptable_".$field_name]." WHERE ".$_REQUEST["dropdlutpk_" . $field_name]."='\".\$resultarray[\"".$field_name."\"].\"'\") or die (mysql_error());
		if(\$result".$field_name."array=mysql_fetch_array(\$result".$field_name.")){
			\$view_tpl->set_var(\"".$field_name."\", \$result".$field_name."array[\"".$_REQUEST["dropdlutsf_" . $field_name]."\"]);
		}<br>";
						}
			$tpl.="<div style=\"padding:.2em .2em 1.2em .2em\">".$_REQUEST["nicefieldname_".$field_name].":<br />\n\{$field_name}<br />\n</div>\n";
?>
		$view_tpl->set_var("<?echo $field_name?>", $resultarray["<?echo $field_name?>"]);
<?
		}
echo "\n\n".$linked;

			?>

		// ADD THIS BLOCK OPTION TO ONLY SHOW THE BLOCK WHEN THE FIELD IS FILLED
		<?$tpl.="<!-- BEGIN ".strtoupper($field_name)." -->\n".$_REQUEST["nicefieldname_".$field_name].":<br />\n\{$field_name}<br />\n<!-- END ".strtoupper($field_name)." -->"?>// $view_tpl->set_block("<?echo strtolower($_REQUEST["titlep"]);?>", "<?echo strtoupper($field_name)?>","<?echo strtoupper($field_name)?>Z");
		//if ($resultarray["<?echo $field_name?>"]<>'') {
		//	$view_tpl->parse("<?echo strtoupper($field_name)?>Z", "<?echo strtoupper($field_name)?>");
		//}
		//else{
		//	$view_tpl->set_var("<?echo strtoupper($field_name)?>Z", "");
		//}

		$view_tpl->parse("<?echo strtolower($_REQUEST["titlep"]);?>z", "<?echo strtolower($_REQUEST["titlep"]);?>", true);
	}
	$view_tpl->parse("b", "block");
	$t->set_var("containera", $view_tpl->get("b"));
}
else {
	$t->set_var("containera", "No <?echo $_REQUEST["titlep"];?> found.");
}


$navt = new Template("./");
$navt->set_file("block", "optionnav.html");	    
$navt->set_block("block", "sub","subz");
$navt->set_block("block", "subsel","subselz");
$navarray=array("all"=>array("url"=>$absolutepathfull."view/".$view."/","lname"=>"All"),
								"past"=>array("url"=>$absolutepathfull."view/".$view."/option/last10","lname"=>"Last 10"),
								);
if ($option) {
		while (list($key, $val)= each($navarray)) {
		$navt->set_var(array ("lname"=> $val["lname"],"url"=>$val["url"]));
		if ($key==$option) {
			$navt->parse("subz", "subsel", true);			    
		}
		else{
			$navt->parse("subz", "sub", true);
		}
	}

}
else{
	while (list($key, $val)= each($navarray)) {
		$navt->set_var(array ("lname"=> $val["lname"],"url"=>$val["url"]));
		if ($key=="all" && $item=='') {
			$navt->parse("subz", "subsel", true);			    
		}
		else{
			$navt->parse("subz", "sub", true);
		}
	}
}
$navt->set_var(array ("options"=> "Selection"));
$navt->parse("b", "block");
$t->set_var("leftnav", $navt->get("b"));

?>

// DON'T COPY THIS LINE
=======================

<font color="#588ab0">FRONT-END TEMPLATE
=======================
// Front-end (website) template file
// copy the code below to <?echo $_REQUEST["titlep"];?>_item_tpl.html</font>

// START COPY BELOW THIS LINE

&lt;div style="padding: 0px 4px 0px 0px">&lt;h2><?echo $_REQUEST["titlep"];?>&lt;/h2>&lt;/div>
      
&lt;!-- BEGIN <?echo strtolower($_REQUEST["titlep"]);?> -->
&lt;div style="border: #ccc solid; border-width: 0 0 1px 0;margin:0px 20px 20px 0px;padding:10px 10px 10px 0px;">
<?echo "".htmlspecialchars($tpl)."\n"?>
&lt;/div>
&lt;!-- END <?echo strtolower($_REQUEST["titlep"]);?> -->

// DON'T COPY THIS LINE
=======================
<?
echo "</pre></td></tr>";
echo "</TABLE>\n</FORM>";
	}
	else{
		$error ="";
		// 2. Get fields
		$local_query = 'SHOW FIELDS FROM ' . $table;
		$fields_rs   = mysql_prefix_query($local_query) or die (mysql_error());
		$fields_cnt  = mysql_num_rows($fields_rs);
		$i         = 0;
		$aryFields = array();
		while ($row = mysql_fetch_array($fields_rs)) {
			$i++;
			$aryFields[]      = $row['Field'];
			$type             = $row['Type'];
			$field_name = htmlspecialchars($row['Field']);
			//form fields
			$ffields.="\t";
			$ffields.= 'array("'.$field_name.'","'.$_REQUEST["dropdinput_".$field_name].'","'.$_REQUEST["nicefieldname_".$field_name].'",';
			$_REQUEST["required_".$field_name]? $ffields.= '"required",': $ffields.= '"",';
			$_REQUEST["process_".$field_name]? $ffields.= '"true",': $ffields.= '"",';
			$ffields.= '"'.$_REQUEST["defaultvalue_".$field_name].'"';
			$ffields.="),\n";
			//list fields
			if ($_REQUEST["list_".$field_name]) {
				$lfields.="\t";
				$lfields.= 'array("'.$field_name.'","'.$_REQUEST["listinput_".$field_name].'","'.$_REQUEST["nicefieldname_".$field_name].'","","",""';
				$lfields.="),\n";
			}
			if ($_REQUEST["droptable_".$field_name]) {
				$dropdowns.="<tr>";
				$dropdowns.="<td nowrap=\"nowrap\">&nbsp;". $field_name."&nbsp;</td>";
				$dropdowns.="<td>".$_REQUEST["droptable_".$field_name]."<input type=\"hidden\" name=\"droptable_".$field_name."\" value=\"".$_REQUEST["droptable_".$field_name]."\"></td>";
				$dropdowns.="<td>";
				$local_query = 'SHOW KEYS FROM ' . $_REQUEST["droptable_".$field_name];
				$result      = mysql_prefix_query($local_query) or die (mysql_error());
				$primary     = '';
				$ret_keys    = array();
				$pk_array    = array(); // will be use to emphasis prim. keys in the table view
				while ($row = mysql_fetch_array($result)) {
					$ret_keys[]  = $row;
						if ($row['Key_name'] == 'PRIMARY') {
							$dropdowns.=$row['Column_name'];
							$dropdowns.="<input class=\"form_input\" type=\"HIDDEN\" name=\"dropdlutpk_" . $field_name. "\" value=\"".$row['Column_name']."\">";
					}
				}
				$dropdowns.="</td><td>";
				$dropdowns.=  "\n<select name=\"dropdlutsf_".$field_name."\">";
				$local_query2 = 'SHOW FIELDS FROM ' . $_REQUEST["droptable_".$field_name];
				$fields_rs2   = mysql_prefix_query($local_query2) or die (mysql_error());
				$fields_cnt2  = mysql_num_rows($fields_rs2);
				while ($row2 = mysql_fetch_array($fields_rs2)) {
					$dropdowns.=  "\n<option class=\"form_select\" value=\"". $row2['Field'] . "\">" . $row2['Field'];
				}
				$dropdowns.=  "\n</select></td>";
				$dropdowns.="<td colspan=2><input class=\"form_input\" type=\"TEXT\" name=\"dropdlutargu_" . $field_name. "\"  size=\"30\" MAXLENGTH=\"255\"></td></tr>";
				$dropdowns.='<tr><td colspan=10 style="PADDING-RIGHT: 0px; PADDING-LEFT: 0px; PADDING-BOTTOM: 0px; PADDING-TOP: 0px" valign="top" background="images/bg_dotpattern.gif" height=1></td></tr>';
			}

			if (isset($pk_array[$row['Field']])) {
				$dfields.="<tr><td nowrap=\"nowrap\">&nbsp;Primarykey&nbsp;</td><td><input class=\"form_input\" type=\"TEXT\" name=\"pkey\" value=\"".$row['Field']."\" size=\"20\" MAXLENGTH=\"255\"></td>";
				$dfields.=$ruler;
			}
		} // end while
		if ($_REQUEST["grouptablename"]) {
			$groupdroprows.="<tr>";
			$groupdroprows.="<td nowrap=\"nowrap\">&nbsp;". $field_name."&nbsp;</td>";
			$groupdroprows.="<td>".$_REQUEST["droptable_".$field_name]."<input type=\"hidden\" name=\"droptable_".$field_name."\" value=\"".$_REQUEST["droptable_".$field_name]."\"></td>";
			$groupdroprows.="<td>";
			$local_query = 'SHOW KEYS FROM ' . $_REQUEST["grouptablename"];
			$result      = mysql_prefix_query($local_query) or die (mysql_error());
			$primary     = '';
			$ret_keys    = array();
			$pk_array    = array(); // will be use to emphasis prim. keys in the table view
			while ($row = mysql_fetch_array($result)) {
				$ret_keys[]  = $row;
					if ($row['Key_name'] == 'PRIMARY') {
						$groupdroprows.=$row['Column_name'];
						$groupdroprows.="<input class=\"form_input\" type=\"HIDDEN\" name=\"dropdlutpk_" . $field_name. "\" value=\"".$row['Column_name']."\">";
				}
			}
			$groupdroprows.="</td><td>";
			$groupdroprows.=  "\n<select name=\"dropdlutsf_".$field_name."\">";
			$local_query2 = 'SHOW FIELDS FROM ' . $_REQUEST["grouptablename"];
			$fields_rs2   = mysql_prefix_query($local_query2) or die (mysql_error());
			$fields_cnt2  = mysql_num_rows($fields_rs2);
			while ($row2 = mysql_fetch_array($fields_rs2)) {
				$groupdroprows.=  "\n<option class=\"form_select\" value=\"". $row2['Field'] . "\">" . $row2['Field'];
			}
			$groupdroprows.=  "\n</select></td>";
			$groupdroprows.="<td colspan=2><input class=\"form_input\" type=\"TEXT\" name=\"dropdlutargu_" . $field_name. "\"  size=\"30\" MAXLENGTH=\"255\"></td></tr>";
			$groupdroprows.='<tr><td colspan=10 style="PADDING-RIGHT: 0px; PADDING-LEFT: 0px; PADDING-BOTTOM: 0px; PADDING-TOP: 0px" valign="top" background="images/bg_dotpattern.gif" height=1></td></tr>';
		}
$codes="&lt;?
//primarykey of the table
\$primarykey=\"".$_REQUEST["pkey"]."\";
//table name
// NOTICE: if applicable, in order to work correctly remove the table prefix!
\$tablename=\"".$_REQUEST["table"]."\";
//title of the administration page
\$pagetitle=\"".$_REQUEST["titlep"]."\";
//name of this file, starting with a slash
\$thisfile =\"/\".array_pop(explode(\"/\", \$_SERVER[\"SCRIPT_NAME\"]));
require(\"../../includes/includes.inc.php\");
//get container id
\$result = mysql_prefix_query(\"SELECT id, uid FROM container WHERE cfile='\".\$thisfile.\"'\");
\$container_id= @mysql_result(\$result,0,'id');
\$mailuid= @mysql_result(\$result,0,'uid');
//workflow support [true|false]
\$wf=".$_REQUEST["workflow"].";
//group list by some row or other table row [true|false]
\$listgroup=false;
//ability to show list of items [true|false]
\$overviewitemoption=true;
//ability to add an item [true|false]
\$addnewitemoption=".$_REQUEST["addnewitemoption"].";
//ability to delete an item [true|false]
\$deleteitemoption=".$_REQUEST["deleteitemoption"].";

//Default column for sorting the overview results
//This column does not have to be a part of the displayed column of the overview
\$default_sort_colomn=\"".$_REQUEST["sort_column"]."\";
//sort order
//ASC: Ascending
//DESC: Descending
\$defaul_sort_order=\"".$_REQUEST["sort_order"]."\";

// format: fieldname, fieldtype, display text, required field [reguired|\"\"], noprocess [true|false,''], default value
\$records=array(\n".$ffields. ");

// format: fieldname,fieldtype,display text
\$listformat=array(\n".$lfields. ");

// format: fieldname,fieldtype,display text
\$grouplistformat=array(
	array(\"\",\"\",\"\"),
);

// format: table,where clause for group query, where clause for records
\$groupsql=array(\"\",\"\",\"\");
";
echo "\n<TABLE border=0 width=100% cellpadding=\"4\" cellspacing=\"0\">";
if (strlen($dropdowns)>0) {
	$span=10;
	$ruler = '<tr><td colspan="'.$span.'" class="ruler"></td></tr>';
	$ruler2 = '<tr><td colspan="'.$span.'" class="ruler2"></td></tr>';

	echo $ruler2;
	echo "\n<tr bgcolor=\"#ffffff\">\n<td colspan=10  background='images/stab-bg.gif'><font color=\"\"><b>Dropdown menu configuration</b></font></td></tr>";
	echo $ruler;
	echo "\n<tr bgcolor=\"#F6F6F6\">";
	echo "\n<td class='tab-g'>dropdownfield</td>";
	echo "\n<td class='tab-g'>Tablename</td>";
	echo "\n<td class='tab-g'>Primarykey</td>";
	echo "\n<td class='tab-g'>Display field</td>";
	echo "\n<td class='tab-g' colspan=2>Argument</td>";
	echo "\n</tr>";
	echo $ruler;
	echo $dropdowns;
	echo "\n<tr><td><br></td></tr>";
}
elseif (strlen($dropdowns)==0) {
 	echo '<tr><td colspan=10 style="PADDING-RIGHT: 0px; PADDING-LEFT: 0px; PADDING-BOTTOM: 0px; PADDING-TOP: 0px" valign="top" bgcolor="#8C8A8C"></td></tr>';
	echo "\n<tr bgcolor=\"#ffffff\">\n<td colspan=10  background='images/stab-bg.gif'><font color=\"\"><b>Please proceed to the final step.</b></font></td></tr>";
	echo '<tr><td colspan=10 style="PADDING-RIGHT: 0px; PADDING-LEFT: 0px; PADDING-BOTTOM: 0px; PADDING-TOP: 0px" valign="top" background="images/bg_dotpattern.gif"></td></tr>';
	echo "\n<tr><td><br></td></tr>";
	echo "\n<tr><td>No dropdown menu's where selected. Please proceed</td></tr>";
	echo "\n<tr><td><br></td></tr>";
}
echo "\n<tr>\n<td colspan=2><input class=\"form_button\" type=\"submit\" value=\"Step Three of Three\" name=\"statusactionsave\">";
echo "\n</td></tr>";
echo "\n<tr><td><br></td></tr>";
echo "\n<input type=\"Hidden\" name=\"table\" value=\"".$_REQUEST["table"]."\">";
echo "\n<input type=\"Hidden\" name=\"codesstep2\" value=\"".urlencode($codes)."\">";
echo "\n<input type=\"Hidden\" name=\"nosi\" value=\"".urlencode($_REQUEST["nosi"])."\">";
echo "\n<input type=\"Hidden\" name=\"workflow\" value=\"".$_REQUEST["workflow"]."\">";
echo "\n<input type=\"Hidden\" name=\"systemtitlefield\" value=\"".urlencode($_REQUEST["systemtitlefield"])."\">";
echo "\n<input type=\"Hidden\" name=\"sort_column\" value=\"".urlencode($_REQUEST["sort_column"])."\">";
echo "\n<input type=\"Hidden\" name=\"sort_order\" value=\"".urlencode($_REQUEST["sort_order"])."\">";

while (list($key, $value) = each($_REQUEST)) {
	echo "\n<input type=\"Hidden\" name=\"".$key."\" value=\"".urlencode($value)."\">";
}

echo "\n<tr><td>";
echo "\n<input type=\"Hidden\" name=\"task\" value=\"step4\">";
echo "\n</td></tr>";
echo "</TABLE>\n</FORM>";
}//end if task is else
}

jetstream_footer();