<?php

/*
//////////////////////////////////////////////////////////////
//															//
//		Vision Source v0.5 Beta								//
//		Created by Ben Maynard copyright 2005				//		
//		Email: volvorules@gmail.com							//
//		URL: http://www.visionsource.org					//
//		Created: 17th March 2005							//
//															//
//----------------------------------------------------------//
//															//
//		Script: class_skin.php								//
//		written by: Ben Maynard								//
//															//
//////////////////////////////////////////////////////////////
*/

if ( ! defined( 'DIRECT' ) )
{	die("<h1>Access denied</h1>You are not allowed to access this file directly.");
}

class skin {

	var $module_title 	= "";
	var $output			= "";
	var $html			= "";
	var $global_output  = "";
	var $header			= "";
	var $blockhtml		= "";
	var $skinid			= "";
	
	function getskinid()
	{
	  global $vsource, $db, $error, $cms;
	  
	  	//-------------------------------------------------------------------------------------
		//	Make sure this has already been run, and if it has return the cache to save time.
	  	//-------------------------------------------------------------------------------------

		if (!$this->skinid == "")
		{
			return $this->skinid;
		}
		
	  	//-----------------------------------------------------
		//	Find defualt skin, and make sure there is only 1
		//-----------------------------------------------------
		
		$db->query('SELECT * FROM vsource_skin WHERE default_skin="1" AND view="1"');
	    
			if (!$db->number_rows() == "1")
			{
        		$error->msg('More than 1 defualt skin selected.');
			}
      
		$s = $db->fetchrow(); //Store infomation
		$db->freemysql();
		
			//-------------------------------
			//	Find member's skin choice
			//-------------------------------
			
			if ($cms->member['is_member'] == 1)
			{
				
					if ($cms->member['skinid'] == "")
          			{
						$this->skinid = $s['directory'];
            			return $s['directory'];
          			}
          
          			else
          			{
            			$this->skinid = $cms->member['skinid'];
						return $cms->member['skinid'];
          			}
      		}
      
			else
			{
				$this->skinid = $s['directory'];
				return $s['directory'];
			}
			
  }
  
	function load($name)
	{
		global $info, $error;
		
			//---------------------------------
			//	Make sure the skin file exists
			//---------------------------------
			
			if (file_exists('skin/'.$this->getskinid().'/'.$name.'.php'))
			{
				require_once('skin/'.$this->getskinid().'/'.$name.'.php');
				return new $name;
			}
			
			else
			{
				$error->skinerror($this->getskinid(), $name);
				exit();
			}
	}
	
	/* Something old
	function skin_global() 
	{
		global $skinid;
		if (file_exists("skin/$skinid/skin_global.php"))
		{
			require_once("skin/$skinid/skin_global.php");
			$this->html	 		 = new skin_global;
			$this->globaloutput .= $this->html->tophead($this->module_title);
			$this->globaloutput .= $this->html->mainarea();
		}
		
		else
		{
			$this->output .= 'error loading global skin';
		}
	}*/
	
	function do_output($output)
	{
		//--------------------------------------
		//	Storing the html for output later
		//--------------------------------------
		
		$this->output .= $output;
	}
	
	function do_title($mod_title)
	{
		//----------------
		//	Adding title
		//----------------
		
		$this->module_title .= ' -> '.$mod_title;
	}
	
	function setheader($url)
	{
	
		//-----------------------------
		// Set the header to redirect
		//-----------------------------
		
		$this->header  = @header("Location: $url");
		$this->output  = "<meta http-equiv='Refresh: 3;$url' />You are beeing redirected in 3 seconds.
		<a href='$url'>Please click here if you are not redirected</a>";
	}
	
	function setheader_http($url)
	{
	
		//-----------------------------------------------------------------
		// Set the header to redirect and add http:// infront of the url
		//-----------------------------------------------------------------
		
		$url 		   = preg_replace('(http://)', '', $url);
		$this->header  = @header("Location: http://$url");
		$this->output  = "<meta http-equiv='Refresh: 3;$url' />You are beeing redirected in 3 seconds.
		<a href='$url'>Please click here if you are not redirected</a>";
	}
	
	function get_css()
	{
		global $info, $error, $cms;
		
			if (file_exists('skin/'.$this->getskinid().'/style.css'))
			{
				$css = $info['base_url'].'/skin/'.$this->getskinid().'/style.css';
			}
			
			else
			{
				$error->msg('Unable to load css file.');
			}
			
		return $css;
	}
	
	function redirect($text, $url)
	{
		global $info;
		
			//-------------------------------------------------
			//	Redirect page (used for login, logout etc.)
			//-------------------------------------------------
	
			$css = $this->get_css();
			$url = "{$info['base_url']}/".$url;
			$this->html   = $this->load('skin_global');
			$this->output = $this->html->redirect($text, $url, $css);
			print $this->output;
			exit();
	}
	
	function blocks($html)
	{
		//------------------------------------------------------
		// Adding block html (still under heaver construction)
		//------------------------------------------------------
		
		$this->blockhtml .= '<p>'.$html.'</p>';
	}
	
	function compile_page()
	{
		global $info;
		
			//-----------------------
			// Load the global skin
			//-----------------------
			
			if (file_exists('skin/'.$this->getskinid().'/skin_global.php'))
			{
				require_once('skin/'.$this->getskinid().'/skin_global.php');
				$this->html = new skin_global;
			}
			
			else
			{
				$error->skinerror($this->getskinid(), 'skin_global');
			}
			
			//---------------------------
			// Check for any redirection
			//---------------------------
		
			if (strlen($this->header < 0))
			{
				$this->header;
				print $this->output;
			}
			
			//----------------------------
			// Finally compile the page
			//----------------------------
			
			else
			{
				print $this->html->tophead($this->module_title, $this->get_css());
				print $this->html->mainarea();
				print $this->blockhtml;
				print $this->html->nav();
				//print $this->globaloutput;
				print $this->output;
			}
	}
	
}


?>
