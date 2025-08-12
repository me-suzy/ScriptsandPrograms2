<?
    include ("../includes/global.php");

    links();

    if($email != "" && $ps != "")
    {
        $member_links=menu($email,$ps);
        print "$member_links";
    }
    if($p != "")
    {
        switch ($p)
        {
	    case "help":
		    //to open the help page
		    $content=file_reader("$site_html_path/help.html");
		    print $content;
		    break;
	    case "terms":
		    //to open the terms page
		    $content=file_reader("$site_html_path/terms&agri.html");
		    print $content;
		    break;
	    case "privacy":
		    //to open the privacy page
		    $content=file_reader("$site_html_path/privacy.html");
		    print $content;
		    break;
	    case "contactus":
		    //to open the contactus page
		    $contents=file_reader("$site_html_path/contact_us.html");
		    $contents=str_replace("[site_url]",$site_url,$contents);
		    $contents=str_replace("[refid]",$refid,$contents);
		    $contents=str_replace("[ps]",$ps,$contents);
		    $contents=str_replace("[email]",$email,$contents);
		    print "<br>$contents";
                break;
        }
    }
    elseif($p == "")
    {
        //to open the contact us page
       $out_message= <<< HTM
       <META HTTP-EQUIV=REFRESH CONTENT="0; URL=$site_url/index.php?refid=$refid">
HTM;
    }
print "$html_footer";
?>