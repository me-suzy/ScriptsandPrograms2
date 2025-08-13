<?
echo <<<EOT
<tr valign="middle" bgcolor="$cl_tab_top">
<td nowrap>&nbsp;&nbsp;Recipient
EOT;
echo (strlen($recipient) >= 1) ? ':' : ' (leave blank to send to all users): ';
echo <<<EOT
</td>
<td nowrap><input type="text" name="recipient" size="32" maxlength="128" value="$recipient"></td>
</tr>
<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;Discount:</td>
<td nowrap><input type="text" name="disc_discount" size="8" maxlength="15" value="$disc_discount">&nbsp;
<select name="disc_type">
EOT;
?>
<option value="Fixed" <? if ($disc_type == "Fixed") echo " selected"; ?>>$</option>
<option value="Percent" <? if ($disc_type == "Percent") echo " selected"; ?>>%</option>
<?
echo <<<EOT
</select>
</td>
</tr>
<tr valign="middle" bgcolor="$cl_tab_top">
<td nowrap>&nbsp;&nbsp;How many times may be used:</td>
<td nowrap><input type="text" name="disc_count" size="2" maxlength="5" value="$disc_count"></td>
</tr>
<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;Expires:</td>
<td nowrap>
<select name="day" size="1">
EOT;
$months = array(1=>"Jan",2=>"Feb",3=>"Mar",4=>"Apr",5=>"May",6=>"Jun",7=>"Jul",8=>"Aug",9=>"Sep",10=>"Oct",11=>"Nov",12=>"Dec");
for ($i=1; $i<=31; $i++) {
	echo "<option value=\"$i\"";
	if ($day == $i) echo " selected";
	echo ">$i</option>";
}
echo "</select><select name=\"month\" size=\"1\">";
for ($i=1; $i<=count($months); $i++) {
	echo "<option value=\"$i\"";
	if ($month == $i) echo " selected";
	echo ">$months[$i]</option>";
}
echo "</select><select name=\"year\" size=\"1\">";
for ($i=2000; $i<=2005; $i++) {
	echo "<option value=\"$i\"";
	if ($year == $i) echo " selected";
	echo ">$i</option>";
}
echo <<<EOT
</select>
</td>
</tr>
EOT;
?>
