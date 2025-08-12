<?php

$db->reBuild("true");

	$round = 5;
  $m_time = explode(" ",microtime());
	$m_time = $m_time[0] + $m_time[1];
	$endtime = $m_time;
	$totaltime = ($endtime - $starttime);
	$_render_time = round($totaltime,$round);

  $tcats = count(@$db->data["_DB"]["cats"]) - 2;
  $tforums = count(@$db->data["_DB"]["forums"]) - 2;
  $ttopics = count(@$db->data["_DB"]["topics"]) - 2;
  $tposts = count(@$db->data["_DB"]["posts"]) - 2 + $ttopics;
  $tmembers = count(@$db->data["_DB"]["users"]) - 2;
  
  $lmember = $tmembers + 1;
  $lmember = @$db->data["_DB"]["users"]["$lmember"][0];
  
  $posts = count(@$db->data["_DB"]["posts"]) - 1;
  $the_topic_numero = @$db->data["_DB"]["posts"]["$posts"][5];
  $topic_numero = @$db->query("topics", "7", $the_topic_numero);

  $last_id = @$db->data["_DB"]["topics"]["$topic_numero"][5];
  $last_tid = @$db->data["_DB"]["topics"]["$topic_numero"][7];

  $last_subj = @$db->data["_DB"]["topics"]["$topic_numero"][1];
    $last_by = @$db->data["_DB"]["posts"]["$posts"][2];
    
    $m = @$db->data["_DB"]["whos_online"];
    $m_total = count($m) - 2;

$member = array();
$guest = array();

for ($i = 2; $i < count(@$db->data["_DB"]["whos_online"]); $i++){
if ($m[$i][0] != ""){
  $member[] = $m[$i][0];
} else {
  $guest[] = $m[$i][1];
}; //end if
}; //end $i

 $members = count($member);
 $guests = count($guest);
 $mname = implode(", ", $member);

  echo ("<font size=\"1\"><br /></font>");

  if (file_exists("$skins/table/tl2.gif")){
  announce_header("<b>$_LANG[22]</b>");
} else {
table_header("<b>$_LANG[22]</b>");
}; //end if

  echo ("
  <table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" width=\"$fwidth\" align=\"center\" bgcolor=\"$tborder_color2\">
     <tr>
        <td class=\"background\" colspan=\"2\" width=\"100%\" align=\"left\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>$_LANG[28]</b></font></td>
     </tr>
     <tr>
        <td bgcolor=\"$tbackground2\" colspan=\"1\" width=\"10%\" align=\"center\"><img src=\"icon/user.gif\" alt=\"User stats\" /></td>
        <td bgcolor=\"$tbackground1\" colspan=\"1\" width=\"90%\" valign=\"top\"><font size=\"$fsmall\" face=\"$fface\" color=\"$fcolor\">$_LANG[23]: <b>$m_total</b>, $_LANG[24]: <b>$guests</b> $_LANG[25]: <b>$members</b>
        [ <font color=\"$cadmin\"><b>$_LANG[26]</b></font> ]  [ <font color=\"$cmember\">$_LANG[27]</font> ]
        <br />
        </font> <font color=\"$fcolor\" size=\"$fsmall\" face=\"$fface\">$mname</font></td>
     </tr>
 <tr>
        <td class=\"background\" colspan=\"2\" width=\"100%\" align=\"left\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>$_LANG[29]</b></font></td>
     </tr>
     <tr>
        <td bgcolor=\"$tbackground2\" colspan=\"1\" width=\"10%\" align=\"center\"><img src=\"icon/stats.gif\" alt=\"Forum stats\" /></td>
        <td bgcolor=\"$tbackground1\" colspan=\"1\" width=\"90%\">


  <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" align=\"center\">
     <tr>
        <td colspan=\"1\" width=\"20%\" align=\"right\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">


          $_LANG[30]: <br />
          $_LANG[31]: <br />
          $_LANG[32]: <br />
          $_LANG[33]: <br />

        </font></td>
        <td colspan=\"1\" width=\"10%\" align=\"left\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">
        
        <b>&nbsp;$tcats</b><br />
        <b>&nbsp;$tforums</b> <br />
        <b>&nbsp;$ttopics</b> <br />
        <b>&nbsp;$tposts</b> <br />


        </font></td>
        <td colspan=\"1\" width=\"2%\" align=\"left\"></td>
        <td colspan=\"1\" width=\"20%\" align=\"right\" valign=\"top\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">

         $_LANG[34]: <br />
         $_LANG[35]: <br />
         $_LANG[36]: <br />
         $_LANG[37]:<br />

        </font></td>
        <td colspan=\"1\" width=\"40%\" align=\"left\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">

         <b>&nbsp;$tmembers</b> <br />
         <b>&nbsp;$_render_time</b> <br />
         <b>&nbsp;$lmember</b> <br />
         &nbsp;<a href=\"view_thread.php?fid=$last_id&tid=$last_tid\">$last_subj</a> $_LANG[38]: <b>$last_by</b><br />

        </font></td>
      </tr>
  </table>



        </td>
     </tr>
    <tr>
        <td class=\"background\" colspan=\"2\" width=\"100%\" align=\"left\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>$_LANG[39]</b></font></td>
     </tr>
     <tr>
        <td bgcolor=\"$tbackground1\" colspan=\"2\" width=\"50%\">

            <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" align=\"center\">
               <tr align=\"center\">
                  <td width=\"33%\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><img src=\"$skins/other/new.gif\" alt=\"New Topics\" /><br />$_LANG[40]</font></td>
                  <td width=\"33%\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><img src=\"$skins/other/old.gif\" alt=\"Old Topics\" /><br />$_LANG[41]</font></td>
                  <td width=\"33%\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><img src=\"$skins/other/link_forum.gif\" alt=\"Sub Forums\" /><br />$_LANG[42]</font></td>
               </tr>
            </table>
        </td>
    </tr>
</table>");


if (file_exists("$skins/table/tl2.gif")){
  announce_footer();
} else {
  table_footer();

};

echo("<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"$width\" align=\"center\">
         <tr class=\"subnav2\">
             <td width=\"20%\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">&nbsp;Atom_DB<sup>v$db->version</sup></font></td>
             <td width=\"50%\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">$title &copy; 2003 - 2005</font></td>
             <td width=\"30%\" align=\"right\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">All Rights Reserved, v$f_version&nbsp;</font></td>
         </tr>
      </table>

</td>
   </tr>
</table>");

echo ("<font size=\"1\"><br /></font><div align=\"center\"><img src=\"http://www.myatom.net/myatom_net.png\" alt=\"MyAtom Solutions\" /></div>");

echo ("\r\n\r\n\t</body>\r\n</html>");
//no more HTML output below this!

?>
