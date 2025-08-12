<?
   function adminlist(){


	$dbh = Connect_Database();

	//- check for sub_actions
	if($GLOBALS[sub_action] == "Votes"){
		if(empty($GLOBALS[sortby])){
			$sortby = "created";
		} else {
			$sortby = $GLOBALS[sortby];
		}
		if($GLOBALS[desc] == "")
			$desc = "desc";
		else
			$desc = "";
	

		$query = "select email, vote, ip, created from votes where quiz_id=$GLOBALS[quiz_id] order by $sortby $GLOBALS[desc]";
		$dbc = mysql_query($query);
		$out = "<table border=1 cellpadding=5 cellspacing=0 width=80%>";

			$out .= "<tr>";
			$out .= "<td bgcolor=gray> 
	<a href=admin.php?pass=$GLOBALS[pass]&action=list&sub_action=Votes&quiz_id=$GLOBALS[quiz_id]&sortby=email&desc=$desc>
				<b><font size=2 face=arial color=white> E-Mail </font></b></a></td>";
			$out .= "<td bgcolor=gray> 
	<a href=admin.php?pass=$GLOBALS[pass]&action=list&sub_action=Votes&quiz_id=$GLOBALS[quiz_id]&sortby=vote&desc=$desc>
				<b><font size=2 face=arial color=white> Vote </font></b></td>";
			$out .= "<td bgcolor=gray> 
	<a href=admin.php?pass=$GLOBALS[pass]&action=list&sub_action=Votes&quiz_id=$GLOBALS[quiz_id]&sortby=ip&desc=$desc>
				<b><font size=2 face=arial color=white> IP Address </font></b></td>";
			$out .= "<td bgcolor=gray> 
	<a href=admin.php?pass=$GLOBALS[pass]&action=list&sub_action=Votes&quiz_id=$GLOBALS[quiz_id]&sortby=created&desc=$desc>
				<b><font size=2 face=arial color=white> Date and Time </font></b></td>";
			$out .= "</tr>";
		while($row = mysql_fetch_array($dbc)){
			$out .= "<tr>";
			$out .= "<td> <font size=2 face=arial> $row[0] </font></td>";
			$out .= "<td> <font size=2 face=arial> $row[1] </font></td>";
			$out .= "<td> <font size=2 face=arial> $row[2] </font></td>";
			$out .= "<td> <font size=2 face=arial> $row[3] </font></td>";
			$out .= "</tr>";
		}
		$out .= "</table>";

	}
	elseif($GLOBALS[sub_action] == "Modify"){
		//- get all quiz data
        	$dbc = mysql_query("select name, question, answer1, answer2, answer3, answer4, start, end from quizes where id=$GLOBALS[quiz_id]");
        	if($row = mysql_fetch_array($dbc) ){
                	$name = stripslashes($row[0]);
               	 	$question = stripslashes($row[1]);
                	$answer1 = stripslashes($row[2]);
                	$answer2 = stripslashes( $row[3]);
                	$answer3 = stripslashes($row[4]);
                	$answer4 = stripslashes($row[5]);
                	$startdate = $row[6];
                	$enddate = $row[7];
        	}else{
                	return "There was a problem in your entry, please try again!";
        	}

		$out = "<form action=admin.php method=post>";
		$out .= "
			<input type=hidden name=pass value=$GLOBALS[pass]>
			<input type=hidden name=action value=list>
			<input type=hidden name=sub_action value=modified>
			<input type=hidden name=quiz_id value=$GLOBALS[quiz_id]>

			<table border=0>
<tr> <td bgcolor=#FFFF89> Name:  </td> <td bgcolor=#FFFF89> <input type=text name=name size=30 value=\"$name\"> </td> </tr>
<tr> <td> Question: </td> <td> <input type=text name=question size=50 value=\"$question\"> </td> </tr>
<tr> <td> Answer 1:</td> <td> <input type=text name=answer1 size=70 value=\"$answer1\"> </td> </tr>
<tr> <td> Answer 2:</td> <td> <input type=text name=answer2 size=70 value=\"$answer2\"> </td> </tr>
<tr> <td> Answer 3:</td> <td> <input type=text name=answer3 size=70 value=\"$answer3\"> </td> </tr>
<tr> <td> Answer 4:</td> <td> <input type=text name=answer4 size=70 value=\"$answer4\"> </td> </tr>
<tr> <td> Start Date:</td> <td> <input type=text name=startdate size=10 value=\"$startdate\"> <font size=1>(example: 2001-10-29)</font> </td> </tr>
<tr> <td> End Date:</td> <td> <input type=text name=enddate size=10 value=\"$enddate\"> <font size=1>(example: 2001-10-29)</font> </td> </tr>

			</table>
			<input type=submit name=submit value=Modify>
		";


		//- place it in a form

	}
	elseif($GLOBALS[sub_action] == "modified"){
        	if($GLOBALS[quiz_id] == ""){
                	print "Serious Error: ID is missing!";
        	}
        	if($GLOBALS[name] == ""){
                	print "Error: you must enter a name for the quiz!";
        	}
        	if($GLOBALS[question] == ""){
                	print "Error: you must enter a question for the quiz!";
        	}

        	$query = "replace into quizes(id, name, question, answer1, answer2, answer3, answer4, start, end)
                        values($GLOBALS[quiz_id], '$GLOBALS[name]', '$GLOBALS[question]', '$GLOBALS[answer1]',
                        '$GLOBALS[answer2]', '$GLOBALS[answer3]', '$GLOBALS[answer4]',
			'$GLOBALS[startdate]','$GLOBALS[enddate]'
			)";
        	$dbc = mysql_query($query)
                	or $err=1;

        	if($err){
                	print "There was a problem in your entry, please try again!";
                	return;
        	}
	}
	elseif($GLOBALS[sub_action] == "HTML"){
		$out = HTML_Code($GLOBALS[quiz_id], "admin");
	}
	elseif($GLOBALS[sub_action] == "Delete"){
        	$dbc = mysql_query("select name, question from quizes where id=$GLOBALS[quiz_id]");
		$out = "Are you sure you would like to delete this quiz and all votes! \n";
		if($row = mysql_fetch_array($dbc) ){
			$out .="<br>Name: <b>$row[0]</b>";
			$out .="<br>Question: <b>$row[1]</b>";
			$out .= "<form action=admin.php method=get>";
			$out .= "<input type=hidden name=pass value=$GLOBALS[pass]>";
			$out .= "<input type=hidden name=action value=list>";
			$out .= "<input type=hidden name=sub_action value=deleted>";
			$out .= "<input type=hidden name=delete_id value=$GLOBALS[quiz_id]>";
			$out .= "<input type=submit name=submit value=DELETE>";
			$out .= "</form>";
	
		}
	}
	elseif($GLOBALS[sub_action] == "deleted"){
        	if($GLOBALS[delete_id] == ""){
                	print "Serious Error: ID is missing!";
        	}

        	$query = "delete from votes where quiz_id=$GLOBALS[delete_id]";
        	$dbc = mysql_query($query)
                	or $err=1;
        	$query = "delete from quizes where id=$GLOBALS[delete_id]";
        	$dbc = mysql_query($query)
                	or $err=1;

        	if($err){
                	print "There was a problem in your entry, please try again!";
                	return;
        	}
		$out = "The quiz is successfully deleted";
	}



	//---------------------------------------------------------------------------
	//- get a lst of all quizes from db
        $dbc = mysql_query("select id, name from quizes order by id desc");
	print "<form action=admin.php method=get>";
	print "<input type=hidden name=pass value=$GLOBALS[pass]>";
	print "<input type=hidden name=action value=list>";
	print "<select name=quiz_id>";
	//print "<option>-------------      Select a quiz ---------------\n";
        while($row = mysql_fetch_array($dbc) ){
		if($GLOBALS[quiz_id] == $row[0])
			print "\n<option value=$row[0] SELECTED>$row[1]</option>";
		else
			print "\n<option value=$row[0]>$row[1]</option>";
        }
	print "</select><br>";
	print "<input type=submit name=sub_action value=Votes>";
	print "<input type=submit name=sub_action value=Modify>";
	print "<input type=submit name=sub_action value=HTML>";
	print "<input type=submit name=sub_action value=Delete>";
	print "</form>";

	print "<p><br>";

	print $out;



  }


?>
