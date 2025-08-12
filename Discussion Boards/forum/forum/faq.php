<?php

$where = "<a href=\"index.php\">Home</a> &gt; Frequently Asked Questions";

include("header.php");

table_header("Frequently Asked Questions");
echo ("
<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" bgcolor=\"$tborder_color2\" width=\"$fwidth\" align=\"center\">
   <tr bgcolor=\"$tbackground2\">
      <td width=\"100%\" colspan=\"1\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">

      <b>FaQ</b><br />
          This FaQ will hopefuly answer some of your questions regarding the useage of this forum
          but support will always be provided by the MyAtom .NET representatives on the
          <a href=\"http://www.myatom.net\">MyAtom.NET forums</a>.<br /><br />
          
      <b>How does the database work?</b><br />
          It is recommended you prune topics because the less information in the database then
          the faster this forum will run, the database is 1 single file (which you should
          always backup ( especially when upgrading )) and contains all information, users
          posts, topics, forums, everything.<br /><br />
          
      <b>Why do you allow a logged in user to register again?</b><br />
          A logged in user can still register because there is no point blocking this ability
          to guests ( not logged in users ), This makes it easier to register a friend.<br /><br />
          
      <b>Is this easy to mod?</b><br />
          If your a PHP programmer then yes, this forum is easily modded, You must have a clear
          in-sign in OOP (Object Oriented Programming). all data is stored in an array ($db->data)
          so if you use <i><u>print_r(\$db->data);</u></i> then you will see the whole DB.<br /><br />
          
      <b>Can i use CSS?</b><br />
          I have intergrated into the forum a smart code for CSS, if you wish to use CSS then
          make sure its named \"style.css\" and place it in the forum folder where all the PHP
          files are located<br /><br />
          
      <b>What are your License Rules?</b><br />
          Our Terms and Conditions on usage of this forum can be found at <a href=\"http://www.myatom.net\">MyAtom.NET</a>,
          We may change the rules at any time without prior notice, it is the Administrators job to
          fulfill these rules and make sure they are being met to the required standards.<br /><br />
          
      <b>When i go in a sub-forum on the same site, why does it log me out?</b><br />
          This happens because the 'sub-forum' is a completly new one and your login was not detected
          in the users database file, imagine it being on another
          web-site, the forum is a completly fresh one, it would be probable you'd have to re-register
          on the sub-forum, note: maybe a mod would register you on both at the same time.<br />
          If your username and password are the same on both then you should be fine.



      </font></td>
   </tr>
</table>");

table_footer();

include("footer.php");

?>
