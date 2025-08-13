<?php
include("config.php");
include("identity.php");
if($refok == 'yes')
{
$appheaderstring='Survey';
include("header.php");
if ($action == '')
	{
        echo "<blockquote><ul>";
	echo "<li><a href='survey.php?action=viewlist'>View Survey Results</a>";
	echo "<p><li><a href='survey.php?action=create'>Create New Survey</a></ul></blockquote>";
	}
if ($action == 'delete')
	{
	dbconnect($dbusername,$dbuserpasswd);
	mysql_query("delete from surveyquestions where id='$target'");
	mysql_query("delete from surveyanswers where questionid='$target'");
	$action='viewlist';
	}
if ($action =='writeresponse')
	{                    	
	if ($response != '')
		{
	dbconnect($dbusername,$dbuserpasswd);
		mysql_query("insert into surveyanswers set questionid='$target', user='$setting[login]', answer='$response'");
		echo "<blockquote>&nbsp;<ul>&nbsp;<font size='4'><B>Thanks!</B></font></ul></blockquote>";
		if ($setting[perm_survey]=='y')
			{
			$action='view';
			echo "<blockquote><p>Results so far for this survey:<p></blockquote>";
			}
		} else
			{
	                $action='complete';
			}
	}
if ($action == 'create')
	{
	echo "<font face='", $setting[heading_fontface], "' size='", $setting[heading_fontsize], "'>Creating a New Survey</font>";
        echo "<center><table border='0' cellpadding='0' cellspacing='0'>";
	echo "<form method='post' action='survey.php'>";
	echo "<tr><td align='right'>Who can access the results?</td><td><select name='access'><option value='open'>anyone with survey permission<option value='restrict'>only me</select></td></tr>";
	echo "<tr><td align='right'>What is the question?</td><td><textarea cols='25' rows='3' wrap='virtual' name='question'></textarea></td></tr>";
	echo "<tr><td align='right'>Is the question multiple choice or free response?</td><td><select name='answertype'><option value='mult'>Multiple Choice<option value='free'>Free Response</select></td></tr>";
	echo "<input type='hidden' name='action' value='create2'>";
	echo "<tr><td align='right' colspan='2'><input type='submit' value='Continue'></td></tr></table>";
	}
if ($action == 'create2')
	{
	echo "<font face='", $setting[heading_fontface], "' size='", $setting[heading_fontsize], "'>Creating a New Survey</font>";
        echo "<center><table border='0' cellpadding='0' cellspacing='0'>";
	echo "<form method='post' action='survey.php'>";
	echo "<input type='hidden' name='access' value='", $access, "'>";
	echo "<input type='hidden' name='question' value='", $question, "'>";
	echo "<input type='hidden' name='answertype' value='", $answertype, "'>";
        if ($answertype=='free')
		{
		echo "<tr><td align='right'>How many lines should there be in the response text box?</td><td><select name='option1'><option>1<option>2<option selected>4<option>6<option>9<option>12<option>15<option>20<option>25</select></td></tr>";
		} else {
			echo "<tr><td colspan='2'>Fill in one box below for each response option. Leave the others blank.</td></tr>";
			$j=1;
			while($j <=10)
				{
	                        echo "<tr><td align='right'>Response Option ", $j, ":</td><td><input type='text' name='option", $j, "'></td></tr>";
				$j++;
        			}
			}
	echo "<input type='hidden' name='action' value='send'>";
	echo "<tr><td align='right' colspan='2'><input type='submit' value='Complete & Send Survey'></td></tr></table>";
	}
if ($action=='send')
	{
	dbconnect($dbusername,$dbuserpasswd);
	srand((double)microtime()+1000000);
	$rand_val=rand(1,9999);
	mysql_query("insert into surveyquestions set querent='$setting[login]',
			access='$access', question='$question', answertype='$answertype',
			option1='$option1', option2='$option2', option3='$option3',
			option4='$option4', option5='$option5', option6='$option6',
  			option7='$option7', option8='$option8', option9='$option9',
			option10='$option10',rand_val='$rand_val', date_time=now()");
	$result3=mysql_query("select * from surveyquestions where querent='$setting[login]' and rand_val='$rand_val'");
	$target=mysql_fetch_array($result3);
	dbconnect($dbusername,$dbuserpasswd);
	$result2=mysql_query("select * from userinfo order by lastname");
	/*
	while($urow=mysql_fetch_array($result2))
		{
		$msgbody = "TO: " . $urow[firstname] . " " . $urow[lastname] . "\nFROM: " . $setting[firstname] . " " . $setting[lastname] . "\n\nPlease complete a brief survey by clicking on this URL:\nhttp://" . $serveripaddy1 . $PHP_SELF . "?action=complete&target=" . $target[id] . "\n\nThank you!";
		$fromline = "From: " . $setting[emailaddress];
                mail($urow[emailaddress],"Please complete this survey.",$msgbody,$fromline);
		echo "<br>Sent request to ", $urow[firstname], " ", $urow[lastname], " (", $urow[emailaddress], ")";
		}
	*/
	echo "<br>Done!"; $action = 'viewlist';
	// mail("scott@bhcinfo.com","TEST OF MAIL FUNCTION","This is a test of the PHP mail function","From: root@bhcinfo.tranquility.net");
	}
if ($action=='complete')
	{
	dbconnect($dbusername,$dbuserpasswd);
	$resultm=mysql_query("select * from surveyquestions where id='$target'");
	$rowq=mysql_fetch_array($resultm);	
        echo "&nbsp;<p><blockquote><table border='0' cellpadding='0' cellspacing='0'>";
	echo "<tr><td><font size='2'>Question posted at ", $rowq[date_time], " by ", $rowq[querent], ".</font></td></tr>";
	echo "<form method='post' action='survey.php'>";
	echo "<tr><td><font size='2'><i>";
	if ($rowq[access]=='open' and $setting[perm_survey]=='y')
		{
                echo "You will be able to see the results of this survey.<br>&nbsp;<br>";
		}
	echo "</i></font></td></tr>";
	echo "<tr><td><font size='4'>", $rowq[question], "</font></td></tr>";
	echo "<tr><td>";
	if ($rowq[answertype]=='mult')
		{
		echo "<select name='response'><option>";
		$t = 1;
		while($t<=10)
			{
			$varname = 'option' . $t;
              	 	 if ($rowq[$varname] != '')
				{
                 	        echo "<option>", $rowq[$varname];
				}
			$t++;
			}
		echo "</select>";
		} else
			{
			if ($rowq[option1] == 1)
				{
	                        echo "<input type='text' size='60' name='response'>";
				} else
					{
		                        echo "<textarea cols='60' rows='", $rowq[option1], "' name='response'></textarea>";
					}
			}
	echo "</td></tr>";
	echo "<input type='hidden' name='action' value='writeresponse'>";
	echo "<input type='hidden' name='target' value='", $target, "'>";
	echo "<tr><td align='right'><input type='submit' value='Submit Your Answer'></td></tr>";
	echo "</table></form></blockquote>";
	}
if ($action == 'viewlist')
	{
	dbconnect($dbusername,$dbuserpasswd);
	$resultm=mysql_query("select * from surveyquestions");
	echo "<blockquote><ul>";
	while($row9=mysql_fetch_array($resultm))
		{
                if ($row9[access]=='open' or $row9[querent]==$setting[login])
			{
                        echo "<li><a href='survey.php?action=view&target=", $row9[id], "'>", $row9[id], ". ", $row9[question], " <i>(", $row9[querent], ", ", $row9[date_time], ")</i></a>";
			if ($row9[querent] == $setting[login] or $setting[perm_admin] == 'y')
				{
                                echo " <a href='survey.php?action=delete&target=", $row9[id], "'";
?>
 onClick="if (confirm('<?php echo "You are about to delete ", $row9[question]; ?> \nThe survey and all its data will be erased\nforever unless you click Cancel right now.') == true) { return true; } else { return false; }"
<?php
				echo "><img src='icons/delete.gif' border='0' alt='Delete!'></a>";
				}
			}
		}
	echo "</ul></blockquote>";
	}
if ($action =='view')
	{
	dbconnect($dbusername,$dbuserpasswd);
	$resultk=mysql_query("select * from surveyquestions where id='$target'");
	$rowt=mysql_fetch_array($resultk);
	echo "<blockquote><font size='", $setting[heading_fontsize], "' face='", $setting[heading_fontface], "'>", $rowt[question], "</font><br><i>", $rowt[querent], ", ", $rowt[date_time], "</i>";	
	dbconnect($dbusername,$dbuserpasswd);
	$powie=mysql_query("select * from surveyanswers where questionid='$target'");
	$numresponding=mysql_num_rows($powie);
	$resultm=mysql_query("select answer, COUNT(answer) AS count
				from surveyanswers where questionid='$target' GROUP BY answer");
	echo "<table border='1' cellpadding='5' cellspacing='0'>";
	echo "<tr><td><b>Response</b></td><td><b>Frequency</b></td><td><b>Percent</b><br>of total employees</td><td><b>Percent</b><br>of those responding</td></tr>";
	while($rowd=mysql_fetch_array($resultm))
		{
		$percent =  ($rowd[count]/$numemployees)*100;
		if ($percent == 100) { $percent = '100'; } else { $percent = substr($percent,0,2); }	
		$percent2 =  ($rowd[count]/$numresponding)*100;
		if ($percent2 == 100) { $percent2 = '100'; } else { $percent2 = substr($percent2,0,2); }	
		echo "<tr><td>", $rowd[answer], "</td><td>", $rowd[count], "</td><td>", $percent, "</td><td>", $percent2, "</td></tr>";
		}
	echo "</table>";
	}
}
?>
