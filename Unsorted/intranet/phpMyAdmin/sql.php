<?php
/* $Id: sql.php,v 1.26 2000/08/06 13:23:57 tobias Exp $ */;

require("lib.inc.php");
$no_require = true;


if(isset($goto) && $goto == "sql.php")
{
    $goto = "sql.php?server=$server&db=$db&table=$table&pos=$pos&sql_query=".urlencode($sql_query);
}

// Go back to further page if table should not be dropped
if(isset($btnDrop) && $btnDrop == $strNo)
{
    if(file_exists($goto))
        include($goto);
    else
        Header("Location: $goto");
    exit;
}

// Check if table should be dropped
$is_drop_sql_query = eregi("DROP +(TABLE|DATABASE)|ALTER TABLE +[[:alnum:]_]* +DROP|DELETE FROM", $sql_query); // Get word "drop"

if(!$cfgConfirm)
    $btnDrop = $strYes;

if($is_drop_sql_query && !isset($btnDrop))
{
    include("header.inc.php");
    echo $strDoYouReally.urldecode(stripslashes($sql_query))."?<br>";
    ?>
    <form action="sql.php" method="post" enctype="application/x-www-form-urlencoded">
    <input type="hidden" name="sql_query" value="<?php echo urldecode(stripslashes($sql_query)); ?>">
    <input type="hidden" name="server" value="<?php echo $server ?>">
    <input type="hidden" name="db" value="<?php echo $db ?>">
    <input type="hidden" name="zero_rows" value="<?php echo isset($zero_rows) ? $zero_rows : "";?>">
    <input type="hidden" name="table" value="<?php echo isset($table) ? $table : "";?>">
    <input type="hidden" name="goto" value="<?php echo isset($goto) ? $goto : "";?>">
    <input type="hidden" name="reload" value="<?php echo isset($reload) ? $reload : "";?>">
    <input type="Submit" name="btnDrop" value="<?php echo $strYes; ?>">
    <input type="Submit" name="btnDrop" value="<?php echo $strNo; ?>">
    </form>
    <?php
}
// if table should be dropped or other queries should be perfomed
//elseif (!$is_drop_sql_query || $btnDrop == $strYes)
else
{
    $sql_query = isset($sql_query) ? stripslashes($sql_query) : '';
    $sql_order = isset($sql_order) ? stripslashes($sql_order) : '';
    if(isset($sessionMaxRows) )
        $cfgMaxRows = $sessionMaxRows;
    $sql_limit = (isset($pos) && eregi("^SELECT", $sql_query)) ? " LIMIT $pos, $cfgMaxRows" : '';
    $result = mysql_db_query($db, $sql_query.$sql_order.$sql_limit);
    // the same SELECT without LIMIT
    if(eregi("^SELECT", $sql_query))
    {
        $OPresult = mysql_db_query($db, $sql_query.$sql_order);
        $SelectNumRows = @mysql_num_rows($OPresult);
    }

    if(!$result)
    {
        $error = mysql_error();
        include("header.inc.php");
        mysql_die($error);
    }

    $num_rows = @mysql_num_rows($result);

    if($num_rows < 1)
    {
        if(file_exists($goto))
        {
            include("header.inc.php");
            if(isset($zero_rows) && !empty($zero_rows))
                $message = $zero_rows;
            else
                $message = $strEmptyResultSet;
            include($goto);
        }
        else
        {
            $message = $zero_rows;
            Header("Location: $goto");
        }
        exit;
    }
    else
    {
        include("header.inc.php");
        display_table($result);
        if(!eregi("SHOW VARIABLES|SHOW PROCESSLIST|SHOW STATUS", $sql_query))
            echo "<p><a href=\"tbl_change.php?server=$server&db=$db&table=$table&pos=$pos&goto=$goto&sql_query=".urlencode($sql_query)."\"> $strInsertNewRow</a></p>";
    }
}
require ("footer.inc.php");
?>