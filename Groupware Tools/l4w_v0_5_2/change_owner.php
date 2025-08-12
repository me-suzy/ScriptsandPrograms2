<?php

    /*=====================================================================
	// $Id: change_owner.php,v 1.3 2005/04/03 06:30:09 carsten Exp $
    // copyright evandor media Gmbh 2004
	//=====================================================================*/

    include ("inc/pre_include_standard.inc.php");

    // --- GET / POST -----------------------------------------------
    $contact_id = $_REQUEST ["contact_id"];
    $submit     = $_REQUEST ["submit"];
    $new_owner  = $_REQUEST ["new_owner"];

	// --- Header ---------------------------------------------------
	include ("header.inc");

    // save and close
    //===============================================================

    if ($submit) {
       $query = "UPDATE ".TABLE_PREFIX."contacts SET owner='$new_owner' WHERE contact_id='$contact_id'";
       mysql_query ($query);
       logDBError (__FILE__, __LINE__, mysql_error());
       ?>
       <script language="javascript">
               opener.location.reload();
               window.close();
       </script>
       <?php
       die($query."</body></html>");
    }

    // not submitted yet
    $res = mysql_query ("SELECT owner, grp, group_read, group_write FROM ".TABLE_PREFIX."contacts WHERE contact_id='$contact_id'");
    logDBError (__FILE__, __LINE__, mysql_error());
    $row = mysql_fetch_array ($res);

    $grp_res = mysql_query ("SELECT name, alias FROM groups WHERE id='".$row['grp']."'");
    logDBError (__FILE__, __LINE__, mysql_error());
    $grp_row = mysql_fetch_array ($grp_res);
    $alias   = $grp_row['alias'];
    if ($alias == "")
        $alias   = $grp_row['name'];

    echo "<form action='change_owner.php' method=post>\n";
    echo "<input type=hidden name='contact_id' value='$contact_id'>\n";
    echo "<table width='95%'><tr><td>\n";
    if (($row['group_read'] == "false") AND ($row['group_write'] == "false")) {
        echo translate ("contact_is_private");
        die ("</td></tr></table></body></html>");
    }

    echo "<b>".translate ("current group").":</b></td><td>".$alias."</td></tr>";
    echo "<tr><td><b>".translate ("new carer").":</b></td>";
    echo "<td><select name=new_owner>\n";
    $user = get_members_of_groups (array ($row['grp']));
    for ($i=0; $i < count ($user); $i++) {
        $new_res = mysql_query ("SELECT vorname, nachname FROM users WHERE id='".$user[$i]."'");
        logDBError (__FILE__, __LINE__, mysql_error());
        $new_row = mysql_fetch_array ($new_res);
        $name    = $new_row['vorname']." ".$new_row['nachname'];
        echo "<option value='".$user[$i]."'>".$name."</option>\n";
    }
    echo "</select></td></tr>";
    echo "<tr><td colspan=2><input class=buttonstyle type=submit value='".get_from_texte ("Abschicken", $language)."' name=submit></td></tr>\n";
    echo "</table></form>";

?>
</body>
</html>