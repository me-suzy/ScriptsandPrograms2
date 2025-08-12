<?
include ("../includes/global.php");

function links()
{
    global $site_html_path,$sitename,$site_url,$refid,$email,$ps;

    //to open the contactus page
    $contents=file_reader("$site_html_path/header.html");
    $contents=str_replace("[pageheader]",$sitename,$contents);
    $contents=str_replace("[site_url]",$site_url,$contents);
    $contents=str_replace("[refid]",$refid,$contents);
    $contents=str_replace("[email]",$email,$contents);
    $contents=str_replace("[ps]",$ps,$contents);

print $contents;
}
?>
links($email,$refid);
</html>


