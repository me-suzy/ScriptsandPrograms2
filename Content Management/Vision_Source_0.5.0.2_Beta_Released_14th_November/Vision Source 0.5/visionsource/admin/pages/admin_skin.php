<?
/*
//////////////////////////////////////////////////////////////
//															//
//		Vision Source v0.5 Beta								//
//		Created by Ben Maynard copyright 2005				//		
//		Email: volvorules@gmail.com							//
//		URL: http://www.visionsource.org					//
//		Created: 21st October 2005							//
//															//
//----------------------------------------------------------//
//															//
//		Script: admin_skin.php								//
//		written by: Ben Maynard								//
//															//
//////////////////////////////////////////////////////////////
*/

if ( ! defined( 'DIRECT' ) )
{	die("<h1>Access denied</h1>You are not allowed to access this file directly.");
}

//Start the class
class admin_skin
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
		$this->html = $admin->load('skin_skin');
		$item = !empty($_GET['item']) ? $_GET['item'] : FALSE;
		switch ($item) {
				case "1":
					$this->manage_skins();
				break;
				case "2":
					$this->add_skin();
				break;
				case "3":
					$this->do_add_skin();
				break;
				case "4":
					$this->edit_skin();
				break;
				case "5":
					$this->do_edit_skin();
				break;
				case "6":
				  $this->default_skin();
				break;
				case "7":
				  $this->delete_skin();
				break;
				default:
					$this->manage_skins();
				break;
			}
			
		$admin->do_output("$this->output");
	}
	
	
	//------------------------------
	//	Lets do all the functions
	//------------------------------
	
	
	function manage_skins()
	{
		global $db, $info;
		
		$db->query('SELECT * FROM vsource_skin ORDER BY id DESC');
		$this->output .= $this->html->manage_top();
			
			while ($row = $db->fetchrow())
			{
				$this->output .= $this->html->list_skins($row);
			}
			
		$db->freemysql();
		
	}
	
	function add_skin()
	{
		$this->output .= $this->html->add_skin();
	}
	
	function do_add_skin()
	{
		global $db, $error;
		
		$name 	= $_POST['name'];
		$dir	  = $_POST['dir'];
		
			if (empty($name))
			{
				$this->output .= $error->error('No name entered', $back = true);
				return;
			}
			
			if (empty($dir))
			{
				$this->output .= $error->error('No message entered');
				return;
			}
			
			if ($_POST['view'] == "on")
			{
				$view = 1;
			}
			
			else
			{
				$view = 0;
			}
		
				$db->query('INSERT INTO vsource_skin SET name="'.$name.'", directory="'.$dir.'", view="'.$view.'", default_skin="0"');
				$this->output .= $this->html->skin_success();
			
	}
	
	function edit_skin()
	{
		global $db, $error;
		
		$skinid	= !empty($_GET['skinid']) ? $_GET['skinid'] : $this->output .= $eror->error('No skin was selected', $back = true);
		$db->query('SELECT * FROM vsource_skin WHERE id="'.$skinid.'"');
		
			if ($db->number_rows() == "1")
			{
				$row			 	= $db->fetchrow();
				$this->output	.= $this->html->edit_skin($row);
			}
			
			else
			{
				$this->output .= $error->error('Could not find the skin.', $back = true);
				$db->freemysql();
				return;
			}
			
		$db->freemysql();

	}
	
	function do_edit_skin()
	{
		global $db, $error;
		$name 	= $_POST['name'];
		$dir    = $_POST['dir'];
		$id		 = intval($_POST['skinid']);
		
			if (empty($name))
			{
				$this->output .= $error->error('No name was entered.', $back = true);
				return;
			}
			
			if (empty($dir))
			{
				$this->output .= $error->error('No directory was selected.', $back = true);
				return;
			}
			
			if ($_POST['view'] == "on")
			{
				$view = 1;
			}
			
			else
			{
				$view = 0;
			}
			
				$db->query('UPDATE vsource_skin SET name="'.$name.'", directory="'.$dir.'", view="'.$view.'" WHERE id="'.$id.'"');
				$this->output .= $this->html->skin_success_edit();
	}
	
	function default_skin()
	{
	  global $db, $error;
	  
	     $skinid	= !empty($_GET['skinid']) ? $_GET['skinid'] : $this->output .= $eror->error('No skin was selected.', $back = true);
	     //$skinid = $_GET['skinid'];
		 $db->query('SELECT default_skin, id FROM vsource_skin WHERE id="'.$skinid.'"');
	     
	       if ($db->number_rows() == 1)
	       {
	           $db->query('UPDATE vsource_skin SET default_skin="0" WHERE default_skin="1"');
	           $db->query('UPDATE vsource_skin SET default_skin="1" WHERE id="'.$skinid.'"');
	           $this->output .= $this->html->default_skin_updated();
	       }
	       
	       else
	       {
	           $error->error('Im sorry, I was unable to locate that skin.', $back = true);
	           return;
	       }
	     
	}
	
	function delete_skin()
	{
	  global $db, $error;
	  
	     $skinid	= !empty($_GET['skinid']) ? $_GET['skinid'] : $this->output .= $eror->error('No skin was selected.', $back = true);
	     $db->query('SELECT id FROM vsource_skin');
	     
	       if ($db->number_rows() ==  1)
	       {
	           $this->output .= $error->error('Im sorry, you must always have 1 skin in the database at all times.', $back = true);
	           return;
	       }
	       
	     $db->query('SELECT default_skin, id FROM vsource_skin WHERE id="'.$skinid.'"');
	       
	       if ($row['default_skin'] == "1")
	       {
	           $this->output .= $error->error('You are not allowed to delete the default skin. Please make another skind default first.', $back = true);
	           return;
	       }
	       
	       if ($db->number_rows() == 1)
	       {
	           $db->query('DELETE FROM vsource_skin WHERE id="'.$skinid.'" LIMIT 1');
	           $this->output .= $this->html->delete_complete();
	       }
	       
	       else
	       {
	           $this->output .= $error->error('Im sorry, but we were unable to locate that skin.', $back = true);
	           return;
	       }
	       
	}
	
} 
 
?>
