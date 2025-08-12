<?
include ('../includes/global.php');

if($B1 =="Save Templates" && $submit_template_email == "1")
{
   $submit_mem_signup=str_replace("\\","",$submit_mem_signup);
   $submit_advt_signup=str_replace("\\","",$submit_advt_signup);
   $submit_html_header=str_replace("\\","",$submit_html_header);
   $submit_html_footer=str_replace("\\","",$submit_html_footer);
   $submit_email_header=str_replace("\\","",$submit_email_header);
   $submit_email_footer=str_replace("\\","",$submit_email_footer);

   file_writer("$site_html_path/email/email_signup.html",$submit_mem_signup);
   file_writer("$site_html_path/email/advertiser_signup.html",$submit_advt_signup);
   file_writer("$site_html_path/header.html",$submit_html_header);
   file_writer("$site_html_path/footer.html",$submit_html_footer);
   file_writer("$site_html_path/email/email_header.html",$submit_email_header);
   file_writer("$site_html_path/email/email_footer.html",$submit_email_footer);

   Print <<< HTML
   <center><font color=green><b>Data Successfully Updated</b></font><br><br>
   The page will automatically redirected to Admin page. <br><br>If not redirected within 5 seconds,Click the link.
   <a href="admin.php">Go to Admin Page</a>
   <META HTTP-EQUIV=REFRESH CONTENT="4; URL=admin.php"></center>
HTML;
   exit;
}
else
{
?>

    <title>Templates</title>
    <br><br>
    Note:<font face="arial" size="3" color=red><b>**Dont Remove the string contains "[" and "]"</font><font color=red>.Design your messages without affecting the word enclosed inside "[" and "]".</b></font>
    <form method="POST" action="admin_email.php">
    <input type="hidden" name="submit_template_email"  value="1">

    <font face="arial" size="3"><b>Signup E-mail</b></font><br><font face="arial" ><small>
    Description: The message people see after they signup.</font>
    <textarea rows="20" name="submit_mem_signup" cols="80" ><?=$GLOBALS["mem_signup"]?></textarea>
    <p>

    <!--
    <b><font face="arial" size="3">Advertiser Registeration E-mail</font></b><br><font face="arial" ><small>
    Description: The message sent to the Advertiser when he registeres.</font>
    <textarea rows="20" name="submit_advt_signup" cols="80"><?=$GLOBALS["advt_signup"]?></textarea>
    <p>
    -->
    <b><font face="arial" size="3">Site Header</font></b><br><font face="arial" ><small>
    Description: Top part of all PHP Pages.</font>
    </small><p>
    <textarea rows="15" name="submit_html_header" cols="80"><?=$GLOBALS["html_header"]?></textarea>
    <p>

    <b><font face="arial" size="3">Site Footer</font></b><br><font face="arial" ><small>
    Description: Bottom part of all PHP Pages.</font>
    </small><p>
    <textarea rows="15" name="submit_html_footer" cols="80"><?=$GLOBALS["html_footer"]?></textarea>
    <p>


    <b><font face="arial" size="3">E-mail Header</font></b><br><font face="arial" ><small>
    Description: Top part of e-mail messages.</font>
    </small><p>
    <textarea rows="10" name="submit_email_header" cols="80"><?=$GLOBALS["email_header"]?></textarea>
    <p>

    <b><font face="arial" size="3">E-mail Footer</font></b><br><font face="arial" ><small>
    Description: Bottom part of e-mail messages.</font>
    </small><p>
    <textarea rows="10" name="submit_email_footer" cols="80"><?=$GLOBALS["email_footer"]?></textarea>
    <p>
    <br><br>
    <center><input type="submit" value="Save Templates" name="B1"></center>
    </form>

<?
}

print  <<<HTM
<br><br><pre><font size="2">Click the link. <a href="$admin_url/admin.php">Go to Admin Index Page</a></font></pre>
HTM;

?>