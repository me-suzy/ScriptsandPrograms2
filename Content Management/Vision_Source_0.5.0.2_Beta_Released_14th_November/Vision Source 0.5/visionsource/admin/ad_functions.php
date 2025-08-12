<?php

/*
//////////////////////////////////////////////////////////////
//															//
//		YourCMS v0.5 Beta									//
//		Created by Ben Maynard copyright 2005				//		
//		Email: volvorules@gmail.com							//
//		URL: http://www.visionsource.org					//
//		Created: 20th July 2005								//
//															//
//----------------------------------------------------------//
//															//
//		Script: ad_functions.php							//
//		written by: Ben Maynard								//
//															//
//////////////////////////////////////////////////////////////
*/

class ad_func
{

	var $html 		  = '';
	var $output 	  = '';
	var $globaloutput = '';
	var $mod_title	  = '';
	var $title 		  = 'Vision Source ACP';
	
	function load($name)
	{
		global $ad_info;
			if (file_exists("skin_admin/{$ad_info->skin_id}/$name.php"))
			{
				require_once("skin_admin/vsource/$name.php");
				return new $name;
			}
			else
			{
				echo "error loading skin";
				exit();
			}
	}
	
	function get_css()
	{
		global $info;
			//if (file_exists($info['base_url'].'/skin/'.$info['skin_id'].'/style.css'))
			//{
				$css = $info['base_url'].'/skin/'.$info['skin_id'].'/style.css';
			/*}
			else
			{
				echo $info['base_url'].'/skin/'.$info['skin_id'].'/style.css';
				exit();
			}*/
			
		return $css;
	}
	
	function redirect($text, $url)
	{
		global $info;
		$css = $this->get_css();
		$url = "{$info['base_url']}/".$url;
		$this->html   = $this->load('skin_global');
		$this->output = $this->html->redirect($text, $url, $css);
		print $this->output;
		exit();
	}
	
	function do_output($output)
	{
		print $output;
	}

	function compile_page()
	{

		global $ad_info;
			if (file_exists("skin_admin/{$ad_info->skin_id}/skin_global.php"))
			{
				require_once("skin_admin/{$ad_info->skin_id}/skin_global.php");
				$this->html = new skin_global;
			}
			
			else
			{
				$this->output = 'Could not load skin';
				return;
			}
				$sesid = $_GET['ses'];
				$this->globaloutput .= $this->html->frame($this->title, $sesid);
				print $this->globaloutput;
	}
	
}
?>