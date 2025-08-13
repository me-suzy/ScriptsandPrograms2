<?
include ("../includes/global.php");

print "<center>";
links();
print "</center><br><br>";

//DB connectivity
$link=dbconnect();
if($action == "done" && $adid != "")
{
      $query1="select mem_id from member_details where email_id='$email' ";
      $sql="SELECT  url,amt_perclick,clicks_recd from advt_email where ad_id='$adid' " ;
      if($res = mysql_query($sql))
      {
         $res1=mysql_fetch_array($res);
         mysql_free_result($res);
         if($res1[2] == 0 || $res1[2]=="")
              $clicks=1;
         else
              $clicks=$res1[2]+1;
         if($res=mysql_query($query1))
         {
           if($res2=mysql_fetch_array($res))
	   {
		mysql_free_result($res);
	        $query="select pd_clickthro from member_earnings where mem_id=$res2[0]";
	        $qry="SELECT * FROM paid_mail where mem_id=$res2[0] and aff_id='$adid'";
                if($result1=mysql_query($qry))
                {        
                   $num1=mysql_num_rows($result1);
                    if($num1 == 0)
		    {
			//no entry in paid_mail so insert new row & update member_earnings
		        $qry1="insert paid_mail values ($res2[0],'$adid')";
			mysql_query($qry1);
                        if($res=mysql_query($query))
	        	{
  		      	     $pay=0;
			     $res3=mysql_fetch_array($res);
			     $rows=mysql_num_rows($res);
                  	     mysql_free_result($res);
                             if($rows == 0)
			     {
				  mysql_query("insert into member_earnings set pd_clickthro=$res1[1], mem_id=$res2[0]");
			     } 
			     else
			     {
                   		 if($res3[0] == 0.00)
                       		     $pay=$res1[1];
                       		 else
                                      $pay=$res3[0]+$res1[1];
                                       mysql_query("update member_earnings set pd_clickthro=$pay where mem_id=$res2[0]");
		             }	
                         }
                     }
		     else
		     {
			  print "Sorry. You have already received credit for this email";
			  print "<br><br><br><br>$html_footer";
			  exit;
		     }
		}
//operation in email_clicks table for having one of click received of each advertiser of each day
                $res2="";
                $res = mysql_query("select clicks from email_clicks where ad_id='$adid'and date=now()");
                if($res)   {

                $res2=mysql_fetch_array($res);
                $cnt=mysql_num_rows($res);
                mysql_free_result($res);

		if($cnt == 0)
                {
                     $uniq=1;
		     mysql_query("insert email_clicks values ('$adid',$uniq,now())");
                }
                else
                {
                     $uniq=$res2[0]+1;
                     mysql_query("update email_clicks set clicks=$uniq where ad_id='$adid' and date=now()");
                }
                }
                mysql_query("update advt_email set clicks_recd=$clicks  where ad_id='$adid'");
                $newurl="http://".$res1[0];
                print <<<MGS
		You are now being re-directed to the advertiser's website...
		<META HTTP-EQUIV=REFRESH CONTENT="1; URL=$newurl"> 
MGS;
            }
        }
    }
       else
           print "Due to some technical problems the site is unable to proceed further.Please try after some time.";
}

if($action != "done")
{
    $sql="SELECT  amt_perclick from advt_email where ad_id=$adid ";
    if($res = mysql_query($sql))
    {
         $res1=mysql_fetch_array($res);

         $contents=file_reader("$site_html_path/petrack.html");
         $contents=str_replace("[site_url]",$site_url,$contents);
         $contents=str_replace("[amount]",$res1[0],$contents);
         $contents=str_replace("[adid]",$adid,$contents);
         print $contents;
    }
}

//to close the link to DB
dbclose($link);

print "<br><br><br><br>$html_footer";
?>
