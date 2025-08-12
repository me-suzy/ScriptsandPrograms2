<?
require_once("_config.inc");

function addlink($name,$email,$title,$url,$recurl,$desc,$lnkxmail) {
	global $config;

  $fp = fopen("links.dat","ab") or msg("Cannot append to links.dat, It requires file mode 777.");
	$crlf = $config["crlf"];
	$contents = "$name|$email|$title|$url|$recurl|$desc|$lnkxmail$crlf";
	fputs($fp,$contents);
	fclose($fp);
}


function dellink($no) {
	global $config;

  $rows=array();
  $fp = fopen("links.dat","rb") or msg("Can not open the links file (links.dat)");
  $content=fread($fp,filesize("links.dat"));
  fclose($fp);
  
  $content = trim(rtrim($content));
  $rows = explode($config["crlf"],$content);
	$linktxt = $rows[$no];
  unset($rows[$no]);
  $rows = array_values($rows);
  
  $fp = fopen("links.dat","wb") or msg("Can not write to the links file (links.dat)");
 	foreach ($rows as $row) {
     $row .= $config["crlf"];
		 fputs($fp,$row);
 	}
  fclose($fp);

  list($name,$email,$title,$url,$recurl,$desc,$lnkxmail)=explode("|",$linktxt);
  msg("Link ($url -- $title) has been deleted successfully");
}


function displink() {
	global $config;
	$crlf = $config["crlf"];

	$html = "";
	$fp = @fopen("links.dat","rb") 
			or msg("Can't open the link file (links.dat) for reading!");
	if (filesize("links.dat")>0) {
  	$contents = fread($fp,filesize("links.dat"));
  	$contents = trim(rtrim($contents));
  	fclose($fp);
  
  	$links = explode($crlf,$contents);
		$isauthor = false;
    foreach ($links as $row)    {
  	  list($name,$email,$title,$url,$recurl,$desc,$lnkxmail)=explode("|",$row);

			$html .= "<li>";
    	$html .= "<a href=\"$url\" target=\"blank\"><b>$title</b></a> - $desc &nbsp;";
			if ($url == base64_decode("aHR0cDovL3Rvcm9udG8uY2l0eXBvc3QuY2E="))
				$isauthor = true;
    }
		if (!$isauthor)
			$html = "ERROR! The first link must be http://toronto.citypost.ca."
				."<a href=http://toronto.citypost.ca/marketing/lnkx>more..</a>";

	}
	else {
		addlink(
			base64_decode("QWxsZW4gS2lt")
			,base64_decode("Y2l0eXBvc3RAY2l0eXBvc3QuY2E=")
			,base64_decode("VG9yb250byBGcmVlIENsYXNzaWZpZWRz")
			,base64_decode("aHR0cDovL3Rvcm9udG8uY2l0eXBvc3QuY2E=")
			,base64_decode("aHR0cDovL3Rvcm9udG8uY2l0eXBvc3QuY2EvbG5reA==")
			,base64_decode("UmVudCwgQXV0bywgQnV5c2VsbCBhbmQgT3BlbiBDb21tdW5pdHkgUG9zdGluZ3MuCUFsc28gcHJvdmlkZXMgbXlzaG9wLCBhdmF0YXIsIGFuZCBhZCBtYW5hZ2VtZW50IGZvciBmcmVlLg==")
			,base64_decode("eWVz")
			);
	}
	print $html;
}

function chklink($url, $recurl, $myurl) {

	//
  // ------ Check if recurl has url
	//
	if ( strpos($recurl,$url) === false )
		return -1;

	//	
  // ------ Check if home has recurl
	//
	if ($url != $recurl) {
    $webfl = @fopen($url, "rb") or msg("Can not open your home ($url) !");
  	$contents = ""; 
    while ($ablock = fread($webfl,1024)) {
  		$contents .= $ablock;
  	}
  
  	$mypos  = strpos($contents,$recurl);
  	if (!$mypos ) 
  		$mypos  = strpos($contents,str_replace($url,"",$recurl));
  	if (!$mypos ) 
  		$mypos  = strpos($contents,str_replace($url."/","",$recurl));
  	
  	if (!$mypos )  
  		return -2;   //home  has no recurl;
	}

	//
  // ------ Check if recurl has my url
	//
  $webfl = @fopen($recurl, "rb") or msg("Can't open your page ($recurl) !");
	$contents=""; 
  while ($ablock = fread($webfl,1024)) {
		$contents .= $ablock;
	}

	$mypos  = strpos($contents,$myurl);
	if (!$mypos) 
		return -3;  //recurl not has myurl

	return 1;
}

function mailme($name,$email,$title,$url,$recurl,$desc,$lnkxmail)  {
	global $config;
	if($config['mailme'] == 1) {
    $message="
        Hi,
        New Link Has been added to the site($config[adminurl]).

        Name: $name ($email)
        Title: $title
        URL: $url
			  Mail Exchange : $lnkxmail
        Description:	$desc
    ";
    $header = "From: LNKX <".$config["adminemail"].">\n";
    $header .= "Reply-To: LNKX <".$config["adminemail"].">\n\n";
    mail($config["adminemail"]
				,"$url Has Been Added To Your Site"
				,$message
				,$header
				);
	}
}

function mailall($name,$email,$title,$url,$recurl,$desc,$lnkxmail)  {
	global $config;

	if ($lnkxmail=="no")
		return;

	$fp = @fopen("links.dat","rb") 
			or msg("Can't open the link file (links.dat) for reading!");
	if (filesize("links.dat")>0) {
  	$contents = fread($fp,filesize("links.dat"));
  	$contents = trim(rtrim($contents));
  	fclose($fp);
  
  	$links = explode($config["crlf"],$contents);
    $i=0;
    foreach ($links as $row)    {
  	  list($rec_name,$rec_email,$t,$u,$r,$d,$rec_lnkxmail)=explode("|",$row);

			if ($rec_lnkxmail == "no")  //if not join mail exchange 
				continue;
			if ($rec_email == $email)   //if sender is receiver
				continue;

      $message="
          Hi,
                    
          This mail is sent by link mail exchange program on $config[adminurl].
          If you do not join this program any more, please reply this saying no.

          New Link Has been added to $config[adminurl].
  
          Name: $name ($email)
          Title: $title
          URL: $url
          Description:	$desc

          Do you want this link exchange program for free?
          visit http://tech.citypost.ca

          Bye.
      ";
      $header =  "From: $config[adminemail]\n";
      $header .= "Reply-To: $config[adminemail]\n\n";
	      mail($rec_email
  				,"[LNKX]$url wants link exchange."
  				,$message
  				,$header
  				);
    }
	}
}

function msg($msg) {
	header("location: message.php?msg=$msg") or print($msg);
	exit();
}
?>