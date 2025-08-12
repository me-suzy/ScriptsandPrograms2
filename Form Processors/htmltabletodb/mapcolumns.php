<?php
	require('htmltabletodb.class.php');
?>
<html>
<head>
<title>HTML &lt;Table&gt; To Database</title>
<script language="javascript">
	function includeColumn(form,checkbox)
	{
		if(checkbox.checked == false){
			form.elements[checkbox.id-1].disabled = true;
			form.elements[checkbox.id-1].value ='';			
		}else{
			form.elements[checkbox.id-1].disabled = false;
		}
	}
	function insertsample(form)
	{
		form.dbTable.value='user_info';
		form.elements[1].value = "user_id";
		form.elements[3].value = "user_name";
		form.elements[5].value = "user_age";
		form.elements[7].value = "user_sex";
		form.elements[9].value = "user_location";
		//form.htmltable.value = "<TABLE>\r<TR><TD>S.No.</TD><TD>Name</TD><TD>Age</TD><TD>Sex</TD><TD>Location</TD></TR>\r<TR><TD>1</TD><TD>Azeem</TD><TD>24</TD><TD>Male</TD><TD>Pakistan</TD></TR>\r<TR><TD>2</TD><TD>Atiq</TD><TD>24</TD><TD>Male</TD><TD>Pakistan</TD></TR>\r<TR><TD>3</TD><TD>Shahid</TD><TD>24</TD><TD>Male</TD><TD>Pakistan</TD></TR>\r</TABLE>";
	}
	function validate()
	{
		var i,isFilled = false;
		for(i=1;i<(frmhtmltabletodb.elements.length-4);i+=2)
		{
			if(frmhtmltabletodb.elements[i].value!=''){
				i = frmhtmltabletodb.elements.length;
				isFilled = true;
			}
		}
		
		if(frmhtmltabletodb.dbTable.value==''){
			alert('Please Enter Table Name');
			frmhtmltabletodb.dbTable.setfocus;
			return false;
		}else if(isFilled==false){
			alert('Please Enter atleast one Database Table Field');
			frmhtmltabletodb.elements[1].setfocus;
			return false;
		}
		return true;
	}
</script>
</head>

<body>
<?php
	$objClass = new htmlTabletoDb();
	$html = $_POST["htmltable"];
	$totalColumns = 0;
	$start = strpos(strtolower($html),'</tr');
	$columns = substr($html,0,$start+5)."</table>";
	$columns = substr_replace($columns,"<table cellspacing=0 width='50%' align='center' ",0,7);
	$columns = str_replace("<td","<td Style=\"border:1px solid #000;\" align=\"center\"",strtolower($columns));
	$columns = str_replace("<TD","<TD Style=\"border:1px solid #000;\" align=\"center\"",strtolower($columns));
	$columns = str_replace("<Td","<Td Style=\"border:1px solid #000;\" align=\"center\"",strtolower($columns));
	$arr_columns = $objClass->ParseTable($columns);
?>
	<form name="frmhtmltabletodb" action="parse.php" method="post" onSubmit="return validate();">
	<table cellpadding="0" cellspacing="0" width="75%" align="center" bgcolor="#CCCCCC">
		<tr>
			<td>Database Table Name</td>
		</tr>
		<tr>
			<td><input type="text" name="dbTable" size="40"></td>
		</tr>
	</table><br>
<?php
	foreach($arr_columns as $key =>$value)
	{
		echo "<table cellspacing=0 width='75%'  align='center' bgcolor=\"#CCCCCC\"><tr><td colspan='3'>Column(s) Name</td></tr><tr><td width=\"80%\"><table cellspacing=0 cellpadding='3' width='100%' align='center'><tr Style='background-color:#999999;'><td>#</td><td>HTML Table Column Name</td><td>Database Table Column Name</td><td>Insert</td>";
		foreach($arr_columns[$key] as $innerkey=>$innervalue)
		{
			echo "<tr><td width=\"5%\" Style=\"border:1px solid #000;\">".($innerkey+1)."</td><td Style=\"border:1px solid #000;\" >".$arr_columns[$key][$innerkey]."</td><td Style=\"border:1px solid #000;\" align=\"center\"  width=\"20%\"><input type=\"text\" Style=\"border:1px solid #000;\" size=20 name=\"column[]\"></td><td Style=\"border:1px solid #000;\" align=\"center\"  width=\"10%\"><input type=\"checkbox\" id=\"".($totalColumns+2)."\" name=\"chk".$totalColumns."\" onClick=\"javascript:includeColumn(this.form,this);\" CHECKED></td></tr>"; 
			$totalColumns+=2;
		}
		echo "</table></td><td valign=\"top\" width=\"20%\"><input type=\"submit\" value=\"Next >>\"><br><input type=\"button\" value=\"Fill Sample Columns\" onClick=\"javascript:insertsample(this.form);\"><br><br><input type=\"hidden\" name=\"execute\"></td></tr></table>";
	}
?>
<BR>
	<table cellpadding="0" cellspacing="0" width="75%" align="center" bgcolor="#CCCCCC">
		<tr>
			<td>Original Data</td>
		</tr>
		<tr>
			<td align="center"><textarea cols="80" rows="25" name="htmltable"><?=$html?></textarea></td>
		</tr>
	</table>
</form>
</body>
</html>
