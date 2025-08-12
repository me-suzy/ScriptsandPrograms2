<?php

/*
//////////////////////////////////////////////////////////////
//															//
//		Vision Source v0.5 Beta								//
//		Created by Ben Maynard copyright 2005				//		
//		Email: volvorules@gmail.com							//
//		URL: http://www.visionsource.org					//
//		Created: 2nd July 2005								//
//															//
//----------------------------------------------------------//
//															//
//		Script: error_handler.php							//
//		written by: Ben Maynard								//
//															//
//////////////////////////////////////////////////////////////
*/

class error_handler
{
	
	function sqlerror($error, $thequery)
	{
		$disp_error = "<html><head><title>Vision Source SQL Error</title>
		               <style>body { font-family: Verdana, Tahoma, Arial, sans-serif; font-size: 11px; margin: 20px; }</style></head>
					   <body>
					   <b>An SQL Error has occurred.</b> <br />
					   <p>
					   <blockquote><b>The SQL Error was:</b> <br />
					   <span style='font-size: 12px;'>MYSQL Query: ".htmlspecialchars($thequery)." <br /> <br />
					   MYSQL Error: ".htmlspecialchars($error)." <br />
					   Date: ".date('l dS of F Y h:i:s A')."</span></blockquote>
					   </p> <br /> <br />
					   We apologise for any inconvenience.
					   </body></head></html>";
					   
		echo $disp_error;
		exit();
	}
	
	function skinerror($skinid, $skinfile)
	{
		global $info;
		$disp_error = "<html><head><title>Vision Source Skin Error</title>
		               <style>body { font-family: Verdana, Tahoma, Arial, sans-serif; font-size: 11px; margin: 20px; }</style></head>
					   <body>
					   <b>Vision Source is unable to load the skin.</b> <br />
					   <p>
					   <blockquote><b>Details:</b> <br />
					   <span style='font-size: 12px;'>Skin File: ".htmlspecialchars($skinfile).".php <br />
					   Location: {$info['base_url']}/skin/".htmlspecialchars($skinid)."/".htmlspecialchars($skinfile).".php <br />
					   Date: ".date('l dS of F Y h:i:s A')."</span></blockquote>
					   </p> <br /> <br />
					   We apologise for any inconvenience.
					   </body></head></html>";
					   
		echo $disp_error;
		exit();
	}
	
	function msg($error)
	{
		global $info;
		$disp_error = "<html><head><title>Vision Source Error</title>
		               <style>body { font-family: Verdana, Tahoma, Arial, sans-serif; font-size: 11px; margin: 20px; }</style></head>
					   <body>
					   <b>Im sorry, an error has occurred</b> <br />
					   <p>
					   <blockquote><b>Details:</b> <br />
					   <span style='font-size: 12px;'>Error Message: ".htmlspecialchars($error)." <br />
					   Date: ".date('l dS of F Y h:i:s A')."</span></blockquote>
					   </p> <br /> <br />
					   We apologise for any inconvenience.
					   </body></head></html>";
					   
		echo $disp_error;
		exit();
	}
	
	function error($error, $back=true)
	{
		$disp_error = "<div class='error'><b>Im sorry an error has occurred</b>. The error message was:
						<p>
						{$error}
						</p>";
						
						if ($back == true)
						{
							$disp_error .= "<p><a href='javascript:back(-1)'>Click here to go back</a></p>";
						}
						
		$disp_error .= "</div>";
		return $disp_error;
	}

}
	
?>