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
//		Script: news.php									//
//		written by: Ben Maynard								//
//															//
//////////////////////////////////////////////////////////////
*/

if ( ! defined( 'DIRECT' ) )
{	die("<h1>Access denied</h1>You are not allowed to access this file directly.");
}

//Start the class
class news {

	var	$output = "";
	var $html = "";

	//--------------------------
	//	Set up all the pages
	//--------------------------
	
	function pages() {
		global $skin;
		
		$this->html = $skin->load('skin_news');
		$skin->do_title('News');
		$do = !empty($_GET['do']) ? $_GET['do'] : FALSE;
		switch ($do) {
				case "1":
					$this->home();
				break;
				case "2":
					$this->item();
				break;
				case "3":
					$this->add_comment();
				break;
				case "4":
					$this->delete_comment();
				break;
				case "5":
					$this->showarchive();
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
	  global $db, $skin, $info;
	
	//-----------------------
	//	Show news items
	//-----------------------
	
	//	$start = intval($_GET['start']);
		$this->output .= $this->html->newstop();
		
	/* Stuff for pages, When i complete it.
	 
		$db->query('SELECT * FROM vsource_news');
		$total_results 	= $db->number_rows();
		$max_results 	= $info['news_limit'];
		$total_pages 	= ceil($total_results/$max_results);
		echo ($total_results);
		echo ($total_pages);
		
			if ($total_pages == 1)
			{
				$pages = '';
			}
			
			else
			{
				echo ($max_results);
				for ($i = 1; $i <= $total_results; $i++)
				{
					$pages .= '<a href="'.$info['base_url'].'/index.php?id=news&amp;do=1&amp;start='.$i.'">'.$i.'</a> ';
				}
			}
			
		
			if (!empty($start))
			{
				$maxnum = $start + $info['news_limit'];
				$db->query('SELECT * FROM vsource_news LIMIT ' . $start . ',' . $maxnum );
				
					while ($row = $db->fetchrow())
					{
						$this->output .= $this->html->shownews($row);
					}
			}
			
			else
			{
			*/
				$db->query('SELECT * FROM vsource_news ORDER BY id DESC LIMIT ' . $info['news_limit']);
				
					while ($row = $db->fetchrow())
					{
						$this->output .= $this->html->shownews($row);
					}
			//}
			
		$this->output .= $this->html->showarchive();
	   
	    $db->freemysql();
 
	}
	
	function item()
	{
	  global $db, $skin, $error, $cms, $info;
		
		//-----------------------
		//	Check for input
		//-----------------------
		
		if (empty($_GET['item']))
		{
			$this->output .= $error->error('Im sorry, we could not locate that article.');
			$skin->do_title('Error');
			return;
		}
		
		$item	= !empty($_GET['item']) ? $_GET['item'] : $this->output .= $this->html->error();
		$id 	= $_GET['item'];
		
			if (ctype_digit($id))
			{
				$db->query('SELECT * FROM vsource_news WHERE id='.intval($id).'');
				$row  = $db->fetchrow();
				
				switch ($item)
				{
					case $id:
						if ($db->number_rows() == 1)
						{
							$this->output .= $this->html->showarticle($row);
							$db->freemysql();
							
							//----------------------
							//  Show comments
							//----------------------
							
							$db->query('SELECT * FROM vsource_comments WHERE newsid='.intval($id));
							
								if ($db->number_rows() == 0)
								{
									$this->output .= "<p>No comments added. So be the first to add a comment!!</p>";
								}
								
								else
								{
									while ($com = $db->fetchrow())
									{
										if ($cms->member['is_admin'] == 1)
										{
											$commentid = $com['id'];
											$delete = '<a href='.$info['base_url'].'/index.php?id=news&amp;do=4&amp;item='.$commentid.'>Delete</a>';
											$this->output .= $this->html->showcomments($com, $delete);
										}
										
										else
										{
											$this->output .= $this->html->showcomments($com);
										}
									}
								}
								
							$this->output .= $this->html->postcomment(intval($id));
							$skin->do_title($row['newstitle']);
						}
						
						else 
						{
							$this->output .= $error->error('Im sorry, we could not locate that article.');
							$skin->do_title('Error');
						}
					
					break;
					
					//--------------------------------------
					//	If invalid input, GIVE EM ERRORS!!
					//--------------------------------------
					
					default:
						$this->output .= $error->error('Im sorry, we could not locate that article.');
						$skin->do_title('Error');
					break;
				}
			}
			
			else
			{
				$this->output .= $error->error('Im sorry, we could not locate that article.');
				$skin->do_title('Error');
			}
			
		$db->freemysql();
		
	}
	
	function add_comment()
	{
	  global $skin, $db, $vsource, $error;
		
		$name 	 = $_POST['name'];
		$email 	 = $_POST['email'];
		$comment = nl2br(htmlentities($_POST['comment']));
		$newsid	 = $_POST['newsid'];
		$guest   = $_POST['is_guest'];
		
			//-------------------------
			// Check for empty fields
			//-------------------------
		
			if (empty($name) OR empty($comment) OR empty($newsid))
			{
				$this->output .= $error->error('You did not fill in all requred fields');
				$skin->do_title('Error');
				return;
			}
			
			if (empty($email))
			{
				$email = "N/A";
			}
			
		//-------------------------
		// Insert comment into db
		//-------------------------
			
		$m = $vsource->get_mem_info();
		$db->query('INSERT INTO vsource_comments SET name="'.$name.'", email="'.$email.'", comment="'.$comment.'", newsid="'.$newsid.'", is_guest="'.$guest.'", mid="'.$m['id'].'"');
		$skin->redirect('Thanks, your comment has been added', 'index.php?id=news&do=2&item='.$newsid);
			
	}
	
	function delete_comment()
	{
	  global $cms, $db, $error;
	  
	  	if ($cms->member['is_member'] == 1)
		{
			$item = intval($_GET['item']);
			$db->query('SELECT id FROM vsource_comments WHERE id = "'.$item.'"');
				
				if ($db->number_rows() == 1)
				{
					$db->query('DELETE FROM vsource_comments WHERE id = "'.$item.'" LIMIT 1');
					$this->output .= $this->html->delete_success();
				}
				
				else
				{
					$this->output .= $error->error('Invalid comment id.');
				}
		}
		
		else
		{
			$this->output .= $error->error('Im sorry, you do not have sufficient permissions.', $back = true);
		}
	}
	
	function showarchive()
	{
	  global $db;
	  
		$db->query('SELECT * FROM vsource_news ORDER BY id DESC');
				
				while ($row = $db->fetchrow())
				{
					$this->output .= $this->html->shownews($row);
				}
	}
} 
 
?>
