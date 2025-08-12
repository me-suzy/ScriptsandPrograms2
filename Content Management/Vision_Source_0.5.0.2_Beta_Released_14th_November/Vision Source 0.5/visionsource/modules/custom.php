<?
/*
//////////////////////////////////////////////////////////////
//															//
//		Vision Source v0.5 Beta								//
//		Created by Ben Maynard copyright 2005				//		
//		Email: volvorules@gmail.com							//
//		URL: http://www.visionsource.org					//
//		Created: 3rd March 2005								//
//															//
//----------------------------------------------------------//
//															//
//		Script: about.php									//
//		written by: Ben Maynard								//
//															//
//////////////////////////////////////////////////////////////
*/

if ( ! defined( 'DIRECT' ) )
{	die("<h1>Access denied</h1>You are not allowed to access this file directly.");
}

//Start the class
class custom {

	var $output = "";
	var $html 	= "";
	
	//--------------------------
	//	Set up all the pages
	//--------------------------
	
	function pages() {
		global $skin;
		
		$this->html = $skin->load('skin_custom');
		$do = !empty($_GET['do']) ? $_GET['do'] : FALSE;
		
			switch ($do)
			{
				case "main":
					$this->home();
				break;
				default:
					$this->home();
				break;
			}
			
		$skin->do_output("$this->output");
	}
	
	
	//------------------------------
	//	Lets do all the functions
	//------------------------------
	
	function home()
	{
	  global $db, $error, $skin, $cms;
	  
	  	$do = !empty($_GET['page']) ? $_GET['page'] : FALSE;
		
			if ($do == FALSE)
			{
				$this->output .= $this->html->custom_home();
				
				$query = 'SELECT title, mem_only, view, pageid FROM vsource_custom WHERE view="1" ';
					
					if ($cms->member['is_member'] == 0)
					{
						$query .= 'AND mem_only="0"';
					}
					
				$db->query($query);
				
				
					if ($db->number_rows() == 0)
					{
						$this->output .= $this->html->no_pages();
						return;
					}
					
					else
					{
						$this->output .= $this->html->start_ul();
						
							while ($row = $db->fetchrow())
							{
								$this->output .= $this->html->list_pages($row);
							}
							
						$this->output .= $this->html->end_ul();
					}
			}
			
			else
			{
				$page = $db->check_input($do);
				
					$db->query('SELECT * FROM vsource_custom WHERE pageid="'.$page.'" AND view="1"');
					
						if ($db->number_rows() == 1)
						{
							$row = $db->fetchrow();
							
								if ($row['mem_only'] == 1)
								{
									if ($cms->member['is_member'] == 0)
									{
										$this->output .= $error->error('Im sorry, This page is only viewable for members only.', $back = true);
										return;
									}
								}
							
								if (strlen($row['title']) > 0)
								{
									$skin->do_title($row['title']);
								}
								
							$this->output .= $this->html->showpage($row);
						}
						
						else
						{
							$this->output .= $error->error('Im sorry, I could not find the page you specified. Please try again.', $back = true);
							return;
						}
			}
						
	}
}
 

?>