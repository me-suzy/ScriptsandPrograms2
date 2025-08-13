<?php
/* $Id: tbl_properties.php,v 1.30 2000/07/14 08:44:43 tobias Exp $ */

if(!isset($message))
{
    include("header.inc.php");
}
else
    show_message($message);

unset($sql_query);
if(MYSQL_MAJOR_VERSION == "3.23")
    {
    if(isset($submitcomment))
        $result = mysql_db_query($db, "ALTER TABLE $table comment='$comment'") or mysql_die();
    $result = mysql_db_query($db, "SHOW TABLE STATUS LIKE '$table'") or mysql_die();
    $row = mysql_fetch_array($result);
    if(!empty($row["Comment"]))
    {
        ?>
        <form method='post' action='tbl_properties.php'>
        <input type="hidden" name="server" value="<?php echo $server;?>">
        <input type="hidden" name="db" value="<?php echo $db;?>">
        <input type="hidden" name="table" value="<?php echo $table;?>">
        <?php
        echo "$strTableComments: <input type='text' name='comment' value='$row[Comment]'><input type='submit' name='submitcomment' value='$strGo'></form>";
    }
}

$result = mysql_db_query($db, "SHOW KEYS FROM $table") or mysql_die();
$primary = "";

while($row = mysql_fetch_array($result))
    if ($row["Key_name"] == "PRIMARY")
        $primary .= "$row[Column_name], ";

$result = mysql_db_query($db, "SHOW FIELDS FROM $table") or mysql_die();

?>
<table border=<?php echo $cfgBorder;?>>
<TR>
   <TH><?php echo $strField; ?></TH>
   <TH><?php echo $strType; ?></TH>
   <TH><?php echo $strAttr; ?></TH>
   <TH><?php echo $strNull; ?></TH>
   <TH><?php echo $strDefault; ?></TH>
   <TH><?php echo $strExtra; ?></TH>
   <?php if (!(isset($printer_friendly) && $printer_friendly)) { ?>
   <TH COLSPAN=5><?php echo $strAction; ?></TH>
   <?php } ?>
</TR>

<?php
$i=0;

$aryFields = array();

while($row= mysql_fetch_array($result))
{
    $aryFields[] = $row["Field"];
    $query = "server=$server&db=$db&table=$table&goto=tbl_properties.php";
    $bgcolor = $cfgBgcolorOne;
    $i % 2  ? 0: $bgcolor = $cfgBgcolorTwo;
    $i++;
    ?>
         <tr bgcolor="<?php echo $bgcolor;?>">
         <td><?php echo $row["Field"];?>&nbsp;</td>
         <td>
    <?php
    $Type = stripslashes($row["Type"]);
    $Type = eregi_replace("BINARY", "", $Type);
    $Type = eregi_replace("ZEROFILL", "", $Type);
    $Type = eregi_replace("UNSIGNED", "", $Type);
    echo $Type;
    ?>&nbsp;</td>
         <td>
    <?php
    $binary   = eregi("BINARY", $row["Type"], $test);
    $unsigned = eregi("UNSIGNED", $row["Type"], $test);
    $zerofill = eregi("ZEROFILL", $row["Type"], $test);
    $strAttribute="";
    if ($binary)
        $strAttribute="BINARY";
    if ($unsigned)
        $strAttribute="UNSIGNED";
    if ($zerofill)
        $strAttribute="UNSIGNED ZEROFILL";
    echo $strAttribute;
    $strAttribute="";
    ?>
     &nbsp;</td>
     <td><?php if ($row["Null"] == "") { echo $strNo;} else {echo $strYes;}?>&nbsp;</td>
         <td><?php if(isset($row["Default"])) echo $row["Default"];?>&nbsp;</td>
         <td><?php echo $row["Extra"];?>&nbsp;</td>

     <?php if (!(isset($printer_friendly) && $printer_friendly)) { ?>
         <td><a href="tbl_alter.php?<?php echo $query;?>&field=<?php echo $row["Field"];?>"><?php echo $strChange; ?></a></td>
         <td><a href="sql.php?<?php echo $query;?>&sql_query=<?php echo urlencode("ALTER TABLE ".$table." DROP ".$row["Field"]);?>&zero_rows=<?php echo urlencode($row["Field"]." ".$strHasBeenDropped);?>"><?php echo $strDrop; ?></a></td>
         <td><a href="sql.php?<?php echo $query;?>&sql_query=<?php echo urlencode("ALTER TABLE ".$table." DROP PRIMARY KEY, ADD PRIMARY KEY($primary".$row["Field"].")");?>&zero_rows=<?php echo urlencode($strAPrimaryKey.$row["Field"]);?>"><?php echo $strPrimary; ?></a></td>
         <td><a href="sql.php?<?php echo $query;?>&sql_query=<?php echo urlencode("ALTER TABLE ".$table." ADD INDEX(".$row["Field"].")");?>&zero_rows=<?php echo urlencode($strAnIndex.$row["Field"]);?>"><?php echo $strIndex; ?></a></td>
         <td><a href="sql.php?<?php echo $query;?>&sql_query=<?php echo urlencode("ALTER TABLE ".$table." ADD UNIQUE(".$row["Field"].")");?>&zero_rows=<?php echo urlencode($strAnIndex.$row["Field"]);?>"><?php echo $strUnique; ?></a></td>
     <?php } ?>
         </tr>
    <?php
}
?>
</table>
<?php

$result = mysql_db_query($db, "SHOW KEYS FROM ".$table) or mysql_die();
if(mysql_num_rows($result)>0)
{
    ?>
    <br>
    <table border=<?php echo $cfgBorder;?>>
      <tr>
      <th><?php echo $strKeyname; ?></th>
      <th><?php echo $strUnique; ?></th>
      <th><?php echo $strField; ?></th>
      <th><?php echo $strAction; ?></th>
      </tr>
    <?php
    for($i=0 ; $i<mysql_num_rows($result); $i++)
    {
        $row = mysql_fetch_array($result);
        echo "<tr>";
        if($row["Key_name"] == "PRIMARY")
        {
            $sql_query = urlencode("ALTER TABLE ".$table." DROP PRIMARY KEY");
            $zero_rows = urlencode($strPrimaryKey." ".$strHasBeenDropped);
        }
        else
        {
            $sql_query = urlencode("ALTER TABLE ".$table." DROP INDEX ".$row["Key_name"]);
            $zero_rows = urlencode($strIndex." ".$row["Key_name"]." ".$strHasBeenDropped);
        }

        ?>
          <td><?php echo $row["Key_name"];?></td>
          <td><?php
        if($row["Non_unique"]=="0")
            echo $strYes;
        else
            echo $strNo;
        ?></td>
        <td><?php echo $row["Column_name"];?></td>
        <td><?php echo "<a href=\"sql.php?$query&sql_query=$sql_query&zero_rows=$zero_rows\">$strDrop</a>";?></td>
        <?php
        echo "</tr>";
    }
    print "</table>\n";
    print show_docu("manual_Performance.html#MySQL_indexes");
}

?>
<div align="left">
<ul>
<li><a href="tbl_printview.php?<?php echo $query;?>"><?php echo $strPrintView; ?></a>
<li><a href="sql.php?sql_query=<?php echo urlencode("SELECT * FROM $table");?>&pos=0&<?php echo $query;?>"><?php echo $strBrowse; ?></a>
<li><a href="tbl_select.php?<?php echo $query;?>"><?php echo $strSelect; ?></a>
<li><a href="tbl_change.php?<?php echo $query;?>"><?php echo $strInsert; ?></a>
<li><form method="post" action="tbl_addfield.php"> <input type="hidden" name="server" value="<?php echo $server;?>">
 <input type="hidden" name="db" value="<?php echo $db;?>">
 <input type="hidden" name="table" value="<?php echo $table;?>">
<?php echo " ".$strAddNewField; ?>:  <input name="num_fields" size=2 maxlength=2 value=1>
<?php
echo " ";
echo " <select name=\"after_field\">\n";
echo '  <option value="--end--">'.$strAtEndOfTable."</option>\n";
echo '  <option value="--first--">'.$strAtBeginningOfTable."</option>\n";
while(list ($junk,$fieldname) = each($aryFields)) {
    echo '  <option value="'.$fieldname.'">'.$strAfter.' '.$fieldname."</option>\n";
}
echo " </select>\n";
?>
<input type="submit" value="<?php echo $strGo;?>">
</form>
<li><a href="ldi_table.php?<?php echo $query;?>"><?php echo $strInsertTextfiles; ?></a>
<li><form method="post" action="tbl_dump.php"><?php echo $strViewDump;?><br>
<table>
    <tr>
        <td>
            <input type="radio" name="what" value="structure" checked><?php echo $strStrucOnly;?>
        </td>
        <td>
            <input type="checkbox" name="drop" value="1"><?php echo $strStrucDrop;?>
        </td>
        <td colspan="3">
            <input type="submit" value="<?php echo $strGo;?>">
        </td>
    </tr>
    <tr>
        <td>
            <input type="radio" name="what" value="data"><?php echo $strStrucData;?>
        </td>
        <td>
            <input type="checkbox" name="asfile" value="sendit"><?php echo $strSend;?>
        </td>
    </tr>
    <tr>
    <td>
    </td>
    <td>
       <input type="checkbox" name="showcolumns" value="yes"><?php echo $strCompleteInserts; ?>
    </td>
    </tr>
    <tr>
        <td>
            <input type="radio" name="what" value="csv"><?php echo $strStrucCSV;?>
        </td>
        <td>
            <?php echo $strTerminatedBy;?> <input type="text" name="separator" size=1 value=";">
        </td>
    </tr>
</table>

 <input type="hidden" name="server" value="<?php echo $server;?>">
 <input type="hidden" name="db" value="<?php echo $db;?>">
 <input type="hidden" name="table" value="<?php echo $table;?>">
</form>

<li><form method="post" action="tbl_rename.php"><?php echo $strRenameTable;?>:<br>
 <input type="hidden" name="server" value="<?php echo $server;?>">
 <input type="hidden" name="db" value="<?php echo $db;?>">
 <input type="hidden" name="table" value="<?php echo $table;?>">
 <input type="hidden" name="reload" value="true">
 <input type="text" name="new_name"><input type="submit" value="<?php echo $strGo;?>">
</form>
<li><form method="post" action="tbl_copy.php"><?php echo $strCopyTable;?><br>
 <input type="hidden" name="server" value="<?php echo $server;?>">
 <input type="hidden" name="db" value="<?php echo $db;?>">
 <input type="hidden" name="table" value="<?php echo $table;?>">
 <input type="hidden" name="reload" value="true">
 <input type="text" name="new_name"><br>
 <input type="radio" name="what" value="structure" checked><?php echo $strStrucOnly;?>
 <input type="radio" name="what" value="data"><?php echo $strStrucData;?>
 <input type="submit" value="<?php echo $strGo;?>">
</form>

</ul>
</div>
<?

require ("footer.inc.php");
?>