<center>
<a href="#" onclick="window.open('http://www.clickXchange.com/fr.phtml?act=417706.54')"><img src="http://www.clickXchange.com/fd.phtml?act=417706.54" border=0></a></center><br>

<center>
<table border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#6B84AE" width="460" id="AutoNumber1" height="198">
  <tr>
    <td width="460" background="admin/topbackground.jpg" height="20" valign="top">
   <font color="blue"><center><b>Chipmunk Stories<b></center></font></td>
  </tr>
  <tr>
    <td width="460" height="177" bgcolor="#F2F2F2" valign="top">


<? //Chipmunk Story, interactive stories for your site. put header above this line
include "admin/connect.php";
if(isset($_GET['ID'])) //looking at specific story
 {
  $ID=$_GET['ID'];
  $r="SELECT * from s_titles where ID='$ID'";
  $r2=mysql_query($r) or die("could not select title");
  $r3=mysql_fetch_array($r2);
  print "<center><A href='add.php?ID=$ID'>Add to Story</a>|<A href='index.php'>Back to Main</a></center><br>";
  print "<center><H3>$r3[title]</H3></center><br><br>";
  $story="SELECT * from s_entries where parent='$ID' order by ID ASC";
  $story2=mysql_query($story) or die("Could not select story");
  while($story3=mysql_fetch_array($story2))
    {
       $story3[entry]=badwords($story3[entry]);
       $story3[entry]=strip_tags($story3[entry]);
       $story3[entry]=wordwrap( $story3[entry], 30, "\n", 1);
       print "$story3[entry]";
    }
    print "<br><br><center><font size='1'>Powered by © <A href='http://www.chipmunk-scripts.com'><font size='1'>Chipmunk         
    stories</font></a></font></center>";
  }
 
 else //looking at root
  {
     $titleselect="SELECT * from s_titles";
     $titleselect2=mysql_query($titleselect) or die ("Could not select stories");
     while ($titleselect3=mysql_fetch_array($titleselect2))
     {
       print "<center><A href='index.php?ID=$titleselect3[ID]'>$titleselect3[title]</a>($titleselect3[numposts] parts)</center><br>";
     }
     print "<br><br><center><font size='1'>Powered by © <A href='http://www.chipmunk-scripts.com'><font size='1'>Chipmunk         
     stories</font></a></font></center>";
  }
//put footer below this line?>
</td></tr></table>
 </center>

<?
 function badwords($post)  //function for filtering out bad words
 {
 $badwords=array( 

    

    'fuck'=>"@#$#",
    'shit'=>"@$#@",
   
   
   
    );

   $post=str_replace(array_keys($badwords), array_values($badwords), $post);
    return $post;
 }

 
?>