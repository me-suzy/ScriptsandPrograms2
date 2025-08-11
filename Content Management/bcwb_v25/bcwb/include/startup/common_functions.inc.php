<?PHP
/************************************************************************/
/* BCWB: Business Card Web Builder                                      */
/* ============================================                         */
/*                                                                      */
/* 	The author of this program code:                                    */
/*  Dmitry Sheiko (sheiko@cmsdevelopment.com)	                    	*/
/* 	Copyright by Dmitry Sheiko											*/
/* 	http://bcwb.cmsdevelopment.com     			                        */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if( !defined("COMMON_FUNCTIONS") ) {
	function send_mail($to_address, $from_address, $subject, $message, $code="windows-1251", $server="") 
	{
		global $HTTP_POST_FILES;
		$message=stripslashes($message);
		if($from_address=="") $from_address="cmsdevelopment.com <info@cmsdevelopment.com>";

	    	$boundary="=_".md5(uniqid(time()));
	    	$headers.="MIME-Version: 1.0\n";
		$headers.="Content-Type: multipart/mixed; boundary=\"$boundary\"\n";
    	$multipart="";
		$multipart.="--$boundary\n";
		$message=trim($message);

	   	$multipart.="Content-Type: text/html; charset=$code\n";
    	$multipart.="Content-Transfer-Encoding: Quot-Printed\n\n";
    	$multipart.="$message\n\n";

    	$mime = "$multipart--$boundary--\n";
		$headers="From: $from_address\nSubject: $subject\nX-Mailer: REDACTOR_Mail\n$headers\n";

		if($server!="")	{
			$fp = fsockopen($server, 25, $GLOBALS[errno], $GLOBALS[errstr], 30);
			if (!$fp)
				 return false;
			fputs($fp,"HELO $server\n");
			fputs($fp,"MAIL FROM: $from_address\n");
			fputs($fp,"RCPT TO: $to_address\n");
			fputs($fp,"DATA\n");
			fputs($fp,$headers);
			if (strlen($headers))
				 fputs($fp,"$headers\n");
			fputs($fp,$mime);
			fputs($fp,"\n.\nQUIT\n");
			while(!feof($fp))
				$resp.=fgets($fp,1024);
			fclose($fp);
			return true;
		} else {
			return @mail($to_address, $subject, $mime, $headers);
		}
	}


function btn($href=false, $title=false, $onclick=false) {
	if($onclick)
	return '<td valign="top" style="cursor:hand" onclick="'.$onclick.'"><IMG SRC="'.$GLOBALS["http_path"].'system/btn_l.gif" WIDTH="10" HEIGHT="30" ALT="" /></td>
			<td valign="middle" align="center" nowrap="nowrap" style="cursor:hand" background="'.$GLOBALS["http_path"].'system/btn_c.gif"><a class="adminarea" href="#" onclick="'.$onclick.'">'.$title.'</a></td>
			<td valign="top" style="cursor:hand" onclick="'.$onclick.'"><IMG SRC="'.$GLOBALS["http_path"].'system/btn_r.gif" WIDTH="10" HEIGHT="30" ALT="" /></td>';
	else	
	return '<td valign="top" style="cursor:hand" onclick="return document.location.href=\''.$href.'\'"><IMG SRC="'.$GLOBALS["http_path"].'system/btn_l.gif" WIDTH="10" HEIGHT="30" ALT="" /></td>
			<td valign="middle" align="center" nowrap="nowrap" style="cursor:hand" background="'.$GLOBALS["http_path"].'system/btn_c.gif"><a class="adminarea" href="'.$href.'">'.$title.'</a></td>
			<td valign="top" style="cursor:hand" onclick="return document.location.href=\''.$href.'\'"><IMG SRC="'.$GLOBALS["http_path"].'system/btn_r.gif" WIDTH="10" HEIGHT="30" ALT="" /></td>';
}

define("COMMON_FUNCTIONS", 1);
}
?>