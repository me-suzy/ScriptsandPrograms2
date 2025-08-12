#!/usr/local/bin/php -q
<?PHP
require_once("engine.inc.php");
include_once('sender.inc.php');
$s_date = date("Y-m-d");
$s_time = date("H:i:s");
$data = $s_date;
list($year,$month,$day) = explode("-",$data);
$s_p = 30;
$displayDate = date("Y-m-d" ,mktime($hours-$s_p,$minutes,$seconds,$month,$day,$year)); 
$limit_date = $displayDate;
$respond = mysql_query ("SELECT * FROM 12all_Respond
						WHERE time != '0'
                       	ORDER BY time, subject
						");
if ($c1 = mysql_num_rows($respond)) {

while($respondf = mysql_fetch_array($respond)) {
	$fid = $respondf["id"];
	$nl = $respondf["nl"];
	$rhours = $respondf["time"];
	$msubject = stripslashes($respondf["subject"]);
	$mfromn = stripslashes($respondf["fromn"]);
	$mfrome = $respondf["frome"];
	$mtype = $respondf["type"];
	$mcontent = stripslashes($respondf["content"]);
	$rid = ",$fid,";
	$membersResult = mysql_query ("SELECT sdate,stime,email,name,id,respond,field1,field2,field3,field4,field5,field6,field7,field8,field9,field10 FROM ListMembers
							 		WHERE active LIKE '0'
									AND email != ''
									AND nl LIKE '$nl'
									AND respond NOT LIKE '%$rid%'
									AND sip != 'imported'
									ORDER BY id
									");

/**
* Loop thru results
*/
	if (mysql_num_rows($membersResult)) {
		while ($row = mysql_fetch_array($membersResult)) {
			$sid = $row["id"];
			$email = $row["email"];
							$name = $row["name"];
							$field1 = $row["field1"];
							$field2 = $row["field2"];
							$field3 = $row["field3"];
							$field4 = $row["field4"];
							$field5 = $row["field5"];
							$field6 = $row["field6"];
							$field7 = $row["field7"];
							$field8 = $row["field8"];
							$field9 = $row["field9"];
							$field10 = $row["field10"];
			$ridold = $row["respond"];
			$findcount = mysql_query ("SELECT * FROM 12all_RespondT
				WHERE sid = '$sid'
				AND fid = '$fid'
				");
			$countdata = mysql_num_rows($findcount);
			if ($countdata == "0"){
			$signupd = $row["sdate"];
			$signupt = $row["stime"];
			//print "&nbsp;&nbsp;Signed up at: $signupd / $signupt  &nbsp;&nbsp;&nbsp;";
				$data = $signupd;
				list($year,$month,$day) = explode("-",$data);
				$s_p = $rhours;
				$displayDate = date("Y-m-d" ,mktime($hours+$s_p,$minutes,$seconds,$month,$day,$year)); 
				$b_date = $displayDate;
				if ($b_date <= $s_date AND $b_date > $limit_date){
					$data = $signupt;
					list($hours,$minutes,$seconds) = explode(":",$data);
					$displayDate = date("H:i:s" ,mktime($hours+$s_p,$minutes,$seconds,$month,$day,$year)); 
					$b_time = $displayDate;
					if ($b_time <= $s_time){
					
						$urlfinder = mysql_query ("SELECT * FROM Backend
                         							WHERE valid LIKE '1'
						 							limit 1
         								            ");
						$findurl = mysql_fetch_array($urlfinder);
						$murl = $findurl["murl"];
						$mcontent = ereg_replace ("%UNSUBSCRIBELINK%", "$murl/box.php?funcml=unsub2&nlbox[1]=$nl&email=$email", $mcontent);
										foreach (array('msubject', 'mcontent') as $var) {
											$$var = str_replace ("subscribername", $name, $$var);
											$$var = str_replace ("subscriberemail", $email, $$var);
											$$var = ereg_replace ("[\]", "", $$var);
										}
										for ($i=10; $i>=1; $i--) {
											$msubject  = str_replace("subscriberfield" . $i, ${'field' . $i}, $msubject);
											$mcontent = str_replace("subscriberfield" . $i, ${'field' . $i}, $mcontent);
										}
						$mail = new htmlMimeMail();
						if ($mtype == 'html') {
							$mail->setHtml($mcontent);
						} elseif ($mtype == 'text') {
							$mail->setText($mcontent);
						} 
						if($mfromn != ""){
							$mfromd = "\"".$mfromn."\" <".$mfrome.">";
							$mfrome = stripslashes($mfromd);
						}

						$mail->setFrom($mfrome);
						$mail->setSubject($msubject);
						$sendResult = $mail->send(array($email));
			
						mysql_query ("INSERT INTO 12all_RespondT (sid,fid,sdate) VALUES ('$sid' ,'$fid' ,'$s_date')");  
						$ridspond = "$ridold $rid";
						mysql_query("UPDATE ListMembers SET respond='$ridspond' WHERE (id='$sid')");

					}
				}
			}
		}
	}
} 

}

$data = $s_date;
list($year,$month,$day) = explode("-",$data);
$s_p = 40;
$displayDate = date("Y-m-d" ,mktime($hours-$s_p,$minutes,$seconds,$month,$day,$year)); 
$d_date = $displayDate;
mysql_query ("DELETE FROM 12all_RespondT
             WHERE sdate < '$d_date'
			 ");
?>