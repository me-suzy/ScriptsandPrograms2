<?php
class uploads
{
	function upload($user_name, $user_rank, $imgupload)
	{
		$get_settings = mysql_query("SELECT * FROM imgup_config");
		$settings = mysql_fetch_array($get_settings);
		$exitp = new functions();
		
		unset($used_space);
		echo "You have used up: <b>";
		
		$opn_dir = opendir($user_name);
		$used_space = 0;
		
		while($read_dir = readdir($opn_dir))
		{
			// Add up all the files so we can see how much they have used, and how much they have left =) 
			if(($read_dir != "..") && ($read_dir != "."))
			{
				$filesize = filesize($user_name . "/" . $read_dir);
				$used_space = $used_space + $filesize;
			}
		}
		
		// convert the result to megabytes or kilobytes
		$count_size = new functions();
		$count_size->size_check($used_space);
		$split_dirall = explode(':', $settings['directory_limit']);
		
		echo "</b> of the allocated: <b>" . $split_dirall[0] . $split_dirall[1] . "</b>.<br /><br />";
		
		unset($used_space);
		unset($opn_dir);
		unset($read_dir);
		unset($filesize);
		
		if(isset ($_POST['upload_image']))
		{
			if(!empty ($imgupload))
			{
				$error_occured = False;
				switch($imgupload['error'])
				{
					case 1:
						$error_occured = True;
						echo "The image you attempted to upload exceeds the max file size limit in the PHP configuration file.<br />";
					break;
					case 2:
						$error_occured = True;
						echo "The image you attempted to upload exceeds the max file size limit set by the admin.<br />";
					break;
				}
				
				if(($settings['useext'] == "no") OR ($settings['useext'] == Null))
				{
					$seperate_img = explode(',', $settings['allowed_img']);
					$count_img = count($seperate_img);
					
					$get_type = exif_imagetype($imgupload['tmp_name']);
					$allowed_image = Null;
					
					for($i_img = 0;$i_img<$count_img;$i_img++)
					{
						$img_array = explode(':', $seperate_img[$i_img]);
						
						if(($img_array[0] == "IMAGETYPE_GIF") && ($img_array[1] == "allow")) 
						{
							$allowed_image = IMAGETYPE_GIF;
						} elseif(($img_array[0] == "IMAGETYPE_JPEG") && ($img_array[1] == "allow"))
						{
							$allowed_image = IMAGETYPE_JPEG;
						} elseif(($img_array[0] == "IMAGETYPE_PNG") && ($img_array[1] == "allow"))
						{
							$allowed_image = IMAGETYPE_PNG;
						} elseif(($img_array[0] == "IMAGETYPE_PSD") && ($img_array[1] == "allow"))
						{
							$allowed_image = IMAGETYPE_PSD;
						} elseif(($img_array[0] == "IMAGETYPE_BMP") && ($img_array[1] == "allow"))
						{
							$allowed_image = IMAGETYPE_BMP;
						} elseif(($img_array[0] == "IMAGETYPE_SWF") && ($img_array[1] == "allow"))
						{
							$allowed_image = IMAGETYPE_SWF;
						}
						
						if($get_type == $allowed_image)
						{
							// w00t, it's teh valid, so set the valid variable to true and exit the loop
							$valid_image = True;
							$i_img = $i_img + 1000;
						} elseif($get_type != $allowed_image)
						{
							// keep the variable false and continue teh loop
							$valid_image = False;
						} elseif($get_type == Null)
						{
							$valid_image = False;
						}
					}
					
					if($allowed_image == Null)
					{
						// Holy crap! The admin is using exif_imagetype, but didn't check any valid images...well, now we have to break the news to the user =(
						echo "The admin has not selected any image types allowed for upload.<br />";
						$exitp->exitp($user_rank);
					}
				} elseif($settings['useext'] == "yes")
				{
					$valid_image = False;
					
					$exten_array = explode(',', $settings['allowed_ext']);
					$count_exten = count($exten_array);
					$file_exten = explode('.', $_FILES['imgupload']['name']);
					
					for($i = 0;$i<$count_exten;$i++)
					{
						if($file_exten[1] == $exten_array[$i])
						{
							$valid_image = True;
							$i = $count_exten + 1000;
						} else {
							$valid_image = False;
						}
					}
				}
				
				if($error_occured == True)
				{
				
				}elseif($valid_image == True)
				{	
					if(is_dir($user_name))
					{
						// alrighty...we've gotten this far...now we check if they have enough space left...
						$open_dir = opendir($user_name);
						$used_space = 0;
						while($read_dir = readdir($open_dir))
						{
							// Add up all the files so we can see how much they have used, and how much they have left =) 
							$filesize = filesize($user_name . "/" . $read_dir);
							$used_space = $used_space + $filesize;
						}
						
						$split_dirsize = explode(':', $settings['directory_limit']);
						if($split_dirsize[1] == "MB")
						{
							$in_mb = $split_dirsize[0];
							$dirsize_set = new functions();
							$dirsize = $dirsize_set->mb_bytes($in_mb);
						} elseif ($split_dirsize[1] == "KB")
						{
							$in_kb = $split_dirsize[0];
							$dirsize_set = new functions();
							$dirsize = $dirsize_set->kb_bytes($in_kb);
						}
						
						if($used_space >= $dirsize)
						{
							echo "You have used up all your directory space.";
						} elseif($used_space < $dirsize)
						{
							if(file_exists($user_name . "/" . $imgupload['name']))
							{
								if($_POST['overwrite_file'] == True)
								{
									// Kill the old, and move in teh new
									unlink($user_name . "/" . $imgupload['name']);
									if(move_uploaded_file ($imgupload['tmp_name'], $user_name . "/" . $imgupload['name']))
									{
										chmod($user_name . "/" . $imgupload['name'], 0644);
										echo "Your image was uploaded successfully.<br />";
										echo "Click " . '<a href="' . $user_name . '/' . $imgupload['name'] . '" target="_blank">here</a>' . " to view your image.";
									}
								} else {
									echo "The image you attempted to upload already exists!<br />Please select to overwrite exisiting images, or rename the image you're attempting to upload.";
								}
							} else {
								if(move_uploaded_file ($imgupload['tmp_name'], $user_name . "/" . $imgupload['name']))
								{
									chmod($user_name . "/" . $imgupload['name'], 0644);
									echo "Your image was uploaded successfully.<br />";
									echo "Click " . '<a href="' . $user_name . '/' . $imgupload['name'] . '" target="_blank">here</a>' . " to view your image.";
								}
							}
						}
					} else {
						echo "Your directory is non-existant. Please contact an adminstrater.<br />";
					}
				} else {
					echo "The image you're attempting to upload is invalid, or is not an allowed image type.<br />";
				}
			} else {
				echo "You must select a file to upload.<br />";
			}
		}
		
		// Unleash the MB/KB crapxx0rz!!!
		$split_maxup = explode(':', $settings['max_upload']);
		
		if($split_maxup[1] == "MB")
		{
			$in_mb = $split_maxup[0];
			$uploadmax_set = new functions();
			$uploadmax = $uploadmax_set->mb_bytes($in_mb);
		} elseif ($split_maxup[1] == "KB")
		{
			$in_kb = $split_maxup[0];
			$uploadmax_set = new functions();
			$uploadmax = $uploadmax_set->kb_bytes($in_kb);
		}
		
		echo '<p><form action="' . $_SERVER['PHP_SELF'] . '?action=upload" enctype="multipart/form-data" method="post">
		          <input type="hidden" name="MAX_FILE_SIZE" value="' . $uploadmax . '" />
		          Overwrite existing image? Yes<input type="checkbox" name="overwrite_file" value="true" /><br />
				  <input type="file" name="imgupload" /><br />
				  <input type="submit" name="upload_image" value="Upload Image" /><br />
				  </form></p>';
	}
	
	function rename($user_name, $user_rank)
	{
		if(rename($user_name . "/" . $_GET['rename'], $user_name . "/" . $_GET['imgname']))
		{
			echo "" . $_GET['rename'] . ", has been successfully renamed to, " . $_GET['imgname'] . "<br />";
			echo 'Click <a href="' . $_SERVER['PHP_SELF'] . '?action=imgdir">here</a> to go back.';
		} else {
			echo "Could not rename the image because of unknown errors.";
			echo 'Click <a href="' . $_SERVER['PHP_SELF'] . '?action=imgdir">here</a> to go back.';
		}
	}
	
	function delete($user_name, $user_rank)
	{
		if(unlink($user_name . "/" . $_GET['delete']))
		{
			echo $_GET['delete'] . " has been deleted successfully.<br />";
			echo 'Click <a href="' . $_SERVER['PHP_SELF'] . '?action=imgdir">here</a> to go back.';
		} else {
			echo "Could not remove the image because of unknown errors.";
			echo 'Click <a href="' . $_SERVER['PHP_SELF'] . '?action=imgdir">here</a> to go back.';
		}
	}
	
	function display($user_name, $user_rank)
	{
		$get_settings = mysql_query("SELECT * FROM imgup_config");
		$settings = mysql_fetch_array($get_settings);
		
		unset($used_space);
		echo "You have used up: <b>";
		
		$opn_dir = opendir($user_name);
		$used_space = 0;
		
		while($read_dir = readdir($opn_dir))
		{
			// Add up all the files so we can see how much they have used, and how much they have left =) 
			if(($read_dir != "..") && ($read_dir != "."))
			{
				$filesize = filesize($user_name . "/" . $read_dir);
				$used_space = $used_space + $filesize;
			}
		}
		
		// convert the result to megabytes or kilobytes
		$count_size = new functions();
		$count_size->size_check($used_space);
		$split_dirall = explode(':', $settings['directory_limit']);
		
		echo "</b> of the allocated: <b>" . $split_dirall[0] . $split_dirall[1] . "</b>.<br /><br />";
		
		unset($used_space);
		unset($opn_dir);
		unset($read_dir);
		unset($filesize);
		
		$open_dir = opendir($user_name);
		while($file_name = readdir($open_dir))
		{
			if(($file_name != ".") && ($file_name != ".."))
			{
				$rem_array = array("-", ".");
				$js_functions = str_replace($rem_array, "", $file_name);
				$split_img_ext = explode('.', $file_name);
				
				echo '<script>
				function ren' . $js_functions . '() 
				{
					var rename_prompt = prompt("Enter the new image name in the textarea below. Do not include the extension(*.jpg, *.gif, etc), the extension will be added during the processing.", "' . $split_img_ext[0] . '")
					if(rename_prompt == null)
					{
					
					} else if(rename_prompt == "")
					{
						alert("You must enter a name for the specified image..")
					} else if(rename_prompt != "")
					{
						var rename = rename_prompt + "." + "' . $split_img_ext[1] . '"
						var url = "' . $_SERVER['PHP_SELF'] . '?action=rename&rename=' . $file_name . '&imgname=" + rename
						window.location = url
					}
				}
				
				function del' . $js_functions. '()
				{
					var delimgfinal = confirm("Are you sure you want to delete, ' . $file_name . '?")
					if(delimgfinal == true)
					{
						window.location = "' . $_SERVER['PHP_SELF'] . '?action=delete&delete=' . $file_name . '"
					} else {
					
					}
				}
		        </script>';
				$filesize = filesize($user_name . "/" . $file_name);
				$kb_mb = new functions();
				
				echo '<p><a href="' . $user_name . '/' . $file_name . '" target="_blank">' . $file_name . '</a><br />';
				$kb_mb->size_check($filesize);
				echo '(bytes: ' . $filesize . ')<br />
					  <input type="button" value="Rename image" onclick="ren' . $js_functions . '()" /> <input type="button" value="Delete image" onclick="del' . $js_functions . '()"/>
        		</p>';
			} else {
				// Do absolutely nothing...
			}
		}
	}
}
?>