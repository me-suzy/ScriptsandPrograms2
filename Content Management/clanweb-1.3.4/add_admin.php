<?php
//	-----------------------------------------
// 	$File: add_admin.php
// 	-----------------------------------------
// 	$Copyright: (c) ClanAdmin Tools 2003-2005
// 	$Last modified: 2005-05-29
// 	$email: support@clanadmintools.com
// 	$License: GPL - See LICENSE for more info
//	-----------------------------------------

      require ('auth.php');
      
    		// header
 				require ('_inc/top.inc.php');

   			// content
   			echo"<div class=\"welcome\">";
	  $sql = $db->query("SELECT user_id FROM " .$db_prefix. "online 
						  WHERE cookiesum = '".$_COOKIE['catcookie']."' LIMIT 1");
	  $read = $db->fetch_array($sql);
						  
      $sql = $db->query("SELECT admin FROM " .$db_prefix. "users 
						  WHERE id = '".$read['user_id']."' LIMIT 1");
	  $read = $db->fetch_array($sql);
 			
			 		if($read['admin'] == 1) 
						{
							if(isset($_POST['new'])) 
							{
							   $check = $db->query("SELECT username FROM " .$db_prefix. "users");
                         	    
								 while ($read = $db->fetch_array($check))
                         	    {
                         	      if($_POST['username'] == $read["username"])
            			          {
            			               echo"<h3>The nickname is already taken</h3>";
                                 	   exit; 		
            		              }                   			
            	                }
            	                
								if(strlen($_POST['password']) <= 0)
            	                {
            	                   echo"<h3>Please enter a password</h3>";
            	                   exit;
                              	}
	                			
								$password = md5($_POST['password']);	
							    
								$sql = "INSERT INTO " .$db_prefix. "users (username, password,admin) 
										VALUES ('".$_POST['username']."', '$password','".$_POST['headadmin']."')";
								  
								  $db->query($sql) or exit('An error occured while saving data.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.mysql_error().'');
								  echo "<h3>$lang_user_added</h3>";
							}
							else
							{
                  			echo"
                                   <form name=\"sends\" method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">\n
            			                 <strong>$lang_username:</strong>\n
                    						   <input class=\"textfelt\" type=\"text\" name=\"username\" size=\"20\" maxlength=\"75\">\n
            				        		   <strong>$lang_password:</strong>\n
            						           <input class=\"textfelt\" type=\"text\" name=\"password\" size=\"20\" maxlength=\"75\">\n
                  							   <br/>\n
            											 <strong>$lang_headadmin:<span style=\"color: red;\">*</span></strong>\n 
														 <select name=\"headadmin\" class=\"textfelt\">
														 		 <option value=\"0\">No</option>
                												 <option value=\"1\">Yes</option>
                										 </select>
            											 <input class=\"button\" type=\"submit\" name=\"new\" value=\"$lang_add_admin\">\n
            			      			     <br/><br/>
            			      			     <span style=\"color: red;\">*</span> $lang_admin_notice
            								</form>
                                  ";
                		  }
						}
						else
						{ 
							echo "$lang_denied"; 
						}
            echo"</div>";
				// close open mysql connections
        $db->close();
        // bottom
        require ('_inc/bottom.inc.php');

?>
