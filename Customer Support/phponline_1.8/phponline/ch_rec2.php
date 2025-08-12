<?php
include_once('noca.php');
include_once('rcq.php');


$CCode = $HTTP_POST_VARS['ccode'];
$LTime = $HTTP_POST_VARS['ltime'];
$LanguageSel = $HTTP_POST_VARS['language_sel'];
$TTime = gmdate("U");
$MsgTemp = "";
$DTime = doubleval($LTime);

$getuptext = 0;
$uptext = "";
$WriteSignal = 0;
$FakeMsgTemp = 0;

$fst = GetCCodeStatus($CCode,"1");
if($fst==1)
{
	SetCCodeStatus($CCode, "0","1");

	$dbh=mysql_connect($DBHost, $DBUsername, $DBPassword,true) or die ('res=0');
	mysql_select_db($DBDatabase,$dbh);

	$res = mysql_query("SELECT * FROM msgdb WHERE ccode=\"$CCode\" AND ttime>$LTime AND code1=2 AND code2=0 ORDER BY ttime ASC",$dbh);
	$res2= mysql_query("UPDATE msgdb SET code2=1 WHERE ccode=\"$CCode\" AND ttime>$LTime AND code1=2 AND code2=0 ORDER BY ttime ASC",$dbh);
	while($row = mysql_fetch_array($res))
	{
		if(substr($row["msg"],0,strlen('EOw3OkAE062f8628bz2y7v47vmW85q4c'))=='EOw3OkAE062f8628bz2y7v47vmW85q4c')
		{
			if($row["msg"]=='EOw3OkAE062f8628bz2y7v47vmW85q4c_1')
			{
				$getuptext = 1;
				$FakeMsgTemp = 1;
			}
			if($row["msg"]=='EOw3OkAE062f8628bz2y7v47vmW85q4c_2')
			{
				$WriteSignal = 1;
				$FakeMsgTemp = 1;
			}
			if($row["msg"]=='EOw3OkAE062f8628bz2y7v47vmW85q4c_3')
			{
				$WriteSignal = 2;
				$FakeMsgTemp = 1;
			}
			if(substr($row["msg"],0,strlen('EOw3OkAE062f8628bz2y7v47vmW85q4c_10'))=='EOw3OkAE062f8628bz2y7v47vmW85q4c_10')
			{
				$row["msg"] = htmlspecialchars(substr($row["msg"],strlen('EOw3OkAE062f8628bz2y7v47vmW85q4c_10')+1));
				$MsgTemp .= ("<p><b><font color=\"#FF0000\">phpOnline> ".$row["msg"]."</font></b></p>");
			}
		}
		else
		{
			$row["msg"] = htmlspecialchars($row["msg"]);
			if(strpos($row["msg"],':4-|ln|_4:')!==false)        
			{
				$TMS2 = explode(":4-|ln|_4:",$row["msg"]);
				$TextLanguageSel = $TMS2[1];
				if($LanguageSel!=$TextLanguageSel)
				{
					$row["msg"] = Translate($TMS2[2],$TextLanguageSel,$LanguageSel);
					$row["msg"] = utf8_encode($row["msg"]);
				}
				else
				{
					$row["msg"] = $TMS2[2];
				}
			}
			$MsgTemp .= ("<p><b><font color=\"#0000FF\">Client> ".$row["msg"]."</font></b></p>");
		}
		$PKT = doubleval($row["ttime"]);
		if($PKT > $DTime)
		{
			$DTime = $PKT;
		}	
	}
	mysql_close($dbh);

	if($getuptext==1)
	{
		$dbh=mysql_connect($DBHost, $DBUsername, $DBPassword,true) or die ('res=0');
		mysql_select_db($DBDatabase,$dbh);

		$res = mysql_query("SELECT * FROM cs WHERE ccode=\"$CCode\"",$dbh);
		while($row = mysql_fetch_array($res))
		{
			$uptext = "<font color=\"#000000\">In chat with </font><font color=\"#0000FF\">".$row['name']."</font><font color=\"#000000\">, IP address </font><font color=\"#0000BB\">".$row['ip']."</font>";
			if($CONF['conf_UseLanguageTranslator']==1)
			{
				$uptext .= " [<font color=\"#008000\">".ucfirst($TransLangs[$row['code1']][1])."]";
			}
			break;
		}
		mysql_close($dbh);
	}


	if($FakeMsgTemp == 1 && $MsgTemp == "")
	{
		$MsgTemp = "EOw3OkAE062f8628bz2y7v47vmW85q4c";
	}


}

$MsgTemp = GetEQString($MsgTemp);
$uptext = GetEQString($uptext);

echo "test1=123&cass2=test&ltime=$DTime&sema10=1&msgtemp=$MsgTemp&fdelim2=1&uptext=$uptext&fdelim3=1&writesignal=$WriteSignal&test=tes1212";

?>