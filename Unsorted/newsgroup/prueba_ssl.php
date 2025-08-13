<?
/*
if(ftp_ssl_connect("mynewsgroups.sf.net","22")){
	echo "iuju";	
}else{
	echo "nooo";	
}
*/
//$fp=fopen("https://www.sf.net",'r');
//echo $fp;

//$fp=fopen("ftps://mynewsgroups.sf.net",'r');
//echo $fp;

/*
if(ftp_connect("mynewsgroups.sourceforge.net","22")){
	echo "iuju";	
}else{
	echo "nooo";	
}
*/


$fp = fsockopen ("ssl://nntp.sourceforge.net", 563, $errno, $errstr, 30);
if (!$fp) {
    echo "$errstr ($errno)<br>\n";
} else {
    fputs ($fp, "HELO");
    if (!feof($fp)) {
        echo fgets ($fp,128);
    }
    fclose ($fp);
}


?>