<?
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	PHP FXP 3.0
	by Harald Meyer (webmaster@harrym.nu) 
	Feel free to use it, but don't delete these lines. 
	PHP FXP is Freeware but all links to the PHP FXP homepage must not be deleted! 
	If you want to use PHP FXP COMMERCIAL please contact me.
	Please send me modified versions! 
	If you use it on your page, a link back to its homepage (to PHP FXP's homepage) 
	would be highly appreciated.
	Homepage: http://fxp.harrym.nu
 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

 /* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	File: functions.inc.php
	Description: -
	Last update: 28-09-2002
	Created by: Harald Meyer
 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

//Save data files
function phpfxp_savetotxt($ttext,$ffile)
{	
	GLOBAL $path;

	$tmpp=$ffile;
	$fn=fopen($tmpp, "w");
	fwrite($fn,$ttext);
	fclose($fn);
} 
// end phpfxp_savetotxt

/**
 * get filesize
 *
 * @param	  INTEGER  
 * @since     1.0
 * @return    BYTES
 * @update    25-09-2002
 */
function phpfxp_getfilesize($f_name)
{
    $fd = fopen($f_name, "r");
	$i=count($http_response_header);
	$ii=0;
	$phpfxp_fs=1;
	while ($ii<=$i) 
		{
		if (eregi("Content-length:",$http_response_header[$ii])) {
		   $phpfxp_fs=$http_response_header[$ii];
		   $phpfxp_fs=trim(eregi_replace("Content-length:","",$phpfxp_fs));     
		   break;
	    }
	    $ii++;
	}    
	fclose($fd);
	return($phpfxp_fs);
} // end phpfxp_getfilesize 

/**
 * calculate buffer size
 *
 * @param	  BYTE  
 * @since     1.0
 * @return    BYTE
 * @update    25-09-2002
 */
function phpfxp_calcbuffer($f_size)
{
	if ($f_size<4096) {
	    $ii=$f_size;
	}
	else
	{
		$ii=4096;
	}
	
    for ($i=$ii;$i>0;$i--) {
        if (($f_size%$i)==0) {
			return($i);
            break;
		}
	}
	return($i);
} // end phpfxp_calcbuffer

/**
 * general transfer function

 * @param     url|to url|"ftp","file"|true/false|FTP_BINARY,FTP_ASCII|INTEGER|"ftp","file"
 * @since     1.0
 * @return    boolean
 * @update    25-09-2002
 */
function transfer_data($sourceurl,$desturl,$transfertype,$pasive,$mode,$buffer,$utransfertype,$upasive)
{
	GLOBAL $storepath;
	GLOBAL $msg;
	GLOBAL $delcached;
	GLOBAL $mirrordir;
	
	$result=true;

	//parse urls
	$surl=parse_url($sourceurl);
	$spath=pathinfo($surl["path"]);
	$durl=parse_url($desturl);
	$dpath=pathinfo($durl["path"]);
	
	//change invalid values
	if ($surl["user"]==NULL) {
	    $surl["user"]="anonymous";
	}
	if ($durl["user"]==NULL) {
	    $durl["user"]="anonymous";
	}

	//NO FTP functions for non-ftp servers!
	if (($surl["scheme"]=="http")OR($surl["scheme"]=="https")) {
	    $transfertype="file";
	}
	if (($durl["scheme"]=="http")OR($durl["scheme"]=="https")OR($durl["scheme"]==NULL)) {
	    $utransfertype="file";
	}
	
	//Binnary for file
	if ($mode==FTP_BINARY) {
	    $modex="b";
	}
	else{
		$modex="";
	}

	//Download file
	if ($surl["scheme"]!=NULL) {
		switch ($transfertype) {
			case "ftp":
				//connect to ftp server
				if (!$conn_id = ftp_connect($surl["host"],$surl["port"])) {
				    $msg.="<font color=red>Error while connecting $sourceurl!</font><br>";
					$result=false;
				}
				else{
					$msg.="Successfully connected to $sourceurl!<br>";
				}
				//set pasive? mode
				if (!@ftp_pasv($conn_id,$pasive)) {
				    $msg.="<font color=red>Error while changing pasive mode!</font><br>";
					$result=false;
				}
				else{
					$msg.="Successfully changed pasive mode!<br>";
				}
				//log in
				if (!ftp_login($conn_id, $surl["user"], $surl["pass"])) {
				    $msg.="<font color=red>Error while logging in!</font><br>";
					$result=false;
				}
				else{
					$msg.="Successfully logged in!<br>";
				}
				//change directory
				if (!ftp_chdir($conn_id, $spath["dirname"])) {
				    $msg.="<font color=red>Error while changing directory to ".$spath["dirname"]."!</font><br>";
					$result=false;
				}
				else{
					$msg.="Successfully changed directory to: ".$spath["dirname"]."!<br>";
				}
				//download file
				if (!ftp_get($conn_id, $storepath.$spath["basename"], $spath["basename"] , $mode)) {
				    $msg.="<font color=red>Error while downloading file ".$spath["basename"]."!</font><br>";
					$result=false;
				}
				else{
					$msg.="Successfully downloaded ".$spath["basename"]."!<br>";
				}
				//close connection
				if (!ftp_quit($conn_id)) {
				    $msg.="<font color=red>Error while closing ftp session!</font><br>";
					$result=false;
				}
				else{
					$msg.="Successfully closed ftp session!<br>";
				}
			break;//ftp
			case "file":
				if ((trim($buffer)=="AUTO")) {
				   $buffer=phpfxp_calcbuffer(phpfxp_getfilesize($sourceurl));
				}
				//open source file
				if (!$fs = fopen($sourceurl, "r$modex")) {
				    $msg.="<font color=red>Error while connecting to \"sourceurl\"!</font><br>";
					$result=false;
				}
				else{
					$msg.="Successfully opened connction to \"sourceurl\"!<br>";
				}
				//open file to write to
				if (!$fd = fopen($storepath.$spath["basename"], "w$modex")) {
				    $msg.="<font color=red>Error while creating temporary file!</font><br>";
					$result=false;
				}
				else{
					$msg.="Successfully created temporary file!<br>";
				}
				
				//download file
				while (!feof($fs)){
					$bufferx = fread($fs,$buffer);
					fwrite($fd,$bufferx);
				}

				fclose($fs);
				fclose($fd);
			break;
		}
	}//end "scheme"==NULL
	
	if ($durl["scheme"]=="http") {
	    $msg.="<font color=red>Cannot upload file to http server!</font><br>";
		$desturl="";
		$result=false;
	}

	//upload file
	if ($desturl!=NULL) {
	    switch ($utransfertype) {
	        case "ftp":
				//connect to ftp server
				if (!$conn_id = ftp_connect($durl["host"],$durl["port"])) {
				    $msg.="<font color=red>Error while connecting $desturl!</font><br>";
					$result=false;
				}
				else{
					$msg.="Successfully connected to $desturl!<br>";
				}
				//set pasive? mode
				if (!@ftp_pasv($conn_id,$upasive)) {
				    $msg.="<font color=red>Error while changing pasive mode!</font><br>";
					$result=false;
				}
				else{
					$msg.="Successfully changed pasive mode!<br>";
				}
				//log in
				if (!ftp_login($conn_id, $durl["user"], $durl["pass"])) {
				    $msg.="<font color=red>Error while logging in!</font><br>";
					$result=false;
				}
				else{
					$msg.="Successfully logged in!<br>";
				}
				//make directory
				$xpath=$dpath["dirname"].$spath["dirname"];
				if (!ftp_mkdir($conn_id,$xpath)OR(!$mirrordir)) {
				    $xpath=$dpath["dirname"];
				}
				
				//change directory
				if (!ftp_chdir($conn_id, $xpath)) {
				    $msg.="<font color=red>Error while changing directory to ".$xpath."!</font><br>";
					$result=false;
				}
				else{
					$msg.="Successfully changed directory to: ".$xpath."!<br>";
				}

				//upload file
				if (!ftp_put($conn_id, $spath["basename"], $storepath.$spath["basename"], $mode)) {
				    $msg.="<font color=red>Error while uploading file ".$spath["basename"]."!</font><br>";
					$result=false;
				}
				else{
					$msg.="Successfully uploaded ".$spath["basename"]."!<br>";
				}
				//close connection
				if (!ftp_quit($conn_id)) {
				    $msg.="<font color=red>Error while closing ftp session!</font><br>";
					$result=false;
				}
				else{
					$msg.="Successfully closed ftp session!<br>";
				}
	        break; //ftp
			case "file":
				if ((trim($buffer)=="AUTO")) {
				   $buffer=phpfxp_calcbuffer(phpfxp_getfilesize($sourceurl));
				}
				//open source file
				if (!$fs = fopen($storepath.$spath["basename"], "r$modex")) {
				    $msg.="<font color=red>Error while opening temporary file!</font><br>";
					$result=false;
				}
				else{
					$msg.="Successfully opened temporary file!<br>";
				}
				//open file to write to
				if (!$fd = fopen($desturl, "w$modex")) {
				    $msg.="<font color=red>Error while creating temporary file!</font><br>";
					$result=false;
				}
				else{
					$msg.="Successfully created temporary file!<br>";
				}
				
				//download file
				while (!feof($fs)){
					$bufferx = fread($fs,$buffer);
					fwrite($fd,$bufferx);
				}

				fclose($fs);
				fclose($fd);
	        break; //file
	    }
	}//$desturl!=NULL
	
	//delete cached file
	if ($delcached) {
		if (!unlink($storepath.$spath["basename"])) {
		   $msg.="<font color=red>Error while deleting temporary file!</font><br>";
			$result=false;
		}
		else{
			$msg.="Temporary file successfully deleted!<br>";
		}
	}//delcached

	//Return
	Return $result;
} // end download_data()


function glue_url($parsed) { 

	if (! is_array($parsed)) return false;

	if (isset($parsed['scheme'])) {
		$sep = (strtolower($parsed['scheme']) == 'mailto' ? ':' : '://');
		$uri = $parsed['scheme'] . $sep;
	} else {
		$uri = '';
	}

	if ($parsed['pass']!=NULL) {
		$uri .= "$parsed[user]:$parsed[pass]@";
	} elseif ($parsed['user']!=NULL) {
		$uri .= "$parsed[user]@";
	}

	if ($parsed['host']!=NULL) $uri .= $parsed['host'];
	if ($parsed['port']!=NULL) $uri .= ":$parsed[port]";
	if ($parsed['path']!=NULL) $uri .= $parsed['path'];
	if ($parsed['query']!=NULL) $uri .= "?$parsed[query]";
	if ($parsed['fragment']!=NULL) $uri .= "#$parsed[fragment]"; 

	Return $uri; 
}//glue_url

/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	END
 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
?>