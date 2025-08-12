<html>
<head>
<title>Rich text</title>
<link rel="stylesheet" href="editor.css" type="text/css">

<script type="text/javascript" src="fckeditor/fckeditor.js"></script>
<script type="text/javascript">
window.onload = function(){
	var sBasePath = 'fckeditor/';
	var oFCKeditor = new FCKeditor('FCKeditor1') ;
	oFCKeditor.BasePath = sBasePath ;
	oFCKeditor.Height = 380;
	oFCKeditor.ReplaceTextarea() ;
}

function Import(){ 
	var theContent=window.opener.document.mainform.<?=$_REQUEST[val]?>.value;
	document.getElementById("FCKeditor1").value = theContent;
	FCK = FCKeditorAPI.GetInstance('FCKeditor1');
	FCK.Focus(); 
	FCK.SetHTML( "", true );
	FCK.InsertHtml(theContent);
} 

function Export(){ 
	var api=FCKeditorAPI.GetInstance('FCKeditor1'); 
	var ss= api.GetHTML(); 
	window.opener.document.mainform.<?=$_REQUEST[val]?>.value = ss;
	window.close();
} 

function FCKeditor_OnComplete(FCKeditor1){
	Import();
}
</script>
</head>

<body>
<textarea id="FCKeditor1" name="FCKeditor1" style="WIDTH: 100%; HEIGHT: 450px">some text....</textarea>
<input type="button" onclick="javascript:Export()" value="Save">
</body>
</html>