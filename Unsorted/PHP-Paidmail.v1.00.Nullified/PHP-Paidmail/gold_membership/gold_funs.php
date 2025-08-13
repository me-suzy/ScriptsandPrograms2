<?
     if(file_exists("../gold_membership/gold_vars.php")) { include ("../gold_membership/gold_vars.php"); }
     else { include ("../gold_vars.php"); }

     function check_GoldMember($email_id)
     {
             global $gold_path;

             $content = file_reader("$gold_path/random.txt");
             $content=split("\n",$content);
             $bool="notexist";
             for($i=0;$i<count($content)-1;$i++)
             {
                $data=split("&&",$content[$i]);
                if($email_id == $data[0])
                {
                     $bool="exist";
                     break;
                }
             }
             return $bool;
     }

     function get_GoldMember_Det()
     {
             global $gold_path,$gold_EmailId,$gold_Info,$gold_Date;

             $content = file_reader("$gold_path/random.txt");
             $content=split("\n",$content);

             for($i=0;$i<count($content)-1;$i++)
             {
                  $data=split("&&",$content[$i]);
                  $gold_EmailId[$i]=$data[0];
                  $gold_Info[$i]=$data[1];
                  $gold_Date[$i]=$data[2];
             }
     }

     function replace_GoldMember($find_email,$replace_email)
     {
             global $gold_path,$gold_EmailId,$gold_Info,$gold_Date;

             $content = file_reader("$gold_path/random.txt");
             $content = str_replace($find_email,$replace_email,$content);

             file_writer("$gold_path/random.txt",$content);
     }


     //input to this function (i.e. $userid) should be an array
     function remove_GoldMember($userid,$file_url)
     {
	    
	    
            global $gold_path;
            $user_count=count($userid);
                  
             $file_content=file_reader($file_url);
             $remaining_users=$file_content;
	    
             $file_content=split("\n",$file_content);
             
             $line_count=count($file_content);
	     for($i=0;$i<$user_count;$i++)
             {
                    for($j=0;$j<($line_count-1);$j++)
                    {
                          $data=split("&&",$file_content[$j]);
                          if($userid[$i] == $data[0])
                          {
                                   $remaining_users = str_replace($file_content[$j],"",$remaining_users);
                                   break;
                          }
                    }
             }
                  
             $member_list=split("\n",$remaining_users);

             $remaining_users="";
             for($i=0;$i<(count($member_list)-1);$i++)
             {
                    if($member_list[$i] == "")
                    continue;

                    $remaining_users.=$member_list[$i]."\n";
             }
             
             //writing the updated contents to the random.txt file
             if(file_writer($file_url,$remaining_users))
             {
		
                 return "true";
             }
             else
	     {
		
	      if($remaining_users=="")
	     {
		
		return "true";
	     }
	     else
	     {
		
                 	return "false";
	     } 

	     }
     }

?>

