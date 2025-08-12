<?
/*
//////////////////////////////////////////////////////////////
//															//
//		Vision Source v0.5 Beta								//
//		Created by Ben Maynard copyright 2005				//		
//		Email: volvorules@gmail.com							//
//		URL: http://www.visionsource.org					//
//		Created: 4th November 2005							//
//															//
//----------------------------------------------------------//
//															//
//		Script: admin_links.php								//
//		written by: Ben Maynard								//
//															//
//////////////////////////////////////////////////////////////
*/

if ( ! defined( 'DIRECT' ) )
{	die("<h1>Access denied</h1>You are not allowed to access this file directly.");
}

//Start the class
class admin_links
{

	var	$output = "";
	var $html = "";
	var $sesid = "";

	//--------------------------
	//	Set up all the pages
	//--------------------------
	
	function pages() {
		global $admin;
		
		$this->sesid = $_GET['ses'];
		$this->html = $admin->load('skin_links');
		$item = !empty($_GET['item']) ? $_GET['item'] : FALSE;
		switch ($item) {
				case "1":
					$this->manage_links();
				break;
				case "2":
					$this->add_link();
				break;
				case "3":
				  $this->add_cat();
				break;
				case "4":
					$this->do_add_link();
				break;
				case "5":
					$this->do_add_cat();
				break;
				case "6":
					$this->edit_link();
				break;
				case "7":
					$this->edit_cat();
				break;
				case "8":
					$this->do_edit_link();
				break;
				case "9":
					$this->do_edit_cat();
				break;
				case "10":
				  $this->delete_link();
				break;
				case "11":
					$this->delete_cat();
				break;
				default:
					$this->manage_links();
				break;
			}
			
		$admin->do_output("$this->output");
	}
	
	
	//------------------------------
	//	Lets do all the functions
	//------------------------------
	
	
	function manage_links()
	{
	  global $db, $info;
		  
		$catid = intval($_GET['catid']);
		  
			if (!empty($catid))
			{
				$db->query('SELECT * FROM vsource_links_cat WHERE id="'.$catid.'"');
          
				$r = $db->fetchrow();
				$this->output .= $this->html->cat_top($r);
				
				
				$db->query('SELECT * FROM vsource_links WHERE catid="'.$catid.'"');
          
					while ($row = $db->fetchrow())
					{
						$this->output .= $this->html->list_links($row);
					}
			}
		
			else
			{      
				$db->query('SELECT id, cat FROM vsource_links_cat ORDER BY id DESC');
			
					while ($row = $db->fetchrow())
					{
						$this->output .= $this->html->list_cats($row);
					}
					
				$this->output .= $this->html->add();
			}
			
		$db->freemysql();
		
	}
	
	function add_link()
	{
	  global $db;
	  
	   $db->query('SELECT id, cat FROM vsource_links_cat');
	   $cats = array();
	     while ($row = $db->fetchrow())
	     {
	       $cat['cat'] = $row['cat'];
	       $cat['id']  = $row['id'];
	       array_push($cats, $cat);
	     }
	     
		$this->output .= $this->html->add_link($cats);
	}
	
	function add_cat()
	{
		$this->output = $this->html->add_cat();
	}
	
	function do_add_link()
	{
		global $db, $error;
		
		$name 	= $_POST['name'];
		$link	  = $_POST['link'];
		$catid  = intval($_POST['catid']);
		
			if (empty($name))
			{
				$this->output .= $error->error('No name entered', $back = true);
				return;
			}
			
			if (empty($link))
			{
				$this->output .= $error->error('No link entered', $back = true);
				return;
			}
			
			if (empty($catid))
			{
				$this->output .= $error->error('No catagory selected', $back = true);
				return;
			}
		
				$db->query('INSERT INTO vsource_links SET name="'.$name.'", link="'.$link.'", catid="'.$catid.'"');
				$this->output .= $this->html->link_success();
			
	}
	
	function do_add_cat()
	{
		global $db, $error;
		
		$cat   	= $_POST['cat'];
		$about	= $_POST['about'];
		
			if (empty($cat))
			{
				$this->output .= $error->error('No catagory name entered', $back = true);
				return;
			}
			
			if (empty($about))
			{
				$this->output .= $error->error('No about entered', $back = true);
				return;
			}
		
				$db->query('INSERT INTO vsource_links_cat SET cat="'.$cat.'", about="'.$about.'"');
				$this->output .= $this->html->cat_success();
			
	}
	
	function edit_link()
	{
		global $db, $error;
		
		$linkid	= !empty($_GET['linkid']) ? $_GET['linkid'] : $this->output .= $eror->error('No link was selected', $back = true);
		$db->query('SELECT * FROM vsource_links WHERE id="'.$linkid.'"');
		
			if ($db->number_rows() == "1")
			{
			   $row			 	= $db->fetchrow();
			   $db->query('SELECT id, cat FROM vsource_links_cat');
	       $cats = array();
	       
	         while ($r = $db->fetchrow())
	         {
	           $cat['cat'] = $r['cat'];
	           $cat['id']  = $r['id'];
	           array_push($cats, $cat);
	         }
	         
				$this->output	.= $this->html->edit_link($row, $cats);
			}
			
			else
			{
				$this->output .= $error->error('Could not find the selected link.', $back = true);
				$db->freemysql();
				return;
			}
			
		$db->freemysql();

	}
	
	function edit_cat()
	{
		global $db, $error;
		
		$catid	= !empty($_GET['catid']) ? $_GET['catid'] : $this->output .= $eror->error('No catagory was selected', $back = true);
		$db->query('SELECT * FROM vsource_links_cat WHERE id="'.$catid.'"');
		
			if ($db->number_rows() == "1")
			{
				$row			 	= $db->fetchrow();
				$this->output	.= $this->html->edit_cat($row);
			}
			
			else
			{
				$this->output .= $error->error('Could not find the selected catagory.', $back = true);
				$db->freemysql();
				return;
			}
			
		$db->freemysql();

	}
	
	function do_edit_link()
	{
		global $db, $error;
		$name  = $_POST['name'];
		$link  = $_POST['link'];
		$catid = intval($_POST['catid']);
		$id		 = intval($_POST['id']);
		
			if (empty($name))
			{
				$this->output .= $error->error('No name was entered.', $back = true);
				return;
			}
			
			if (empty($link))
			{
				$this->output .= $error->error('No link was entered.', $back = true);
				return;
			}

			if (empty($catid))
			{
				$this->output .= $error->error('No catagory was selected.', $back = true);
				return;
			}
			
			if (empty($id))
			{
				$this->output .= $error->error('No link was selected.', $back = true);
				return;
			}
			
				$db->query('UPDATE vsource_links SET name="'.$name.'", link="'.$link.'", catid="'.$catid.'" WHERE id="'.$id.'"');
				$this->output .= $this->html->link_success_edit();
	}
	
	function do_edit_cat()
	{
		global $db, $error;
		$cat   = $_POST['cat'];
		$about = $_POST['about'];
		$id		 = intval($_POST['id']);
		
			if (empty($cat))
			{
				$this->output .= $error->error('No catagory name was entered.', $back = true);
				return;
			}
			
			if (empty($about))
			{
				$this->output .= $error->error('No about catagory was entered.', $back = true);
				return;
			}

			if (empty($id))
			{
				$this->output .= $error->error('No catagory was selected.', $back = true);
				return;
			}
			
				$db->query('UPDATE vsource_links_cat SET cat="'.$cat.'", about="'.$about.'" WHERE id="'.$id.'"');
				$this->output .= $this->html->cat_success_edit();
	}
	
	function delete_link()
	{
	  global $db, $error;
	  
	     $linkid	= !empty($_GET['linkid']) ? $_GET['linkid'] : $this->output .= $eror->error('No link was selected.', $back = true);
	     $db->query('SELECT id FROM vsource_links WHERE id="'.$linkid.'"');
	     
	       if ($db->number_rows() ==  1)
	       {
	           $db->query('DELETE FROM vsource_links WHERE id="'.$linkid.'" LIMIT 1');
	           $this->output .= $this->html->link_delete_complete();
	       }
	       
	       else
	       {
	           $this->output .= $error->error('Im sorry, but we were unable to locate that link.', $back = true);
	           return;
	       } 
	}
	
	function delete_cat()
	{
	  global $db, $error;
	  
	     $catid	= !empty($_GET['catid']) ? $_GET['catid'] : $this->output .= $eror->error('No catagory was selected.', $back = true);
	     $db->query('SELECT id FROM vsource_links_cat WHERE id="'.$catid.'"');
	     
	       if ($db->number_rows() ==  1)
	       {
	           $db->query('DELETE FROM vsource_links_cat WHERE id="'.$catid.'" LIMIT 1');
	           $this->output .= $this->html->cat_delete_complete();
	       }
	       
	       else
	       {
	           $this->output .= $error->error('Im sorry, but we were unable to locate that catagory.', $back = true);
	           return;
	       } 
	}
	
} 
 
?>
