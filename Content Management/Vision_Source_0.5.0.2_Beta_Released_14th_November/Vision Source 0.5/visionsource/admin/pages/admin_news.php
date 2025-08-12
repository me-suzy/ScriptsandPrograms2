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
class admin_news
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
		$this->html = $admin->load('skin_news');
		$item = !empty($_GET['item']) ? $_GET['item'] : FALSE;
		switch ($item) {
				case "1":
					$this->manage_news();
				break;
				case "2":
					$this->add_news();
				break;
				case "3":
					$this->manage_edit();
				break;
				case "4":
					$this->do_edit();
				break;
				case "5":
					$this->do_add_news();
				break;
				case "6":
					$this->delete();
				break;
				default:
					$this->home();
				break;
			}
			
		$admin->do_output("$this->output");
	}
	
	
	//------------------------------
	//	Lets do all the functions
	//------------------------------
	
	
	function home()
	{
	
	global $db, $info;
		$this->output .= 'You can edit news here <br /> click <a href="'.$info['base_url'].'/admin.php?ses='.$this->sesid.'&amp;do=3&amp;id=manage_news&amp;item=2">here</a> <br />
		<a href="'.$info['base_url'].'/admin.php?ses='.$this->sesid.'&amp;do=3&amp;id=manage_news&amp;item=1">manage news</a>';
 
	}
	
	function manage_news()
	{
		global $db, $info;
		
		$this->output .= $this->html->manage_top();
		$db->query('SELECT * FROM vsource_news ORDER BY id DESC');
			
			while ($row = $db->fetchrow())
			{
				$this->output .= $this->html->list_news($row);
			}
			
		$db->freemysql();
		
	}
	
	function add_news()
	{
		$this->output .= $this->html->top();
	}
	
	function do_add_news()
	{
		global $db;
		
		$title 	= strip_tags($_POST['title']);
		$text	= nl2br($_POST['text']);
		
			if (empty($title))
			{
				$this->output .= $this->html->error('No title entered');
				return;
			}
			
			if (empty($text))
			{
				$this->output .= $this->html->error('No message entered');
				return;
			}
			
		$date 	= date("F j, Y, g:i a");
		
		//-------------------------
		//  Get the poster details
		// ------------------------
		
		$userid = $_SESSION['admin_userid'];
		$pass	= $_SESSION['admin_passhash'];
		$db->query('SELECT username, password FROM vsource_users WHERE id="'.$userid.'" AND password="'.$pass.'"');
			
			if ($db->number_rows() == "1")
			{
				$row 	= $db->fetchrow();
				$poster = $row['username'];
				$db->freemysql();
				$db->query('INSERT INTO vsource_news SET newstitle="'.$title.'", newstext="'.$text.'", poster="'.$poster.'", thedate="'.$date.'"');
				$this->output .= $this->html->news_success();
			}
			
			else
			{
				$this->output .= $this->html->error('Unable to find your username details. Please try and log in again');
				return;
			}
			
	}
	
	function manage_edit()
	{
		global $db;
		
		$newsid	= !empty($_GET['newsid']) ? $_GET['newsid'] : $this->output .= $this->html->error('No news id was selected');
		$id		= intval($_GET['newsid']);
		$db->query('SELECT * FROM vsource_news WHERE id="'.$id.'"');
		
			if ($db->number_rows() == "1")
			{
				$row			 	= $db->fetchrow();
				$info['newstitle'] 	= $row['newstitle'];
				$info['newstext']	= ereg_replace('<br />', '', $row['newstext']);
				$info['id']			= $row['id'];
				$this->output	.= $this->html->edit_news($info);
			}
			
			else
			{
				$this->output .= $this->html->error('Could not find any news');
				$db->freemysql();
				return;
			}
			
		$db->freemysql();

	}
	
	function do_edit()
	{
		global $db;
		$title 	= strip_tags($_POST['title']);
		$text	= nl2br($_POST['text']);
		$id		= intval($_POST['id']);
		
			if (empty($title))
			{
				$this->output .= $this->html->error('No title entered');
				return;
			}
			
			if (empty($text))
			{
				$this->output .= $this->html->error('No message entered');
				return;
			}
			
				$db->query('UPDATE vsource_news SET newstitle="'.$title.'", newstext="'.$text.'" WHERE id="'.$id.'"');
				$this->output .= $this->html->news_success_edit();
	}
	
	function delete()
	{
		global $db;
			
			$newsid	= !empty($_GET['newsid']) ? $_GET['newsid'] : $this->output .= $this->html->error('No news id was selected');
			$id		= intval($newsid);
			
			if (!is_numeric($newsid))
			{
				$this->output .= $this->html->error('Invalid news id');
				return;
			}
			
			else
			{
				$db->query('SELECT id FROM vsource_news WHERE id="'.$id.'"');
				
					if ($db->number_rows() == 1)
					{
						$db->query('DELETE FROM vsource_news WHERE id="'.$id.'" LIMIT 1');
						$this->output .= "News Deleted.";
					}
					
					else
					{
						$this->output .= $this->html->error('Invalid News id');
					}
			}
	}
	
} 
 
?>