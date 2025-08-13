<?
//////////////////////////////////////////////////////////////////////////////
// Program Name         : Image Vote - Photo Rating System                  //
// Release Version      : 2.0.0                                             //
// Program Author       : Ronald James                                      //
// Supplied by          : Scoons [WTN]                                      //
// Nullified by         : CyKuH [WTN]                                       //
//////////////////////////////////////////////////////////////////////////////
// COPYRIGHT NOTICE                                                         //
// (c) 2002 Ronald James    All Rights Reserved.                            //
// Distributed under the licencing agreement located in wtn_release.nfo     //
//////////////////////////////////////////////////////////////////////////////

require_once ("config.php");
langtop();

// connect to the database until the end

mysql_connect($host,$user,$pass);
@mysql_select_db($database) or die( "Unable to select database");

$query = "select $usertable.name, $usertable.category from $usertable, $imagetable "; $y=0;

if ($searchsex != "") { $query .= "WHERE "; $query .= "$usertable.category = '$searchsex' "; $y++; }

for ($i=1; $i < 21; $i++)
 { $marker = "searchinfo".$i; $markdat = $$marker;
if ($markdat != "") { if ($y>0) $query .= "AND "; else $query .= "where "; $query .= "$usertable.info1 like '%$searchinfo1%' "; $y++; }
 }

if ($searchage1 != "") { if ($y>0) $query .= "AND "; else $query .= "where "; $query .= "$usertable.age >= '$searchage1' "; $y++; }
if ($searchage2 != "") { if ($y>0) $query .= "AND "; else $query .= "where "; $query .= "$usertable.age <= '$searchage2' "; $y++; }
if ($searchname != "") { if ($y>0) $query .= "AND "; else $query .= "where "; $query .= "$usertable.name like '%$searchname%' "; $y++; }
if ($picturesonly == "yes") {if ($y>0) $query .= "AND "; else $query .= "where "; $query .= "$usertable.name = $imagetable.name and $imagetable.status = 'active'";  $y++;}

if ($searchentire != "") {
   if ($y>0) $query .= "AND "; else $query .= "where ";
   $fields = mysql_list_fields($database, $usertable);
   $columns = mysql_num_fields($fields);
   for ($i = 3; $i < $columns; $i++)
    {
      $fname = mysql_field_name($fields, $i);
if ($fname != "email" || $fname != "password" || $fname != "category" || $fname != "self") $where .= "$usertable.$fname LIKE \"%$searchentire%\" OR ";
       }
       $where = substr ($where, 0, -3);        // remove the last OR
       $query .= $where;
       $y++;
}
if ($picturesonly == "yes") $query .= " GROUP BY $usertable.name";  else $query .= " GROUP BY $usertable.name";
if ($searchsort == "name") $query .= " ORDER BY $usertable.name"; 
elseif ($searchsort == "average") $query .= " ORDER BY $imagetable.average DESC"; 
else $query .= " ORDER BY $usertable.joindate DESC";

if (!isset($resultswanted)) $resultswanted = 25;
$query .= " LIMIT $resultswanted";
$result=mysql_query($query) or die(mysql_error());
$rows2=mysql_num_rows($result);
?>
<? if (!isset($go)) {?>
<html>

<head>
<script LANGUAGE="JavaScript">
function fullScreen(theURL) {
window.open(theURL, '', 'fullscreen=no, scrollbars=no, width=350,height=400');
}
</script>
	<script LANGUAGE="JavaScript">
function scrollScreen(theURL) {
window.open(theURL, '', 'fullscreen=no, scrollbars=yes, width=420,height=400');
}
</script>
<title><?=$sitename?></title>
<STYLE type=text/css>

A:visited 	{TEXT-DECORATION: underline}
A:hover 	{BACKGROUND-COLOR: #C7D8EA; COLOR: #110474}
A:link		{TEXT-DECORATION: underline}
A:active 	{TEXT-DECORATION: none}
BODY 		{CURSOR: default; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 12px}
UL		{CURSOR: default; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 12px}
LI 		{CURSOR: default; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 12px}
P		{CURSOR: default; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 12px}
TD 		{FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 12px}
TR 		{FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 12px}
TEXTAREA	{BACKGROUND-COLOR: #C7D8EA; COLOR: #110474; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 10px}
FORM 		{FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 10px}
</STYLE>
</head>

<body bgcolor="#ffffff" text="#000000" link="#006699" alink="#000000" vlink="#000000" marginheight="0" marginwidth="0" topmargin=0 leftmargin=0 rightmargin=0>
<table width="100%" border="0" cellspacing="0" cellpadding="3">
  <tr bgcolor="#375288">
    <td colspan="3">&nbsp;</td>
  </tr>
</table>
<center>
  <table border="0" width="760" cellpadding="5" cellspacing="0">
    <tr bgcolor="#ffffff">
      <td valign="bottom" align="left" width="203">
        <div align="left"><a href="<?=$siteurl?>"><img src="picturevoting.gif" height="60" width="161" border="0" alt="Vote your opinion about pictures at <?=$sitename?>"></a> 
        </div>
      </td>
      <td align="right" nowrap valign="bottom" width="537">
        <div align="center"> </div>
      </td>
    </tr>
  </table>
</center>
<table border=0 cellpadding=2 cellspacing=2 width="760" align="center" bgcolor="#375288">
  <tr bgcolor="#FFFFFF"> 
    <td colspan="2">
<? } ?>

     
<p align="center"><b>Search Results</b><br>
      </p>
      
<table width="604" border="0" cellspacing="2" cellpadding="3" align="center">
  <tr bgcolor="#000033"> 
    <td width="100"> 
      <div align="left"><font color="#FFFFFF">Name</font></div>
    </td>
    <td width="82"> 
      <div align="left"><font color="#FFFFFF">Category</font></div>
    </td>
    <td width="174"> 
      <div align="left"><font color="#FFFFFF">Description</font></div>
    </td>
    <td width="99"> 
      <div align="left"><font color="#FFFFFF">Profile</font></div>
    </td>
    <td width="107"> 
      <div align="left"><font color="#FFFFFF">Vote / View</font></div>
    </td>
  </tr>
  <? $bgcolor = "#E6E6E6"; while ($resultz = mysql_fetch_array($result)) { ?> 
  <tr bgcolor="<?=$bgcolor?>"> 
    <td width="100" valign="top"> <? 
$result1 = mysql_query("select id from imagetable where name = '$resultz[name]' limit 1");
$rows1 = mysql_num_rows($result1);
if ($rows1 > 0) { print "<a href=\"index.php?who=$resultz[name]\">"; }
print $resultz[name];
if ($rows1 > 0) print "</a>";
?> </td>
    <td width="82" valign="top"> 
      <div align="left"><?=$resultz[category]?></div>
    </td>
    <td width="174" valign="top"> 
      <div align="left"><? if (strlen($resultz[about]) > 1) print $resultz[about]; else print "-";?></div>
    </td>
    <td width="99" valign="top"><a href="javascript:void(0);" onClick="scrollScreen('<?=$profilephp?>?u=<?=$resultz[name]?>')">View 
      Profile</a> </td>
    <td width="107" valign="top"> <?
$result1 = mysql_query("select id from $imagetable where name = '$resultz[name]' limit 1");
$rows1 = mysql_num_rows($result1);
if ($rows1 > 0) { print "<a href=\"index.php?who=$resultz[name]\">";
print "View User"; print "</a>"; }  else print "<font color=\"#FFFFFF\" size =\"1\">(no pic)</font>";
?> </td>
  </tr>
  <?if ($bgcolor=="#E6E6E6") $bgcolor = "#F3F3F3"; else $bgcolor="#E6E6E6";}?> 
</table>
      <div align="center"><br>
      <? if ($rows2 == 0) print "<span >No users found for that search. <a href=\"search.php\"> Try Again</a></span>"; ?>
      </div>
      <p align="center"><span class="blueboldtext"><a href="search.php">Search 
        Again</a> - <a href="<?=$votephp?>">Return To Vote</a></span></p>
<? if (!$go) {?>
	</td>
     </tr>
</table>
<center>
  <table border="0" width="760" cellpadding="5" cellspacing="0">
    <tr>
      <td valign="center" align="center"> <br>
        &copy; 2001 <?=$sitename?> <br>
        <br>
      </td>
    </tr>
  </table>
  </center>
</body>
</html>
<? } //  Image Vote(c) 2001 ProPHP.Com   ?>
<?
// done with database?  better close it
mysql_close ();
?>
