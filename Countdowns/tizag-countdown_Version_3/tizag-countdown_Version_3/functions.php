<?php

	

	function colorArray($color){
		$array = explode('.', $color);
		
// 		for($i = 0; $i < 2; $i++){
// 			if($array[$i] == '')
// 				$array[$i] = 0;
// 		}
		return $array;
	}
	
	
	
	function colorCombine($red, $green, $blue){
// 		if($red = '')
// 			$red = 0;
// 		if($green = '')
// 			$green = 0;
// 		if($blue = '')
// 			$blue = 0;
// 			
		return $red.".".$green.".".$blue;
	}
	
	function colorCreator($im, $color){
		$color_array = explode('.', $color);
		return imagecolorallocate($im, $color_array[0], $color_array[1], $color_array[2]);
	}
	
	function echoIntro(){
		include("filereader.php");
		$bgc_a = colorArray($bgcolor);
	
		echo "<h3>Choose a Counter Type</h3>
		<table border='0'>
		<tr valign='top'>
			<td>Graphical or Plain Text Countdown?</td>
			<td>";
		if($mode){
			echo "<input type='radio' name='mode' value='1' onclick=\"gfxMode();\" checked>Graphical | <input type='radio' name='mode' value='0' onclick=\"plainMode();\">Plain Text";
		} else {
			echo "<input type='radio' name='mode' value='1' onclick=\"gfxMode();\" >Graphical | <input type='radio' name='mode' value='0' onclick=\"plainMode();\" checked>Plain Text";
		}
		
			
		echo "</td>
		</tr>\n
		<tr valign='top'>
		<td>
			<input type='hidden' name='MAX_FILE_SIZE' value='1000000' />";
			
			
		if($picture){
			echo "<input type='radio' name='picture' value='2'>Upload a new picture (Allowed Image Formats:
			.jpg, .jpeg, .gif, .png):<br />
			 -OR- <br />
			 <input type='radio' name='picture' value='1' checked>Use Existing Picture
			 <br />
			 -OR- <br />
			<input type='radio' name='picture' value='0'>Use a Background Color RGB (0-255):";
		} else {
			echo "<input type='radio' name='picture' value='2'>Upload a new picture (Allowed Image Formats:
			.jpg, .jpeg, .gif, .png):<br />
			 -OR- <br />
			 <input type='radio' name='picture' value='1'>Use Existing Picture
			 <br />
			 -OR- <br />
			<input type='radio' name='picture' value='0' checked>Use a Background Color RGB (0-255):";
		}
		
		echo "</td>
		<td><input size ='35' type='file' name='imgfile' onclick=\"document.countdown.picture[0].checked = true; \"/><br />--Max File Size: 1,000KB--<br /><br /><br />
			Red:<input size='3' maxlength='3' type='text' name='bgred' value='$bgc_a[0]' /> 
			Green:<input size='3' maxlength='3' type='text' name='bggreen' value='$bgc_a[1]'  /> 
			Blue:<input size='3' maxlength='3' type='text' name='bgblue' value='$bgc_a[2]' / >
		</td>\n
		</tr>
		</table>";
	}
	
	function echoTextInfo(){
		
		include("filereader.php");

		$txtc_a = colorArray($txtcolor);
		$shadowc_a = colorArray($shadowcolor);
		echo "<h3>Text & Font Options</h3>
			<table border='0' cellspacing='0'>
			<tr valign='top'>
				<td>Text Color RGB:</td>
				<td>
					Red:<input size='3' maxlength='3' type='text' name='txtred' value='$txtc_a[0]' /> 
					Green:<input size='3' maxlength='3' type='text' name='txtgreen' value='$txtc_a[1]' /> 
					Blue:<input size='3' maxlength='3' type='text' name='txtblue' value='$txtc_a[2]' />
				</td>
			</tr>\n
			<tr valign='top'>
			<td><input type='checkbox' name='dropshadow' value='1' checked>Text Drop Shadow RGB</td>
			<td>
				Red:<input size='3' maxlength='3' type='text' name='shadowred' value='$shadowc_a[0]' /> 
				Green:<input size='3' maxlength='3' type='text' name='shadowgreen' value='$shadowc_a[1]' /> 
				Blue:<input size='3' maxlength='3' type='text' name='shadowblue' value='$shadowc_a[2]' /> 
			</td>
			</tr>\n
			<tr valign='top'>
				<td>Font Size:</td>
				<td>
					<input size='3' maxlength='3' type='text' name='font_size' value='$font_size' />
				</td>
			</tr>\n
		</table>";
	}
	
	function echoBorder(){
		include("filereader.php");
		$borderc_a = colorArray($bordercolor);
		echo "<h3>Border Option</h3>
		<table border='0'>
		<tr valign='top'>
		<td><input type='checkbox' name='border' value='1' checked>Countdown Border</td>
		<td>\n
			Red:<input size='3' maxlength='3' type='text' name='borderred' value='$borderc_a[0]' /> 
			Green:<input size='3' maxlength='3' type='text' name='bordergreen' value='$borderc_a[1]' /> 
			Blue:<input size='3' maxlength='3' type='text' name='borderblue' value='$borderc_a[2]' />
		</td>
		</tr>\n
		</table>";
	}
	
	function echoDate(){
		include("filereader.php");
		
		echo "<h3>Countdown Information</h3>
		<table border='0'>
		<tr valign='top'>
			<td>Countdown Text: </td>
			<td><input size='35' type='text' name='user_text' value='$text'/></td>
		</tr>\n
		<tr valign='top'>
			<td>Target Date (Click Icon):</td>
			<td>
			<a href=\"javascript:show_calendar('countdown.user_date');\"
			onMouseOver=\"window.status='Popup Date Chooser!'; overlib('Click here to choose a date.'); return true;\"
			onMouseOut=\"window.status=''; nd(); return true;\"><img src='pics/calendar.gif' border=0></a>
			<input type='text' name='user_date' value='$date'/></td>
		</tr>\n
		</table>";
	}
	
	function echoPosition(){
		include("filereader.php");
		
		echo "<h3>Position Options</h3>
		<table border='0'>
		<tr valign='top'>
		<td colspan='2'>
			<p>Position X is the distance between the left edge of the image and
			the start of the text.  Position Y is the distance from the top of the
			image to the start of the text.  X=0, Y=0 would make the text appear
			in the top left of the screen. 
			</p>
		</td>
		</tr>\n
		<tr>
		<td>
			Text Positioning:
		</td>
		<td>
		X:<input type='text' size='4' maxlength='4' value='$xpos' name='xpos' /> 
		Y:<input type='text' size='4' maxlength='4' value='$ypos' name='ypos' />
		</td>
		</tr>
		<tr>
		<td>
			Offset From Message to Countdown (pixels):
		</td>
		<td>
		X:<input type='text' size='4' maxlength='4' value='$xposoff' name='xposoff' /> 
		Y:<input type='text' size='4' maxlength='4' value='$yposoff' name='yposoff' />
		</td>
		</tr>
		</table>";
	}
	
	function echoSubmit(){
		include("filereader.php");
		echo "<h3>Generate Countdown</h3>
		<table border='1' align='center'>
		<tr valign='top'>
			<td colspan='2' id='submit'><input type='submit' value='Generate Countdown' name='submit' />
			<br /><br />
			<td colspan='2'><input type='button' value='Run Validation' name='test' 
			onclick=\"formValidation(document.countdown);\"/>
			</td>
		</tr>
		</table>";
		
	}
	
	function gfxCountdownHTML(){
		include("filereader.php");
		$location = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$location = str_replace("htmlcode.php", "preview.php", $location);
		$location = "http://".$location;
		$color_a = colorArray($bordercolor);
		echo "<h2>HTML Code</h2>";
		echo "<p>Copy this HTML code onto the page you want your countdown image displayed.</p>";
		echo "<form><textarea rows='4' cols='70'>";
		if($border){
		echo "<img style='border: 3px solid rgb($color_a[0], $color_a[0], $color_a[0]);'src=' $location '/>";
		} else {
			echo "<img src=' $location '/>";
		}
		echo "</textarea> </form>";
	}
	
	function plainCountdownHTML(){
		include("filereader.php");
		$location = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$location = str_replace("htmlcode.php", "preview.php", $location);
		$location = "http://".$location;
		$color_a = colorArray($bordercolor);
		echo "<h2>PHP Code</h2>";
		echo "<p>Copy this PHP code to the page you want your countdown image displayed.";
		echo "You must change your web page to a .php file for the plain text countdown to work.</p>";
		echo "<form><textarea rows='4' cols='70'>";
		if($border){
			echo "<?php include('plain.php'); ?>";
		} else {
			echo "<?php include('plain.php'); ?>";
		}
			echo "</textarea> </form>";
	}
	
?>