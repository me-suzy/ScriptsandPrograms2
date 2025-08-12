<?
session_start();
include "_config.inc";
include "lnkx_functions.php";

$req = "";
if ($_REQUEST["add"]) $req = "add";
if ($_REQUEST["del"]) $req = "del";

if ($_REQUEST["admin"]) $req = "admin";
if ($_REQUEST["listall"]) $req = "listall";
if ($_REQUEST["chkall"]) $req = "chkall";
if ($_REQUEST["logout"]) $req = "logout";

$msg = "";
switch($req) {
	case "add" :
      $name   = $_REQUEST["name"]   or msg("name is required");
      $email  = $_REQUEST["email"]  or msg("email is required");
      $title  = $_REQUEST["title"]  or msg("title is required");
      $url    = $_REQUEST["url"]    or msg("url is required");
      $recurl = $_REQUEST["recurl"] or msg("your link page back to us is necessary");
      $desc   = $_REQUEST["desc"]   or msg("descriptin is required");
      $lnkxmail   = $_REQUEST["lnkxmail"]   or msg("Answer for receiving mail is required");

      if ($_SESSION[adminmode]) {
     		addlink($name,$email,$title,$url,$recurl,$desc,$lnkxmail);
   			$msg = "URL ($url) has been added by the admin.";
      }
      else {
   			$rc = chklink($url, $recurl, $config["adminurl"]);
   			if ($rc== 0) msg("Unexpected Error Occurred");
   			if ($rc==-1) msg("Your link page($recurl) must be in $url");
   			if ($rc==-2) msg("Your link page($recurl) is not linked by ($url)");
   			if ($rc==-3) msg("Your link page does not have ".$config["adminurl"]);
   
     		addlink($name,$email,$title,$url,$recurl,$desc,$lnkxmail);
   			mailme($name,$email,$title,$url,$recurl,$desc,$lnkxmail);
   			mailall($name,$email,$title,$url,$recurl,$desc,$lnkxmail);
   			$msg = "Your URL has been added";
      }
		break;

	case "del" :
		dellink($_REQUEST["del"]);
		break;

	case "admin" :
    if (!$_REQUEST["adminpass"]) 
			msg("password is required");
		if ($_REQUEST["adminpass"] == $config["adminpass"]) {
			$_SESSION["adminmode"]=1;
			$msg = "Admininstator Mode Is Available.<a href=free_link_exchange.php>Start Admin Job</a>";
		}
		else {
			$msg = "Invalid Password";
			$_SESSION["adminmode"]="";			
		}
		break;

	case "logout" :
		$_SESSION["adminmode"]="";
		header("location: ".$_SERVER["HTTP_REFERER"]);
		exit();
		break;

	case "chkall" :
     $rows=array();
     $fp = fopen("links.dat","rb") or msg("Can not open the links file (links.dat)");
     $content=fread($fp,filesize("links.dat"));
     fclose($fp);

     $rows = explode($config["crlf"],$content);

		 include "_header.inc";
		 print "<ol>";
     foreach ($rows as $row) {
   	 		list($name,$email,$title,$url,$recurl,$desc,$lnkxmail)=explode("|",$row);
				if (!$url)
					continue;

				print "<li><b>$url</b> &nbsp; $title ----- $name($email)<br>";
				print "    <ul>";

				$rc = chklink($url, $recurl, $config["adminurl"]);
				if      (strpos($url,"citypost.ca")>0) 
					print "<li>Good ! It is the program default link";
				else if ($rc==1) 
					print "<li>Good ! Well linked to $config[adminurl]";
				else if ($rc==-1) 
					print "<li style='color:red'>Link page ($recurl) is not in $url";
				else if ($rc==-2) 
					print "<li style='color:red'>Link page ($recurl) is not linked by ($url). Is your home redirected?";
				else if ($rc==-3) 
					print "<li style='color:red'>Link Page ($recurl) does not have ".$config["adminurl"];
				else 
					print "<li style='color:red'>Cannot Check Link. Possibly Program Error!!";
				print "   </ul>";
		 }
		 print "</ol>";
		 include "_footer.inc";
		 exit(0);
		break;

	case "listall" :
     $rows=array();
     $fp = fopen("links.dat","rb") or msg("Can not open the links file (links.dat)");
     $content=fread($fp,filesize("links.dat"));
     fclose($fp);

     $rows = explode($config["crlf"],$content);

		 include "_header.inc";
		 print "<table border=1 width=100%>
						<tr>
 							<th>Action<th>name<th>email
							<th>
								<table><tr><th>title<tr><th>URL<tr><th>Link Page</table>
							<th>Description<th>Mail	";
		 $i=0;
     foreach ($rows as $row) {
	 	 		list($name,$email,$title,$url,$recurl,$desc,$lnkxmail)=explode("|",$row);
				if (!$url)
					continue;
				
				print "<tr>
 							<td><a href=\"?del=$i\"><font color=red>del</font></a>
							<td>$name<td>$email
							<td>
								<table><tr><td>$title<tr><td>$url<tr><td>$recurl</table>
							<td>$desc<td>$lnkxmail";
     		$i++;
		 }
		 print "</table>";
		 include "_footer.inc";
		 exit(0);
		break;

	default: 
		msg("invalid page call");
}

msg($msg)
?>