<?

 function Exit_Error($message){

	print $message;
	exit;

 }

 function Connect_Database()
 { 
        global $db_host;
        global $db_name;
        global $db_user;
        global $db_password;
                
        $dbh = mysql_connect($db_host, $db_user, $db_password)
                or Exit_Error("Cannot Connect To Database");
        mysql_select_db($db_name)
                or Exit_Error("Cannot Use Database");
        $GLOBALS[connection] = 1;
        return $dbh;
}      


function HTML_Code($myid, $type){
	global $tempz;

	if(!$myid>0){
		print "something is wrong with the id! Try again!";
		exit;
	}
        $dbc = mysql_query("select id, name, question, answer1, answer2, answer3, answer4 from quizes where id='$myid'");
        if($row = mysql_fetch_array($dbc) ){
                $myid = $row[0];
                $myname = $row[1];
                $myquestion = $row[2];
                $myanswer1 = $row[3];
                $myanswer2 = $row[4];
                $myanswer3 = $row[5];
                $myanswer4 = $row[6];
        }else{
                $myout .= "There was a problem in your entry, please try again!";
                return $myout;
        }

        $myout .= "Please copy and paste this HTML code into your site:<p>";
        $myout .= "<form><textarea cols=90 rows=20>";

        $myname = stripslashes($myname);
        $myquestion = stripslashes($myquestion);
        $myanswer1 = stripslashes($myanswer1);
        $myanswer2 = stripslashes($myanswer2);
        $myanswer3 = stripslashes($myanswer3);
        $myanswer4 = stripslashes($myanswer4);

        $t = new Template();
        $temp = $tempz[form];
        $t->set_file("Page", $temp);
        $t->set_var( array(
                "id" => $myid,
                "name" => $myname,
                "question" => $myquestion,
                "answer1" => $myanswer1,
                "answer2" => $myanswer2,
                "answer3" => $myanswer3,
                "answer4" => $myanswer4
        ));
        $t->parse("P", "Page");
        $myout .= $t->get_var("P");
        $out .= $t->get_var("P");

        $myout .= "</textarea></form>";

	if($type == "admin")
		return $myout;
	return $out;
}

?>
