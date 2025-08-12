<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_205; ?></strong></font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><?PHP if ($val != go){ print $lang_206; ?></font></p>
<table width="100%" height="25" border="0" cellpadding="0" cellspacing="0" bgcolor="#D5E2F0">
  <tr>
    <td><table width="100%" height="23" border="0" cellpadding="1" cellspacing="0">
        <tr bgcolor="#ECF8FF"> 
          <td> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_207; ?> 
              <?PHP
					  $findcount = mysql_query ("SELECT * FROM ListMembers
                         WHERE nl LIKE '$nl'
                         AND email != ''
				 		 AND active LIKE '1'
                       ");

$countdata = mysql_num_rows($findcount);
print $countdata;
print " ";
print $lang_208; 
?>
</font><font color="#80A8D0"><b></b></font></div></td>
        </tr>
      </table></td>
  </tr>
</table>
<p><font size="2" face="Arial, Helvetica, sans-serif"> 
  <?PHP
if ($countdata != 0){
if ($countdata > 50){
print "$lang_209";
}
else {
print "$lang_210 $countdata $lang_208";
}
}
else {
print "$lang_211";
}
?>
  </font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><img src="media/line_mblue.gif" width="550" height="1"></font></p>
<p><font color="#336699" size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_212; ?>:</font></p>
<FORM ENCTYPE="multipart/form-data" ACTION="main.php" METHOD=POST>
  <div align="left"> 
    <table width="450" border="0" cellspacing="0" cellpadding="0">
      <tr valign="top"> 
        <td width="130"><font size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_213; ?><br>
          </strong> </font></td>
        <td><table width="300" border="0" cellspacing="0" cellpadding="0">
            <tr> 
              <td><div align="center"><strong><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_214; ?><br>
                  <select name="month" id="month">
                    <option value="01" selected>01</option>
                    <option value="02">02</option>
                    <option value="03">03</option>
                    <option value="04">04</option>
                    <option value="05">05</option>
                    <option value="06">06</option>
                    <option value="07">07</option>
                    <option value="08">08</option>
                    <option value="09">09</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                  </select>
                  </font></strong></div></td>
              <td><div align="center"><strong><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_215; ?><br>
                  <select name="day" id="day">
                    <option value="01" selected>01</option>
                    <option value="02">02</option>
                    <option value="03">03</option>
                    <option value="04">04</option>
                    <option value="05">05</option>
                    <option value="06">06</option>
                    <option value="07">07</option>
                    <option value="08">08</option>
                    <option value="09">09</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                    <option value="24">24</option>
                    <option value="25">25</option>
                    <option value="26">26</option>
                    <option value="27">27</option>
                    <option value="28">28</option>
                    <option value="29">29</option>
                    <option value="30">30</option>
                    <option value="31">31</option>
                  </select>
                  </font></strong></div></td>
              <td><div align="center"><strong><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_216; ?><br>
                  <select name="year" id="year">
                    <option value="2002">2002</option>
                    <option value="2003" selected>2003</option>
                    <option value="2004">2004</option>
                    <option value="2005">2005</option>
                    <option value="2006">2006</option>
                    <option value="2007">2007</option>
                    <option value="2008">2008</option>
                    <option value="2009">2009</option>
                    <option value="2010">2010</option>
                  </select>
                  </font></strong></div></td>
            </tr>
          </table>
          <div align="center"></div></td>
      </tr>
    </table>
    <br>
    <font size="2" face="Arial, Helvetica, sans-serif"><font size="1"><?PHP print $lang_217; ?></font> </font> 
    <p><font color="#990000" size="1" face="Arial, Helvetica, sans-serif"><?PHP print $lang_218; ?></font></p>
    <p> 
      <INPUT TYPE="submit" VALUE="<?PHP print $lang_21; ?>" name="backup">
      <input name="page" type="hidden" id="page" value="list_purgenc">
      <input name="nl" type="hidden" id="nl" value="<?PHP print $nl; ?>">
      <input name="val" type="hidden" id="val" value="go">
    </p>
    </div>
</FORM>
<?PHP
}
else{
?>
<p><font size="2" face="Arial, Helvetica, sans-serif">
  <?PHP
  $thedate = "$year$month$day";
					  $findcount = mysql_query ("SELECT * FROM ListMembers
                         WHERE nl LIKE '$nl'
                         AND email != ''
				 		 AND active LIKE '1'
						 AND sdate < '$thedate'
                       ");

$countdata = mysql_num_rows($findcount);
mysql_query ("DELETE FROM ListMembers
                         WHERE nl LIKE '$nl'
                         AND email != ''
				 		 AND active LIKE '1'
						 AND sdate < '$thedate'
								");
if ($countdata == "0"){
print "$lang_219";
}
else {
print "$countdata $lang_220";
}
}
?>
  </font></p>
