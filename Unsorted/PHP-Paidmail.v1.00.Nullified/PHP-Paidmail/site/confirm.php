<?
    include ("../includes/global.php");

    print "<center>";
    links();
    print "</center><br><br>";

    //DB connectivity
    $link=dbconnect();

    if($pwd == $p)
    {
        if($password == $pwd)
        {
            // insertion of updated datas by the user
            $query ="update member_details set  email_id=$email,address='$address',
 city='$city', state='$state', zip='$zipcode',country='$country' where password='$pwd' ";

            print "<b><font color=green>Your details are successfully updated in our database </font><br><br>";

            mysql_query($query);
        }
        else
        {
             $query ="update member_details set  email_id=$email,address='$address',
 city='$city', state='$state',zip='$zipcode',country='$country',password='$password'
 where password='$pwd' ";

             print "<b><font color=green>Your details are successfully updated in our database </font><br><br>";
             mysql_query($query);
        }
    }
    else
    {
         print  "<br><br><br><br><center><b>Hi&nbsp;<font color=green>&nbsp;&nbsp;
You have entered a invalid user id or password</font></b></html>";
    }
?>
<a  href="member_area.php?email='<?$email?>'&amp;pwd='<?$pwd?>' "><b><font
face="Arial" color="blue" size="3">Edit Your Information</font></b></a>

<?//to close to DB link
dbclose($link);?>











