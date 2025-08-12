<?php
session_start(); 
include("../Includes/PortalConection.php");
include("../Includes/Database.php");

$strRootpath= "../";
include_once ("../Includes/validsession.php");

$strFileName= QuerySafeString($_REQUEST["txtFile"]);

$strAction= QuerySafeString($_REQUEST["txtAction"]);
$strErrorMessages="";
//html FILE DELETE CHECK
if ($strAction== "DEL")
{
// check to see if file exists
  // if so, erase it 
  $strTemp=FileUploadPath.$strFileName;
  if (file_exists($strTemp))
  {
	  unlink ($strTemp) or die ("Could not delete file");
  }
  else
  {
  $strErrorMessages.="File does not exist";
  }
}
elseif ($strAction== "MOD")
{
		$strhead= $_REQUEST["txtHTMLHEAD"];
		$strhead=str_replace("/\\/\\/\\",'\"',$strhead);
		
		$strNoBody= $_REQUEST["txtHTMLNoBODY"];
		$strNoBody=str_replace("/\\/\\/\\","\"",$strNoBody);
		
		$strTitle= $_REQUEST["txtTitle"];
		$strBody= $_REQUEST["textarea1"];
		
		$strTemp= strtoupper($strhead);
		//Finds the title string so that it can be shown in the text box
		$intFirst = strpos($strTemp,"<TITLE>");

		if ($intFirst!==FALSE)
		{
			$intLast= strpos($strTemp, "</TITLE>");
			if ($intFirst!==FALSE)
			{	
				$strhead=substr($strhead,0,$intFirst-1) . "<TITLE>" . $strTitle . "</TITLE>" . substr($strhead,$intLast+8);
			}
			
		}

		$strNewHTML = $strhead . $strBody . $strNoBody;
		
		$strTemp=FileUploadPath.$strFileName;
		$strErrorMessages=SaveFile($strTemp,$strNewHTML,"E");
}
else
{
		$strTitle= $_REQUEST["txtTitle"];
		$strBody= $_REQUEST["textarea1"];
		$strNewHTML= "<HTML><HEAD><TITLE>" . $strTitle . "</TITLE></HEAD><BODY>" . $strBody . "</BODY></HTML>";
		$strTemp=FileUploadPath.$strFileName;
		
		$strErrorMessages=SaveFile($strTemp,$strNewHTML,"N");	
}
//}
//exit;
if ($strErrorMessages=="")
{	Redirect ("ListFiles.php");}

print "<HTML><HEAD>";
include_once ("../Includes/Styles.php");
print "</HEAD><BODY>";
print $strErrorMessages;
print "<A HREF=\"ListFiles.php\"> Back to List</A></BODY></HTML>";
?>