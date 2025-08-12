<?
/*
Copyright Information
Script File :  help.php
Creator:  Jose Blanco (snyper)
Version:  1.0
Date Created: Feb. 20 / 2005
Released :  Feb. 27 / 2005
website: http://x-php.com , Shadowphp.net
e-mail: joseblanco.jr@g-mail.com
Aim: xphp snyper , Junior Snyper
please keep this copyright in place. :)
*/
if($sec_inc_code=="081604"){
if($_GET["admin"]=="help"){ 
$title.="- Help/update";
		  $cont.= '
		  <table width="549" border="1" style="border-style: dashed; border-collapse:collapse" bordercolor="#000000">
  <tr> 
    <td height="18" colspan="2" align="center" ><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Help/ 
      Updates </strong></font></td>
  </tr>
  <tr> 
    <td rowspan="3" valign="top">
        <font size="1" face="Verdana, Arial, Helvetica, sans-serif">&raquo; 
          Need Help? <br>
          &raquo; Welcome to the help/update section of fusion contact. <br>
         -<a href="?admin=help&topic=1">Including Form.php</a><br>
         -<a href="?admin=help&topic=2">Editing Form Template</a> <br>
         -<a href="?admin=help&topic=3">Editing AdminCp Skin </a> </font>
';   
if($_GET["topic"]=="1"){
		  $cont.= <<<HTML
		  <br><br>
		  <font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Including 
        Form.php</strong></font><br>
--------------------<br>

		  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">Well 
          there isn't to much to do all you have to do is use the simple php include 
          function<br>
          <font color="#0000FF"><strong>&lt;?php include(&quot;form.php&quot;); 
          ?&gt;</strong></font><br>
          this script wasn't really designed to be put in a subfolder eg.( www.yourdomain.com/subfolder/form.php 
          )<br>
          well snice that is all you have to do beacuse you can edit the form 
          from the admin cp.</font>

HTML;

}   elseif($_GET["topic"]=="2"){
		  $cont.= <<<HTML
		  <br><br>
	<font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Editing 
        Form template</strong></font><br>
--------------------<br>
<p><font size="1" face="Verdana, Arial, Helvetica, sans-serif">In this 
            section I will go over some of the template code and what they do.<br>
            <br>
            ***ok a new thing here to look at, as there are 10 emails you can 
            edit if there is only one in the first box with id 1 then no drop 
            down box will be displayed , so if you then decided to put a new email 
            in the email box with id 2-10 the drop down will show with the users 
            emails.<br>
            also make sure you still include the <strong>{emails}</strong> tag 
            in your template or else the email will not be sent;<br>
            beacuse it includes a hidden field with the first email. ***</font> 
            <font size="1" face="Verdana, Arial, Helvetica, sans-serif">i hope 
            you understand what i mean. if not just leave the <strong>{emails}</strong> 
            tag in place.</font></p>
          <table width="549" border="1" bordercolor="#000000" style="border-style: dashed; border-collapse:collapse">
            <tr> 
              <td width="92" align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Template 
                Code</strong> </font></td>
              <td width="435" align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Description</strong></font></td>
            </tr>
            <tr> 
              <td align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">{</font><font size="1" face="Verdana, Arial, Helvetica, sans-serif">fname1} 
                </font></td>
              <td width="435"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">snice 
                the first field is default this would display<br>
                ( Name ) where ever you put it. </font></td>
            </tr>
            <tr> 
              <td align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">{fname2} 
                </font></td>
              <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">snice 
                the secnd field is default this would display<br>
                ( E-mail ) where ever you put it.</font></td>
            </tr>
            <tr> 
              <td height="22" align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">{fname3}</font></td>
              <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">like 
                name and e-mail what ever you call the text box it will display 
                the name where ever you put it</font></td>
            </tr>
            <tr> 
              <td align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">{fname4}</font></td>
              <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">like 
                name and e-mail what ever you call the text box it will display 
                the name where ever you put it</font></td>
            </tr>
            <tr> 
              <td align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">{fname5}</font></td>
              <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">like 
                name and e-mail what ever you call the text box it will display 
                the name where ever you put it</font></td>
            </tr>
            <tr> 
              <td align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
                {fname6}</font></td>
              <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">like 
                name and e-mail what ever you call the text box it will display 
                the name where ever you put it</font></td>
            </tr>
            <tr> 
              <td align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">{fname7}</font></td>
              <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">like 
                name and e-mail what ever you call the text box it will display 
                the name where ever you put it</font></td>
            </tr>
            <tr> 
              <td align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">{fname8}</font></td>
              <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">like 
                name and e-mail what ever you call the text box it will display 
                the name where ever you put it</font></td>
            </tr>
            <tr> 
              <td align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">{fname9}</font></td>
              <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">like 
                name and e-mail what ever you call the text box it will display 
                the name where ever you put it</font></td>
            </tr>
            <tr> 
              <td align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">{fname10}</font></td>
              <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">like 
                name and e-mail what ever you call the text box it will display 
                the name where ever you put it</font></td>
            </tr>
            <tr> 
              <td width="92" height="16" align="center"> <font size="1" face="Verdana, Arial, Helvetica, sans-serif">{field1}&nbsp; 
                </font> </td>
              <td width="435"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">ok 
                this is the actual textbox this one is default for <strong>name</strong> 
                so as you may have seen in the template that comes with the script 
                when you first upload it. it goes with <strong>{fname1}</strong></font></td>
            </tr>
            <tr> 
              <td height="16" align="center"> <font size="1" face="Verdana, Arial, Helvetica, sans-serif">{field2} 
                </font></td>
              <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">ok 
                this is the actual textbox this one is default for <strong>email</strong> 
                so as you may have seen in the template that comes with the script 
                when you first upload it. it goes with <strong>{fname2}</strong></font></td>
            </tr>
            <tr> 
              <td height="22" align="center"> <font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                {field3} </font></td>
              <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">this 
                text box is customizable</font></td>
            </tr>
            <tr> 
              <td height="16" align="center"> <font size="1" face="Verdana, Arial, Helvetica, sans-serif">{field4} 
                </font></td>
              <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">this 
                text box is customizable</font></td>
            </tr>
            <tr> 
              <td height="16" align="center"> <font size="1" face="Verdana, Arial, Helvetica, sans-serif">{field5} 
                </font></td>
              <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">this 
                text box is customizable</font></td>
            </tr>
            <tr> 
              <td height="16" align="center"> <font size="1" face="Verdana, Arial, Helvetica, sans-serif">{field6} 
                </font></td>
              <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">this 
                text box is customizable</font></td>
            </tr>
            <tr> 
              <td height="16" align="center"> <font size="1" face="Verdana, Arial, Helvetica, sans-serif">{field7} 
                </font></td>
              <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">this 
                text box is customizable</font></td>
            </tr>
            <tr> 
              <td height="16" align="center"> <font size="1" face="Verdana, Arial, Helvetica, sans-serif">{field8} 
                </font></td>
              <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">this 
                is a textarea box </font></td>
            </tr>
            <tr> 
              <td align="center"> <font size="1" face="Verdana, Arial, Helvetica, sans-serif">{field9} 
                </font></td>
              <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">this 
                is a textarea box </font></td>
            </tr>
            <tr> 
              <td height="16" align="center"> <font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                {field10} </font></td>
              <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">this 
                is a textarea box </font></td>
            </tr>
            <tr> 
              <td height="16" align="center"> <font size="1" face="Verdana, Arial, Helvetica, sans-serif">{emails}</font></td>
              <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">this 
                is a drop down menu it will display the emails that you have saved.</font></td>
            </tr>
            <tr> 
              <td height="16" align="center"> <font size="1" face="Verdana, Arial, Helvetica, sans-serif">{subjects} 
                </font></td>
              <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">this 
                is a drop down menu it will display the subjects that you have 
                saved.</font></td>
            </tr>
            <tr> 
              <td height="16" align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">{submit}</font></td>
              <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">this 
                is your submit button put it where ever you want</font></td>
            </tr>
          </table>
          <p><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> <br>
            now here is a small example on how you would do this : </font><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><br>
            ------------------------------------------------------------- <br>
            <br>
            </font> <font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 

            &lt;table width=&quot;400&quot; border=&quot;0&quot; cellspacing=&quot;0&quot; 
            cellpadding=&quot;0&quot;&gt;<br>
            &lt;tr&gt; <br>
            &lt;td width=&quot;60&quot;&gt;&lt;font size=&quot;1&quot; face=&quot;Verdana, 
            Arial, Helvetica, sans-serif&quot;&gt;<strong>{fname1} </strong><br>
            &lt;/font&gt;&lt;/td&gt;<br>
            &lt;td width=&quot;340&quot;&gt;&lt;font size=&quot;1&quot; face=&quot;Verdana, 
            Arial, Helvetica, sans-serif&quot;&gt;<strong>{field1}</strong>&amp;nbsp; 
            <br>
            &lt;/font&gt; &lt;/td&gt;<br>
            &lt;/tr&gt;<br>
            &lt;tr&gt; <br>
            &lt;td&gt;&lt;font size=&quot;1&quot; face=&quot;Verdana, Arial, Helvetica, 
            sans-serif&quot;&gt;<strong>{fname2} </strong><br>
            &lt;/font&gt;&lt;/td&gt;<br>
            &lt;td&gt;&lt;font size=&quot;1&quot; face=&quot;Verdana, Arial, Helvetica, 
            sans-serif&quot;&gt;<strong>{field2}</strong>&lt;/font&gt;&lt;/td&gt;<br>
            &lt;/tr&gt;<br>
            &lt;tr&gt; <br>
            &lt;td&gt;&lt;font size=&quot;1&quot; face=&quot;Verdana, Arial, Helvetica, 
            sans-serif&quot;&gt;<strong>To:</strong>&lt;/font&gt;&lt;/td&gt;<br>
            &lt;td&gt;&lt;font size=&quot;1&quot; face=&quot;Verdana, Arial, Helvetica, 
            sans-serif&quot;&gt;<strong>{emails} </strong><br>
            &lt;/font&gt;&lt;/td&gt;<br>
            &lt;/tr&gt;<br>
            &lt;tr&gt; <br>
            &lt;td height=&quot;12&quot;&gt;&lt;font size=&quot;1&quot; face=&quot;Verdana, 
            Arial, Helvetica, sans-serif&quot;&gt;<strong>subject</strong>&lt;/font&gt;&lt;/td&gt;<br>
            &lt;td&gt;&lt;font size=&quot;1&quot; face=&quot;Verdana, Arial, Helvetica, 
            sans-serif&quot;&gt;<strong>{subjects}</strong>&lt;/font&gt;&lt;/td&gt;<br>
            &lt;/tr&gt;<br>
            &lt;tr&gt; <br>
            &lt;td&gt;&lt;font size=&quot;1&quot; face=&quot;Verdana, Arial, Helvetica, 
            sans-serif&quot;&gt;<strong>{fname8} </strong><br>
            &lt;/font&gt;&lt;/td&gt;<br>
            &lt;td&gt;&lt;font size=&quot;1&quot; face=&quot;Verdana, Arial, Helvetica, 
            sans-serif&quot;&gt;<strong>{field8} </strong><br>
            &lt;/font&gt;&lt;/td&gt;<br>
            &lt;/tr&gt;<br>
            &lt;tr&gt; <br>
            &lt;td colspan=&quot;2&quot;&gt;&lt;div align=&quot;center&quot;&gt;&lt;font 
            size=&quot;1&quot; face=&quot;Verdana, Arial, Helvetica, sans-serif&quot;&gt;<strong>{submit}</strong>&lt;/font&gt;&lt;/div&gt;&lt;/td&gt;<br>
            &lt;/tr&gt;<br>
            &lt;/table&gt;<br>
            <br>
            ------------------------------------------------------------- <br>
            thats a basic example it would show up like this:<br>
            </font> </p>
          <table width="400" border="0" cellspacing="0" cellpadding="0">
            <tr> 
              <td width="60"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Name</font></td>
              <td width="340"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
                <input type="text" name="textfield">
                </font> </td>
            </tr>
            <tr> 
              <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">E-mail 
                </font></td>
              <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                <input type="text" name="textfield2">
                </font></td>
            </tr>
            <tr> 
              <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">To:</font></td>
              <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                <select name="select">
                  <option value="user@user.com" selected>user</option>
                  <option value="user2@user2.com">user2</option>
                </select>
                </font></td>
            </tr>
            <tr> 
              <td height="12"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">subject</font></td>
              <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                <select name="select2">
                  <option value="question">question</option>
                  <option value="request">request</option>
                  <option value="staff app">Staff Application</option>
                </select>
                </font></td>
            </tr>
            <tr> 
              <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">comments 
                </font></td>
              <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                <textarea name="textarea"></textarea>
                </font></td>
            </tr>
            <tr> 
              <td colspan="2"><div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                  <input type="submit" name="Submit" value="Submit">
                  </font></div></td>
            </tr>
          </table>

HTML;

}
   
	  
	elseif($_GET["topic"]=="3"){
		  $cont.= '
	<br><br>
<font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Editing 
        Admin Cp Skin</strong></font><br>
--------------------<br>
<font size="1" face="Verdana, Arial, Helvetica, sans-serif">well 
            it\'s pretty much the same as editing the form template. same type 
            of code excpet easier to edit!</font><br>
<p><font size="1" face="Verdana, Arial, Helvetica, sans-serif"></font></p>
          <table width="549" border="1" bordercolor="#000000" style="border-style: dashed; border-collapse:collapse">
            <tr> 
              <td width="92" align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Template 
                Code</strong> </font></td>
              <td width="435" align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Description</strong></font></td>
            </tr>
            <tr> 
              <td align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&#123;content&#125; 
                </font></td>
              <td width="435"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">this 
                is the main content it displays the main script</font></td>
            </tr>
            <tr> 
              <td align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&#123;title&#125; 
                </font></td>
              <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">this 
                is the title of the title bar in the browser</font></td>
            </tr>
            <tr> 
              <td height="22" align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&#123;linksbr&#125;</font></td>
              <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">the 
                links will be displayed like:<br>
                <a href="#">Link</a><br>
                <a href="#">Link</a><br>
                <a href="#">Link</a></font></td>
            </tr>
            <tr> 
              <td align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&#123;links&#125;</font></td>
              <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">the 
                links will be displayed like:<br>
                <a href="#">Link</a> | <a href="#">Link</a> |<a href="#"> Link 
                </a></font></td>
            </tr>
            <tr> 
              <td align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&#123;login&#125;</font></td>
              <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">this 
                just shows your login id eg:<br>
                Logged in as : Admin ( Super Admin)</font></td>
            </tr>
          </table>

';

}


 
		  $cont.= <<<HTML
		 <br> <font size="1" face="Verdana, Arial, Helvetica, sans-serif"><br>
      </font></td>
  </tr>
</table>
<br>
<table width="549" border="1" style="border-style: dashed; border-collapse:collapse" bordercolor="#000000">
  <tr> 
  
    <td colspan="2" valign="top"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">if 
      you have any problems and need more support then you may contact me ,<br>
      Email : joseblanco.jr@gmail.com<br>
      AIM : Junior Snyper<br>
      <br>
      or you can post at <a href="http://shadowphp.net/forums">Our Support Forums 
      </a> in the topic fusion contact.<br>
      <br>
      <br>
      well i hope you enjoy the script, and please notify me of any bugs. or problems 
      that you may come across.</font> 
      <p><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><em>the script 
        is free so please leave the copyright's in tact , you may put this script 
        up for download on your site, you may not edit the script and call it 
        your own.</em></font></p></td>
  </tr>
</table>
HTML;
}
	}// Ends Securtiy Check now lets display error msg
	else die(" You can not run this file from here");
?>