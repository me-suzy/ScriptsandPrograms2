<html>
<head><title>
<?php 

//Code by apg88 "apgForm(toExcel) 1.4"
// Determine if the form was sent through the GET methog or the POST method.
if($_GET){			
	$array = $_GET;
}else if($_POST){
	$array = $_POST;
} else {
		echo "You must Access this file through a form.";	// If someone accesses the file directly, it wont work :)
}	


if(!$array['title']){
		// if the title wasnt sent through the form, it will become whatever you set it equal to in the next line
		$array['title'] = "apgForm";	//Set default title to be displayed
}
echo $array['title'] .'</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>';
	
	//Check if the filename was sent through the form or not
	if(!$array['filename']){
		// if the filename wasnt sent through the form, it will become form.xls, you can change the default if you want.
		$array['filename'] = "form.xls";	//Set the file to save the information in
	
	} else {
		if(!(stristr($array['filename'],".xls"))){
			$array['filename'] = $array['filename'] . ".xls";
		}
	}
	
	
	// Change this to whatever you want the users to see after the form is processed
	$continue = ' Here is a the Comment form your info was just sent to <a href="test.xls">Click
      Here</a> to see the excel file.<br> <a href = "index.php">Click Here</a> To Return to apgForm  ';
	
	// Change this to the character(s) you want to be placed instead of line breaks(new line, enter, etc)
	$lbChar = " ";	// default is a space, you may change it to whatever you want
	
	
	//-------------------------------------------------------------------------------------
	//----------------You do not need to change anything below this line-------------------
	//-------------------------------------------------------------------------------------


	// Define the tab and carriage return characters:
	$tab = "\t";	//chr(9);
	$cr = "\n";		//chr(13);
	
	if($array){
			// Make The Top row for the excel file and store it in the $header variable
			$keys = array_keys($array);
			foreach($keys as $key){
				if(strtolower($key) != 'filename' && strtolower($key) != 'title'){ 
					$header .= $key . $tab;
				}
			}
			$header .= $cr;
			
			//Make the line with the contents to write to the excel file.
			foreach($keys as $key){
				if(strtolower($key) != 'filename' && strtolower($key) != 'title'){ 

					$array[$key] = str_replace("\n",$lbChar,$array[$key]);
					$array[$key] = preg_replace('/([\r\n])/e',"ord('$1')==10?'':''",$array[$key]);
					$array[$key] = str_replace("\\","",$array[$key]);
					$array[$key] = str_replace($tab, "    ", $array[$key]);
					$data .= $array[$key] . $tab ;
				}
			}
			$data .= $cr;
				
			
			if (file_exists($array['filename'])) {
			   $final_data = $data;		//if the file does exist, then only write the information the user sent
			} else {
				$final_data = $header . $data;		//if file does not exist, write the header(first line in excel with titles) to the file
			}
			// open the file and write to it
			
			$fp = fopen($array['filename'],"a"); // $fp is now the file pointer to file $array['filename']
			
			if($fp){
				
				fwrite($fp,$final_data);	//Write information to the file
				fclose($fp);		// Close the file
				echo "Form Received Successfully! <br> " . $continue;
			} else {
				echo "Error receiving form! <br>" . $continue;
			}
	}
	//Copyright © 2004 apg88. All Rights Reserved 
?>

</body>
</html>