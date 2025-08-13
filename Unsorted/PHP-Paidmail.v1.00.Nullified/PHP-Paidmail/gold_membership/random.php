<?

if($gold_membership == "ON")
{
      $link=dbconnect();

      if(file_exists("$gold_path/random.txt"))
      {

            get_GoldMember_Det();

            $count=(count($gold_EmailId)-1);
	    

	    if ($count >= 0)
	      {
		$rand=rand(0,$count);
	      }
            //to fetch the member id corresponding to the email id selected randomly
            $qry="Select mem_id from member_details where email_id='$gold_EmailId[$rand]' ";

            $res=mysql_query($qry);
            $id=mysql_fetch_array($res);
            if($rand > 0)  {
            if((!$refid) && (!$adid) && isset($rand))
            {
                  Print <<< HTM
<META HTTP-EQUIV=REFRESH CONTENT="0; URL=$site_url/index.php?refid=$id[0]">
HTM;
            }
            else
            {
                 $file_content = file_reader("$gold_html_path/display.html");
                 $file_content = str_replace("[site_url]",$site_url,$file_content);
                 $file_content = str_replace("[refid]",$id[0],$file_content);
                 $file_content = str_replace("[email]",$gold_EmailId[$rand],$file_content);
                 $file_content = str_replace("[info]",$gold_Info[$rand],$file_content);
		 
                 //print $file_content;
            }
            }
      }
      mysql_close($link);
}

?>