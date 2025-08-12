<?PHP



if($_userlevel != 3){

    die("<center><span class=red>You need to be an admin to access this page!</span></center>");

}



echo "<a href=\"index.php\"><img src=\"images/home_lnk.gif\" border=0 alt=\"\"></a><img src=\"images/spacer.gif\" border=0 alt=\"\" width=5><img src=\"images/rules_title.gif\" border=0 alt=\"\"><p>";

echo '<div align=right><a href=index.php?page=rules&act=new><img src=images/addrule_btn.gif border=0></a></div>';



$id=$_GET['id'];

if (!eregi("[0-9]+", $id) && $_GET['act'] != "new") { //Not a valid id and not making a new rule, so show list of rules



echo "<table width=100% class=category_table cellpadding=0 cellspacing=0 align=center>

		<tr>

			<td class=table_1_header>			

				<img src=\"images/arrow_up.gif\"> <b>Rules</b>			

			</td>

		</tr>

		<tr>

			<td>

			<table  width=100% border=0 cellpadding=0 cellspacing=0 align=center>

				<tr>

					<td class=table_subheader width=25></td>

					<td class=table_subheader align=center><b>Rule</b></td>

					<td class=table_subheader width=40 align=center></td>

					<td class=table_subheader width=40 align=left></td>

					<td class=table_subheader width=40 align=center></td>

					<td class=table_subheader width=40 align=left></td>

				</tr><tr>";



    $rules_result=mysql_query("SELECT * FROM `settings` WHERE `name`='rule' ORDER BY `id`");

    $rules_row_number=mysql_num_rows($rules_result);

    $row=1; // 1 or 2 for row formatting

    $current_row=1; // Holds number of current row

    while ($rules_row=mysql_fetch_array($rules_result)) {

?>

        <tr><td align=center class=row<?php echo $row;?> valign=middle><b><?php echo $current_row;?></b></td>

        <td class=row<?php echo $row;?> valign=middle><?php echo ekincode($rules_row['value'], $user["theme"]);?></td>

        <td align=center class=row2 valign=middle><a href=index.php?page=rules&act=edit&id=<?php echo $rules_row['id'];?>><img src=images/rule_edit_btn.gif border=0></a></td>

        <td align=center class=row2 valign=middle><a href=index.php?page=rules&act=del&id=<?php echo $rules_row['id'];?>><img src=images/rule_delete_btn.gif border=0></a></td>

        <td align=center class=row2 valign=middle><?php echo ($current_row == 1) ? '' : '<a href=index.php?page=rules&act=moveup&id='.$rules_row['id'].'><img src="images/cat_up_btn.gif" border=0></a>';?></td>

        <td align=center class=row2 valign=middle><?php echo ($current_row == $rules_row_number) ? '' : '<a href=index.php?page=rules&act=movedown&id='.$rules_row['id'].'><img src="images/cat_down_btn.gif" border=0></a>';?></td></tr>

<?php

        $current_row++;

        $row=($row == 1) ? 2 : 1;

    }

    echo '</table></td></tr></table>';



} else if ($_GET['act'] == "new") { // Create a new rule

    if ($_GET['step'] == "2") { // Create the new rule (add it into database)

        $result=mysql_query("INSERT INTO `settings` (`name`, `value`) VALUES ('rule', '$_POST[rcontent]')");



        header("Location: index.php?page=rules");

    } else { // Show them the form

?>

<table width=100% class=category_table cellpadding=0 cellspacing=0>

    <tr class=table_1_header>

        <td width=100% class=table_1_header><img src="images/arrow_up.gif"> <b>New Rule</b></td>

        <td class=table_1_header><img src="images/EKINboard_header_right.gif"></td>

    </tr><tr>

        <td colspan=2 class=contentmain><center><form action=index.php?page=rules&act=new&step=2 method=post>

            <table border=0 cellpadding=0 cellspacing=0>

                <tr>

                    <td width=35% valign=top><b>Rule Content:</b><br />HTML is not allowed<br />EKINcode is displayed</td>

                    <td><textarea name='rcontent' rows='10' cols='50' class='textbox'></textarea></td>

                </tr>

            </table>

        </td>

    </tr><tr>

        <td align=center class=table_bottom colspan=2><input type=submit name=submit value="Add > >"></form></td>

    </tr>

</table>

<?php

    }

} else { // Valid id, do editing/deleting/moving

    if ($_GET['act'] == "edit") { // Edit the rule

        if ($_GET['step'] == "2") { // Apply changes to database

            $result=mysql_query("UPDATE `settings` SET `value`='$_POST[rcontent]' WHERE `id`='$id'");



            header("Location: index.php?page=rules");

        } else { // Show them the form

        $rule_result=mysql_query("SELECT `value` FROM `settings` WHERE `id`='$id'");

        $rule_row=mysql_fetch_row($rule_result);

?>

<table width=100% class=category_table cellpadding=0 cellspacing=0>

    <tr class=table_1_header>

        <td width=100% class=table_1_header><img src="images/arrow_up.gif"> <b>Edit Rule</b></td>

        <td class=table_1_header><img src="images/EKINboard_header_right.gif"></td>

    </tr><tr>

        <td colspan=2 class=contentmain><center><form action=index.php?page=rules&act=edit&id=<?php echo $id;?>&step=2 method=post>

            <table border=0 cellpadding=0 cellspacing=0>

                <tr>

                    <td width=35% valign=top><b>Rule Content:</b><br />HTML is not allowed<br />EKINcode is displayed</td>

                    <td><textarea name='rcontent' rows='10' cols='50' class='textbox'><?php echo $rule_row[0];?></textarea></td>

                </tr>

            </table>

        </td>

    </tr><tr>

        <td align=center class=table_bottom colspan=2><input type=submit name=submit value="Save > >"></form></td>

    </tr>

</table>

<?php

        }

    } else if ($_GET['act'] == "del") { // Delete the rule

        if ($_GET['sure'] == "yes") { // Apply changes to database

            $result=mysql_query("DELETE FROM `settings` WHERE `id`='$id'");



            header("Location: index.php?page=rules");

        } else { // Show them an "Are You Sure" form

?>

<table class=category_table width=100% cellpadding=0 cellspacing=0>

    <tr class=table_1_header>

        <td class=table_1_header width=100%><img src="images/arrow_up.gif"> <b>Delete</b></td>

        <td class=table_1_header><img src="images/EKINboard_header_right.gif"></td>

    </tr><tr class=table_subheader>

        <td class=table_subheader colspan=2>Are you sure you would like to delete this rule?</td>

    </tr><tr>

        <td class=contentmain align=center>

            <table cellpadding=3 cellspacing=0 border=0>

                <tr>

                    <td class=redtable align=center width=100><a href=index.php?page=rules&act=del&id=<?php echo $id;?>&sure=yes class=link2>Yes</a></td>

                    <td width=20></td>

                    <td class=bluetable align=center width=100><a href=javascript:history.go(-1) class=link2>No</a></td>

                </tr>

            </table>

        </td>

    </tr>

</table>

<?php

        }

    } else if ($_GET['act'] == "moveup") { // Move up selected rule

        $id2_result=mysql_query("SELECT `id` FROM `settings` WHERE `id`<'$id' AND `name`='rule' ORDER BY `id` DESC LIMIT 1"); // Get id of row above it

        $id2_row=mysql_fetch_row($id2_result);

        $id2=$id2_row[0];

        mysql_query("UPDATE `settings` SET `id`='0' WHERE `id`='$id'"); // Set one we want to switch to '0'

        mysql_query("UPDATE `settings` SET `id`='$id' WHERE `id`='$id2'"); // Set other one to this one

        mysql_query("UPDATE `settings` SET `id`='$id2' WHERE `id`='0'"); // Set this one to other one



        header("Location: index.php?page=rules");

    } else if ($_GET['act'] == "movedown") { // Move down selected rule

        $id2_result=mysql_query("SELECT `id`, `value` FROM `settings` WHERE `id`>'$id' AND `name`='rule' ORDER BY `id` LIMIT 1"); // Get id of row below it

        $id2_row=mysql_fetch_row($id2_result);

        $id2=$id2_row[0];

        mysql_query("UPDATE `settings` SET `id`='0' WHERE `id`='$id'"); // Set one we want to switch to '0'

        mysql_query("UPDATE `settings` SET `id`='$id' WHERE `id`='$id2'"); // Set other one to this one

        mysql_query("UPDATE `settings` SET `id`='$id2' WHERE `id`='0'"); // Set this one to other one



        header("Location: index.php?page=rules");

    } else { // Send them to the main rules page

        header("Location: index.php?page=rules");

    }

}

?>