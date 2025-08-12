<?php
// leave if wrong IP
if ($all_see_tables == "no" and $client == "not_allowed")
{
echo ('<tr><td><span style="color:red;">Sorry, this IP address is not allowed access to this page. You may want to see the <a href="help/help.htm">help</a> section to know more about this website, to download the software, etc.</span></p></td></tr></table><div>');
include ('footer.php');
exit();
}
?>
<?php
// start -------------- where conditions for mysql query; sterm is term, smenu is menu option, sbool is for and/or
if (isset($_GET['where_condition']) and $_GET['where_condition'] !=''){$where_condition = $_GET['where_condition'];}
else{$where_condition = "";}
// override if _GET
if(!isset($_GET['sbool'])){$sbool="OR";}else{$sbool=$_GET['sbool'];}
if (isset($_GET['sterm_1']) and $_GET['sterm_1'] !='')
{
 if (isset($_GET['sterm_2']) and $_GET['sterm_2'] !=''){$where_condition = " WHERE ".$_GET['smenu_1']." LIKE '%".add_slashes($_GET['sterm_1'])."%' ".$sbool." ".$_GET['smenu_2']." LIKE '%".add_slashes($_GET['sterm_2'])."%'";}
 else {$where_condition = " WHERE ".$_GET['smenu_1']." LIKE '%".add_slashes($_GET['sterm_1'])."%'";}
}
else
{
 if (isset($_GET['sterm_2']) and $_GET['sterm_2'] !=''){$where_condition = " WHERE ".$_GET['smenu_2']." LIKE '%".add_slashes($_GET['sterm_2'])."%'";}
}
// end ------------- get where conditions for query
// start ----------- sort conditions for mysql query
if (isset($_GET['order_condition']) and $_GET['order_condition'] !='')
{$order_condition = $_GET['order_condition'];}
 // default sort condition
else
{
if ($table == 'item'){$order_condition = "`name` ASC";}
if ($table == 'order'){$order_condition = "`ordered_date` DESC";}
if ($table == 'vendor'){$order_condition = "`Name` ASC";}
}
 // but override if _GET
if (!(isset($_POST['sort_1']))){$_POST['sort_1'] = '';}
if (isset($_GET['sort_1']) and $_GET['sort_1'] !='')
{
  if (isset($_GET['order_1'])){$order_condition = $_GET['sort_1']." ".$_GET['order_1'];}
  else{$order_condition = $_GET['sort_1']." ASC";}
  // if the optional second sort option was chosen - conditional on 1st being chosen
  if (isset($_GET['sort_2']) and $_GET['sort_2'] !='' and $_GET['sort_2'] !== $_POST['sort_1'])
   {
    if (isset($_GET['order_2'])){$order_condition .= ", ".$_GET['sort_2']." ".$_GET['order_2'];}
    else{$order_condition .= ", ".$_GET['sort_2']." ASC";}
   }
}

// end - get sort conditions for query
// page numbering setup
if(!isset($_GET['page']))
 {$page = 1;} 
else 
 {$page = $_GET['page'];}
//get checked values
if (isset($_POST['checked'])){foreach ($_POST['checked'] as $key => $value){$POSTchecked[] = $key;}}
//if no checked value, still set postchecked
else {$POSTchecked = array();}
//set session checked if not set
if (!isset($_SESSION['checked'])) {$_SESSION['checked'] = array();}
//add checked values to session checked
$_SESSION['checked'] = array_unique(array_merge($_SESSION['checked'], $POSTchecked));
//get unchecked values
if (isset($_POST['unchecked'])){foreach ($_POST['unchecked'] as $key => $value){$POSTunchecked[] = $key;}}
//if no unchecked value, still set postunchecked
else {$POSTunchecked = array();}
//set session unchecked if not set
if (!isset($_SESSION['unchecked'])) {$_SESSION['unchecked'] = array();}
//subtract postunchecked from session checked
$_SESSION['checked'] = array_diff($_SESSION['checked'], $POSTunchecked);
// reset to 0 if 'empty cart'
if (isset($_POST['empty']) and $_POST['empty'] == 'Clear all'){$_SESSION['checked'] = array();}
// items to show by page 
$from = (($page * $max_results) - $max_results);
// get total entries
$num_tot = mysql_num_rows(mysql_query("SELECT * FROM `".$table."`"));
// get number of entries satisfying WHERE conditions
$num_sat = mysql_num_rows(mysql_query("SELECT * FROM `".$table."`".$where_condition));
if ($num_tot !== $num_sat)
{
$total_pages = ceil($num_sat / $max_results);
}
else
{
$total_pages = ceil($num_tot / $max_results);
}
// build search form //////////////////////////////////////////////////////
/////////// get values to prefill
echo ('<tr><td valign="top">');
if($num_tot > 0){
if (strstr($where_condition, " AND ")) 
{
list($s1, $s2) = explode(" AND ", $where_condition);
$sbool = "AND";
}
elseif (strstr($where_condition, " OR ")) 
{
list($s1, $s2) = explode(" OR ", $where_condition);
$sbool = "OR";
}
else // only first search field filled                                     
{
$s1 = $where_condition;
$s2 = '';
$sterm_2 = ''; 
$smenu_2 = '';
}
// get the option - smenu - and term - sterm - from each sort
if (!isset($smenu_1)){$smenu_1='';}
if (!isset($smenu_2)){$smenu_2='';}
if (!isset($sterm_1)){$sterm_1='';}
if (!isset($sterm_2)){$sterm_2='';}
if ($s1 !='')
{list($smenu_1, $sterm_1) = explode(" LIKE ", $s1);}
if ($s2 !='')
{
list($smenu_2, $sterm_2) = explode(" LIKE ", $s2); 
} 
// remove the % signs which are part of MySQL query
$sterm_1 =  substr($sterm_1, 2, -2);
$sterm_2 =  substr($sterm_2, 2, -2);
// remove the WHERE 
$smenu_1 =  trim($smenu_1, "WHERE ");
/////////// end - get values to prefill

 // add extra item to option-value array
$option_value3 = $option_value;
$option_value3['name'] = "Has in name or...";
echo ('<form action="'.$_SERVER['PHP_SELF'].'" method="get"><p>');
       //--------------------------------------------------
echo ('<select single="single" id="smenu_1" name="smenu_1">');
 foreach ($option_value3 as $value => $option)
 {
 echo ('<option value="'.$value);
 if ($value == $smenu_1)
  {
  echo ('" selected="selected"');
  }
 else
  {
  echo ('"');  
  }
 echo ('>'.$option.'</option>');
 }
echo ('</select>');
echo ('<input name="sterm_1" id="sterm_1" type="text" size="15" maxlength="30" value="'.strip_slashes($sterm_1).'" />');
       //--------------------------------------------------
echo ('<select single="single" name="sbool" id="sbool">');
echo ('<option value="AND"'); 
  if (isset($_GET['sbool']) and $_GET['sbool'] == "AND"){echo (' selected="selected"');}
echo('>And</option>');
echo ('<option value="OR"'); 
  if (isset($_GET['sbool']) and $_GET['sbool'] == "OR"){echo (' selected="selected"');}
echo('>Or</option>');
echo ('</select>');
       //--------------------------------------------------
echo ('<select single="single" name="smenu_2" id="smenu_2">');
 foreach ($option_value3 as $value => $option)
 {
 echo ('<option value="'.$value);
 if ($value == $smenu_2)
  {
  echo ('" selected="selected"');
  }
 else
  {
  echo ('"');  
  }
 echo ('>'.$option.'</option>');
 }
echo ('</select>');
       //--------------------------------------------------
echo ('<input name="sterm_2" id="sterm_2" type="text" size="15" maxlength="30" value="'.strip_slashes($sterm_2).'" />');

echo ('<input type="hidden" name="order_condition" id="order_condition" value="'.$order_condition.'" /><input type="submit" value="Find" name="submit_find" id="submit_find" /><a href="help/help.htm#search" onclick="return popitup(\'help/help.htm#search\')">?</a></p></form>');
}
// end - build search form
///////////////////////////////////////////////////////////////////////////
// build menu form for resorting; the option values are table column headings
 // only if more than one page of data
if ($total_pages>1)
{
 // so selected option is displayed so
   // for the two sort types and orders when both sorts done
if (strstr($order_condition, ", ")) 
{
list($o1, $o2) = explode(", ", $order_condition);
}
   // only first sort was done
else                                           
{
$o1 = $order_condition;
$o2 = '';
$order_2 = ''; 
$sort_2 = '';
}
   // get the type and order from each sort
list($sort_1, $order_1) = explode(" ", $o1); 
if ($o2 !='')
{
list($sort_2, $order_2) = explode(" ", $o2); 
}
 // add extra item to option-value array
$option_value2 = $option_value;
$option_value2[''] = "Sort by"; 
 // form html code
echo ('
<form action="' . $_SERVER['PHP_SELF'] . '" method="get"><p>');
       //--------------------------------------------------
echo ('<select single="single" name="sort_1" id="sort_1">');
 foreach ($option_value2 as $value => $option)
 {
 echo ('<option value="'.$value);
 if ($value == $sort_1)
  {
  echo ('" selected="selected"');
  }
 else
  {
  echo ('"');  
  }
 echo ('>'.$option.'</option>');
 }
echo ('</select>');
       //--------------------------------------------------
echo ('<select single="single" id="order_1" name="order_1">');
echo ('<option value="ASC"'); 
  if ($order_1 == "ASC"){echo (' selected="selected"');}
echo('>Ascending</option>');
echo ('<option value="DESC"'); 
  if ($order_1 == "DESC"){echo (' selected="selected"');}
echo('>Descending</option>');
echo ('</select> then (optional) ');
       //--------------------------------------------------
echo ('<select single="single" id="sort_2" name="sort_2">');
 foreach ($option_value2 as $value => $option)
 {
 echo ('<option value="'.$value);
 if ($value == $sort_2)
  {
  echo ('" selected="selected"');
  }
 else
  {
  echo ('"');  
  }
 echo ('>'.$option.'</option>');
 }
echo ('</select>');
       //--------------------------------------------------
echo ('<select single="single" id="order_2" name="order_2">');
echo ('<option value="ASC"'); 
  if ($order_2 == "ASC"){echo (' selected="selected"');}
echo('>Ascending</option>');
echo ('<option value="DESC"'); 
  if ($order_2 == "DESC"){echo (' selected="selected"');}
echo('>Descending</option>');
echo ('</select>');
       //--------------------------------------------------
echo ('<input type="hidden" name="where_condition" id="where_condition" value="'.$where_condition.'" /><input type="submit" name="submit_sort" id="submit_sort" value="Sort" /><a href="help/help.htm#sort" onclick="return popitup(\'help/help.htm#sort\')">?</a></p></form>');
}
// end - build sort form
///////////////////////////////////////////////////////////////////////////
if ($num_sat !== 0)
{
if ($num_tot !== $num_sat)
 {
 echo ('<p>Showing '.$num_sat.' from a <a href="'.$_SERVER['PHP_SELF'].'">total</a> of '.$num_tot.' entries');
 }
else
 {
 echo ("<p>Showing all ".$num_sat." of ".$num_tot." entries");
 }
  // depending on total number of pages
if ($total_pages > 1)
 {
echo (" on ".$total_pages." pages, sorted as indicated</p>");
 }
else
 {
echo (" on one page </p>");
 }
}
else
{
if ($num_tot !== 0)
 {
 echo ('<p>None of the <a href="'.$_SERVER['PHP_SELF'].'">total</a> '.$num_tot.' entries match your search criteria. Note that the default search is against name/description and an entry will be returned only if your query is a part of its name/description (e.g., "zp" is a part of "ZP 1" but "ZP1" is not). Try changing the search phrase, e.g., "rabbits" to "rabbit" and "2 at 4 deg" to "2 at 4-deg." Searches are not case sensitive, but wild cards are not allowed.</p>');
 }
else
 {
 echo ('<p>The table is empty! <br /></p>');
 }
}
// end upper table
echo ('</td></tr></table>');
// generate the query for mysql only if num_sat is more than zero
if ($num_sat !==0)
{
$query = "SELECT * FROM `".$table."`";

if ($where_condition != '')
{$query .= $where_condition;}
 // expand the query to include ORDER conditions
$query .= " ORDER BY ".$order_condition;

// get page-number dictated entries satisfying WHERE and ORDER conditions 
$query .= " LIMIT ".$from.", ".$max_results;
$result = mysql_query($query);
$numofrows = mysql_num_rows($result);
// table start
echo ('<table summary="list" width="750" style="background-color:#efefef; border:0;" cellpadding="5" cellspacing="1">');
}
?>