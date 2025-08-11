<?php	
	
function Redirect($pstrPath_file)
{	
	header("Location: ".$pstrPath_file); 
}

function killChars($strWords)
{

	$badChars = array("select", "drop", ";", "--", "insert", "delete","Update");
	$newChars   = array("", "", "", "", "", "", "");

	$strWords = str_replace($badChars, $newChars, $strWords);
	return  $strWords;

}
function NewLineinHTML($pstrString)
{
	$badChars = array("\n");
	$newChars   = array("<BR>");

	$pstrString = str_replace($badChars, $newChars, $pstrString);
	return  $pstrString;
}
function NewLinefromHTML($pstrString)
{
	$badChars = array("<BR>");
	$newChars   = array("\n");

	$pstrString = str_replace($badChars, $newChars, $pstrString);
	return  $pstrString;
}
function BackSlash($pstrString)
{
	$badChars = array("\\");
	$newChars   = array("\\\\");

	$pstrString = str_replace($badChars, $newChars, $pstrString);
	return  $pstrString;
}

function SQLSafeString($pstrString)
{
	$badChars = array("'","\'");
	$newChars   = array("''","''");

	$pstrString = str_replace($badChars, $newChars, $pstrString);
	return  $pstrString;
}
function JSSafeString($pstrString)
{
	$badChars = array("'");
	$newChars   = array("\'");

	$pstrString = str_replace($badChars, $newChars, $pstrString);
	return  $pstrString;
}
function QuerySafeString($pstrString)
{
	$badChars = array("\'");
	$newChars   = array("'");
	
	$pstrString = str_replace($badChars, $newChars, $pstrString);
	$pstrString=killChars($pstrString);
	return $pstrString;
}

class Database
{
	function Database()
	{
	     /* Connecting, selecting database */
	     $link = mysql_connect(HOSTM, dbUser, dbPassword)
		 or die("Could not connect : " . mysql_error());
	     //print "Connected successfully";
	     mysql_select_db(dbToUse) or die("Could not select database");

	}
	function  Execute($strSQL,$strErrorMessages)
	{
	    $result = mysql_query($strSQL) or die("Query failed : " . mysql_error());
	    return $result;

	}
}

function ComboBoxOptionList($pstrSQL,$pstrSelectedValue)
{
$datab = new Database();
$rst= $datab->Execute ($strsql,$strTempResults);
$strTemp = "";
if ($strTempResults =="")
{
	while ($line = mysql_fetch_array($rst, MYSQL_ASSOC)) 
	{
		$strTemp=$line['CODE'];
		$strTemp1=$line['VALUE'];
		if ($pstrSelectedValue==$strTemp)
			{
			$strTempResults .= "<OPTION SELECTED VALUE='".$strTemp . "'>". $strTemp1 . "</OPTION>";
			}
		else
			{
			$strTempResults .= "<OPTION VALUE='".$strTemp."'>".$strTemp1."</OPTION>";
			}
	}
}
else
{
	$strTempResults =  "<OPTION VALUE=''>" . $strTempResults . "</OPTION>";
}

return $strTempResults;

}

function TableBodyList($pstrSQL,$pstrHeaderRowParameters, 
					$pstrHeaderColumnParameters,
					$pstrDataRowParameters, 
					$pstrDataColumnParameters,
					$intFieldCount)
{
	$strTemp="";
	$datab = new Database;
	$result=$datab->Execute($pstrSQL,$strTemp);
	if ($strTemp!="")
	{
		$strTemp="<TR ".$pstrDataRowParameters."><TD ".$pstrDataColumnParameters.">".$strTemp + "</TD></TR>";
	}
	else
	{
		$strTemp="";$iCount=0;
		$strTemp="<TR ".$pstrHeaderRowParameters . ">";
		
		for ($i = 0;$i<$intFieldCount;$i++) 
		{
			$strTemp.= "<TH " .$pstrHeaderColumnParameters .">" . mysql_field_name($result, $i)."</TH>";
		}

		$strTemp.= "<TH " .$pstrHeaderColumnParameters.">Delete</TH>";
		$strTemp.= "</TR>";
	
	     //$strTemp=+ "<table>\n";
	     while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) 
	     {
		 //$strTemp=+ "\t<tr>\n";
		$strTemp.= "\t<TR ".$pstrDataRowParameters .">\n";
		$iCount=0;$strKey="";
		 foreach ($line as $col_name) 
		 {
			if ($iCount==0) 
			{
				$strKey=$col_name;
				$strTemp.="<TD ".$pstrDataColumnParameters ."><A HREF =\"javaScript:ModifyData('". $strKey ."')\" >" ;
				$strTemp.= $strKey ."</A></TD>";
				$iCount=1;
			}
			else
			{
				$strTemp.= "\t\t<TD " .$pstrDataColumnParameters. ">". $col_name. "</TD>\n";
			     //$strTemp=+ "\t\t<td>$col_name</td>\n";

			}
		 }
		  $strTemp.= "<TD " .$pstrDataColumnParameters . "><A HREF =\"javaScript:DeleteData('" . $strKey  . "')\">Delete</TD>";

		 $strTemp.= "\t</tr>\n";
	     }
	     //$strTemp.=  "</table>\n";

	}
	return $strTemp;

}


function TableBodyListForPage($pstrSQL,$pstrHeaderRowParameters, 
					$pstrHeaderColumnParameters,
					$pstrDataRowParameters, 
					$pstrDataColumnParameters,
					$intFieldCount)
{
	$strTemp="";
	$datab = new Database;
	$result=$datab->Execute($pstrSQL,$strTemp);
	if ($strTemp!="")
	{
		$strTemp="<TR ".$pstrDataRowParameters."><TD ".$pstrDataColumnParameters.">".$strTemp + "</TD></TR>";
	}
	else
	{
		$strTemp="";$iCount=0;
		$strTemp="<TR ".$pstrHeaderRowParameters . ">";
		
		for ($i = 0;$i<$intFieldCount;$i++) 
		{
			$strTemp.= "<TH " .$pstrHeaderColumnParameters .">" . mysql_field_name($result, $i)."</TH>";
		}

		$strTemp.= "<TH " .$pstrHeaderColumnParameters.">Select</TH>";
		$strTemp.= "</TR>";
	
	     //$strTemp=+ "<table>\n";
	     while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) 
	     {
		 //$strTemp=+ "\t<tr>\n";
		$strTemp.= "\t<TR ".$pstrDataRowParameters .">\n";
		$iCount=0;$strKey="";
		 foreach ($line as $col_name) 
		 {
			if ($iCount==0) 
			{
				$strKey=$col_name;
				$strTemp.="<TD width=5%><A HREF =\"javaScript:ModifyData('". $strKey ."')\" >" ;
				$strTemp.= $strKey ."</A></TD>";
				$iCount=1;
			}
			else
		if ($iCount==2) 
			{
				$strChecked="";
				if ($col_name=='Y')
				{
					$strChecked=" checked ";
				}
				$strTemp.= "\t\t<TD width=5%>". $col_name. "</TD>\n";
				 //$strTemp.= "<TD  width=10%><INPUT type=\"checkbox\" name=\"chkStartPage[]\" id=\"chkStartPage[]\" value=\"$strKey\" $strChecked></TD>";
				//$strTemp.= "\t\t<TD " .$pstrDataColumnParameters. ">". $col_name. "</TD>\n";
			     //$strTemp=+ "\t\t<td>$col_name</td>\n";
				$iCount=0;

			}
			
			else 
			{
				$strTemp.= "\t\t<TD " .$pstrDataColumnParameters. ">". $col_name. "</TD>\n";
			     //$strTemp=+ "\t\t<td>$col_name</td>\n";
				$iCount+=1;
			}

		 }
		  $strTemp.= "<TD  width=10%><INPUT type=\"checkbox\" name=\"chkSelect[]\" id=\"chkSelect[]\" value=\"$strKey\"></TD>";

		 $strTemp.= "\t</tr>\n";
	     }
	     //$strTemp.=  "</table>\n";

	}
	return $strTemp;

}


function TableMenuList($pstrSQL,$pstrHeaderRowParameters, $pstrHeaderColumnParameters,$pstrDataRowParameters, $pstrDataColumnParameters,$intFieldCount)
{
$datab = new Database();
$strTemp="";
$rst= $datab->Execute ($pstrSQL,$strTemp);

if ($strTemp!="")
{
	$strTemp="<TR ".$pstrDataRowParameters . "><TD " .$pstrDataColumnParameters . ">" . $strTemp . "</TD></TR>";
	return $strTemp;
}

else
	{
	$strTemp="";

	//$strTemp="";
	while ($line = mysql_fetch_array($rst, MYSQL_ASSOC)) 
	{
		$strTemp2=$line['News'];
		$strTemp1=$line['ID'];
		$strTemp3=$line['Date'];
		
		
		$strTemp.= "<TR ".$pstrDataRowParameters.">";

		$strTemp.="<TD ".$pstrDataColumnParameters."><A HREF =\"NewsDetails.php?ID=" .$strTemp1. "\" >".$strTemp2. "</A></TD>";
		$strTemp.="<TD ".$pstrDataColumnParameters.">" .$strTemp3. "</TD>";
		
		$strTemp.= "</TR>";
	}
}

return $strTemp;
}

function getTitleBody($pstrHTML)
{
	$strTemp=strtoupper($pstrHTML);

	$pstrTitle="";
	$intFirst= strpos($strTemp , "<TITLE>");
	$intLast= strpos($strTemp, "</TITLE>");
	if ($intFirst !== FALSE)
	
	{
		if ($intLast!==FALSE)
		{
			if ($intLast>$intFirst)
			{
				$intFirst=$intFirst+7;
				$pstrTitle= substr($pstrHTML,$intFirst,$intLast-$intFirst);
			}
		}
	}
	
	$pstrBody="";
	$pstrNoBODY="";
	$pstrHead="";
	$intFirst= strpos($strTemp, "<BODY");
	$intLast= strpos($strTemp, "</BODY>");
	if ($intFirst!==FALSE)
	{
	
		if ($intLast !== FALSE)
		{
			if ($intLast > $intFirst)
			{
        			$intFirst1= strpos(substr($strTemp,$intFirst),">")+$intFirst+1;
				$pstrHead=substr($pstrHTML, 0,$intFirst1);
				$intFirst=$intFirst1;
				$pstrBody= substr($pstrHTML,$intFirst,$intLast-$intFirst);
				$pstrNoBODY=substr($pstrHTML,$intLast);
				//print $pstrNoBODY;
			}
		}
	}
$arr = array("TITLE" =>"","BODY" =>"","NOBODY" =>"","HEAD" =>"");
$arr["TITLE"]=$pstrTitle;
$arr["BODY"]=$pstrBody;
$arr["NOBODY"]=$pstrNoBODY;
$arr["HEAD"]=$pstrHead;
//$arr["TITLE","BODY","NOBODY","HEAD"];

return $arr;
}

function FormatingOriginalHTML($pstrHTML)
{

	$pstrHTML =str_replace('/\\/\\/\\','"',$pstrHTML);
	$pstrHTML =str_replace('>','&gt;',$pstrHTML);
	$pstrHTML =str_replace('<','&lt;',$pstrHTML);
	return $pstrHTML;
}

function SaveFile($filename,$somecontent,$opentype)
{

	if ($opentype=="N")
	{
		$strOpen="w+";
	}
	elseif ($opentype=="E")
	{
		$strOpen="w+";
	}
	else
	{
	     return "Specified open type is wrong";
	}
	// Let's make sure the file exists and is writable first.
	if (is_writable($filename) || ($opentype=="N")) {

	   // In our example we're opening $filename in append mode.
	   // The file pointer is at the bottom of the file hence 
	   // that's where $somecontent will go when we fwrite() it.
	   if (!$handle = fopen($filename, $strOpen)) {
	         return "Cannot open file ($filename)";
	   }

	   // Write $somecontent to our opened file.
	   if (fwrite($handle, stripslashes($somecontent)) === FALSE) {
	       return "Cannot write to file ($filename)";
	   }
   

	   fclose($handle);
                   
	} 
	else 
	{
		return "The file $filename is not writable";
	}
}

function DateFormatMysql($pstrDate)
{//return $pstrDate;
	$intPos=strpos($pstrDate,'-');
	if ($intPos === false) {
		$intPos=strpos($pstrDate,'/');
		if ($intPos === false) {
		$strMonth="";
		$strDay="";
		$strYear="";
		return "";

		} else {
		   	$strMonth=substr($pstrDate,0,$intPos);
		 	$intPos1=strpos($pstrDate,'/',$intPos+1);
		   	$strDay=substr($pstrDate,$intPos+1,$intPos1-($intPos+1));
		   	$strYear=substr($pstrDate,$intPos1+1) 	;
		
		}
	
   	} else {
   	$strMonth=substr($pstrDate,1,$intPos);
 	$intPos1=strpos($pstrDate,'-',$intPos);
   	$strDay=substr($pstrDate,$intPos+1,$intPos1-($intPos+1));
   	$strYear=substr($pstrDate,$intPos1+1) 	;
	}
return $strYear.'-'.$strMonth.'-'.$strDay;
}



?>