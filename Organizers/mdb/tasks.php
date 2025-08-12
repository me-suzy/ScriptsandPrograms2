<?php

if(isset($Submit)) {
	if($update == "" || $update == " ") {
		include "header.inc.php";
			echo ("<p>&nbsp;</p><p align=center>You entered an empty entry into the database.<br>Please make some enter something in the update box or we will have to reject your entry.</p><p align=center><a href=\"$HTTP_REFERER\">Go Back</a></p>");
		include "footer.inc.php";
		exit;
	}
	include "data.inc.php";
	$CONNECT = mysql_connect($DB_host, $DB_user, $DB_pass) or die(Mysql_error());
	mysql_select_db($DB_name);

	$Mdate = date("Y-m-d");
	$updated = stripslashes(htmlspecialchars($update));

	$Update = mysql_query("INSERT INTO $Table_task_updates SET date=\"$Mdate\", new_update=\"$updated\", sub=\"$What\"") or die(mysql_error());

	mysql_close($CONNECT);
	header("location: tasks.php?Sec=tasks");
}

if(isset($Done)) {
	include "data.inc.php";
	$CONNECT = mysql_connect($DB_host, $DB_user, $DB_pass) or die(Mysql_error());
	mysql_select_db($DB_name);

	$Update = mysql_query("UPDATE $Table_tasks SET completed=\"1\" WHERE T_ID=\"$Done\"") or die(mysql_error());

	mysql_close($CONNECT);
	header("location: $HTTP_REFERER");
}

if (isset($UDelete)) {
	include "data.inc.php";
	$CONNECT = mysql_connect($DB_host, $DB_user, $DB_pass) or die(Mysql_error());
	mysql_select_db($DB_name);

	$Update = mysql_query("DELETE FROM $Table_task_updates WHERE TU_ID=\"$UDelete\"") or die(mysql_error());

	mysql_close($CONNECT);
	header("location: $HTTP_REFERER");
}

if(isset($DeleteMe)) {
	include "data.inc.php";
	$CONNECT = mysql_connect($DB_host, $DB_user, $DB_pass) or die(Mysql_error());
	mysql_select_db($DB_name);

	$Update = mysql_query("DELETE FROM $Table_tasks WHERE T_ID=\"$DeleteMe\"") or die(mysql_error());
	$Kill_updates = mysql_query("DELETE FROM $Table_task_updates WHERE sub=\"$DeleteMe\"") or die(mysql_error());

	mysql_close($CONNECT);
	header("location: $HTTP_REFERER");
}


include "header.inc.php";

$Query = mysql_query("SELECT *,date_format(due_date, '%M, %d, %Y')as date,TO_DAYS(NOW())as Now FROM $Table_tasks ORDER BY priority DESC") or die(mysql_error());

$Count = mysql_query("SELECT * FROM $Table_tasks") or die(mysql_error());
$Counted = mysql_num_rows($Count);

echo ("&nbsp;&nbsp;&nbsp;<a href=\"index.php\">Home</a> > Tasks<br><br>");
?>

<table width="95%" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000" align="center">
  <tr>
    <td><table width="100%" border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
        <tr>
          <td><img src="images/mytasks.gif"><br><table width="98%" border="0" align="center" cellpadding="2" cellspacing="0">
              <tr>
                <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
                    <tr>
                      <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#6699FF">
                          <tr>
                            <td class="Title" style="padding='3'">&nbsp;&nbsp;Complete listing of tasks</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>

<?php			  

	if ($Counted == 0) {
		echo ("<tr><td align=center><strong>There are no tasks in database.</strong></td></tr>");
	}


	while ($Row=mysql_fetch_object($Query)) {
		$GetDate = mysql_query("SELECT TO_DAYS(due_date)as DUE FROM $Table_tasks WHERE T_ID=\"$Row->T_ID\"") or die(mysql_error());
		$Fetch = mysql_fetch_object($GetDate);

		// set prioirty image
		if($Row->priority == 0) {
			$arrow = "arrow_right2";
		}

		if ($Row->priority == 1) {
			$arrow = "arrow_right6";
		}

		if ($Row->priority == 2) {
			$arrow = "arrow_right";
		}

		// check to see if it is out dated
		if($Row->Now > $Fetch->DUE) {
			$out = "<strong>Failed to complete task</strong> ($Row->due_date)";
			$bad = 1;
		}

		else if ($Row->Now < $Fetch->DUE && $Row->completed != 1) {
			$out = "Due date: " . $Row->date;
		}

		// check and see if its completed
		if ($Row->completed == 1) {
			$out = "<strong><font color=red>Completed</font></strong> ($Row->due_date)";
			$Com_image = "";
		}

		if ($Row->completed != 1) {
			$Com_image = "<a href=\"tasks.php?Sec=tasks&Done=$Row->T_ID\"><img src=\"images/completed.gif\" alt=\"Mark Completed\" width=\"16\" height=\"18\" border=\"0\"></a>";
		}


		if(isset($View)) {
			if ($Row->T_ID == $View) {
				$Do = mysql_query("SELECT * FROM $Table_task_updates WHERE sub=\"$Row->T_ID\"") or die(mysql_error());
				$TASK = nl2br($Row->task);
				
				$print = "<table width=\"98%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" align=\"right\">\n<tr>\n<td><table width=\"98%\" border=\"0\" cellpadding=\"1\" cellspacing=\"0\" bgcolor=\"#000000\" align=\"right\"><tr><td><table width=\"100%\" border=\"0\" cellpadding=\"1\" cellspacing=\"0\" bgcolor=\"#FFFFFF\">\n<tr><td align=\"right\" bgcolor=\"#003399\" class=\"BottomBorder\"><font size=\"1\"><a href=\"$PHP_SELF?Sec=tasks\" class=\"link\" style=\"color='#FFFFFF'\">Close</a></font>&nbsp;</td>\n</tr><tr><td style=\"padding='5'\"><font size=1>$TASK</font></td>\n</tr></table></td></tr></table></td>";
				
				while ($D = mysql_fetch_object($Do)) {
					$new_upate = nl2br($D->new_update);

					$print .= "<tr><td><table width=\"98%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" align=\"right\">\n<tr>\n<td><table width=100%><tr><td><font size=1>&nbsp;&nbsp;&nbsp;&nbsp;Added: $D->date</font></td><td><div align=right><a href=\"tasks.php?Sec=tasks&UDelete=$D->TU_ID\" class=\"link\"><font size=1 style=\"color='red'\"><strong>Delete</strong></font></a></td></tr></table><table width=\"98%\" border=\"0\" cellpadding=\"1\" cellspacing=\"0\" bgcolor=\"#000000\" align=\"right\"><tr><td><tr><td><table width=\"100%\" border=\"0\" cellpadding=\"1\" cellspacing=\"0\" bgcolor=\"#FFFFFF\">\n<tr><td style=\"padding='5'\"><font size=1>$new_upate</font></td>\n</tr></table></td></tr></table></td></tr></table></td></tr>";
				}

				$print .= "</tr></table>";
				$Link = $PHP_SELF . "?Sec=tasks";
				
			}

			else if($Row->T_ID != $View) {
				$Link = $PHP_SELF . "?Sec=tasks&View=" . $Row->T_ID;
			}
		}

		if(!isset($View)) {
			$Link = $PHP_SELF . "?Sec=tasks&View=" . $Row->T_ID;
		}

		if(isset($Update)) {
			if ($Row->T_ID == $Update) {
				$print_update = "<form name=\"form1\" method=\"post\" action=\"\"><input type=\"hidden\" name=\"What\" value=\"$Row->T_ID\"><div align=\"center\">\n<font size=1>Add an update to this task</font><br><textarea name=\"update\" cols=\"42\" rows=\"6\" id=\"update\"></textarea><br>\n<input type=\"submit\" name=\"Submit\" value=\"  Add Update  \"></div></form>";

				$update_link = "tasks.php?Sec=tasks";
			}

			else if ($Row->T_ID != $Update) {
				$update_link = "tasks.php?Sec=tasks&Update=" . $Row->T_ID;
			}
		}

		if(!isset($Update)) {
			$update_link = "tasks.php?Sec=tasks&Update=" . $Row->T_ID;
		}


?>
			  
			  <tr>
                <td>
				  <table width="100%" border="0" align="center" cellpadding="1" cellspacing="0" bgcolor="#000000">
                    <tr> 
                      <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#FFFFCC">
                          <tr> 
                            <td><table width="100%" border="0" cellspacing="1" cellpadding="1">
                                <tr valign="top"> 
                                  <td width="22"> <div align="center"><img src="images/<?= $arrow; ?>.gif" width="15" height="13" alt="Priority"></div></td>
                                  <td>&nbsp;<a href="<?= $Link; ?>" class="link"><strong><?= $Row->title; ?></strong></a><br> <font size="1">- <?= $out; ?></font></td>
                                  <td width="22"> <div align="center"><a href="<?= $update_link; ?>"><img src="images/note.gif" alt="Update Task" width="16" height="16" border="0"></a></div></td>
                                  <td width="22"> <div align="center"><?= $Com_image; ?></div></td>
                                  <td width="22"> <div align="center"><a href="edit_task.php?Sec=tasks&task=<?= $Row->T_ID; ?>"><img src="images/edit.gif" alt="Edit Task" width="16" height="16" border="0"></a></div></td>
                                  <td width="22"> <div align="center"><a href="tasks.php?Sec=tasks&DeleteMe=<?= $Row->T_ID; ?>"><img src="images/delete.gif" alt="Delete Task" width="16" height="16" border="0"></a></div></td>
                                </tr>
                              </table>
							<?= $print; ?>
							<?= $print_update; ?>
							</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
				</td>
              </tr>

<?php
	$print = "";
	$print_update = "";
	$Link = "";
}


?>

              <tr>
                <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
                    <tr> 
                      <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#6699FF">
                          <tr> 
                            <td><strong><font color="#FFFFFF" size="1">&nbsp;&nbsp;&nbsp;Total Tasks: <?= $Counted; ?></font></strong></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>

<?php

include "footer.inc.php";
?>