<html>
<head>
<title>Live Chat Module</title>
</head>

<script language=javascript>

function closeWindow() {

	document.location.href = 'exitchat.php';
	
}

</script>
<frameset rows="24,*" frameborder="NO" border="0" framespacing="0"> 
  <frame name="topFrame" scrolling="NO" noresize src="includes/chattop.php" >
  <frameset cols="358,*">
    <frame name="mainFrame" src="openchat.php">
    <frame name="navFrame" src="includes/userchatnavigation.php">
  </frameset>
</frameset>
</html>