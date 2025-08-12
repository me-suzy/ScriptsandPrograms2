<?php

/*
//////////////////////////////////////////////////////////////
//															//
//		Vision Source v0.5 Beta								//
//		Created by Ben Maynard copyright 2005				//		
//		Email: volvorules@gmail.com							//
//		URL: http://www.visionsource.org					//
//		Created: 11th April 2005							//
//															//
//----------------------------------------------------------//
//															//
//		Script: links.php									//
//		written by: Ben Maynard								//
//															//
//////////////////////////////////////////////////////////////
*/

if ( ! defined( 'DIRECT' ) )
{
	die("<h1>Access denied</h1>You are not allowed to access this file directly.");
}

class links {

	var	$output = "";
	var $html = "";

	function pages()
	{
		global $skin;
		
		$this->html = $skin->load('skin_links');
		$skin->do_title('Links');
		
		$do = !empty($_GET['do']) ? $_GET['do'] : FALSE;
		switch ($do)
		{
				case "1":
					$this->cat();
				break;
				case "2":
					$this->goto();
				break;
				case "3":
					$this->home();
				break;
				default:
					$this->home();
				break;
		}
				
		$skin->do_output("$this->output");
	}
	 

			//Just set up to show links if a cat has been selected.
			function cat() {
			
				global $db, $skin;
					$catid			= !empty($_GET['catid']) ? intval($_GET['catid']) : FALSE;
					//$cat1			= $_GET['cat'];
					$db->query('SELECT about, cat FROM vsource_links_cat WHERE id="'.$catid.'"');
					$row			= $db->fetchrow();
					$about			= nl2br($row['about']);
					$cat			= $row['cat'];
						if ($db->number_rows() == "1")
						{
							switch ($catid)
							{
								case $catid:
									$this->output .= $this->html->showcatinfo($about, $cat);
								break;
								default:
									$this->output .= $this->html->error();
									return;
								break;
							}
						}
						
						else
						{
							$this->output .= $this->html->error();
							return;
						}
						
					$db->freemysql();
				
				//Time to show all the links!
					if ($catid) {
						$db->query('SELECT * FROM vsource_links WHERE catid="'.$catid.'"');
							if ($db->number_rows() == "0")
							{
								$this->output .= $this->html->error();
								return;
							}
							
							else
							{
								while($row = $db->fetchrow())
								{
									$name	= $row['name'];
									$link	= $row['link'];
									$hits	= $row['hits'];
									$id		= $row['id'];
						
									$this->output .= $this->html->link($id, $name, $hits);  
								}
							} 
					}
					
					$db->query('SELECT cat FROM vsource_links_cat WHERE id="'.$catid.'"');
					$row = $db->fetchrow();				  
					$this->output .= $this->html->goback();
					$db->freemysql();
					$skin->do_title($row['cat']);
			
			}
			
			function goto() {
			
				global $db, $skin;	
				$goto 		 = !empty($_GET['goto']) ? $_GET['goto'] : $this->output .= $this->html->error();
				$id			 = $_GET['goto'];
				$db->query('SELECT link FROM vsource_links WHERE id="'.$id.'"');
				$row		 = $db->fetchrow();
				$db->freemysql();
				$link		 = $row['link'];
					if ($goto)
					{
						switch ($goto)
						{
								case $id:
									$db->query('UPDATE vsource_links SET hits=(hits + 1) WHERE id="'.$id.'"');
									$skin->setheader_http($link);
								break;
								default:
									$this->output .= $this->html->error();
								break;
						}				
					
					}	
			
			}
			
			function home() {
				global $db;
							
			//No cat selected? display main page.
				$this->output .= $this->html->hometop();
			
			//Show all the cat's.
				$db->query('SELECT id, cat FROM vsource_links_cat');
					while($row = $db->fetchrow())
					{
						$catid	= $row['id'];
						$cat	= $row['cat'];
						$this->output .= $this->html->showcat($catid, $cat);
					}	
			
					$this->output .= $this->html->endcat();
					$db->freemysql();
			
			}
}

?>