<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>HTML <Table> To Database</title>
<script language="javascript">
	function insertsample(form)
	{
		form.htmltable.value = "<TABLE>\r<TR><TD>S.No.</TD><TD>Name</TD><TD>Age</TD><TD>Sex</TD><TD>Location</TD></TR>\r<TR><TD>1</TD><TD>Azeem</TD><TD>24</TD><TD>Male</TD><TD>Pakistan</TD></TR>\r<TR><TD>2</TD><TD>Khurram</TD><TD>24</TD><TD>Male</TD><TD>Pakistan</TD></TR>\r<TR><TD>3</TD><TD>Mushhad</TD><TD>24</TD><TD>Male</TD><TD>Pakistan</TD></TR>\r<TR><TD>3</TD><TD>Qamar</TD><TD>24</TD><TD>Male</TD><TD>Pakistan</TD></TR>\r</TABLE>";
	}
	function validate(form)
	{
		if(frmhtmltabledb.htmltable.value==''){
			alert('Please enter a HTML Table or Click "Fill Sample Text"');
			return false;
		}	
	}
</script>
</head>

<body>
<form name="frmhtmltabledb" action="mapcolumns.php" method="post" onSubmit="return validate(this.form);">
<table cellpadding="3" cellspacing="0" align="center" width="75%" bgcolor="#CCCCCC">
	<tr>
		<td colspan="2">HTML &lt;Table&gt; Here</td>
	</tr>
	<tr>
		<td><textarea cols="75" rows="25" name="htmltable"></textarea></td>
		<td valign="top"><input type="submit" value="Next >>"><br><input type="button" value="Fill Sample Text" onClick="javascript:insertsample(this.form);"></td>
	</tr>
</table>
</form>
</body>
</html>
