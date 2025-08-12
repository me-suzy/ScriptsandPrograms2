<?php
	require('htmltabletodb.class.php');
?>
<html>
<head>
<title>HTML &lt;Table&gt; To Database</title>
<script language="javascript">
	function selectAll(theField) {
	  var tempval=eval("document."+theField)
	  tempval.focus()
	  tempval.select()
	}
	function copy_clip(meintext)
{

 if (window.clipboardData) 
   {
   
   // the IE-manier
   window.clipboardData.setData("Text", meintext);
   
   // waarschijnlijk niet de beste manier om Moz/NS te detecteren;
   // het is mij echter onbekend vanaf welke versie dit precies werkt:
   }
   else if (window.netscape) 
   { 
   
   // dit is belangrijk maar staat nergens duidelijk vermeld:
   // you have to sign the code to enable this, or see notes below 
   netscape.security.PrivilegeManager.enablePrivilege('UniversalXPConnect');
   
   // maak een interface naar het clipboard
   var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
   if (!clip) return;
   
   // maak een transferable
   var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);
   if (!trans) return;
   
   // specificeer wat voor soort data we op willen halen; text in dit geval
   trans.addDataFlavor('text/unicode');
   
   // om de data uit de transferable te halen hebben we 2 nieuwe objecten nodig   om het in op te slaan
   var str = new Object();
   var len = new Object();
   
   var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
   
   var copytext=meintext;
   
   str.data=copytext;
   
   trans.setTransferData("text/unicode",str,copytext.length*2);
   
   var clipid=Components.interfaces.nsIClipboard;
   
   if (!clip) return false;
   
   clip.setData(trans,null,clipid.kGlobalClipboard);
   
   }
   return false;
}
</script>
</head>
<body>
<?php
	$objClass = new htmlTabletoDb();
	$html = $_POST["htmltable"];
	$tableName = $_POST["dbTable"];
	$arr_columnsName = $_POST["column"];
	$totalRows = 0;
	$totalColumns = 0;
	$columnsName = '';
	$columnsData = '';

	foreach($arr_columnsName as $cKey => $cValue)
	{
		if($arr_columnsName[$cKey]!='' && $columnsName=='')
			$columnsName .= $arr_columnsName[$cKey];
		elseif($arr_columnsName[$cKey]!='')
			$columnsName .= ",".$arr_columnsName[$cKey];
	}

	$arr_data = $objClass->ParseTable($html);

	foreach($arr_data as $outerKey => $outerValue)
	{
		$sql .= "\n\rINSERT INTO ".$tableName."(".$columnsName.") \nVALUES(";
		foreach($arr_data[$outerKey] as $innerKey => $innerValue)
		{
			if($arr_columnsName[$innerKey]!=''){
				if($columnsData =='')
					$columnsData .="'".trim($arr_data[$outerKey][$innerKey])."'";
				else
					$columnsData .=",'".trim($arr_data[$outerKey][$innerKey])."'";
			}
		}
		$sql .= $columnsData.");";
		$columnsData = '';
	}
?>
	<form name="test">
	<table cellpadding="0" cellspacing="0" width="75%" align="center" >
		<tr>
			<td>SQL Quries</td>
			<td align="right"><a href="index.php">Home</a> &nbsp;&nbsp;::&nbsp;&nbsp;<a href="javascript:selectAll('test.select2')">Select All</a>&nbsp;&nbsp;::&nbsp;&nbsp;<input type="button" value="Copy" onClick="return copy_clip(test.select2.value);"></td>
		</tr>
		<tr>
			<td colspan="2"><textarea cols="100" rows="25" name="select2" ><?=$sql?></textarea></td>
		</tr>
	</table>
</form>	
</body>
</html>