<?

    include ("../includes/global.php");



    //DB Connection function

    $link=dbconnect();



    links();



    //reading the login.html page

    $content=file_reader("$site_html_path/login.html");

    print($content);

   

    if($adid =="")

    {

        $contents=file_reader("$site_html_path/body.html");

        $contents=str_replace("[site_url]",$site_url,$contents);

        $contents=str_replace("[refid]",$refid,$contents);

        print $contents;

	

    }

    elseif($adid != "")

    {

        $sql="SELECT  amt_perclick from advt_email where ad_id=$adid ";

        $res = mysql_query($sql);

        if($res1=mysql_fetch_array($res))

        {

	    $contents=file_reader("$site_html_path/petrack.html");

	    $contents=str_replace("[site_url]",$site_url,$contents);

	    $contents=str_replace("[amount]",$res1[0],$contents);

	    $contents=str_replace("[adid]",$adid,$contents);

	    print $contents;

        }

        else

        {

	    $out_message= <<<HTM

Invalid Ad Id

HTM;

	    //function to display message

	    out_message($out_message,$color_feedback_bad);

            Print <<<HTM

<META HTTP-EQUIV=REFRESH CONTENT="2; URL=$site_url/index.php">

HTM;

        }

    }

if($gold_membership == "ON")

{

      if(file_exists("../gold_membership/gold_funs.php")) { include ("../gold_membership/gold_funs.php"); }

      if(file_exists("$gold_path/random.txt"))

      {

	include ("$gold_path/random.php");

      }

}



//to close to DB link

dbclose($link);



print <<<HTM

    $html_footer

HTM;

?>