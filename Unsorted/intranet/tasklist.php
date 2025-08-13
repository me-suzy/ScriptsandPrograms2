<html><head></head>
<?php
include("config.php");
include("identity.php");
if ($refok == "yes")
	{

$appheaderstring='Task List';
include("header.php");

if ($action == "delete") { mysql_query( "delete from tasklist where id ='$id'"); }
if ($action == "update") {
	$result = mysql_query( "select id, task, priority, who_owns, tstamp, who_wrote
				from tasklist where who_owns ='$setting[login]' order by priority, tstamp");
	while ($foo = mysql_fetch_row($result))
 		{
		$idnum = $foo[0];
		mysql_query( "update tasklist set task='$task[$idnum]', priority='$priority[$idnum]', tstamp='$unstamp[$idnum]'
					where id='$idnum'");
		}
			}
if ($action == "add") { mysql_query( "insert into tasklist (task, priority, who_owns, tstamp, who_wrote)
				values('$newtask', '$priority', '$whoownsit', NULL, '$setting[login]')");
		   }

echo "<center><p><table width='95%' border='0' cellpadding='0' cellspacing='0'><form action='tasklist.php' method='post' name='boris'><tr><td><font size='1'>PRIORITY</font></td><td colspan='2'></td><td><a href='printtasklist.php' target='printerfriendly'><img src='icons/printer.gif' border='0' alt='Printer Friendly Version'></a></td></tr>";
		echo "<tr><td colspan='4'><hr width='100%'></td></tr>";
	$result = mysql_query( "select id, task, priority, who_owns, tstamp, who_wrote from tasklist where who_owns ='$setting[login]' order by priority, tstamp");
	$number = mysql_num_rows($result);
	while ($taskdata = mysql_fetch_row($result))
		{
		echo "<tr><td><input type='text' size='3' maxlength='3' name='priority[", $taskdata[0], "]' value='", $taskdata[2], "'></td>";
		echo "<td align='center'><input type='text' size='50' name='task[", $taskdata[0], "]' value='", $taskdata[1], "'>";
		echo "</td><td align='right'><img src='icons/whowrote.gif' alt='", $taskdata[5], "' border='0'></a> <a href='tasklist.php?id=", $taskdata[0], "&action=delete'><img src='icons/delete.gif' border='0' alt='Delete!'></a></td></tr>";
		echo "<input type='hidden' name='unstamp[", $taskdata[0], "]' value='", $taskdata[4], "'>";
                    }
	if ($number > 0) {
		echo "<tr><td colspan='4' align='right'><input type='hidden' name='action' value='update'><input type='submit' value='Update'></td></tr>";
			} else { echo "<tr><td colspan='4'>You don't have any tasks on your task list. To add a task, put its priority (a smaller number is a higher priority) in the first box below and the task itself in the second box below. Then click <b>Add</b>. You can add a task to another user's task list by chosing their name from the pull-down menu, but you won't be able to modify or delete that task later.</td></tr>";
				}
		echo "</form><tr><td colspan='4'><hr width='100%'></td></tr>";
		echo "<form action='tasklist.php' method='post' name='newone'>";
		echo "<tr><td><input type='text' size='3' maxlength='3' name='priority' value='1'></td>";
		echo "<td align='center'><input type='text' size='50' name='newtask' value=''><input type='hidden' name='action' value='add'></td><td></td></tr>";
		echo "<tr><td> </td><td align='right'>Add the above new task for: <select name='whoownsit'>";
		$result2 = mysql_query( "select login from userinfo");
		while ($userlist = mysql_fetch_row($result2))
			{
			echo "<option value='", $userlist[0], "' ";
			if ($userlist[0] == $setting[login]) { echo " selected"; }
			echo ">", $userlist[0];
			}
		echo "</select> &nbsp; &nbsp; </td><td><input type='submit' value='Add'></td></tr></form>";
?>
</table></center></body></html>
<?php
	} ?>
