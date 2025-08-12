<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_101; ?> 
  <?PHP if ($val != final){ ?>
  <font size="3"><b><font size="3"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><font size="4" face="Arial, Helvetica, sans-serif" color="#336699"> 
  </font></font></b></font></b></font></b></font></b></font></strong></font></p>
<form name="form1" method="post" action="main.php">
  <table width="470" border="0" cellpadding="5" cellspacing="0">
    <tr valign="top"> 
      <td colspan="2" background="media/h_n1.gif"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_102; ?>:</strong></font></div></td>
    </tr>
    <tr valign="top" bgcolor="#FFFFFF"> 
      <td colspan="2"><div align="center"></div>
        <table width="450" border="0" cellspacing="1" cellpadding="1" align="center">
          <tr bgcolor="#FFFFFF"> 
            <?PHP
		$numbox = 1; 
		if (empty($offset)) {
    $offset=0;
}
		$count1 = 0;


		$finder = mysql_query ("SELECT * FROM Lists
		WHERE name != ''
                       	ORDER BY name 
						");

if ($c1 = mysql_num_rows($finder))
{
while($find = mysql_fetch_array($finder)) {
$selid = $find["id"];
					$selid = " , $selid ";
$seluser = $row_admin["user"];
					$selector = mysql_query ("SELECT * FROM Admin
		WHERE user LIKE '$seluser'
		AND lists LIKE '%$selid%'
						");

if ($seld = mysql_fetch_array($selector))
{

?>
            <td width="150" valign="top" bgcolor="#<?PHP if ($nl == $find["id"]){ print D5E2F0; } else { print F3F3F3; } ?>"> 
              <div align="left"><font size="1" face="Arial, Helvetica, sans-serif"> 
                <input type="checkbox" name="nlbox[<?PHP print $numbox; ?>]" value="<?PHP print $find["id"]; ?>" <?PHP if ($nl == $find["id"]){ print checked; } ?> >
                <?PHP print $find["name"]; ?></font></div></td>
            <?PHP
$count1 = $count1 + 1;
$numbox = $numbox + 1;

if ($count1 == 3){
?>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <?PHP
$count1 = 0;
}
}
}
while($count1 != 3 AND $count1 != 0) {
if ($count1 != 0){
?>
            <td width="150" bgcolor="#FFFFFF" >&nbsp; </td>
            <?PHP

$count1 = $count1 + 1;
}
}
}
else {
?>
            <font size="2" face="Arial, Helvetica, sans-serif"> 
            <?PHP
print "$lang_19";
?>
            </font> 
            <?PHP
}
?>
        </table></td>
    </tr>
    <tr valign="top" bgcolor="#D5E2F0"> 
      <td colspan="2"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_103; ?></strong></font><font size="3"><b><font size="3"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><font size="4" face="Arial, Helvetica, sans-serif" color="#336699"> 
          <?PHP
		  $result = mysql_query ("SELECT * FROM Lists
                         WHERE id LIKE '$nl'
						 
						 limit 1
                       ");
$row = mysql_fetch_array($result)
?>
          </font></font></b></font></b></font></b></font></b></font><font size="2" face="Arial, Helvetica, sans-serif"><br>
          <font size="1">(<?PHP print $lang_104; ?>)</font></font></div></td>
    </tr>
    <tr valign="top"> 
      <td colspan="2" bgcolor="#FFFFFF"><div align="left"><font size="2" face="Arial, Helvetica, sans-serif"></font></div>
        <table width="450" border="0" align="center" cellpadding="1" cellspacing="1">
          <tr valign="top"> 
            <td width="150"><font size="1" face="Arial, Helvetica, sans-serif"> 
              <input name="inc_name" type="checkbox" id="inc_name" value="yes" checked>
              Name <br>
              <br>
              </font></td>
            <td width="150"><font size="1" face="Arial, Helvetica, sans-serif"> 
              <input name="inc_field1" type="checkbox" id="inc_field1" value="yes">
              <?PHP print $lang_105; ?> 1<br>
              <?PHP	print $row["field1"];	?>
              </font></td>
            <td width="150"><font size="1" face="Arial, Helvetica, sans-serif"> 
              <input name="inc_field2" type="checkbox" id="inc_field2" value="yes">
              <?PHP print $lang_105; ?> 2<br>
              <?PHP	print $row["field2"];	?>
              </font></td>
          </tr>
          <tr valign="top"> 
            <td width="150"><font size="1" face="Arial, Helvetica, sans-serif"> 
              <input name="inc_field3" type="checkbox" id="inc_field3" value="yes">
              <?PHP print $lang_105; ?> 3<br>
              <?PHP	print $row["field3"];	?>
              </font></td>
            <td width="150"><p><font size="1" face="Arial, Helvetica, sans-serif"> 
                <input name="inc_field4" type="checkbox" id="inc_field4" value="yes">
                <?PHP print $lang_105; ?> 4<br>
                </font><font size="1" face="Arial, Helvetica, sans-serif"> 
                <?PHP	print $row["field4"];	?>
                </font></p></td>
            <td width="150"><font size="1" face="Arial, Helvetica, sans-serif"> 
              <input name="inc_field5" type="checkbox" id="inc_field5" value="yes">
              <?PHP print $lang_105; ?> 5 <br>
              <?PHP	print $row["field5"];	?>
              </font></td>
          </tr>
          <tr valign="top"> 
            <td width="150"><font size="1" face="Arial, Helvetica, sans-serif"> 
              <input name="inc_field6" type="checkbox" id="inc_field6" value="yes">
              <?PHP print $lang_105; ?> 6<br>
              <?PHP	print $row["field6"];	?>
              </font></td>
            <td width="150"><font size="1" face="Arial, Helvetica, sans-serif"> 
              <input name="inc_field7" type="checkbox" id="inc_field7" value="yes">
              <?PHP print $lang_105; ?> 7<br>
              <?PHP	print $row["field7"];	?>
              </font></td>
            <td width="150"><font size="1" face="Arial, Helvetica, sans-serif"> 
              <input name="inc_field8" type="checkbox" id="inc_field8" value="yes">
              <?PHP print $lang_105; ?> 8<br>
              <?PHP	print $row["field8"];	?>
              </font></td>
          </tr>
          <tr valign="top"> 
            <td width="150"><font size="1" face="Arial, Helvetica, sans-serif"> 
              <input name="inc_field9" type="checkbox" id="inc_field9" value="yes">
              <?PHP print $lang_105; ?> 9<br>
              <?PHP	print $row["field9"];	?>
              </font></td>
            <td width="150"><font size="1" face="Arial, Helvetica, sans-serif"> 
              <input name="inc_field10" type="checkbox" id="inc_field10" value="yes">
              <?PHP print $lang_105; ?> 10<br>
              <?PHP	print $row["field10"];	?>
              </font></td>
            <td width="150">&nbsp;</td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td colspan="2" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input name="asel" type="radio" value="0" checked>
        <?PHP print $lang_519; ?> </font></td>
    </tr>
    <tr> 
      <td colspan="2" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="radio" name="asel" value="1">
        <?PHP print $lang_520; ?></font></td>
    </tr>
    <tr bgcolor="#D5E2F0"> 
      <td colspan="2"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><strong>*<?PHP print $lang_106; ?><br>
          </strong> <font size="1"><?PHP print $lang_107; ?></font></font></div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="50%"><div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><?PHP print $lang_108; ?></font><font size="2" face="Arial, Helvetica, sans-serif"> 
          <br>
          <input name="add3" type="text" id="name13" size="30">
          </font></div></td>
      <td width="50%"><div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><?PHP print $lang_109; ?></font><font size="2" face="Arial, Helvetica, sans-serif"> 
          <br>
          <input name="unsub3" type="text" id="name42" size="30">
          </font></div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="50%"><div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><?PHP print $lang_110; ?><br>
          </font><font size="2" face="Arial, Helvetica, sans-serif"> 
          <input name="add2" type="text" id="name22" size="30">
          </font><font size="1" face="Arial, Helvetica, sans-serif"> </font></div></td>
      <td width="50%"><div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><?PHP print $lang_111; ?></font><font size="2" face="Arial, Helvetica, sans-serif"> 
          <br>
          <input name="unsub2" type="text" id="name52" size="30">
          </font></div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td><div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><?PHP print $lang_466; ?><br>
          </font><font size="2" face="Arial, Helvetica, sans-serif"> 
          <input name="add4" type="text" id="add3" size="30">
          </font><font size="1" face="Arial, Helvetica, sans-serif"> </font></div></td>
      <td><div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><?PHP print $lang_467; ?><br>
          </font><font size="2" face="Arial, Helvetica, sans-serif"> 
          <input name="unsub4" type="text" id="unsub2" size="30">
          </font><font size="1" face="Arial, Helvetica, sans-serif"> </font></div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="50%"><div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><?PHP print $lang_112; ?></font><font size="2" face="Arial, Helvetica, sans-serif"> 
          <br>
          <input name="add1" type="text" id="name32" size="30">
          </font></div></td>
      <td width="50%"><div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><?PHP print $lang_113; ?></font><font size="2" face="Arial, Helvetica, sans-serif"> 
          <br>
          <input name="unsub1" type="text" id="name62" size="30">
          </font></div></td>
    </tr>
    <tr bgcolor="#D5E2F0"> 
      <td colspan="2"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_114; ?><br>
          </strong> <font size="1"><?PHP print $lang_115; ?></font></font></div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td colspan="2"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"> 
          <input name="label" type="text" id="name43" size="30">
          </font></div></td>
    </tr>
    <tr> 
      <td colspan="2" bgcolor="#FFFFFF"><font size="2" face="Arial, Helvetica, sans-serif"><br>
        </font><font size="2"><font face="Arial, Helvetica, sans-serif"> 
        <input type="submit" name="Submit" value="<?PHP print $lang_116; ?>">
        <input name="val" type="hidden" id="val" value="final">
        <font size="2"><font size="2"><font size="2"> 
        <input name="nl" type="hidden" id="nl" value="<?PHP print $nl; ?>">
        <font size="2"><font size="2"><font size="2"><font size="2"> 
        <input name="page" type="hidden" id="page" value="engine_code">
        </font></font></font></font></font></font></font></font></font></td>
    </tr>
  </table>
  <p><font size="2" face="Arial, Helvetica, sans-serif"><strong>*Optional Redirection 
    Pages - Further Information</strong></font> </p>
  <ul>
    <li><font size="2" face="Arial, Helvetica, sans-serif">By entering a url in 
      any of the fields, the user will be redirected to that url when the action 
      takes place.<br>
      IE: you enter http://www.website.com/thanks.htm in the &quot;Successful 
      Completed Subscription URL&quot; field. Then when a user successfully subscribes 
      to your list he or she would be redirected to http://www.website.com/thanks.htm 
      instantly upon filling out the initial form. The users information will 
      be added to your list in the background while redirection is taking place.</font></li>
    <li><font size="2" face="Arial, Helvetica, sans-serif">Pre-Confirmed Subscription 
      URL or Pre-Confirmed Un-Subscription URL will only be used when you have 
      Require Opt-In/Opt-Out turned on.</font></li>
    <li><font size="2" face="Arial, Helvetica, sans-serif">You may further customize 
      your redirection pages by including the system generated messages within 
      your redirection page. This message will include such information as the 
      users e-mail address, list name, and details of the status of their attempt 
      of their subscription and will detail any errors if applicable. To insert 
      the system message simply use the following code if your redirection URL 
      is a .php page</font> 
      <blockquote> 
        <p><font size="2" face="Arial, Helvetica, sans-serif">&lt;? print $mesg; 
          ?&gt;</font></p>
      </blockquote>
    </li>
  </ul>
  <hr width="100%" size="1" noshade>
  <p><font size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_117; ?></strong></font></p>
  <blockquote> 
    <p><font size="2"><font face="Arial, Helvetica, sans-serif"><b><a name="2"></a></b></font></font><font face="Arial, Helvetica, sans-serif" size="2"><b><?PHP print $lang_118; ?></b></font> 
      <font size="4" face="Arial, Helvetica, sans-serif"><b><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><font size="3"><b><font size="3"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif">
      <?PHP
		  $result = mysql_query ("SELECT * FROM Backend
                         WHERE valid LIKE '1'
						 
						 limit 1
                       ");
$row = mysql_fetch_array($result);
$murl = $row["murl"];
?>
      </font></b></font></b></font></b></font></b></font></font></b></font> </p>
    <p><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_119; ?>:</font></p>
    <blockquote> 
      <p><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $murl; ?>/box.php?nl=<?PHP print $nl; ?></font></p>
    </blockquote>
    <p><br>
      <hr width="100%" size="1" noshade>
    </p>
    <p><font size="2"><font face="Arial, Helvetica, sans-serif"><b><a name="6" id="6"></a></b></font></font><font face="Arial, Helvetica, sans-serif" size="2"><b><?PHP print $lang_120; ?></b></font> 
    </p>
    <p><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_121; ?>:</font></p>
    <blockquote> 
      <p><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $murl; ?>/box.php?nl=<?PHP print $nl; ?>&amp;mlt=no</font></p>
    </blockquote>
  </blockquote>
</form>
<?PHP
}
else {
?>
<p><font size="4" face="Arial, Helvetica, sans-serif"><b><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><font size="3"><b><font size="3"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"> 
  <?PHP
		  $result = mysql_query ("SELECT * FROM Backend
                         WHERE valid LIKE '1'
						 
						 limit 1
                       ");
$row = mysql_fetch_array($result);
$murl = $row["murl"];
?>
  </font></b></font></b></font></b></font></b></font></font></b></font><font size="2" face="Arial, Helvetica, sans-serif" color="#FF0000"> 
  <?PHP
		mysql_query ("INSERT INTO 12all_SubForms (add1, add2, add3, add4, unsub1, unsub2, unsub3, unsub4, label) VALUES ('$add1' ,'$add2' ,'$add3' ,'$add4' ,'$unsub1' ,'$unsub2' ,'$unsub3' ,'$unsub4' ,'$label')");  
		$listtab = mysql_query ("SELECT * FROM 12all_SubForms
									ORDER BY id DESC
									LIMIT 1
								");
		$listtabs = mysql_fetch_array($listtab);
		$p = $listtabs["id"];
?>
  </font><font size="3"><b><font size="3"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><font size="4" face="Arial, Helvetica, sans-serif" color="#336699"> 
  <?PHP
		  $result = mysql_query ("SELECT * FROM Lists
                         WHERE id LIKE '$nl'
						 
						 limit 1
                       ");
$row = mysql_fetch_array($result)
?>
  </font></font></b></font></b></font></b></font></b></font><font size="2" face="Arial, Helvetica, sans-serif" color="#FF0000"> 
  &lt;form name=&quot;&quot; method=&quot;post&quot; action=&quot;<?PHP print $murl; ?>/box.php&quot;&gt;<br>
  &lt;table width=&quot;212&quot; border=&quot;0&quot; cellspacing=&quot;0&quot; 
  cellpadding=&quot;0&quot;&gt;<br>
  &lt;tr&gt; <br>
  &lt;td width=&quot;75&quot;&gt;&lt;div align=&quot;right&quot;&gt;<?PHP print $lang_5; ?>&lt;/div&gt;&lt;/td&gt;<br>
  &lt;td&gt;&lt;div align=&quot;right&quot;&gt; <br>
  &lt;input name=&quot;email&quot; type=&quot;text&quot; id=&quot;email&quot; 
  value=&quot;&quot; size=&quot;16&quot;&gt;<br>
  &lt;/div&gt;&lt;/td&gt;<br>
  &lt;/tr&gt;<br>
  <?PHP if ($inc_name == "yes"){ ?>
  &lt;tr&gt; <br>
  &lt;td width=&quot;75&quot;&gt;&lt;div align=&quot;right&quot;&gt;<?PHP print $lang_4; ?>&lt;/div&gt;&lt;/td&gt;<br>
  &lt;td&gt;&lt;div align=&quot;right&quot;&gt; <br>
  &lt;input name=&quot;name&quot; type=&quot;text&quot; id=&quot;name&quot; value=&quot;&quot; 
  size=&quot;16&quot;&gt;<br>
  &lt;/div&gt;&lt;/td&gt;<br>
  &lt;/tr&gt;<br>
  <?PHP 
  }
  if ($inc_field1 == "yes"){ 
  ?>
  &lt;tr&gt; <br>
  &lt;td width=&quot;75&quot;&gt;&lt;div align=&quot;right&quot;&gt; 
  <?PHP	print $row["field1"];	?>
  &lt;/div&gt;&lt;/td&gt;<br>
  &lt;td&gt;&lt;div align=&quot;right&quot;&gt; <br>
  &lt;input name=&quot;field1&quot; type=&quot;text&quot; id=&quot;field1&quot; 
  value=&quot;&quot; size=&quot;16&quot;&gt;<br>
  &lt;/div&gt;&lt;/td&gt;<br>
  &lt;/tr&gt;<br>
  <?PHP 
  }
  if ($inc_field2 == "yes"){ 
  ?>
  &lt;tr&gt; <br>
  &lt;td width=&quot;75&quot;&gt;&lt;div align=&quot;right&quot;&gt; 
  <?PHP	print $row["field2"];	?>
  &lt;/div&gt;&lt;/td&gt;<br>
  &lt;td&gt;&lt;div align=&quot;right&quot;&gt; <br>
  &lt;input name=&quot;field2&quot; type=&quot;text&quot; id=&quot;field2&quot; 
  value=&quot;&quot; size=&quot;16&quot;&gt;<br>
  &lt;/div&gt;&lt;/td&gt;<br>
  &lt;/tr&gt;<br>
  <?PHP 
  }
  if ($inc_field3 == "yes"){ 
  ?>
  &lt;tr&gt; <br>
  &lt;td width=&quot;75&quot;&gt;&lt;div align=&quot;right&quot;&gt; 
  <?PHP	print $row["field3"];	?>
  &lt;/div&gt;&lt;/td&gt;<br>
  &lt;td&gt;&lt;div align=&quot;right&quot;&gt; <br>
  &lt;input name=&quot;field3&quot; type=&quot;text&quot; id=&quot;field3&quot; 
  value=&quot;&quot; size=&quot;16&quot;&gt;<br>
  &lt;/div&gt;&lt;/td&gt;<br>
  &lt;/tr&gt;<br>
  <?PHP 
  }
  if ($inc_field4 == "yes"){ 
  ?>
  &lt;tr&gt; <br>
  &lt;td width=&quot;75&quot;&gt;&lt;div align=&quot;right&quot;&gt; 
  <?PHP	print $row["field4"];	?>
  &lt;/div&gt;&lt;/td&gt;<br>
  &lt;td&gt;&lt;div align=&quot;right&quot;&gt; <br>
  &lt;input name=&quot;field4&quot; type=&quot;text&quot; id=&quot;field4&quot; 
  value=&quot;&quot; size=&quot;16&quot;&gt;<br>
  &lt;/div&gt;&lt;/td&gt;<br>
  &lt;/tr&gt;<br>
  <?PHP 
  }
  if ($inc_field5 == "yes"){ 
  ?>
  &lt;tr&gt; <br>
  &lt;td width=&quot;75&quot;&gt;&lt;div align=&quot;right&quot;&gt; 
  <?PHP	print $row["field5"];	?>
  &lt;/div&gt;&lt;/td&gt;<br>
  &lt;td&gt;&lt;div align=&quot;right&quot;&gt; <br>
  &lt;input name=&quot;field5&quot; type=&quot;text&quot; id=&quot;field5&quot; 
  value=&quot;&quot; size=&quot;16&quot;&gt;<br>
  &lt;/div&gt;&lt;/td&gt;<br>
  &lt;/tr&gt;<br>
  <?PHP 
  }
  if ($inc_field6 == "yes"){ 
  ?>
  &lt;tr&gt; <br>
  &lt;td width=&quot;75&quot;&gt;&lt;div align=&quot;right&quot;&gt; 
  <?PHP	print $row["field6"];	?>
  &lt;/div&gt;&lt;/td&gt;<br>
  &lt;td&gt;&lt;div align=&quot;right&quot;&gt; <br>
  &lt;input name=&quot;field6&quot; type=&quot;text&quot; id=&quot;field6&quot; 
  value=&quot;&quot; size=&quot;16&quot;&gt;<br>
  &lt;/div&gt;&lt;/td&gt;<br>
  &lt;/tr&gt;<br>
  <?PHP 
  }
  if ($inc_field7 == "yes"){ 
  ?>
  &lt;tr&gt; <br>
  &lt;td width=&quot;75&quot;&gt;&lt;div align=&quot;right&quot;&gt; 
  <?PHP	print $row["field7"];	?>
  &lt;/div&gt;&lt;/td&gt;<br>
  &lt;td&gt;&lt;div align=&quot;right&quot;&gt; <br>
  &lt;input name=&quot;field7&quot; type=&quot;text&quot; id=&quot;field7&quot; 
  value=&quot;&quot; size=&quot;16&quot;&gt;<br>
  &lt;/div&gt;&lt;/td&gt;<br>
  &lt;/tr&gt;<br>
  <?PHP 
  }
  if ($inc_field8 == "yes"){ 
  ?>
  &lt;tr&gt; <br>
  &lt;td width=&quot;75&quot;&gt;&lt;div align=&quot;right&quot;&gt; 
  <?PHP	print $row["field8"];	?>
  &lt;/div&gt;&lt;/td&gt;<br>
  &lt;td&gt;&lt;div align=&quot;right&quot;&gt; <br>
  &lt;input name=&quot;field8&quot; type=&quot;text&quot; id=&quot;field8&quot; 
  value=&quot;&quot; size=&quot;16&quot;&gt;<br>
  &lt;/div&gt;&lt;/td&gt;<br>
  &lt;/tr&gt;<br>
  <?PHP 
  }
  if ($inc_field9 == "yes"){ 
  ?>
  &lt;tr&gt; <br>
  &lt;td width=&quot;75&quot;&gt;&lt;div align=&quot;right&quot;&gt; 
  <?PHP	print $row["field9"];	?>
  &lt;/div&gt;&lt;/td&gt;<br>
  &lt;td&gt;&lt;div align=&quot;right&quot;&gt; <br>
  &lt;input name=&quot;field9&quot; type=&quot;text&quot; id=&quot;field9&quot; 
  value=&quot;&quot; size=&quot;16&quot;&gt;<br>
  &lt;/div&gt;&lt;/td&gt;<br>
  &lt;/tr&gt;<br>
  <?PHP 
  }
  if ($inc_field10 == "yes"){ 
  ?>
  &lt;tr&gt; <br>
  &lt;td width=&quot;75&quot;&gt;&lt;div align=&quot;right&quot;&gt; 
  <?PHP	print $row["field10"];	?>
  &lt;/div&gt;&lt;/td&gt;<br>
  &lt;td&gt;&lt;div align=&quot;right&quot;&gt; <br>
  &lt;input name=&quot;field10&quot; type=&quot;text&quot; id=&quot;field10&quot; 
  value=&quot;&quot; size=&quot;16&quot;&gt;<br>
  &lt;/div&gt;&lt;/td&gt;<br>
  &lt;/tr&gt;</font><font size="2" face="Arial, Helvetica, sans-serif" color="#FF0000"><br>
  <?PHP   }
  ?>
  <?PHP
  if ($asel == "1"){

  ?>
  &lt;tr&gt; <br>
  &lt;td height=&quot;30&quot; colspan=&quot;2&quot;&gt;&lt;div align=&quot;left&quot;&gt;&lt;strong&gt;Select 
  Lists&lt;/strong&gt;&lt;/div&gt;&lt;/td&gt;<br>
  &lt;/tr&gt;
  <?PHP
    $cucc = 1;
  foreach ($nlbox as $something)
  {
  if ($something != "")
  { 
  		  $lfind = mysql_query ("SELECT * FROM Lists
                         WHERE id LIKE '$something'
						 limit 1
                       ");
		$lfinder = mysql_fetch_array($lfind);
?>
  <br>
  &lt;tr&gt; <br>
  &lt;td colspan=&quot;2&quot;&gt; &lt;div align=&quot;left&quot;&gt; <br>
  &lt;input type=&quot;checkbox&quot; name=&quot;nlbox[<?PHP print $cucc; ?>]&quot; 
  value=&quot;<?PHP print $something; ?>&quot; checked&gt;<br>
  <?PHP print $lfinder["name"]; ?>&lt;/div&gt;&lt;/td&gt;<br>
  &lt;/tr&gt; 
  <?PHP
  $cucc = $cucc + 1;
  } 
  }
  }
  ?>
  <br>
  </font><font size="2" face="Arial, Helvetica, sans-serif" color="#FF0000">&lt;tr&gt; 
  <br>
  &lt;td width=&quot;75&quot;&gt;&lt;div align=&quot;right&quot;&gt; &lt;/div&gt;&lt;/td&gt;<br>
  &lt;td&gt; &lt;div align=&quot;left&quot;&gt; &amp;nbsp; &amp;nbsp; <br>
  &lt;input name=&quot;funcml&quot; type=&quot;radio&quot; value=&quot;add&quot; 
  checked&gt;<br>
  <?PHP print $lang_38; ?> &lt;/div&gt;&lt;/td&gt;<br>
  &lt;/tr&gt;<br>
  &lt;tr&gt; <br>
  &lt;td width=&quot;75&quot;&gt;&lt;div align=&quot;right&quot;&gt; &lt;/div&gt;&lt;/td&gt;<br>
  &lt;td&gt; &lt;div align=&quot;left&quot;&gt; &amp;nbsp;&amp;nbsp;&amp;nbsp; 
  <br>
  &lt;input name=&quot;funcml&quot; type=&quot;radio&quot; value=&quot;unsub2&quot;&gt;<br>
  <?PHP print $lang_65; ?> &lt;/div&gt;&lt;/td&gt;<br>
  &lt;/tr&gt;<br>
  </font><font size="2" face="Arial, Helvetica, sans-serif" color="#FF0000"> &lt;tr&gt; 
  <br>
  &lt;td width=&quot;75&quot;&gt;&lt;div align=&quot;right&quot;&gt;<br>
  &lt;input name=&quot;p&quot; type=&quot;hidden&quot; id=&quot;p&quot; value=&quot;<?PHP print $p; ?>&quot;&gt;<br>
  <?PHP
  if ($asel == "0"){
  $cucc = 1;
  foreach ($nlbox as $something)
  {
  if ($something != "")
  { 
  ?>
  &lt;input type=&quot;hidden&quot; name=&quot;nlbox[<?PHP print $cucc; ?>]&quot; 
  value=&quot;<?PHP print $something; ?>&quot;&gt;<br>
  <?PHP
  $cucc = $cucc + 1;
  } 
  }
  }
  ?>
  &lt;/div&gt;&lt;/td&gt;<br>
  &lt;td&gt; &lt;div align=&quot;right&quot;&gt; <br>
  &lt;br&gt; <br>
  &lt;input type=&quot;submit&quot; name=&quot;<?PHP print $lang_21; ?>&quot; value=&quot;<?PHP print $lang_21; ?>&quot;&gt;<br>
  &lt;/div&gt;&lt;/td&gt;<br>
  &lt;/tr&gt;<br>
  &lt;/table&gt;<br>
  &lt;/form&gt; <br>
  </font> </p>
<p> <font size="2" face="Arial, Helvetica, sans-serif" color="#FF0000"> </font> 
  <?PHP } ?>
