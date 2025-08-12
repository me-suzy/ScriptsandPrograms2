<?
   function adminadd(){
?>

<form action=admin.php method=post>
<input type=hidden name=pass value=<? print $GLOBALS[pass]; ?>>
<input type=hidden name=action value=added>

<table border=0>
<tr> <td bgcolor=#FFFF89> Name:  </td> <td bgcolor=#FFFF89> <input type=text name=name size=30> </td> </tr>
<tr> <td> Question: </td> <td> <input type=text name=question size=50> </td> </tr>
<tr> <td> Answer 1:</td> <td> <input type=text name=answer1 size=70> </td> </tr>
<tr> <td> Answer 2:</td> <td> <input type=text name=answer2 size=70> </td> </tr>
<tr> <td> Answer 3:</td> <td> <input type=text name=answer3 size=70> </td> </tr>
<tr> <td> Answer 4:</td> <td> <input type=text name=answer4 size=70> </td> </tr>
<tr> <td> Start Date:</td> <td> <input type=text name=startdate size=10> <font size=1>(example: 2001-10-29)</font></td> </tr>
<tr> <td> End Date:</td> <td> <input type=text name=enddate size=10> <font size=1>(example: 2001-10-29)</font> </td> </tr>

</table>

<input type=submit name=submit value=Add>

<?
  }

 function adminadded(){
	global $tempz;
	if($GLOBALS[name] == ""){
		print "Error: you must enter a name for the quiz!";
	}
	if($GLOBALS[question] == ""){
		print "Error: you must enter a question for the quiz!";
	}

	$query = "insert into quizes(name, question, answer1, answer2, answer3, answer4, start, end) 
			values('$GLOBALS[name]', '$GLOBALS[question]', '$GLOBALS[answer1]', 
			'$GLOBALS[answer2]', '$GLOBALS[answer3]', '$GLOBALS[answer4]', 
			'$GLOBALS[startdate]', '$GLOBALS[enddate]')";
	$dbh = Connect_Database();
	$dbc = mysql_query($query) 
		or $err=1;

	if($err){
		print "There was a problem in your entry, please try again!"; 
		print "<pre>$query</pre>";
		return;
	}

	$dbc = mysql_query("select id from quizes where name='$GLOBALS[name]'");
	if($row = mysql_fetch_array($dbc) ){
		$id = $row[0];
	}else{
		print "There was a problem in your entry, please try again!"; 
		return;
	}

	print "Quiz Add. Please copy and paste HTML code into your site:<p>";
	print "<form><textarea cols=30 rows=10>";

	$name = stripslashes($GLOBALS[name]);
	$question = stripslashes($GLOBALS[question]);
	$answer1 = stripslashes($GLOBALS[answer1]);
	$answer2 = stripslashes($GLOBALS[answer2]);
	$answer3 = stripslashes($GLOBALS[answer3]);
	$answer4 = stripslashes($GLOBALS[answer4]);
	$startdate = stripslashes($GLOBALS[enddate]);
	$enddate = stripslashes($GLOBALS[startdate]);

        $t = new Template();
        $temp = $tempz[form];
        $t->set_file("Page", $temp);
        $t->set_var( array(
                "id" => $id,
                "name" => $name,
                "question" => $question,
                "answer1" => $answer1,
                "answer2" => $answer2,
                "answer3" => $answer3,
                "answer4" => $answer4,
                "startdate" => $enddate,
                "enddate" => $enddate
        ));
        $t->parse("P", "Page");
        echo $t->get_var("P");

	print "</textarea></form>";
	

 }

?>
