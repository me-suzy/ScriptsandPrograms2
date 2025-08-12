<?
switch($formelement) {
		case 'uid_show' :
			$display_name='Bezoeker';
			if( $action=='edit' || $action=='reedit'){
			}
			else{
				$cellvalue=$_SESSION["uid"];
			}
			$sql = "SELECT * FROM user WHERE uid='" . $cellvalue ."'";
			$res = mysql_prefix_query($sql) or die(mysql_error());
			if ($num = mysql_num_rows($res)) {
				$display_name=mysql_result($res,0,'display_name');
			}
			//echo "<input type=\"hidden\" name=\"$value\" value=\"$cellvalue\">";
			echo "<tr><td nowrap>$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td width=\"90%\"><input class=\"form_input\" type=\"hidden\" name=\"" . $value. "\" value=\"".$cellvalue."\">".$display_name."</td></tr>";

			//$noruler=true;
		break;
		case 'stamp':
			if( $action=='edit' || $action=='reedit'){	
				$splitdate = split('-',$cellvalue,3);
			}
			else{
				$splitdate = split('-',date("Y-m-d"));
			}
			echo "<tr><td>$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td>";
			echo "". substr($splitdate[2],"0", "2") ."-". $splitdate[1] ."-". $splitdate[0]."";
			echo "</td></tr>";

		break;
		case 'user_rights':
			$span=5;
			$ruler = '<tr><td colspan="'.$span.'" class="ruler"></td></tr>';
			$ruler2 = '<tr><td colspan="'.$span.'" class="ruler2"></td></tr>';
			if($formrarray["type"]<>''){
				$usert[$formrarray["type"]]="checked";
			}
			else{
				$usert["user"]="checked";
			}
			echo "<tr><td width=10% nowrap>System account</td><td></td><td><input type=\"radio\" name=\"type\" value=\"administrator\" ".$usert["administrator"]." onclick=\"javascript:hideLayer('rest2".$counter."');\">System administrator</td></tr>";
			echo "<tr><td width=10%></td><td></td><td><input type=\"radio\" name=\"type\" value=\"user\" ".$usert["user"]." onclick=\"javascript:showLayer('rest2".$counter."');\">Standard user</td></tr>";
			echo "<tr><td colspan=3 style=\"PADDING-RIGHT: 0px; PADDING-LEFT: 0px; PADDING-BOTTOM: 0px; PADDING-TOP: 0px\">";
			if ($usert["administrator"]) {
		    $displaylayer="none";
			}
			echo "<div id=rest2".$counter." style=\"DISPLAY: ".$displaylayer."\">";
			echo "\n<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\">";

		echo "<tr><td colspan=2>&nbsp;</td></tr>";
		echo $ruler2;
		echo "\n<tr bgcolor=\"#ffffff\">\n<td background='images/stab-bg.gif'><b>Userrights</b></td>
		\n<td background='images/stab-bg.gif' width=\"15%\"><b>Author</b></td>
		\n<td background='images/stab-bg.gif' width=\"15%\"><b>Editor</b></td>
		\n<td background='images/stab-bg.gif' width=\"15%\"><b>Administrator</b></td>
		\n<td background='images/stab-bg.gif' width=\"\"><b>None</b></td></tr>";
		//echo $ruler;
		
		$sql= "SELECT container.* FROM container WHERE container.inuseradmin='1'";
		$moduleresults= mysql_prefix_query($sql) or die (mysql_error());
		while($modulearray = mysql_fetch_array($moduleresults)){
			echo $ruler;
			unset ($right);
			//if ($_REQUEST["task"]=='editrecord' ||$_REQUEST["task"]=='updateedit' || $_REQUEST["task"]=='updatereedit') {
			if( $action=='edit' || $action=='reedit'){
				if ($action == 'reedit') {
					$formrarray["uid"]=$_REQUEST["uid"];
				}
				$sqlm= "SELECT * from userrights where container_id='".$modulearray["id"]."' AND uid=".$formrarray["uid"];
				$mresults= mysql_prefix_query($sqlm) or die (mysql_error()." Line: ".__LINE__);
				if($marray = mysql_fetch_array($mresults)){
					$right[$marray["type"]]="checked";
				}
				else{
					$right["none"]="checked";
				}
			}
			else{
				$right["none"]="checked";
			}

			echo "<tr><td width=10% nowrap>".$modulearray["cname"]."</td>";
			echo "<td><input type=\"radio\" name=\"right[".$modulearray["id"]."]\" value=\"author\" ".$right["author"]."></td>
				<td><input type=\"radio\" name=\"right[".$modulearray["id"]."]\" value=\"editor\" ".$right["editor"]."></td>
				<td><input type=\"radio\" name=\"right[".$modulearray["id"]."]\" value=\"administrator\" ".$right["administrator"]."></td>
				<td><input type=\"radio\" name=\"right[".$modulearray["id"]."]\" value=\"\" ".$right["none"]."></td>";
			echo "</tr>";
		}
		echo "</table></div></td></tr>";
		break;


		case 'branches':
			$all_branches=mysql_prefix_query("SELECT * FROM branch ORDER BY branch.branch ASC");
			
			if ($action == 'reedit' || $action == 'recreate'){
				$separator="";
				for(@reset($_POST["list2"]); list($key, $val)=@each($_POST["list2"]); ) {
					$p_list.=$separator."'".$val."'";
					$separator=", ";
				}
				$current_branches=mysql_prefix_query("SELECT * FROM branch WHERE b_id IN (". $p_list.") ORDER BY branch.branch ASC");
			}
			elseif ($action == 'edit') {
				//$formrarray[$fieldname[0]]
				//echo $formrarray["uid"];
				//haal alle porjecten binnen behalve de projecten waar deze gebruiker manager van is
				$current_branches=mysql_prefix_query("SELECT * FROM b_links, branch WHERE uid='".$formrarray["uid"]."' AND b_links.b_id=branch.b_id") or die(mysql_error());
			}
	
			if(is_resource($current_branches)){
				while ($alreadya=mysql_fetch_array($current_branches)) {
					$alr.="<OPTION VALUE=\"".$alreadya["b_id"]."\">".$alreadya["branch"]."</OPTION>";
					$alr_array[$alreadya["b_id"]]=$alreadya["branch"];
				}
			}
			
			while ($allusersa=mysql_fetch_array($all_branches)) {
				 if ($alr_array[$allusersa["b_id"]]=='') {
					 $alu.="<OPTION VALUE=\"".$allusersa["b_id"]."\">".$allusersa["branch"]."</OPTION>";
				 }
			}

			echo "\n<tr bgcolor=\"#ffffff\">\n<td colspan=3  background='images/stab-bg.gif'><b>Branches</b></td></tr>";
			echo $ruler;
			echo "<tr><td colspan=\"3\" style=\"padding:0; margin:0;\">";
			echo "\n<TABLE BORDER=\"1\" width=\"1px\" cellpadding=\"4\" cellspacing=\"0\">\n";
			//echo "\n<FORM enctype=\"multipart/form-data\" ACTION=\"". $jetstream_url . $thisfile. "\" METHOD=\"POST\" name=\"mainform\" 		onsubmit=\"selectAllOptions(document.getElementById('list2[]'));\">";
			//echo $ruler2;

			echo "\n<tr bgcolor=\"#F6F6F6\">";
			echo "\n<td class='tab-g'>Alle</td>";	
			echo "\n<td class='tab-g'>&nbsp;</td>";	
			echo "\n<td class='tab-g'>Geselecteerd</td>";	
			echo "\n</tr>";
			echo $ruler;

			?>
			<TR>
				<TD >
				<SELECT NAME="list1" MULTIPLE SIZE="10" style="width: 180px;" onDblClick="moveSelectedOptions(this.form['list1'],this.form['list2[]'],true,this.form['movepattern1'].value)">
					<?
					 echo $alu;
					?>
				</SELECT>
				</TD>
				<TD VALIGN="MIDDLE" ALIGN="CENTER" >
					<INPUT TYPE="button" NAME="right" VALUE="&gt;&gt;" ONCLICK="moveSelectedOptions(this.form['list1'],this.form['list2[]'],true,this.form['movepattern1'].value)"><BR><BR>
					<INPUT TYPE="button" NAME="right" VALUE="Alle &gt;&gt;" ONCLICK="moveAllOptions(this.form['list1'],this.form['list2[]'],true,this.form['movepattern1'].value)"><BR><BR>
					<INPUT TYPE="button" NAME="left" VALUE="&lt;&lt;" ONCLICK="moveSelectedOptions(this.form['list2[]'],this.form['list1'],true,this['form'].movepattern1.value)"><BR><BR>
					<INPUT TYPE="button" NAME="left" VALUE="Alle &lt;&lt;" ONCLICK="moveAllOptions(this.form['list2[]'],this.form['list1'],true,this.form['movepattern1'].value)">
				</TD>
				<TD width="182px">
				<SELECT NAME="list2[]" MULTIPLE SIZE=10 style="width: 180px;" onDblClick="moveSelectedOptions(this.form['list2[]'],this.form['list1'],true,this.form['movepattern1'].value)">
					<?
					 echo $alr;
					?>
				</SELECT>
				<INPUT TYPE="hidden" NAME="movepattern1" VALUE="">
				</TD>
			</TR>
			<?
			//echo "\n<tr><td colspan=\"3\"><br><INPUT TYPE=\"Hidden\" NAME=\"list3[]\" VALUE=\"\"></td></tr>";

			echo "\n</TABLE>";
			echo "</td></tr>";

		break;
		case 'dropdown' :
			echo "<tr><td>$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td>";
			general_displaydropdown($value, $cellvalue, $dropdown);
			echo "</td></tr>";
		break;
		case 'dropdown2' :
			echo "<tr><td>$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td>";
			general_displaydropdown($value, $cellvalue, $dropdown, "Main");
			echo "</td></tr>";
		break;
		case 'SelectCategory':
			$selectCat = new SelectCategory($value, $tablename);
			$selectCat->SetFirstOption(':: Root level ::', '0');
			$selectCat->ExcludeId = $_SETTINGS[$primarykey];
			$selectCat->SelectedId = $cellvalue;
			$parentList = $selectCat->Output();
			echo "<tr><td nowrap>$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td width=\"90%\">".$parentList."</td></tr>";
		break;
		case 'SelectAllCategory':
			$selectCat = new SelectCategory($value, 'odfaq_category');
			//$selectCat->SetFirstOption(':: Root level ::', '0');
			//$selectCat->ExcludeId = $_SETTINGS[$primarykey];
			$selectCat->SelectedId = $cellvalue;
			$parentList = $selectCat->Output();
			echo "<tr><td nowrap>$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td width=\"90%\">".$parentList."</td></tr>";
		break;
		case 'dropdownurl' :
			if( $action=='create'){
				$cellvalue=$_REQUEST[$value];
			}
			echo "<tr><td>$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td>";
			general_displaydropdown($value, $cellvalue, $dropdown);
			echo "</td></tr>";
		break;
		case 'show' :
			echo "<tr><td nowrap>$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td width=\"90%\"><input class=\"form_input\" type=\"hidden\" name=\"" . $value. "\" value=\"".$cellvalue."\">".$cellvalue."</td></tr>";
		break;
		case 'password' :
			echo "<tr><td nowrap>$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td width=\"90%\"><input class=\"form_input\" type=\"password\" name=\"" . $value. "\" size=\"79\" maxlength=\"255\"></td></tr>";
		break;
		case 'password30' :
			echo "<tr><td nowrap>$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td width=\"90%\"><input class=\"form_input\" type=\"password\" name=\"" . $value. "\" size=\"30\" maxlength=\"255\"></td></tr>";
		break;
		case 'string' :
			echo "<tr><td nowrap>$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td width=\"90%\"><input class=\"form_input\" type=\"text\" name=\"" . $value. "\" value=\"".$cellvalue."\" size=\"79\" maxlength=\"255\"></td></tr>";
		break;
		case 'string10' :
			echo "<tr><td nowrap>$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td width=\"90%\"><input class=\"form_input\" type=\"text\" name=\"" . $value. "\" value=\"".$cellvalue."\" size=\"10\" maxlength=\"255\"></td></tr>";
		break;
		case 'string20' :
			echo "<tr><td nowrap>$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td width=\"90%\"><input class=\"form_input\" type=\"text\" name=\"" . $value. "\" value=\"".$cellvalue."\" size=\"20\" maxlength=\"255\"></td></tr>";
		break;
		case 'string30' :
			echo "<tr><td nowrap>$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td width=\"90%\"><input class=\"form_input\" type=\"text\" name=\"" . $value. "\" value=\"".$cellvalue."\" size=\"30\" maxlength=\"255\"></td></tr>";
		break;
		case 'string40' :
			echo "<tr><td nowrap>$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td width=\"90%\"><input class=\"form_input\" type=\"text\" name=\"" . $value. "\" value=\"".$cellvalue."\" size=\"40\" maxlength=\"255\"></td></tr>";
		break;
		case 'ebateheadline' :
			if ($task=="edit" &&  $formrarray["inforum"]=="yes"){
				echo "<tr><td colspan=2><b>Deze wijzigingen worden niet doorgevoerd in het forum</b></td></tr>";
			}
			echo "<tr><td nowrap>$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td width=\"90%\"><input class=\"form_input\" type=\"text\" name=\"" . $value. "\" value=\"".$cellvalue."\" size=\"79\" maxlength=\"255\"></td></tr>";
		break;
		case 'blob' :
			echo "<tr><td nowrap></td><td bgcolor=\"#".$requiredcolor."\" width=\"1\">";
			echo " <td width=90%></td> </tr>";
			echo "<tr><td valign=\"top\">$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td><textarea class=\"form_input_textarea\" name=\""  . $value. "\" ROWS=\"3\" COLS=\"81\" wrap=\"virtual\" onkeyup=\"sz(this);\" onchange=\"sz(this);\" onfocus=\"sz(this);\">".$cellvalue."</textarea></td></tr>";
		break;
		case 'blob_max_length' :
			echo "<tr><td nowrap></td><td bgcolor=\"#".$requiredcolor."\" width=\"1\">";
			echo " <td width=90%></td> </tr>";
			echo "<tr><td valign=\"top\">$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td><textarea class=\"form_input_textarea\" name=\""  . $value. "\" ROWS=\"3\" COLS=\"62\" wrap=\"virtual\" onkeyup=\"sz(this);textCounter(this,this.form.remLen,1500);\" onchange=\"sz(this);\" onfocus=\"sz(this);\">".$cellvalue."</textarea><br><input readonly type=text name=remLen size=3 maxlength=3 value=\"1500\"> characters left</td></tr>";
		break;
		case 'richblob' :
			global $richblob_co;
			$richblob_co++;
			$richurl='';
			//if ($_SESSION["browser"]=="ie" && $_SESSION["maj_ver"]>="5") {
				$richurl="<br><a href=\"Javascript:JetstreamRTE('".$value."')\">Edit in rich text</a>";
			//}
	
			echo "<tr><td valign=\"top\">".$display;
			if ($_SESSION["browser"]<>"iee"){
				echo "<div id=rb_open".$richblob_co." style=\"DISPLAY: block\"><a title=\"Preview\" href=\"#\" onClick=\"showLayer('rb_sec_open".$richblob_co."'); hideLayer('rb_sec_close".$richblob_co."'); hideLayer('rb_open".$richblob_co."'); hideLayer('rb_open2".$richblob_co."'); showLayer('rb_close".$richblob_co."');document.getElementById('". $value. "preview').innerHTML=document.getElementById('".$value."').value; return false;\">Preview</a></div><div id=rb_close".$richblob_co." style=\"DISPLAY: none\"><a title=\"Edit\" href=\"#\" onClick=\"hideLayer('rb_sec_open".$richblob_co."'); showLayer('rb_sec_close".$richblob_co."'); showLayer('rb_open".$richblob_co."'); hideLayer('rb_close".$richblob_co."'); window.setTimeout('showLayer(\'rb_open2".$richblob_co."\')', 1); return false;\">Edit</a></div>";
			}
			echo "</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td><div id=rb_sec_close".$richblob_co." style=\"DISPLAY: block\"><textarea class=\"form_input_textarea\" id=\""  . $value. "\" name=\""  . $value. "\" ROWS=\"8\" COLS=\"78\" wrap=\"virtual\" onkeyup=\"sz(this);\" onchange=\"sz(this);\" onfocus=\"sz(this);\">".$cellvalue."</textarea>".$richurl."</div><div id=rb_sec_open".$richblob_co." style=\"DISPLAY: none\"><div id=\"".$value."preview\" style=\"width: 480px; padding:4px 0px 4px 3px\"></div></div><div id=rb_open2".$richblob_co." style=\"DISPLAY: block; height:1px; padding:0; margin:0;\"></div></td></tr>";
		break;
		case 'hidden' :
			echo "<input type=\"hidden\" name=\"$value\" value=\"$cellvalue\">";
			$noruler=true;
		break;
		case 'novalue' :
			echo "<input type=\"hidden\" name=\"$value\" value=\"\">";
			$noruler=true;
		break;
		case 'no_element' :
			//echo "<input type=\"hidden\" name=\"$value\" value=\"\">";
			$noruler=true;
		break;

		case 'date' :
			$splitdate = split('-',$cellvalue,3);
			echo "<input type=\"hidden\" name=\"dbadmindatefield[]\" value=\"$value\">";
			echo "<tr><td  valign=\"top\">" . $display . "</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td valign=\"top\">".general_dateinput($value."_day",$splitdate[2],$value."_month",$splitdate[1],$value."_year",$splitdate[0])."</td></tr>";
		break;
		case 'wf_online' :
			list($year,$month,$day,$hour,$min,$sec)=explode(":", preg_replace("([ |-]+)",":", $cellvalue));
			echo "<input type=\"hidden\" name=\"dbadmindatefield[]\" value=\"$value\">";
			echo "<tr bgcolor=\"#F6F6F6\"><td  valign=\"top\">" . $display . "</td><td width=\"1\"></td><td valign=\"top\">".general_datetimeinput($value."_day",$day,
			$value."_month",$month,
			$value."_year",$year,
			$value."_hour",$hour,
			$value."_minute",round($min/5)*5)."</td></tr>";
		break;
		case 'checkbox' :
			if ($cellvalue==1) {
				echo "<tr><td>$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td valign=\"top\"><input type=\"checkbox\" checked name=\"$value\" value=\"1\"></td></tr>";
			}
			else {
					echo "<tr><td>$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td valign=\"top\"><input type=\"checkbox\" name=\"$value\" value=\"1\"></td></tr>";
			}
		break;
		case 'radio' :
			if ($cellvalue=='no' || $cellvalue=='' || $cellvalue==0 ||  $cellvalue==false) {
				echo "<tr><td>$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td valign=\"top\"><input type=\"radio\" name=\"$value\" checked value=\"no\">No &nbsp; &nbsp; <input type=\"radio\" name=\"$value\" value=\"yes\"> Yes</td></tr>";
			}
			else {
				echo "<tr><td>$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td valign=\"top\"><input type=\"radio\" name=\"$value\" value=\"no\">No &nbsp; &nbsp; <input type=\"radio\" name=\"$value\" checked value=\"yes\"> Yes</td></tr>";
			}
		break;
		case 'radioonerezo' :
			if ($cellvalue==0 && strlen($cellvalue)<>0) {
				echo "<tr><td>$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td valign=\"top\"><input type=\"radio\" name=\"$value\" checked value=\"0\">No &nbsp; &nbsp; <input type=\"radio\" name=\"$value\" value=\"1\"> Yes</td></tr>";
			}
			else {
				echo "<tr><td>$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td valign=\"top\"><input type=\"radio\" name=\"$value\" value=\"0\">No &nbsp; &nbsp; <input type=\"radio\" name=\"$value\" checked value=\"1\"> Yes</td></tr>";
			}
		break;
		case 'radioonezero' :
			if ($cellvalue==0 && strlen($cellvalue)<>0) {
				echo "<tr><td nowrap>$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td valign=\"top\"><input type=\"radio\" name=\"$value\" checked value=\"0\">No &nbsp; &nbsp; <input type=\"radio\" name=\"$value\" value=\"1\"> Yes</td></tr>";
			}
			else {
				echo "<tr><td nowrap>$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td valign=\"top\"><input type=\"radio\" name=\"$value\" value=\"0\">No &nbsp; &nbsp; <input type=\"radio\" name=\"$value\" checked value=\"1\"> Yes</td></tr>";
			}
		break;

		case 'radioyesno' :
			if ($cellvalue=='no') {
				echo "<tr><td>$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td valign=\"top\"><input type=\"radio\" name=\"$value\" checked value=\"no\">No &nbsp; &nbsp; <input type=\"radio\" name=\"$value\" value=\"yes\"> Yes</td></tr>";
			}
			else {
				echo "<tr><td>$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td valign=\"top\"><input type=\"radio\" name=\"$value\" value=\"no\">No &nbsp; &nbsp; <input type=\"radio\" name=\"$value\" checked value=\"yes\"> Yes</td></tr>";
			}
		break;
		case 'radiotruefalse' :
			if ($cellvalue==false) {
				echo "<tr><td>$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td valign=\"top\"><input type=\"radio\" name=\"$value\" checked value=\"false\">No &nbsp; &nbsp; <input type=\"radio\" name=\"$value\" value=\"true\"> Yes</td></tr>";
			}
			else {
				echo "<tr><td>$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td valign=\"top\"><input type=\"radio\" name=\"$value\" value=\"false\">No &nbsp; &nbsp; <input type=\"radio\" name=\"$value\" checked value=\"true\"> Yes</td></tr>";
			}
		break;
		case 'radioYN' :
			if ($cellvalue=='N') {
				echo "<tr><td>$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td valign=\"top\"><input type=\"radio\" name=\"$value\" checked value=\"N\">No &nbsp; &nbsp; <input type=\"radio\" name=\"$value\" value=\"Y\"> Yes</td></tr>";
			}
			else {
				echo "<tr><td>$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td valign=\"top\"><input type=\"radio\" name=\"$value\" value=\"N\">No &nbsp; &nbsp; <input type=\"radio\" name=\"$value\" checked value=\"Y\"> Yes</td></tr>";
			}
		break;
		case 'containerradio3' :
			$cellvalue>10 ? ($cellvalue>100 ? $checked[1000]='checked' : $checked[100]='checked') : $checked[10]='checked';
			echo "<tr><td valign=base>$display</td><td bgcolor=\"#".$requiredcolor."\"></td><TD VALIGN=\"TOP\" width=\"90%\">
			<INPUT TYPE=\"RADIO\" NAME=\"$value\" $checked[10] VALUE=\"10\"> author and higher<br>
			<INPUT TYPE=\"RADIO\" NAME=\"$value\" $checked[100] VALUE=\"100\"> container administrator and higher<br>
			<INPUT TYPE=\"RADIO\" NAME=\"$value\" $checked[1000] VALUE=\"1000\"> system administrator only
			</TD></TR>";
			$checked='';
		break;
		case 'sexe' :
			if ($cellvalue==0 && strlen($cellvalue)<>0) {
				echo "<tr><td nowrap>$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td valign=\"top\"><input type=\"radio\" name=\"$value\" checked value=\"0\"> Man &nbsp; &nbsp; <input type=\"radio\" name=\"$value\" value=\"1\"> Vrouw</td></tr>";
			}
			else {
				echo "<tr><td nowrap>$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td valign=\"top\"><input type=\"radio\" name=\"$value\" value=\"0\"> Man &nbsp; &nbsp; <input type=\"radio\" name=\"$value\" checked value=\"1\"> Vrouw</td></tr>";
			}
		break;

		case 'int' :
			if (isdropdown($value)) {
				echo "<tr><td>$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td>";
				displaydropdown($dropmenu,$cellvalue);
				echo "</td></tr>";
			}
			else {
				echo "<tr><td nowrap>$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td width=\"90%\"><input class=\"form_input\" type=\"text\" name=\"" . $value. "\" value=\"".$cellvalue."\" size=\"50\" maxlength=\"255\"></td></tr>";
			}
		break;
		case 'file' :
			echo "<tr><td nowrap>$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td width=\"90%\"><input class=\"form_input\" TYPE=\"file\" NAME=\"userfile\" size=\"50\">";
			if( $action=='edit' || $action=='reedit'){
				echo "<br>Current value: ".$cellvalue;
			}
			echo "</td></tr>";
			echo "<input type=\"hidden\" name=\"$value\" value=\"$cellvalue\">";

		break;
		case 'valuefromurl':
			echo "<input type=\"hidden\" name=\"$value\" value=\"$_REQUEST[$value]\">";
			$noruler=true;
		break;
		case 'valuefromurlshowlinked' :
			if( $action=='edit' || $action=='reedit'){
				$_REQUEST[$value]=$cellvalue;
			}
			echo "<tr><td>$display</td><td width=\"1\"></td><td>".general_linkedvalue($value, $_REQUEST[$value], $dropdown);
			echo "</td></tr>";
			echo "<input type=\"hidden\" name=\"$value\" value=\"$_REQUEST[$value]\">";
		break;
		case 'valuefromurlshowlinkednovalue' :
			if( $action=='edit' || $action=='reedit'){
				$_REQUEST[$value]=$cellvalue;
			}
			echo "\n<tr>\n<td nowrap>$display</td>\n<td width=\"1\"></td>\n<td>".general_linkedvalue($value, $_REQUEST[$value], $dropdown);
			echo "</td>\n</tr>";
		break;

		case 'uid' :
			if( $action=='edit' || $action=='reedit'){
			}
			else{
				$cellvalue=$_SESSION["uid"];
			}
			echo "<input type=\"hidden\" name=\"$value\" value=\"$cellvalue\">";
			$noruler=true;
		break;
		case 'image' :
			$richurl='';
			if ($_SESSION["browser"]=="ie" && $_SESSION["maj_ver"]>="5") {
				$richurl="<br><a href=\"Javascript:SelImage('".$value."')\">Select image</a>";
			}
			echo "<tr><td>$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td><td width=\"90%\"><input class=\"form_input\" type=\"text\" name=\"" . $value. "\" value=\"".$cellvalue."\" size=\"40\" maxlength=\"255\">".$richurl."</td></tr>";
		break;
		case 'separator' :
			echo "<tr><td colspan=3 nowrap>$display</td></tr>";
		break;

		default :
			echo "<tr><td nowrap>$display</td><td bgcolor=\"#".$requiredcolor."\" width=\"1\"></td width=\"90%\"><td><input class=\"form_input\" type=\"text\" name=\"" . $value. "\" value=\"".$cellvalue."\" size=\"50\" maxlength=\"255\"></td></tr>";
		break;
	}