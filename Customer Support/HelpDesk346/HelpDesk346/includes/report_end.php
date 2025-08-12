<!--
	Chad - I am writing this page, but you can redesign it with Dreamweaver as it only straight HTML. This will gvie you some flexability in
	desiging a success page - This is just something simple
	Remember paths are relative to the root of the helpdesk, so design it there and then move it
-->
<html>
	<head>
		<title>Problem Reported Successfully</title>
		<link rel="stylesheet" type="text/css" href="./style.css" />
	</head>
	
	<body>
		Problem Reported Successfully<br/>
		<b>Call Status</b><br/>
		<div style="padding-left:10px">Call Status: Opened</div>
		<div style="padding-left:10px">Ticket Number: <?php echo $ticketno; ?></div>
		<div style="padding-left:10px">Position in Line: <?php echo ($num_calls + 1); ?></div>
		<a href="index.php">Return to Non-Admin Help Desk Main Screen</a><br/>
		<p align="center"><font size="2" face="Times New Roman, Times, serif">CopyRight 
          2005 Help Desk Reloaded<br>
          <a href="http://www.helpdeskreloaded.com">Today's Help Desk Software 
          for Tomorrows Problem.</a></font></p>
        <p align="center"><a href="http://www.helpdeskreloaded.com"><img src="http://www.helpdeskreloaded.com/reload/help-desk-copyright.jpg" alt="http://www.helpdeskreloaded.com Help Desk Software By  HelpDeskReloaded &quot;Help Desk Reloaded&quot;" border="0"></a></p>
	</body>
</html>