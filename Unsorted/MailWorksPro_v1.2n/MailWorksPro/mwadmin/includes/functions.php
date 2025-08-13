<?php
///////////////////////////////////////////////////////////////////////////////
//                                                                           //
//   Program Name         : MailWorks Professional                           //
//   Release Version      : 1.2                                              //
//   Program Author       : SiteCubed Pty. Ltd.                              //
//   Supplied by          : CyKuH [WTN]                                      //
//   Packaged by          : WTN Team                                         //
//   Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                           //
//                       WTN Team `2000 - `2002                              //
///////////////////////////////////////////////////////////////////////////////
  define("RECS_TO_SHOW", 15);

  function doDbConnect()
  {
    global $dbServer, $dbUser, $dbPass, $dbName;
    
	$s = @mysql_connect($dbServer, $dbUser, $dbPass) or die("<p style='margin-left:20'><br><font face=verdana size=2 color=red><b>ERROR: Couldn't connect to MySQL Database (" . mysql_error() . ")<br><br><a href='config.php'>Update Config File >></a></b></font>");
	$d = @mysql_select_db($dbName, $s) or die("<p style='margin-left:20'><br><font face=verdana size=2 color=red><b>ERROR: Couldn't select MySQL Database (" . mysql_error() . ")<br><br><a href='config.php'>Update Config File >></a></b></font>");
  }
  
  function JSRedirectTo($Page, $Time)
  {
  ?>
    <html>
	  <head>
	    <meta http-equiv="refresh" content="<?php echo $Time; ?>; url=<?php echo $Page; ?>">
	  </head>
	</html>
  <?php
  }
  
  function isLoggedIn()
  {
    if(@$_COOKIE["auth"] == true)
	  return true;
	else
	  return false;
  }
  
  function GetTopicList($Selected = -1)
  {
    doDbConnect();
	
	$result = mysql_query("select * from topics order by tName asc");
	
	while($row = mysql_fetch_row($result))
	{
	  echo "<option ";
	  
	  if($row[0] == $Selected)
		echo " SELECTED ";
	  
	  echo " value='{$row[0]}'>{$row[1]}</option>";
	}
  }
  
  function GetFrequencyList($WhichList, $Selected)
  {
    if($WhichList == 1)
	{
      for($i = 1; $i <= 7; $i++)
      {
	    echo "<option ";
	    
	    if($i == $Selected)
			echo " SELECTED ";
	    
	    echo " value=$i>$i</option>";
	  }
	}
	else
	{
	  echo "<option ";
	  
	  if($Selected == 1)
		echo " SELECTED ";
	  
	  echo "value='1'>day(s)</option>";
	  
	  echo "<option ";
	  
	  if($Selected == 2)
		echo " SELECTED ";
	  
	  echo "value='2'>week(s)</option>";
	  
	  echo "<option ";
	  
	  if($Selected == 3)
		echo " SELECTED ";
	  
	  echo "value='3'>month(s)</option>";

	}
  }
  
  function GetTemplateList($Selected = 0, $SelectedId = 0, $ShowDesc = true)
  {
	// Grab a list of templates and return them as <select> list options
	doDbConnect();
	
	$result = mysql_query("select pk_nId, nName, nFormat from templates order by nName asc");
	$i = 0;
	
	if($ShowDesc == true)
		echo "<option value='-1'>-- Select Template --</option>";
	
	while($row = mysql_fetch_row($result))
	{
		++$i;
		echo "<option ";
		
		if($i == $Selected || $row[0] == $SelectedId)
			echo " SELECTED ";
		
		echo " value='" . $row[0] . "'>" . $row[1] . "</option>";
	}
  }
  
  function GenerateRandomPassword()
  {
	// Generate a random password for imported users
	$rndPass = "";
	
	for($i = 0; $i < rand(10, 20); $i++)
		$rndPass .= chr(rand(100, 120));
	
	return $rndPass;
  }

function DateDiff ($interval, $date1,$date2) {

    // get the number of seconds between the two dates
$timedifference =  $date2 - $date1;
    
    switch ($interval) {
        case "w":
            $retval  = $timedifference / 604800;
            break;
        case "d":
            $retval  = $timedifference / 86400;
            break;
        case "h":
             $retval = $timedifference / 3600;
            break;        
        case "n":
            $retval  = $timedifference / 60;
            break;        
        case "s":
            $retval  = $timedifference;
            break;        

    }    
    return $retval;
    
}


?>