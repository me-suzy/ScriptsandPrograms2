<?php

$loader = new admin_main();

class admin_main
{
	var $output;
	
	function admin_main()
	{
		global $IN, $OUTPUT;

		switch($IN["act"])
		{
            case 'home':
				$main_content = $this->admin_home();
				//$nav = adminNav("", -1);
				break;
			case 'news':
				$main_content = $this->admin_news();
				//$nav = adminNav("", -1);
				break;
			default:
				$main_content = $this->admin_home();
				//$nav = adminNav("", -1);
				break;

		}
		$OUTPUT->add_output($this->output);
	}

	function admin_home()
	{
		global $CONFIG, $updateversion;
		ob_start();
		echo admin_head(GETLANG("rwdownload"), GETLANG("acp"));
		
		echo new_table(-1, "", "", "100%");
			echo new_row(-1, "", "", "50%");
				echo "Welcome to RW::Download 4 lite. This is the free version of our download manager available from www.rwscripts.com. A premium version of our download software is also available from www.rwscripts.com. To see a feature comparison between the free and premium versions of our scripts, click <a href='http://www.rwscripts.com/index.php?url=scripts.htm'>here</a>. We hope you enjoy using RW::Download. Please report any problems or requests to our <a href='http://www.rwscripts.com/forum/'>support forums</a>.<br>";
				echo "If you enjoy our script, please rate us at Hotscripts.com or using the form below. Please rememeber to follow the on screen instructions after voting. Thankyou";
				echo "<form action='http://www.hotscripts.com/rate/21660.html?RID=764' method='post' target='body'>
<p class='navhead'>Rate Our Script</p>
<p class='navbox'>
<select name='rating' size='1'>
	<option selected='selected'>Select Your Rating</option>
	<option value='5'>Excellent!</option>
	<option value='4'>Very Good</option>
	<option value='3'>Good</option>
	<option value='2'>Fair</option>
	<option value='1'>Poor</option>
</select>
<div align='center'><input name='submit' type='submit' value='Rate us at Hotscripts.com'></div>
</p></form>";
			echo new_col();
			if ( $CONFIG['doscriptcheck'] )
				echo "<iframe src='http://www.rwscripts.com/lite.php' name='news' width='100%' marginwidth='0' height='200' marginheight='0' scrolling='yes' frameborder='1'>";
			//echo "<iframe src='http://localhost/download4/rwupdate/update.php?v={$updateversion}' name='news' width='100%' marginwidth='0' height='200' marginheight='0' scrolling='yes' frameborder='1'>";
		echo end_table();
		echo admin_foot();
		$this->output = ob_get_contents();
		ob_end_clean();
		
		
	}
	
	function admin_news()
	{
		$this->output .= admin_head(GETLANG("rwdownload"), GETLANG("news"));
		ob_start();
		readfile("http://www.rwscripts.com/forum/ssi.php?a=news&show=10");
		$this->output .= ob_get_contents();
		ob_end_clean();
		$this->output .= admin_foot();
	}
}

?>