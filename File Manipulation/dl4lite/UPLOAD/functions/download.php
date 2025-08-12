<?php

/*********************************************************
 * Name: download.php
 * Author: Dave Conley
 * Contact: realworld@blazefans.com
 * Description: All my funky new functions for making 
 *		sure the user can download files properly
 * Version: 4.00
 * Last edited: 22nd March, 2004
 *********************************************************/

$loader = new downloader();

class downloader
{
	function downloader()
	{
		global $IN;
//		echo "HERE!";
		switch($IN["ACT"])
		{
			case 'dl':
			$this->begin();
			break;
		}
	}

	// Main download function
    function begin()
    {
		global $DB, $CONFIG, $OUTPUT, $IN, $rwdInfo, $std;

		$dlid = intval($IN['id']);
		$std->updateNavDL(" > ".GETLANG("download"), $dlid);
		$result = $DB->query("SELECT * FROM dl_links WHERE did=$dlid");
		if ( !$myrow = $DB->fetch_row($result) )
		{
			$std->error(GETLANG("er_nofile"));
			return;
		}

		// Get real url to file
		if ( $std->isExternalFile($myrow['download']) )
		{
			$dl_url = $myrow["download"];
			$dl_path = "";
		}
		else
		{
			if ( $myrow["maskName"] )
			{
				$dl_url = $rwdInfo->url."/downloads/".$myrow["maskName"];
				$dl_path = $rwdInfo->path."/downloads/".$myrow["maskName"];
			}
			else
			{
				$dl_url = $rwdInfo->url."/downloads/".$myrow["download"];
				$dl_path = $rwdInfo->path."/downloads/".$myrow["download"];
			}
                  if ($CONFIG['hta_user'])
                  {
                      $urlBits[] = parse_url($dl_url);
                      $dl_url = $urlBits[0]['scheme']."://".$CONFIG['hta_user'].":".$CONFIG['hta_pass']."@".$urlBits[0]['host'].$urlBits[0]['path'].$urlBits[0]['query'].$urlBits[0]['fragment'];
                  }
		}

		if ( file_exists( $dl_path ) || $dl_path == "" )
		{
			// If path is blank no need to show this because its an external file
			if ( $dl_path )
			{
				$filename = $myrow["download"];
			}
			else
			{
				preg_match("/([^\/]+\.\w{1,})$/",$myrow["download"],$arMatches);
				$filename = $arMatches[1];
			}
			$filetype = $myrow["fileType"];
			if ( $filetype = "" )
				$filetype = "application/octet-stream";

			if ($dl_path) 
			{   
				// If its a local file file then check we can open it
				if ($CONFIG['hta_user'])
					$fh = @fopen( $dl_url, 'rb' );  // It seems we're going to have to use fopen on a url if we're protecting with .htaccess
				else
					$fh = @fopen( $dl_path, 'rb' ); // path is more reliable 
				if (!$fh)
				{
					if ($CONFIG['hta_user'])
						$std->error(GETLANG("er_fopenfail")."<br>".$dl_url);
					else
						$std->error(GETLANG("er_fopenfail")."<br>".$dl_path);
					@fclose( $fh );
					return;
				}
			}
                  
			// Update download count
			$DB->query("UPDATE dl_links SET downloads=downloads+1 WHERE did=$dlid");
			//echo "Here".$myrow['download']."||".$std->GetFileExtention($myrow['download']);	
			//exit();
			if ($std->GetFileExtention($myrow['download']) == ".swf") 
			{
				$url = $rwdInfo->url."/download/".$myrow['maskName'];
				$out = "<div align='center'>
					        <object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0'>
					          <param name='movie' value='{$url}'>
					          <param name='quality' value='high'>
					          <embed src='{$url}' quality='high' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash' ></embed></object>
					      </div>";
				$OUTPUT->add_output($out);
				return;
				//header("Location: {$rwdInfo->url}/download/{$myrow['download']}");
			}
			else if ($dl_path ) 
			{ // ***** If there is a dl path, then it's on our server...
								
				// Pointless doing this if we dont know the filesize!
				if ($myrow["realsize"] && $CONFIG['partial_transfers'] && isset($_SERVER['HTTP_RANGE'])) 
				{
					// Support for partial transfers enabled and browser requested a partial transfer
					header("HTTP/1.1 206 Partial content\n");
					$start = preg_replace(array("/(\040*|)bytes(\040*|)=(\040*|)/","/(\040*|)\-.*$/"),array("",""),$_SERVER['HTTP_RANGE']);
					if ($myrow["realsize"] < $start) 
					{
							header("HTTP/1.1 411 Length Required\n");
  							echo "Trying to download past the end of the file. You have probably requested the wrong file. Please try again.";
					}
					$transfer_size = $myrow["realsize"] - $start;
					header("Accept-Ranges: bytes");
					header("Content-Range: bytes ".$transfer_size."-".($myrow["realsize"]-1)."/".$myrow["realsize"]);
					header("Content-Length:".$transfer_size."\n");
					fseek($fh,$startat_byte);
				}
				else
				{
					header("HTTP/1.1 200 OK\n");
					if ( $myrow["realsize"] )
						header("Content-Length: ".(string)($myrow["realsize"]) );
				}
				// Print the http header
				header("Cache-control: private\n"); // fix for IE to correctly download files
				header("Pragma: no-cache\n");       // fix for http/1.0
				header("Content-Type: ".$filetype);
				header("Content-Disposition: attachment; filename=".$filename);
				header("Content-Transfer-Encoding: binary");
				
				if ($CONFIG['nopassthrough'])
				{
					if ($CONFIG['speedlimit'] != 0) 
					{
				 		$chunk = $CONFIG['speedlimit'] * 1024;
				 		// Stream our file in chunks
						while(!feof($fh)) 
						{
				  			echo fread($fh, $chunk);
				  			flush();
				  			sleep(1);
				 		}
					} 
					else 
					{
						// File streaming for the impatient
				 		while(!feof($fh)) 
						{
				  			echo fread($fh, 4096);
				 		}
					}
				} 
				else 
				{
					// The old faithful
					fpassthru($fh);
				}
				fclose($fh);
			}
			else
			{
				// External link...
				header("Location: {$myrow['download']}"); // Do a redirect once we've logged everything dude!
		    }
		    exit(); 
		}
		else
			$std->error(GETLANG("er_missinglink")."<br>".$dl_url);
		
    }
}
?>