<?
/*
//////////////////////////////////////////////////////////////
//															//
//		YourCMS v0.5 Beta									//
//		Created by Ben Maynard copyright 2005				//		
//		Email: volvorules@gmail.com							//
//		URL: http://www.visionsource.org					//
//		Created: 3rd March 2005								//
//															//
//----------------------------------------------------------//
//															//
//		Script: admin_news.php								//
//		written by: Ben Maynard								//
//															//
//////////////////////////////////////////////////////////////
*/

if ( ! defined( 'DIRECT' ) )
{	die("<h1>Access denied</h1>You are not allowed to access this file directly.");
}

//Start the class
class admin_custom
{

	var	$output = "";
	var $html 	= "";
	var $sesid	= "";

	//--------------------------
	//	Set up all the pages
	//--------------------------
	
	function pages() {
		global $admin;
		
		$this->sesid = $_GET['ses'];
		$this->html = $admin->load('skin_custom');
		$item = !empty($_GET['item']) ? $_GET['item'] : FALSE;
		switch ($item) {
				case "1":
					$this->manage_pages();
				break;
				case "2":
					$this->add_page();
				break;
				case "3":
					$this->page_edit();
				break;
				case "4":
					$this->do_edit();
				break;
				case "5":
					$this->do_add_page();
				break;
				case "6":
					$this->delete();
				break;
				default:
					$this->manage_pages();
				break;
			}
			
		$admin->do_output("$this->output");
	}
	
	
	//------------------------------
	//	Lets do all the functions
	//------------------------------
	
	function manage_pages()
	{
		global $db;
		
		$this->output .= $this->html->manage_top();
		$db->query('SELECT * FROM vsource_custom ORDER BY id DESC');
			
			while ($row = $db->fetchrow())
			{
				$this->output .= $this->html->list_pages($row);
			}
			
		$db->freemysql();
		
	}
	
	function add_page()
	{
		$this->output .= $this->html->add_page();
	}
	
	function do_add_page()
	{
		global $db, $error;
		
		$title 		= strip_tags($_POST['title']);
		$pageid		= strip_tags($_POST['pageid']);
		$text		= nl2br($_POST['text']);
		$mem_only	= $_POST['mem_only'];
		$view		= $_POST['view'];
		
			if (empty($title))
			{
				$this->output .= $error->error('No title entered');
				return;
			}
			
			if (preg_match("/[^a-z_-]/i", $pageid))
			{
					$this->output .= $error->error('The page id contained illegal characters.');
					return;
			}
			
			if (empty($pageid))
			{
				$this->output .= $error->error('No pageid entered');
				return;
			}
			
			if (empty($text))
			{
				$this->output .= $error->error('No text entered');
				return;
			}
			
			if ($mem_only == "on")
			{
				$mem_only = "1";
			}
			
			else
			{
				$mem_only = "0";
			}
			
			if ($view == "on")
			{
				$view = "1";
			}
			
			else
			{
				$view = "0";
			}
			
		$db->query('SELECT pageid FROM vsource_custom WHERE pageid="'.$pageid.'"');
		
			if ($db->number_rows() == 1)
			{
				$this->output .= $error->error('Im sorry, that page id already exists. Please try again.');
				return;
			}
			
		$db->query('INSERT INTO vsource_custom SET pageid="'.$pageid.'", html="'.$text.'", title="'.$title.'", mem_only="'.$mem_only.'", view="'.$view.'"');
		$this->output .= $this->html->page_success();
			
	}
	
	function page_edit()
	{
		global $db, $error;
		
		$customid	= !empty($_GET['customid']) ? $_GET['customid'] : $this->output .= $error->error('No custom page was selected');
		$id			= intval($_GET['customid']);
		$db->query('SELECT * FROM vsource_custom WHERE id="'.$id.'"');
		
			if ($db->number_rows() == "1")
			{
				$row			 	= $db->fetchrow();
				$info['title'] 		= $row['title'];
				$info['html']		= ereg_replace('<br />', '', $row['html']);
				$info['id']			= $row['id'];
				$info['pageid']		= $row['pageid'];
				
					if ($row['mem_only'] == "1")
					{
						$info['mem_only'] = 'checked="checked"';
					}
					
					if ($row['view'] == "1")
					{
						$info['view'] = 'checked="checked"';
					}
				
				$this->output	.= $this->html->edit_page($info);
			}
			
			else
			{
				$this->output .= $error->error('Could not find the selected page. Please try again.');
				$db->freemysql();
				return;
			}
			
		$db->freemysql();

	}
	
	function do_edit()
	{
		global $db, $error;
		$id			= $_POST['id'];
		$title 		= strip_tags($_POST['title']);
		$pageid		= strip_tags($_POST['pageid']);
		$text		= nl2br($_POST['text']);
		$mem_only	= $_POST['mem_only'];
		$view		= $_POST['view'];
		
			if (empty($title))
			{
				$this->output .= $error->error('No title entered.');
				return;
			}
			
			if (empty($id))
			{
				$this->output .= $error->error('No id was entered.');
				return;
			}
			
			if (empty($pageid))
			{
				$this->output .= $error->error('No pageid entered.');
				return;
			}
			
			if (preg_match("/[^a-z_-]/i", $pageid))
			{
					$this->output .= $error->error('The page id contained illegal characters.');
					return;
			}
			
			if (empty($text))
			{
				$this->output .= $error->error('No page text entered.');
				return;
			}
			
			if ($mem_only == "on")
			{
				$mem_only = "1";
			}
			
			else
			{
				$mem_only = "0";
			}
			
			if ($view == "on")
			{
				$view = "1";
			}
			
			else
			{
				$view = "0";
			}
			
		$db->query('SELECT pageid, id FROM vsource_custom WHERE pageid="'.$pageid.'" AND id != "'.$id.'"');
		
			if ($db->number_rows() == 1)
			{
				$this->output .= $error->error('Im sorry, that page id already exists. Please try again.');
				return;
			}
			
		$db->query('UPDATE vsource_custom SET pageid="'.$pageid.'", html="'.$text.'", title="'.$title.'", mem_only="'.$mem_only.'", view="'.$view.'" WHERE id="'.$id.'"');
		$this->output .= $this->html->page_success_edit();
	}
	
	function delete()
	{
		global $db, $error;
			
			$customid	= !empty($_GET['customid']) ? $_GET['customid'] : $this->output .= $error->error('No id was selected');
			$id			= intval($customid);
			
			if (!is_numeric($customid))
			{
				$this->output .= $error->error('Invalid id.');
				return;
			}
			
			else
			{
				$db->query('SELECT id FROM vsource_custom WHERE id="'.$id.'"');
				
					if ($db->number_rows() == 1)
					{
						$db->query('DELETE FROM vsource_custom WHERE id="'.$id.'" LIMIT 1');
						$this->output .= "Page Deleted.";
					}
					
					else
					{
						$this->output .= $error->error('Could not find that selected page. Please try again.');
					}
			}
	}
	
} 
 
?>